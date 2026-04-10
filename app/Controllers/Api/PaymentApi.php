<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class PaymentApi extends BaseController{
    use ResponseTrait;

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        // Memuat helper ThirdParty Midtrans
        require_once APPPATH . 'ThirdParty/Midtrans/Midtrans.php';
        
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey    = 'SB-Mid-server-UKNiwjL6WD2HSFzQ4vP8oKeg';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    /**
     * FUNGSI TAGIHAN DESAIN
     */
    public function getDesignPaymentToken($invoiceId)
    {
        $invoice = $this->db->table('project_invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (!$invoice) return $this->failNotFound('Tagihan Desain tidak ditemukan.');
        $designRequest = $this->db->table('design_requests')->where('id', $invoice['design_request_id'])->get()->getRowArray();
        return $this->createMidtransTransaction($designRequest['user_id'], $invoice, 'project_invoices');
    }

    /**
     * FUNGSI TAGIHAN KONSTRUKSI
     */
    public function getConstructionPaymentToken($invoiceId)
    {
        $invoice = $this->db->table('construction_invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (!$invoice) return $this->failNotFound('Tagihan Konstruksi tidak ditemukan.');
        return $this->createMidtransTransaction($invoice['user_id'], $invoice, 'construction_invoices');
    }

    /**
     * FUNGSI TAGIHAN RENOVASI
     */
    public function getRenovationPaymentToken($invoiceId){
        $invoice = $this->db->table('renovation_invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (!$invoice) return $this->failNotFound('Tagihan Renovasi tidak ditemukan.');
        return $this->createMidtransTransaction($invoice['user_id'], $invoice, 'renovation_invoices');
    }

    /**
     * HELPER: MEMBUAT TRANSAKSI MIDTRANS
     */
    private function createMidtransTransaction($userId, $invoice, $tableName)
    {
        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) return $this->failNotFound('User tidak ditemukan.');
        
        $customOrderId = $tableName . '-' . $invoice['id'] . '-' . time();
        $grossAmount = (int) ($invoice['amount'] ?? 0);

        $params = [
            'transaction_details' => ['order_id' => $customOrderId, 'gross_amount' => $grossAmount],
            'customer_details' => [
                'first_name' => $user['full_name'] ?? 'Pelanggan',
                'email' => $user['email'] ?? 'customer@example.com',
                'phone' => $user['phone_number'] ?? '08123456789',
            ],
        ];

        try {
            $transaction = \Midtrans\Snap::createTransaction($params);
            $this->db->table($tableName)->where('id', $invoice['id'])->update(['midtrans_order_id' => $customOrderId]);
            return $this->respond([
                'status' => true,
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
        if (empty($orderId)) return $this->fail('Order ID kosong.');
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
        if (empty($orderId)) return;

        try {
            $status = \Midtrans\Transaction::status($orderId);
            $transactionStatus = $status->transaction_status;

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
                elseif (strpos($orderId, 'PASANGIN-') === 0) {
                    $tableName = 'orders';
                    $idColumn = 'order_id';
                }

                if (!empty($tableName)) {
                    $this->db->table($tableName)->where($idColumn, $orderId)->update([$statusColumn => 'PAID']);
                }
            }
        } catch (\Exception $e) { }
    }
}