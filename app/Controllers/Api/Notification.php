<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class Notification extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        // 1. SETUP CONFIG MIDTRANS
        $midtransPath = APPPATH . 'ThirdParty/Midtrans/Midtrans.php';
        if (!file_exists($midtransPath)) {
             $midtransPath = APPPATH . 'ThirdParty/midtrans/Midtrans.php';
        }
        
        if (file_exists($midtransPath)) {
            require_once $midtransPath;
            
            // Konfigurasi HARUS SAMA dengan controller DesignRequests
            \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY'); 
            \Midtrans\Config::$isProduction = filter_var(getenv('MIDTRANS_IS_PRODUCTION'), FILTER_VALIDATE_BOOLEAN);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;
        } else {
            log_message('error', 'MIDTRANS NOTIF: Library Midtrans tidak ditemukan!');
            return $this->respond(['status' => 'error', 'message' => 'Library not found'], 500);
        }

        try {
            // 2. TERIMA DATA NOTIFIKASI
            $notif = new \Midtrans\Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Catat log biar tau ada notif masuk
            log_message('error', "MIDTRANS NOTIF MASUK: OrderID: $order_id | Status: $transaction");

            // 3. LOGIKA PENENTUAN STATUS
            $newStatus = null;

            if ($transaction == 'capture') {
                // Untuk pembayaran Kartu Kredit
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $newStatus = 'PENDING';
                    } else {
                        $newStatus = 'PAID';
                    }
                }
            } else if ($transaction == 'settlement') {
                // Untuk Transfer Bank, GOPAY, dll (Paling Sering Dipakai)
                $newStatus = 'PAID'; 
            } else if ($transaction == 'pending') {
                $newStatus = 'PENDING';
            } else if ($transaction == 'deny') {
                $newStatus = 'UNPAID';
            } else if ($transaction == 'expire') {
                $newStatus = 'UNPAID'; 
            } else if ($transaction == 'cancel') {
                $newStatus = 'UNPAID';
            }

            // 4. UPDATE DATABASE
            if ($newStatus) {
                $db = \Config\Database::connect();
                
                // Cek dulu apakah invoice ada?
                $cekInvoice = $db->table('project_invoices')->where('midtrans_order_id', $order_id)->get()->getRow();

                if ($cekInvoice) {
                    $db->table('project_invoices')
                       ->where('midtrans_order_id', $order_id)
                       ->update(['status' => $newStatus]);
                       
                    log_message('error', "MIDTRANS SUCCESS: OrderID $order_id berhasil update jadi $newStatus");
                    
                    return $this->respond(['status' => 'ok', 'message' => 'Notification processed'], 200);
                } else {
                    log_message('error', "MIDTRANS ERROR: OrderID $order_id tidak ditemukan di database project_invoices");
                    return $this->respond(['status' => 'error', 'message' => 'Invoice not found'], 404);
                }
            }

            return $this->respond(['status' => 'ok', 'message' => 'No status change'], 200);

        } catch (\Exception $e) {
            log_message('error', "MIDTRANS EXCEPTION: " . $e->getMessage());
            return $this->respond(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
