<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class PaymentApi extends BaseController
{
    use ResponseTrait;

    protected $db;

    public function __construct()
    {
        // Hubungkan database
        $this->db = \Config\Database::connect();
        
        // Load library Midtrans
        require_once APPPATH . 'ThirdParty/Midtrans/Midtrans.php';
    }

    /**
     * API untuk mengambil daftar tagihan/invoice untuk sebuah proyek.
     * URL: GET /api/payment/invoices/[design_request_id]
     */
    public function invoices($designRequestId)
    {
        $invoices = $this->db->table('project_invoices')
                             ->where('design_request_id', $designRequestId)
                             ->orderBy('id', 'ASC')
                             ->get()->getResultArray();

        return $this->respond([
            'status' => 200, 
            'data' => $invoices ?? []
        ]);
    }

    /**
     * API untuk meminta Snap Token dari Midtrans.
     * URL: GET /api/payment/token/[invoice_id]
     */
    public function getPaymentToken($invoiceId)
    {
        $invoice = $this->db->table('project_invoices')->where('id', $invoiceId)->get()->getRowArray();
        if (!$invoice) {
            return $this->failNotFound('Tagihan tidak ditemukan');
        }

        \Midtrans\Config::$serverKey    = 'SB-Mid-server-UKNiwjL6WD2HSFzQ4vP8oKeg'; // Ganti dengan server key Anda
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
        
        // Selalu buat Order ID baru setiap kali token diminta untuk menghindari error "transaction is expired"
        $customOrderId = 'INV-' . $invoice['id'] . '-' . time();
        
        $params = [
            'transaction_details' => [
                'order_id' => $customOrderId, 
                'gross_amount' => (int) $invoice['amount'], 
            ],
            'customer_details' => [
                'first_name' => 'Pelanggan Proyek ' . $invoice['design_request_id'],
                'email' => 'customer-' . $invoice['design_request_id'] . '@example.com',
            ],
            'item_details' => [
                [
                    'id' => 'INV-' . $invoice['id'],
                    'price' => (int) $invoice['amount'],
                    'quantity' => 1,
                    'name' => substr($invoice['description'] ?? 'Pembayaran Tagihan', 0, 50)
                ]
            ]
        ];

        try {
            $transaction = \Midtrans\Snap::createTransaction($params);

            // Update DB dengan token & order_id yang baru dibuat untuk pelacakan
            $this->db->table('project_invoices')->where('id', $invoiceId)->update([
                'snap_token' => $transaction->token,
                'midtrans_order_id' => $customOrderId
            ]);

            return $this->respond([
                'status' => 200,
                'snap_token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url,
                'order_id' => $customOrderId
            ]);

        } catch (\Exception $e) {
            log_message('error', '[MIDTRANS GetToken Error] Invoice ID ' . $invoiceId . ': ' . $e->getMessage());
            return $this->failServerError('Gagal membuat transaksi Midtrans.');
        }
    }

    /**
     * ================================================================
     * === PLAN B: FUNGSI JEMPUT BOLA (VERSI PALING SEMPURNA) =========
     * ================================================================
     * API ini akan dipanggil oleh Flutter setelah pembayaran di WebView selesai.
     * URL: GET /api/payment/check_status/[order_id]
     */
    public function checkStatus($orderId)
    {
        \Midtrans\Config::$serverKey = 'SB-Mid-server-UKNiwjL6WD2HSFzQ4vP8oKeg'; // Ganti dengan server key Anda
        \Midtrans\Config::$isProduction = false;
        
        try {
            $status = \Midtrans\Transaction::status($orderId);
        } catch (\Exception $e) {
            log_message('error', "[PLAN B] Gagal koneksi ke Midtrans: " . $e->getMessage());
            return $this->failServerError('Gagal memeriksa status pembayaran di Midtrans.');
        }

        $invoice = $this->db->table('project_invoices')->where('midtrans_order_id', $orderId)->get()->getRowArray();
        
        if ($invoice) {
            $transactionStatus = $status->transaction_status;
            $fraudStatus       = $status->fraud_status;
            log_message('info', "[PLAN B] Invoice ID {$invoice['id']} ditemukan. Status Midtrans: {$transactionStatus}");

            if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {
                
                // =========================================================================
                // === INI DIA LOGIKA KUNCI YANG TELAH DISEMPURNAKAN =======================
                // =========================================================================
                // Selama status di database BELUM 'PAID', kita paksa untuk UPDATE.
                if ($invoice['payment_status'] !== 'PAID') {
                    
                    $this->db->table('project_invoices')
                             ->where('id', $invoice['id'])
                             ->update(['payment_status' => 'PAID']);
                    log_message('critical', "[PLAN B] SUKSES (DIPAKSA): Invoice ID {$invoice['id']} diupdate menjadi PAID.");

                } else {
                    log_message('warning', "[PLAN B] DIABAIKAN: Invoice ID {$invoice['id']} statusnya memang sudah 'PAID'.");
                }
            }
        } else {
            log_message('error', "[PLAN B] FATAL: Invoice dengan midtrans_order_id '{$orderId}' tidak ditemukan.");
        }
        
        // Selalu kembalikan 200 OK ke Flutter agar alur aplikasi tidak terputus
        return $this->respond([
            'status' => 200, 
            'message' => 'Status checked successfully.',
            'transaction_status' => $status->transaction_status ?? 'unknown'
        ]);
    }

    /**
     * API Notifikasi dari Midtrans (Metode Cadangan/Webhook)
     * URL: POST /api/payment/notification
     */
    public function notification()
    {
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$serverKey = 'SB-Mid-server-UKNiwjL6WD2HSFzQ4vP8oKeg'; // Ganti dengan server key Anda

        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            log_message('error', '[NOTIFIKASI] GAGAL: Payload tidak valid. Pesan: ' . $e->getMessage());
            return $this->response->setStatusCode(400)->setBody('Invalid Payload');
        }

        $transactionStatus = $notif->transaction_status;
        $orderId           = $notif->order_id;
        $fraudStatus       = $notif->fraud_status;

        log_message('info', "[NOTIFIKASI] Diterima: OrderID({$orderId}), Status({$transactionStatus})");

        if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {

            $invoice = $this->db->table('project_invoices')->where('midtrans_order_id', $orderId)->get()->getRowArray();

            if ($invoice) {
                // Gunakan logika yang sama persis dengan checkStatus() demi konsistensi
                if ($invoice['payment_status'] !== 'PAID') {
                    $this->db->table('project_invoices')
                            ->where('id', $invoice['id'])
                            ->update(['payment_status' => 'PAID']);
                    log_message('critical', "[NOTIFIKASI] SUKSES: Invoice ID {$invoice['id']} (OrderID: {$orderId}) telah diupdate menjadi PAID.");
                }
            } else {
                log_message('error', "[NOTIFIKASI] GAGAL: Invoice dengan OrderID {$orderId} tidak ditemukan di database.");
            }
        }

        return $this->response->setStatusCode(200)->setBody('Notification Processed');
    }
}
