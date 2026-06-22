<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class PaymentApi extends BaseController
{
    use ResponseTrait;

    protected $db;
    protected $notifService;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notifService = new \App\Modules\Notifications\Services\NotificationService();
        // Memuat helper ThirdParty Midtrans
        require_once APPPATH . 'ThirdParty/Midtrans/Midtrans.php';

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = filter_var(getenv('MIDTRANS_IS_PRODUCTION'), FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    /**
     * FUNGSI TAGIHAN PRODUK/TRANSAKSI (TRX-...)
     */
    public function getPaymentToken($id)
    {
        $transaction = $this->db->table('transactions')->where('id', $id)->get()->getRowArray();
        if (!$transaction) {
            return $this->failNotFound('Transaksi tidak ditemukan.');
        }

        // Cek jika transaksi sudah dibayar
        if (strtoupper($transaction['status']) === 'PAID') {
            return $this->fail('Transaksi ini sudah lunas.');
        }

        $userId = $transaction['user_id'];
        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            return $this->failNotFound('User tidak ditemukan.');
        }

        $transactionIdUnique = $transaction['transaction_id'];
        $customOrderId = $transactionIdUnique . '-' . time();
        $grossAmount = (int) $transaction['total_amount'];

        // Ambil salah satu order untuk mendapatkan data penerima (opsional, sebagai fallback)
        $order = $this->db->table('orders')
            ->where('transaction_id', $transactionIdUnique)
            ->get()
            ->getRowArray();

        $recipientName = $order['recipient_name'] ?? $user['full_name'] ?? 'Pelanggan';
        $recipientPhone = $order['recipient_phone'] ?? $user['phone_number'] ?? '08123456789';

        $params = [
            'transaction_details' => [
                'order_id' => $customOrderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $recipientName,
                'email' => $user['email'] ?? 'customer@example.com',
                'phone' => $recipientPhone,
            ],
        ];

        try {
            $midtransTransaction = \Midtrans\Snap::createTransaction($params);
            return $this->respond([
                'status' => true,
                'transaction_id' => $transactionIdUnique,
                'midtrans_order_id' => $customOrderId,
                'gross_amount' => $grossAmount,
                'redirect_url' => $midtransTransaction->redirect_url,
                'order_id' => $customOrderId
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * FUNGSI TAGIHAN DESAIN
     */
    public function getDesignPaymentToken($invoiceId, $voucherCode = null)
    {
        $invoice = $this->db->table('project_invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (!$invoice)
            return $this->failNotFound('Tagihan Desain tidak ditemukan.');

        $grossAmount = (int) ($invoice['amount'] ?? 0);

        // Jika voucherCode tidak dari URL segment, coba ambil dari request
        if (empty($voucherCode)) {
            $voucherCode = $this->request->getGet('voucher_code') ?? $this->request->getPost('voucher_code');
        }

        if (!empty($voucherCode)) {
            $voucher = $this->db->table('vouchers')
                ->where('code', $voucherCode)
                ->where('is_active', 1)
                ->where('valid_until >=', date('Y-m-d'))
                ->get()
                ->getRowArray();

            if (!$voucher) {
                return $this->fail('Kode voucher tidak valid atau sudah kedaluwarsa.');
            }

            $discount = (int) $voucher['discount_nominal'];
            $grossAmount = max(0, $grossAmount - $discount);

            $this->db->table('project_invoices')->where('id', $invoiceId)->update(['voucher_code' => $voucherCode]);
        }

        $designRequest = $this->db->table('design_requests')->where('id', $invoice['design_request_id'])->get()->getRowArray();
        return $this->createMidtransTransaction($designRequest['user_id'], $invoice['id'], 'project_invoices', $grossAmount);
    }

    /**
     * FUNGSI TAGIHAN KONSTRUKSI
     */
    public function getConstructionPaymentToken($invoiceId, $voucherCode = null)
    {
        $invoice = $this->db->table('construction_invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (!$invoice)
            return $this->failNotFound('Tagihan Konstruksi tidak ditemukan.');

        $grossAmount = (int) ($invoice['amount'] ?? 0);

        if (empty($voucherCode)) {
            $voucherCode = $this->request->getGet('voucher_code') ?? $this->request->getPost('voucher_code');
        }

        if (!empty($voucherCode)) {
            $voucher = $this->db->table('vouchers')
                ->where('code', $voucherCode)
                ->where('is_active', 1)
                ->where('valid_until >=', date('Y-m-d'))
                ->get()
                ->getRowArray();

            if (!$voucher) {
                return $this->fail('Kode voucher tidak valid atau sudah kedaluwarsa.');
            }

            $discount = (int) $voucher['discount_nominal'];
            $grossAmount = max(0, $grossAmount - $discount);

            $this->db->table('construction_invoices')->where('id', $invoiceId)->update(['voucher_code' => $voucherCode]);
        }

        return $this->createMidtransTransaction($invoice['user_id'], $invoice['id'], 'construction_invoices', $grossAmount);
    }

    /**
     * FUNGSI TAGIHAN RENOVASI
     */
    public function getRenovationPaymentToken($invoiceId, $voucherCode = null)
    {
        $invoice = $this->db->table('renovation_invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (!$invoice)
            return $this->failNotFound('Tagihan Renovasi tidak ditemukan.');

        $grossAmount = (int) ($invoice['amount'] ?? 0);

        if (empty($voucherCode)) {
            $voucherCode = $this->request->getGet('voucher_code') ?? $this->request->getPost('voucher_code');
        }

        if (!empty($voucherCode)) {
            $voucher = $this->db->table('vouchers')
                ->where('code', $voucherCode)
                ->where('is_active', 1)
                ->where('valid_until >=', date('Y-m-d'))
                ->get()
                ->getRowArray();

            if (!$voucher) {
                return $this->fail('Kode voucher tidak valid atau sudah kedaluwarsa.');
            }

            $discount = (int) $voucher['discount_nominal'];
            $grossAmount = max(0, $grossAmount - $discount);

            $this->db->table('renovation_invoices')->where('id', $invoiceId)->update(['voucher_code' => $voucherCode]);
        }

        return $this->createMidtransTransaction($invoice['user_id'], $invoice['id'], 'renovation_invoices', $grossAmount);
    }

    /**
     * HELPER: MEMBUAT TRANSAKSI MIDTRANS
     */
    private function createMidtransTransaction($userId, $invoiceId, $tableName, $grossAmount)
    {
        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user)
            return $this->failNotFound('User tidak ditemukan.');

        // Ambil data asli untuk detail item
        $invoice = $this->db->table($tableName)->where('id', $invoiceId)->get()->getRowArray();
        $originalAmount = (int) ($invoice['amount'] ?? 0);
        $discount = $originalAmount - $grossAmount;

        $customOrderId = $tableName . '-' . $invoiceId . '-' . time();

        $itemDetails = [
            [
                'id' => 'ITEM-' . $invoiceId,
                'price' => $originalAmount,
                'quantity' => 1,
                'name' => 'Tagihan ' . ucfirst(str_replace('_invoices', '', $tableName)),
            ]
        ];

        // Jika ada diskon, tambahkan sebagai item minus
        if ($discount > 0) {
            $itemDetails[] = [
                'id' => 'DISC-' . $invoiceId,
                'price' => -$discount,
                'quantity' => 1,
                'name' => 'Voucher: ' . ($invoice['voucher_code'] ?? 'Promo'),
            ];
        }

        $params = [
            'transaction_details' => ['order_id' => $customOrderId, 'gross_amount' => (int) $grossAmount],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $user['full_name'] ?? 'Pelanggan',
                'email' => $user['email'] ?? 'customer@example.com',
                'phone' => $user['phone_number'] ?? '08123456789',
            ],
        ];

        try {
            $transaction = \Midtrans\Snap::createTransaction($params);
            $this->db->table($tableName)->where('id', $invoiceId)->update(['midtrans_order_id' => $customOrderId]);
            return $this->respond([
                'status' => true,
                'invoice_amount' => $originalAmount,
                'discount_amount' => $discount,
                'gross_amount' => (int) $grossAmount,
                'redirect_url' => $transaction->redirect_url,
                'order_id' => $customOrderId
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * CEK STATUS MANUAL DARI FLUTTER
     */
    public function checkStatus($orderId = null)
    {
        if (empty($orderId))
            return $this->fail('Order ID kosong.');
        $this->_updatePaymentStatus($orderId);
        return $this->respond(['status' => true, 'message' => 'Status Updated']);
    }

    /**
     * WEBHOOK NOTIFIKASI OTOMATIS
     */
    public function notification()
    {
        try {
            $notif = new \Midtrans\Notification();
            $this->_updatePaymentStatus($notif->order_id);
            return $this->response->setStatusCode(200);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400);
        }
    }

    /**
     * LOGIKA INTI UPDATE STATUS DATABASE
     */
    private function _updatePaymentStatus($orderId)
    {
        if (empty($orderId))
            return;

        try {
            $status = \Midtrans\Transaction::status($orderId);
            $transactionStatus = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

            // Extract base transaction ID if it has a suffix (TRX-{timestamp}-{userId}-{timestamp})
            $lookupId = $orderId;
            if (strpos($orderId, 'TRX-') === 0) {
                $parts = explode('-', $orderId);
                if (count($parts) > 3) {
                    $lookupId = implode('-', array_slice($parts, 0, 3));
                }
            }

            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {

                $tableName = '';
                $statusColumn = 'status';
                $idColumn = 'midtrans_order_id';

                if (strpos($orderId, 'project_invoices-') === 0) {
                    $tableName = 'project_invoices';
                    $statusColumn = 'payment_status';
                } elseif (strpos($orderId, 'construction_invoices-') === 0) {
                    $tableName = 'construction_invoices';
                } elseif (strpos($orderId, 'renovation_invoices-') === 0) {
                    $tableName = 'renovation_invoices';
                }
                // --- INI UNTUK PESANAN PRODUK PASANGIN ---
                elseif (strpos($orderId, 'TRX-') === 0) {
                    $tableName = 'orders';
                    $idColumn = 'transaction_id';
                }

                if (!empty($tableName)) {
                    // Ambil data invoice/order  
                    $dataObj = $this->db->table($tableName)->where($idColumn, $lookupId)->get()->getRowArray();

                    if ($dataObj) {
                        // Cek status saat ini untuk mencegah pemrosesan ulang/duplikasi transaksi saldo
                        $currentStatus = $dataObj[$statusColumn] ?? '';

                        if (strtoupper($currentStatus) !== 'PAID') {
                            // Update Status  
                            $this->db->table($tableName)->where($idColumn, $lookupId)->update([$statusColumn => 'PAID']);

                            // Jika pembayaran ini untuk tambahan kuota revisi desain, tambahkan kuota
                            if ($tableName === 'project_invoices' && strpos($dataObj['description'] ?? '', 'Tambahan Kuota Revisi') !== false) {
                                $qty = 1;
                                if (preg_match('/Tambahan Kuota Revisi \((\d+)x\)/', $dataObj['description'] ?? '', $matches)) {
                                    $qty = (int) $matches[1];
                                }
                                $this->db->table('design_requests')
                                    ->where('id', $dataObj['design_request_id'])
                                    ->set('max_revision', 'max_revision + ' . $qty, false)
                                    ->update();
                            }

                            // Update transactions table as well
                            if ($tableName == 'orders') {
                                $this->db->table('transactions')
                                    ->where('transaction_id', $lookupId)
                                    ->update(['status' => 'PAID', 'updated_at' => date('Y-m-d H:i:s')]);
                            }

                            // =========================================================================
                            // SINKRONISASI SALDO ADMIN DENGAN TRANSAKSI MIDTRANS
                            // =========================================================================
                            $alreadyLogged = $this->db->table('admin_transactions')
                                ->where('reference_id', $orderId)
                                ->where('source', 'midtrans_payin')
                                ->get()
                                ->getRow();

                            if (!$alreadyLogged) {
                                $grossAmount = is_array($status) ? (float) ($status['gross_amount'] ?? 0) : (float) ($status->gross_amount ?? 0);
                                $fee = $this->_calculateMidtransFee($status);

                                $adminBalanceSvc = new \App\Modules\Wallets\Services\AdminBalanceService();
                                try {
                                    // 1. Catat uang masuk (gross)
                                    $adminBalanceSvc->addTransaction(
                                        $grossAmount,
                                        'income',
                                        'midtrans_payin',
                                        $orderId,
                                        'Pembayaran ' . ucfirst(str_replace('_invoices', '', $tableName)) . ' via Midtrans (Order ID: ' . $orderId . ')'
                                    );

                                    // 2. Catat biaya layanan Midtrans (pengeluaran)
                                    if ($fee > 0) {
                                        $adminBalanceSvc->addTransaction(
                                            $fee,
                                            'expense',
                                            'midtrans_fee',
                                            $orderId,
                                            'Biaya layanan Midtrans untuk Order ID: ' . $orderId
                                        );
                                    }
                                } catch (\Exception $e) {
                                    log_message('error', 'Gagal mencatat saldo admin otomatis dari Midtrans: ' . $e->getMessage());
                                }
                            }
                            // =========================================================================

                            $userId = $dataObj['user_id'] ?? null;
                            $title = "Pembayaran Berhasil";
                            if ($tableName == 'orders') {
                                $message = "Terima kasih! Pembayaran Anda untuk transaksi {$lookupId} telah kami terima.";
                            } else {
                                $message = "Terima kasih! Pembayaran Anda untuk tagihan {$orderId} telah kami terima.";
                            }
                            $permission = "";

                            // Tentukan Permission Admin  
                            if ($tableName == 'project_invoices') {
                                $permission = 'design_pembayaran';
                            } elseif ($tableName == 'construction_invoices') {
                                $permission = 'construction_pembayaran';
                            } elseif ($tableName == 'renovation_invoices') {
                                $permission = 'renovation_pembayaran';
                            } elseif ($tableName == 'orders') {
                                $permission = 'order_view';
                            }

                            // 1. Kirim Notif ke Client  
                            if ($userId) {
                                $this->notifService->sendPersonal('client', (int) $userId, $title, $message);
                            }

                            // 2. Kirim Notif ke Admin  
                            if ($permission) {
                                $this->notifService->sendToPermission($permission, "Pembayaran Masuk", "Pembayaran lunas untuk tagihan {$orderId}.");
                            }

                            // 3. Kirim Notif ke Supplier (untuk orders)
                            if ($tableName == 'orders') {
                                $suppliers = $this->db->table('orders')
                                    ->select('products.supplier_id')
                                    ->join('order_items', 'order_items.order_id = orders.id')
                                    ->join('products', 'products.id = order_items.product_id')
                                    ->where('orders.transaction_id', $lookupId)
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
                            }

                            // =========================================================================
                        }

                        // =========================================================================
                        // 4. OTOMATIS BUAT PESANAN MATERIAL KONSTRUKSI (Self-healing / Run jika status PAID)
                        // =========================================================================
                        if ($tableName == 'construction_invoices') {
                            $rabId = $dataObj['rab_id'] ?? null;
                            if ($rabId) {
                                $this->_createOrdersForRab((int) $dataObj['id'], $rabId, $dataObj['user_id'] ?? null, $dataObj['construction_id'] ?? null);
                            }
                        }
                    }
                }
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                if (strpos($orderId, 'TRX-') === 0) {
                    $transaction = $this->db->table('transactions')
                        ->where('transaction_id', $lookupId)
                        ->get()
                        ->getRow();

                    if ($transaction && $transaction->status === 'PENDING') {
                        // Kembalikan stok produk
                        $orders = $this->db->table('orders')
                            ->where('transaction_id', $lookupId)
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

                        // Update status orders ke CANCELLED
                        $this->db->table('orders')
                            ->where('transaction_id', $lookupId)
                            ->update(['status' => 'CANCELLED']);

                        // Update status transaction ke FAILED
                        $this->db->table('transactions')
                            ->where('transaction_id', $lookupId)
                            ->update(['status' => 'FAILED', 'updated_at' => date('Y-m-d H:i:s')]);
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Update Status Error: ' . $e->getMessage());
        }
    }

    /**
     * Menghitung biaya admin Midtrans berdasarkan tipe pembayaran + PPN 11%.
     * Sesuai dengan tarif resmi akun PT. Indrayata Architecture Construction.
     */
    private function _calculateMidtransFee($statusObj)
    {
        $paymentType = is_array($statusObj) ? ($statusObj['payment_type'] ?? '') : ($statusObj->payment_type ?? '');
        $grossAmount = is_array($statusObj) ? (float) ($statusObj['gross_amount'] ?? 0) : (float) ($statusObj->gross_amount ?? 0);

        $baseFee = 4000.00;

        switch ($paymentType) {
            case 'bank_transfer':
            case 'echannel':
            case 'permata':
                $baseFee = 4000.00;
                break;
            case 'gopay':
                $baseFee = $grossAmount * 0.02;
                break;
            case 'qris':
                $baseFee = $grossAmount * 0.007;
                break;
            case 'shopeepay':
                $baseFee = $grossAmount * 0.02;
                break;
            case 'cstore': // Indomaret / Alfamart
                $baseFee = 5000.00;
                break;
            default:
                $baseFee = 4000.00;
                break;
        }

        // PPN 11% dihitung dari nilai biaya metode pembayaran
        $totalFee = $baseFee * 1.11;
        return round($totalFee, 2);
    }

    /**
     * OTOMATIS MEMBUAT PESANAN KE PRODUK AHSP_BAHAN DI RAB_MATERIAL YANG TERPILIH
     * Dipanggil saat tagihan konstruksi (construction_invoices) berstatus PAID.
     */
    private function _createOrdersForRab($invoiceId, $rabId, $userId, $constructionId)
    {
        try {
            // 1. Cek duplikasi agar tidak membuat order ganda untuk invoice_id yang sama
            $existingOrder = $this->db->table('orders')
                ->where('construction_invoice_id', $invoiceId)
                ->get()
                ->getRow();
            if ($existingOrder) {
                log_message('info', "Pemesanan material untuk invoice_id {$invoiceId} sudah pernah dibuat.");
                return;
            }

            // 2. Ambil data RAB
            $rab = $this->db->table('construction_rabs')->where('id', $rabId)->get()->getRowArray();
            if (!$rab) {
                log_message('error', "Gagal membuat pesanan material: RAB ID {$rabId} tidak ditemukan.");
                return;
            }

            $volume = (float) ($rab['volume'] ?? 0);
            if ($volume <= 0) {
                log_message('info', "Volume RAB ID {$rabId} adalah 0, skip pembuatan pesanan.");
                return;
            }

            // 3. Ambil produk material terpilih untuk rab_id ini
            $selectedMaterials = $this->db->table('construction_rab_materials crm')
                ->select('crm.*, p.name as product_name, p.price as product_price, p.supplier_id, p.stock as product_stock')
                ->join('products p', 'p.id = crm.product_id')
                ->where('crm.rab_id', $rabId)
                ->where('crm.selected', 1)
                ->get()
                ->getResultArray();

            if (empty($selectedMaterials)) {
                log_message('info', "Tidak ada material terpilih (selected = 1) untuk rab_id {$rabId}.");
                return;
            }

            // 4. Ambil data construction_requests untuk info pengiriman
            $construction = $this->db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();
            $recipientName = $construction['full_name'] ?? 'Pelanggan';
            $recipientPhone = $construction['phone'] ?? '08123456789';
            $shippingAddress = $construction['address'] ?? 'Alamat Konstruksi';
            $latitude = $construction['latitude'] ?? null;
            $longitude = $construction['longitude'] ?? null;

            // 5. Kelompokkan item berdasarkan supplier_id
            $itemsBySupplier = [];
            foreach ($selectedMaterials as $sm) {
                // Ambil koefisien dari ahsp_bahan
                $bahan = $this->db->table('ahsp_bahan')->where('id', $sm['ahsp_bahan_id'])->get()->getRowArray();
                $koef = $bahan ? (float) ($bahan['koefisien'] ?? 0) : 0.0;

                $quantity = (int) ceil($koef * $volume);
                if ($quantity <= 0) {
                    continue;
                }

                $supplierId = $sm['supplier_id'];
                $itemsBySupplier[$supplierId][] = [
                    'product_id' => $sm['product_id'],
                    'product_name' => $sm['product_name'],
                    'quantity' => $quantity,
                    'price' => (float) $sm['product_price']
                ];
            }

            if (empty($itemsBySupplier)) {
                log_message('info', "Kuantitas produk material untuk rab_id {$rabId} bernilai 0 setelah kalkulasi.");
                return;
            }

            // 6. Hitung total grand amount untuk transaksi
            $grandTotalAmount = 0;
            foreach ($itemsBySupplier as $supplierId => $items) {
                foreach ($items as $item) {
                    $grandTotalAmount += $item['price'] * $item['quantity'];
                }
            }

            // 7. Mulai Transaksi Database
            $this->db->transStart();

            // Buat transaksi utama
            $transactionIdUnique = 'TRX-CONST-RAB-' . $rabId . '-' . time() . '-' . rand(10, 99);
            $this->db->table('transactions')->insert([
                'transaction_id' => $transactionIdUnique,
                'user_id' => $userId,
                'total_amount' => $grandTotalAmount,
                'status' => 'PAID',
                'payment_method' => 'MIDTRANS',
                'order_count' => count($itemsBySupplier),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Buat order untuk masing-masing supplier
            $orderIndex = 0;
            foreach ($itemsBySupplier as $supplierId => $items) {
                $orderIndex++;
                $orderIdUnique = 'PASANGIN-CONST-' . time() . '-' . $userId . '-' . $orderIndex;

                $oTotalPrice = 0;
                foreach ($items as $item) {
                    $oTotalPrice += $item['price'] * $item['quantity'];
                }

                $this->db->table('orders')->insert([
                    'order_id' => $orderIdUnique,
                    'user_id' => $userId,
                    'construction_invoice_id' => $invoiceId,
                    'recipient_name' => $recipientName,
                    'recipient_phone' => $recipientPhone,
                    'total_price' => $oTotalPrice,
                    'shipping_fee' => 0.00,
                    'app_fee' => 0.00,
                    'tax_amount' => 0.00,
                    'status' => 'PAID',
                    'shipping_address' => $shippingAddress,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'voucher_code' => null,
                    'discount_amount' => 0.00,
                    'transaction_id' => $transactionIdUnique,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $dbOrderId = $this->db->insertID();

                // Insert ke order_items & kurangi stok
                foreach ($items as $item) {
                    $this->db->table('order_items')->insert([
                        'order_id' => $dbOrderId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    // Kurangi stok produk
                    $this->db->table('products')
                        ->set('stock', 'stock - ' . (int) $item['quantity'], false)
                        ->where('id', $item['product_id'])
                        ->update();
                }

                // Kirim notifikasi ke Supplier
                if ($supplierId) {
                    $this->notifService->sendPersonal(
                        'supplier',
                        (int) $supplierId,
                        'Pesanan Baru',
                        'Anda mendapatkan pesanan baru dari pekerjaan konstruksi! Silakan cek notifikasi pesanan Anda.'
                    );
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $dbError = $this->db->error();
                log_message('error', "Gagal memproses pembuatan pesanan material untuk invoiceId {$invoiceId} di database. DB Error: [" . ($dbError['code'] ?? '') . "] " . ($dbError['message'] ?? ''));
            } else {
                log_message('info', "Berhasil membuat pesanan material otomatis untuk invoiceId {$invoiceId}.");
            }

        } catch (\Exception $e) {
            log_message('error', 'Error saat membuat pesanan material otomatis: ' . $e->getMessage());
        }
    }
}


