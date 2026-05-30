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
            $voucherCode = $this->request->getVar('voucher_code');
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
            $voucherCode = $this->request->getVar('voucher_code');
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
            $voucherCode = $this->request->getVar('voucher_code');
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
                    $dataObj = $this->db->table($tableName)->where($idColumn, $orderId)->get()->getRowArray();

                    if ($dataObj) {
                        // Update Status  
                        $this->db->table($tableName)->where($idColumn, $orderId)->update([$statusColumn => 'PAID']);

                        $userId = $dataObj['user_id'] ?? null;
                        $title = "Pembayaran Berhasil";
                        $message = "Terima kasih! Pembayaran Anda untuk tagihan {$orderId} telah kami terima.";
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
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
}