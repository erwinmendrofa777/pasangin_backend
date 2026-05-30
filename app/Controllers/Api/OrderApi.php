<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Modules\Notifications\Services\NotificationService;

class OrderApi extends BaseController
{
    use ResponseTrait;
    protected $db;
    protected NotificationService $notifService;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notifService = new NotificationService();
    }

    public function checkout()
    {
        $userId = $this->request->user->uid;

        // ================================
        // Ambil input umum
        // ================================
        $recipientName = $this->request->getVar('recipient_name');
        $recipientPhone = $this->request->getVar('recipient_phone');
        $address = $this->request->getVar('shipping_address');
        $lat = $this->request->getVar('latitude');
        $long = $this->request->getVar('longitude');
        $totalPrice = $this->request->getVar('total_price') ?? 0;
        $voucher = $this->request->getVar('voucher_code') ?? '';
        $discount = $this->request->getVar('discount_amount') ?? 0;

        // Fee flat untuk mode legacy (backward compat)
        $shippingFee = $this->request->getVar('shipping_fee') ?? 0;
        $appFee = $this->request->getVar('app_fee') ?? 0;
        $taxAmount = $this->request->getVar('tax_amount') ?? 0;

        // ================================================================
        // Ambil cart (join products untuk supplier_id & harga terkini)
        // ================================================================
        $selectedCartIds = $this->request->getVar('selected_cart_ids');

        $cartQuery = $this->db->table('cart')
            ->select('cart.*, products.supplier_id, products.price as product_price, products.name as product_name, products.stock as product_stock')
            ->join('products', 'products.id = cart.product_id')
            ->where('cart.user_id', $userId);

        if ($selectedCartIds && is_array($selectedCartIds)) {
            $cartQuery->whereIn('cart.id', $selectedCartIds);
        }

        $cartItems = $cartQuery->get()->getResultArray();

        if (empty($cartItems)) {
            return $this->fail('Keranjang kosong.');
        }

        // Validasi kecukupan stok produk
        foreach ($cartItems as $item) {
            if ($item['product_stock'] < $item['quantity']) {
                return $this->fail('Stok produk "' . $item['product_name'] . '" tidak mencukupi. Tersedia: ' . $item['product_stock']);
            }
        }

        // ================================================================
        // DETEKSI MODE: Multi-supplier atau legacy single-order
        // Fee per supplier dikirim sebagai map: {"supplier_id": amount}
        // Contoh: shipping_fees[1]=10000&shipping_fees[2]=15000
        // ================================================================
        $shippingFees = $this->request->getVar('shipping_fees');
        $appFees = $this->request->getVar('app_fees');
        $taxAmounts = $this->request->getVar('tax_amounts');

        $isMultiMode = !empty($shippingFees) || !empty($appFees) || !empty($taxAmounts);

        if ($isMultiMode) {
            $shippingFees = is_array($shippingFees) ? $shippingFees : (array) $shippingFees;
            $appFees = is_array($appFees) ? $appFees : (array) $appFees;
            $taxAmounts = is_array($taxAmounts) ? $taxAmounts : (array) $taxAmounts;
        }

        // ================================================================
        // Grouping cart items per supplier_id
        // Mode legacy: gabung semua ke 1 kelompok '_legacy'
        // ================================================================
        $itemsBySupplier = [];

        if ($isMultiMode) {
            foreach ($cartItems as $item) {
                $sid = $item['supplier_id'];
                $itemsBySupplier[$sid][] = $item;
            }
        } else {
            $itemsBySupplier['_legacy'] = $cartItems;
        }

        try {
            $this->db->transStart();

            // ================================
            // 1. BUAT TRANSACTION DULU (FIX FK)
            // ================================
            $transactionIdUnique = 'TRX-' . time() . '-' . $userId;

            $transactionInsert = $this->db->table('transactions')->insert([
                'transaction_id' => $transactionIdUnique,
                'user_id' => $userId,
                'total_amount' => $totalPrice,
                'status' => 'PENDING',
                'payment_method' => 'MIDTRANS',
                'order_count' => 0, // diupdate setelah semua order dibuat
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if (!$transactionInsert) {
                throw new \Exception('Gagal menyimpan transaction: ' . $this->db->error()['message']);
            }

            // ================================
            // 2. CREATE ORDER PER SUPPLIER
            // ================================
            $createdOrderIds = [];
            $createdOrderMeta = []; // menyimpan order_id string & supplier_id untuk response
            $orderIndex = 0;

            foreach ($itemsBySupplier as $supplierId => $items) {
                $orderIndex++;

                if ($isMultiMode) {
                    // Ambil fee khusus untuk supplier ini
                    $oShippingFee = $shippingFees[$supplierId] ?? 0;
                    $oAppFee = $appFees[$supplierId] ?? 0;
                    $oTaxAmount = $taxAmounts[$supplierId] ?? 0;

                    // Hitung subtotal produk untuk order ini
                    $oSubtotal = 0;
                    foreach ($items as $item) {
                        $oSubtotal += $item['product_price'] * $item['quantity'];
                    }
                    $oTotalPrice = $oSubtotal + (float) $oShippingFee + (float) $oAppFee + (float) $oTaxAmount;
                } else {
                    // Legacy: satu order menggunakan fee flat dari request
                    $oShippingFee = $shippingFee;
                    $oAppFee = $appFee;
                    $oTaxAmount = $taxAmount;
                    $oTotalPrice = $totalPrice;
                }

                // Buat order_id unik per order (suffix index agar tidak collision dalam 1 detik)
                $orderIdUnique = 'PASANGIN-' . time() . '-' . $userId . '-' . $orderIndex;

                $orderInsert = $this->db->table('orders')->insert([
                    'order_id' => $orderIdUnique,
                    'user_id' => $userId,
                    'recipient_name' => $recipientName,
                    'recipient_phone' => $recipientPhone,
                    'total_price' => $oTotalPrice,
                    'shipping_fee' => $oShippingFee,
                    'app_fee' => $oAppFee,
                    'tax_amount' => $oTaxAmount,
                    'status' => 'UNPAID',
                    'shipping_address' => $address,
                    'latitude' => $lat,
                    'longitude' => $long,
                    'voucher_code' => $voucher,
                    'discount_amount' => $discount,
                    'transaction_id' => $transactionIdUnique,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                if (!$orderInsert) {
                    throw new \Exception('Gagal menyimpan order ke-' . $orderIndex . ': ' . $this->db->error()['message']);
                }

                $dbOrderId = $this->db->insertID();
                $createdOrderIds[] = $dbOrderId;
                $createdOrderMeta[] = [
                    'db_id' => $dbOrderId,
                    'order_id' => $orderIdUnique,
                    'supplier_id' => $supplierId === '_legacy' ? null : (int) $supplierId,
                    'total_price' => $oTotalPrice,
                ];

                // =====================================
                // 3. INSERT ORDER ITEMS & REDUCE STOCK
                // =====================================
                foreach ($items as $item) {
                    $itemInsert = $this->db->table('order_items')->insert([
                        'order_id' => $dbOrderId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['product_price']
                    ]);

                    if (!$itemInsert) {
                        throw new \Exception('Gagal menyimpan order_item: ' . $this->db->error()['message']);
                    }

                    // Kurangi stok produk
                    $stockUpdate = $this->db->table('products')
                        ->set('stock', 'stock - ' . (int)$item['quantity'], false)
                        ->where('id', $item['product_id'])
                        ->update();

                    if (!$stockUpdate) {
                        throw new \Exception('Gagal memperbarui stok produk.');
                    }
                }
            }

            // ================================
            // 4. HAPUS CART
            // ================================
            if ($selectedCartIds && is_array($selectedCartIds)) {
                $this->db->table('cart')->whereIn('id', $selectedCartIds)->delete();
            } else {
                $this->db->table('cart')->where('user_id', $userId)->delete();
            }

            // ================================
            // 5. UPDATE ORDER COUNT
            // ================================
            $this->db->table('transactions')
                ->where('transaction_id', $transactionIdUnique)
                ->update(['order_count' => count($createdOrderIds)]);

            // ================================
            // 6. COMMIT DB
            // ================================
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction DB gagal');
            }

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->fail('Error checkout: ' . $e->getMessage());
        }

        // ================================
        // 7. MIDTRANS (1 payment untuk seluruh transaksi)
        // ================================
        try {
            require_once APPPATH . 'ThirdParty/Midtrans/Midtrans.php';

            \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = filter_var(getenv('MIDTRANS_IS_PRODUCTION'), FILTER_VALIDATE_BOOLEAN);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transactionIdUnique,
                    'gross_amount' => (int) $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $recipientName,
                    'phone' => $recipientPhone,
                ],
            ];

            $midtransTransaction = \Midtrans\Snap::createTransaction($params);

            // Kirim Notifikasi ke Client
            $this->notifService->sendPersonal(
                'client',
                (int) $userId,
                'Checkout Berhasil',
                'Pesanan Anda telah berhasil dibuat. Silakan selesaikan pembayaran untuk melanjutkan.'
            );

            return $this->respond([
                'status' => true,
                'transaction_id' => $transactionIdUnique,
                'order_count' => count($createdOrderIds),
                'orders' => $createdOrderMeta,
                'redirect_url' => $midtransTransaction->redirect_url
            ]);

        } catch (\Exception $e) {
            return $this->fail('Midtrans error: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan riwayat pesanan milik user
     */
    public function history()
    {
        $userId = $this->request->user->uid;
        $data = $this->db->table('orders')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Ambil rincian produk di dalam satu pesanan
     */
    public function detail($orderId)
    {
        //tambahkan informasi pesanan ini menggunakan voucher apa, berapa potongan harga, total sebelum diskon, total setelah diskon.
        $data = $this->db->table('order_items')
            ->select('order_items.*, orders.voucher_code, orders.discount_amount, orders.tax_amount, orders.app_fee, orders.shipping_fee, products.name, products.photo')
            ->join('products', 'products.id = order_items.product_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->where('order_items.order_id', $orderId)
            ->get()->getResultArray();

        foreach ($data as &$item) {
            $item['image_url'] = base_url('uploads/products/' . ($item['photo'] ?? 'default.png'));
        }

        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Menghapus Order beserta item di dalamnya
     */
    public function delete($id)
    {
        $order = $this->db->table('orders')->where('id', $id)->get()->getRow();

        if (!$order) {
            return $this->failNotFound('Order tidak ditemukan.');
        }

        $this->db->transStart();

        // Kembalikan stok produk jika status order yang dihapus adalah UNPAID
        if ($order->status === 'UNPAID') {
            $orderItems = $this->db->table('order_items')
                ->where('order_id', $id)
                ->get()
                ->getResultArray();

            foreach ($orderItems as $item) {
                $this->db->table('products')
                    ->set('stock', 'stock + ' . (int)$item['quantity'], false)
                    ->where('id', $item['product_id'])
                    ->update();
            }
        }

        // Hapus item order terlebih dahulu
        $this->db->table('order_items')->where('order_id', $id)->delete();

        // Hapus order utama
        $this->db->table('orders')->where('id', $id)->delete();

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->fail('Gagal menghapus order.');
        }

        return $this->respondDeleted([
            'status' => true,
            'message' => 'Order berhasil dihapus.'
        ]);
    }

    /**
     * Ambil detail transaction dengan semua orders yang terkait
     */
    public function transactionDetail($transactionId)
    {
        // Ambil data transaction
        $transaction = $this->db->table('transactions')
            ->where('transaction_id', $transactionId)
            ->get()
            ->getRow();

        if (!$transaction) {
            return $this->fail('Transaksi tidak ditemukan.');
        }

        // Ambil semua orders yang terkait dengan transaction ini
        $orders = $this->db->table('orders')
            ->where('transaction_id', $transactionId)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        // Untuk setiap order, ambil list items
        foreach ($orders as &$order) {
            $order['items'] = $this->db->table('order_items')
                ->select('order_items.*, products.name, products.photo')
                ->join('products', 'products.id = order_items.product_id')
                ->where('order_id', $order['id'])
                ->get()
                ->getResultArray();

            foreach ($order['items'] as &$item) {
                $item['image_url'] = base_url('uploads/products/' . ($item['photo'] ?? 'default.png'));
            }
        }

        return $this->respond([
            'status' => true,
            'transaction' => $transaction,
            'orders' => $orders
        ]);
    }

    /**
     * Webhook callback dari Midtrans untuk update status pembayaran
     */
    public function webhookMidtrans()
    {
        try {
            // Validasi signature dari Midtrans
            require_once APPPATH . 'ThirdParty/Midtrans/Midtrans.php';
            \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');

            $json = file_get_contents('php://input');
            $notif = json_decode($json);

            // Update transaction status berdasarkan Midtrans response
            $transactionId = $notif->order_id;
            $transaction = $this->db->table('transactions')
                ->where('transaction_id', $transactionId)
                ->get()
                ->getRow();

            if (!$transaction) {
                return $this->fail('Transaksi tidak ditemukan');
            }

            $status = $notif->transaction_status;

            // Map Midtrans status ke aplikasi
            $mappedStatus = 'PENDING';
            if ($status == 'capture' || $status == 'settlement') {
                $mappedStatus = 'PAID';
                // Update SEMUA orders dalam transaksi ini ke PAID
                $this->db->table('orders')
                    ->where('transaction_id', $transactionId)
                    ->update(['status' => 'PAID']);

                // Kirim Notifikasi ke Client
                $this->notifService->sendPersonal(
                    'client',
                    (int) $transaction->user_id,
                    'Pembayaran Berhasil',
                    'Terima kasih! Pembayaran Anda untuk transaksi ' . $transactionId . ' telah kami terima.'
                );

                // Kirim Notifikasi ke Supplier
                $suppliers = $this->db->table('orders')
                    ->select('products.supplier_id')
                    ->join('order_items', 'order_items.order_id = orders.id')
                    ->join('products', 'products.id = order_items.product_id')
                    ->where('orders.transaction_id', $transactionId)
                    ->distinct()
                    ->get()
                    ->getResultArray();

                foreach ($suppliers as $s) {
                    if ($s['supplier_id']) {
                        $this->notifService->sendPersonal(
                            'supplier',
                            (int) $s['supplier_id'],
                            'Pesanan Baru',
                            'Anda mendapatkan pesanan baru! Silakan cek notifikasi pesanan Anda.'
                        );
                    }
                }
            } elseif ($status == 'deny' || $status == 'cancel' || $status == 'expire') {
                $mappedStatus = 'FAILED';

                // Kembalikan stok produk jika status transaksi sebelumnya masih PENDING
                if ($transaction->status === 'PENDING') {
                    $orders = $this->db->table('orders')
                        ->where('transaction_id', $transactionId)
                        ->get()
                        ->getResultArray();

                    foreach ($orders as $order) {
                        $orderItems = $this->db->table('order_items')
                            ->where('order_id', $order['id'])
                            ->get()
                            ->getResultArray();

                        foreach ($orderItems as $item) {
                            $this->db->table('products')
                                ->set('stock', 'stock + ' . (int)$item['quantity'], false)
                                ->where('id', $item['product_id'])
                                ->update();
                        }
                    }
                }

                // Update SEMUA orders dalam transaksi ini ke CANCELLED
                $this->db->table('orders')
                    ->where('transaction_id', $transactionId)
                    ->update(['status' => 'CANCELLED']);
            }

            // Update transaction
            $this->db->table('transactions')
                ->where('transaction_id', $transactionId)
                ->update(['status' => $mappedStatus, 'updated_at' => date('Y-m-d H:i:s')]);

            return $this->respond(['status' => true, 'message' => 'Webhook processed']);

        } catch (\Exception $e) {
            log_message('error', 'Midtrans Webhook Error: ' . $e->getMessage());
            return $this->fail('Webhook error: ' . $e->getMessage());
        }
    }

    /**
     * Ambil list transactions user dengan summary orders
     */
    public function transactionHistory()
    {
        $userId = $this->request->user->uid;

        $transactions = $this->db->table('transactions')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($transactions as &$txn) {
            // Hitung jumlah orders dan status
            $orders = $this->db->table('orders')
                ->where('transaction_id', $txn['transaction_id'])
                ->get()
                ->getResultArray();

            $txn['orders_detail'] = $orders;
        }

        return $this->respond([
            'status' => true,
            'data' => $transactions
        ]);
    }
}