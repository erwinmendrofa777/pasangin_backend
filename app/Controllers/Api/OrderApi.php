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
                        ->set('stock', 'stock - ' . (int) $item['quantity'], false)
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

            $customOrderId = $transactionIdUnique . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $customOrderId,
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
                'midtrans_order_id' => $customOrderId,
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


        $orders = $this->db->table('orders')
            ->select('orders.id, orders.transaction_id, orders.order_id, orders.status, orders.created_at, orders.recipient_name, orders.recipient_phone, orders.shipping_address, orders.total_price, orders.discount_amount, orders.tax_amount, orders.app_fee, orders.shipping_fee, transactions.id as id_transaction, transactions.total_amount as transaction_total_amount')
            ->join('transactions', 'transactions.transaction_id = orders.transaction_id')
            ->where('orders.user_id', $userId)
            ->orderBy('orders.id', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($orders as &$order) {
            $order['items'] = $this->db->table('order_items')
                ->select('order_items.quantity, order_items.price, products.name, products.photo')
                ->join('products', 'products.id = order_items.product_id')
                ->where('order_items.order_id', $order['id'])
                ->get()
                ->getResultArray();

            foreach ($order['items'] as &$item) {
                $item['image_url'] = base_url('uploads/products/' . ($item['photo'] ?? 'default.png'));
                unset($item['photo']); // Pangkas data photo
            }
        }

        return $this->respond([
            'status' => true,
            'data' => $orders
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
    public function delete($transactionId)
    {
        // Ambil data transaksi
        $transaction = $this->db->table('transactions')
            ->where('transaction_id', $transactionId)
            ->get()
            ->getRow();

        if (!$transaction) {
            return $this->failNotFound('Transaksi tidak ditemukan.');
        }

        if ($transaction->status === 'FAILED') {
            return $this->respond([
                'status' => true,
                'message' => 'Transaksi sudah dibatalkan sebelumnya.'
            ]);
        }

        // Ambil semua orders yang terkait dengan transaksi ini
        $orders = $this->db->table('orders')
            ->where('transaction_id', $transactionId)
            ->get()
            ->getResultArray();

        if (empty($orders)) {
            return $this->failNotFound('Order tidak ditemukan.');
        }

        $this->db->transStart();

        foreach ($orders as $order) {
            // Kembalikan stok produk jika status order adalah UNPAID
            if ($order['status'] === 'UNPAID') {
                $orderItems = $this->db->table('order_items')
                    ->where('order_id', $order['id']) // Menggunakan order ID (integer)
                    ->get()
                    ->getResultArray();

                foreach ($orderItems as $item) {
                    $this->db->table('products')
                        ->set('stock', 'stock + ' . (int) $item['quantity'], false)
                        ->where('id', $item['product_id'])
                        ->update();
                }
            }

            // Ubah status order menjadi CANCELLED
            $this->db->table('orders')
                ->where('id', $order['id'])
                ->update(['status' => 'CANCELLED']);
        }

        // Ubah status transaksi menjadi FAILED jika transaksi masih PENDING
        if ($transaction->status === 'PENDING') {
            $this->db->table('transactions')
                ->where('transaction_id', $transactionId)
                ->update(['status' => 'FAILED']);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->fail('Gagal membatalkan transaksi.');
        }

        // Kirim Notifikasi ke Client/User jika transaksi sudah dibatalkan
        $this->notifService->sendPersonal(
            'client',
            (int) $transaction->user_id,
            'Pesanan Dibatalkan',
            "Pesanan Anda untuk transaksi {$transactionId} telah dibatalkan."
        );

        return $this->respondDeleted([
            'status' => true,
            'message' => 'Transaksi berhasil dibatalkan.'
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

            // Extract base transaction ID if it has a suffix (TRX-{timestamp}-{userId}-{timestamp})
            if (strpos($transactionId, 'TRX-') === 0) {
                $parts = explode('-', $transactionId);
                if (count($parts) > 3) {
                    $transactionId = implode('-', array_slice($parts, 0, 3));
                }
            }

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
                                ->set('stock', 'stock + ' . (int) $item['quantity'], false)
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

    /**
     * Konfirmasi pesanan diterima oleh pelanggan
     * POST api/orders/complete/(:num)
     */
    public function complete($orderId)
    {
        $userId = $this->request->user->uid;

        // Ambil data order
        $order = $this->db->table('orders')->where('id', $orderId)->get()->getRow();
        if (!$order) {
            return $this->failNotFound('Pesanan tidak ditemukan.');
        }

        // Validasi kepemilikan pesanan
        if ((int) $order->user_id !== (int) $userId) {
            return $this->failForbidden('Anda tidak memiliki akses untuk pesanan ini.');
        }

        // Cek status saat ini
        if ($order->status === 'COMPLETED') {
            return $this->respond([
                'status' => true,
                'message' => 'Pesanan sudah diselesaikan sebelumnya.'
            ]);
        }

        // Hanya pesanan yang sudah dibayar atau dikirim yang bisa diselesaikan
        if (in_array($order->status, ['CANCELLED', 'UNPAID', 'FAILED'])) {
            return $this->fail('Pesanan yang dibatalkan, belum dibayar, atau gagal tidak dapat diselesaikan.');
        }

        // Jika pesanan terkait proyek konstruksi, harus sudah dilaporkan sampai oleh mandor (ARRIVED)
        if (!empty($order->construction_invoice_id) && $order->status !== 'ARRIVED') {
            return $this->fail('Pesanan proyek konstruksi belum dilaporkan sampai oleh mandor.');
        }

        try {
            $this->db->transStart();

            // 1. Update status pesanan menjadi COMPLETED
            $this->db->table('orders')
                ->where('id', $orderId)
                ->update([
                    'status' => 'COMPLETED',
                    'client_confirmed_at' => date('Y-m-d H:i:s')
                ]);

            // 2. Ambil semua item produk di pesanan ini untuk mendapatkan supplier_id & menghitung subtotal
            $items = $this->db->table('order_items')
                ->select('order_items.price, order_items.quantity, products.supplier_id')
                ->join('products', 'products.id = order_items.product_id')
                ->where('order_items.order_id', $orderId)
                ->get()
                ->getResultArray();

            if (empty($items)) {
                throw new \Exception('Item pesanan tidak ditemukan.');
            }

            $supplierId = null;
            $totalProductAmount = 0;
            foreach ($items as $item) {
                $totalProductAmount += (float) $item['price'] * (int) $item['quantity'];
                $supplierId = $item['supplier_id']; // Dipecah per supplier di checkout, jadi semua item di order yang sama memiliki supplier_id yang sama
            }

            // Tambahkan ongkir (shipping_fee) ke biaya yang dikirim ke supplier
            $shippingFee = (float) ($order->shipping_fee ?? 0);
            $totalSupplierAmount = $totalProductAmount + $shippingFee;

            // 3. Tambahkan nominal total ke kolom balance di tabel suppliers
            if ($supplierId && $totalSupplierAmount > 0) {
                $this->db->table('suppliers')
                    ->where('id', $supplierId)
                    ->set('balance', 'balance + ' . $totalSupplierAmount, false)
                    ->update();

                // 4. Catat riwayat transaksi supplier ke tabel supplier_transactions (Dipisah antara total produk & ongkir)
                if ($totalProductAmount > 0) {
                    $this->db->table('supplier_transactions')->insert([
                        'supplier_Id' => $supplierId,
                        'amount' => $totalProductAmount,
                        'type' => 'income',
                        'description' => 'Pendapatan produk dari pesanan ' . $order->order_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                if ($shippingFee > 0) {
                    $this->db->table('supplier_transactions')->insert([
                        'supplier_Id' => $supplierId,
                        'amount' => $shippingFee,
                        'type' => 'income',
                        'description' => 'Ongkos kirim dari pesanan ' . $order->order_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            // 5. Tambahkan biaya aplikasi (app_fee) & pajak (tax_amount) ke Saldo Admin secara otomatis
            $adminBalanceSvc = new \App\Modules\Wallets\Services\AdminBalanceService();

            $appFee = (float) ($order->app_fee ?? 0);
            if ($appFee > 0) {
                $adminBalanceSvc->addTransaction(
                    $appFee,
                    'income',
                    'order_app_fee',
                    $order->order_id,
                    'Biaya aplikasi dari pesanan ' . $order->order_id
                );
            }

            $taxAmount = (float) ($order->tax_amount ?? 0);
            if ($taxAmount > 0) {
                $adminBalanceSvc->addTransaction(
                    $taxAmount,
                    'income',
                    'order_tax_amount',
                    $order->order_id,
                    'Pajak dari pesanan ' . $order->order_id
                );
            }


            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal memproses transaksi di database.');
            }

            // ==========================================
            // 4. KIRIM NOTIFIKASI
            // ==========================================
            $amountFormatted = number_format($totalSupplierAmount, 0, ',', '.');

            // Notifikasi ke Client (Pembeli)
            $this->notifService->sendPersonal(
                'client',
                (int) $userId,
                'Pesanan Selesai',
                "Terima kasih! Pesanan Anda dengan kode {$order->order_id} telah selesai."
            );

            // Notifikasi ke Supplier
            if ($supplierId) {
                $this->notifService->sendPersonal(
                    'supplier',
                    (int) $supplierId,
                    'Dana Pesanan Masuk',
                    "Pesanan {$order->order_id} telah selesai. Dana sebesar Rp {$amountFormatted} telah ditambahkan ke saldo Anda."
                );
            }

            return $this->respond([
                'status' => true,
                'message' => 'Pesanan berhasil diselesaikan dan saldo supplier telah diperbarui.'
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->fail('Gagal menyelesaikan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi pesanan diterima oleh mandor (sampai di lokasi proyek)
     * POST api/orders/mandor-confirm/(:num)
     */
    public function mandorConfirm($orderId)
    {
        $user = $this->request->user;
        if (!in_array($user->role, ['tukang', 'mandor'])) {
            return $this->failForbidden('Akses khusus mandor atau tukang.');
        }

        $order = $this->db->table('orders')->where('id', $orderId)->get()->getRow();
        if (!$order) {
            return $this->failNotFound('Pesanan tidak ditemukan.');
        }

        // Hanya pesanan yang sedang dikirim yang bisa dikonfirmasi sampai
        if ($order->status !== 'SHIPPED') {
            return $this->fail('Hanya pesanan berstatus SHIPPED (sedang dikirim) yang dapat dikonfirmasi sampai.');
        }

        // Upload foto bukti penerimaan
        $file = $this->request->getFile('delivery_photo');
        if (!$file || !$file->isValid()) {
            return $this->fail('Foto bukti pengiriman (delivery_photo) wajib diunggah.');
        }

        // Validasi folder upload
        $uploadPath = FCPATH . 'uploads/deliveries';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        if (!$file->move($uploadPath, $newName)) {
            return $this->fail('Gagal menyimpan foto bukti pengiriman.');
        }

        $notes = $this->request->getVar('delivery_notes') ?? '';

        try {
            $this->db->transStart();

            $this->db->table('orders')
                ->where('id', $orderId)
                ->update([
                    'status' => 'ARRIVED',
                    'delivery_photo' => $newName,
                    'delivery_notes' => $notes,
                    'mandor_confirmed_at' => date('Y-m-d H:i:s')
                ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal memperbarui status pengiriman di database.');
            }

            // Kirim notifikasi ke client (pemilik order)
            $this->notifService->sendPersonal(
                'client',
                (int) $order->user_id,
                'Pesanan Sampai di Lokasi',
                "Pesanan {$order->order_id} telah diterima oleh mandor. Silakan periksa laporan fisik dan konfirmasi pesanan Anda."
            );

            return $this->respond([
                'status' => true,
                'message' => 'Konfirmasi penerimaan pesanan berhasil disimpan.'
            ]);

        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }
}