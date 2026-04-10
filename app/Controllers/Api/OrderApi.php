<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class OrderApi extends BaseController {
    use ResponseTrait;
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function checkout() {
        // Ambil data dari payload JWT (disaring oleh filter auth)
        $userId = $this->request->user->uid;

        // Ambil data kiriman dari Flutter
        $recipientName  = $this->request->getVar('recipient_name');
        $recipientPhone = $this->request->getVar('recipient_phone');
        $address        = $this->request->getVar('shipping_address'); // Sesuai kolom DB
        $lat            = $this->request->getVar('latitude');
        $long           = $this->request->getVar('longitude');
        $totalPrice     = $this->request->getVar('total_price'); // Ini total final dari Flutter
        $voucher        = $this->request->getVar('voucher_code');
        $discount       = $this->request->getVar('discount_amount') ?? 0;

        // --- DATA BIAYA BARU ---
        $shippingFee    = $this->request->getVar('shipping_fee') ?? 0;
        $appFee         = $this->request->getVar('app_fee') ?? 0;
        $taxAmount      = $this->request->getVar('tax_amount') ?? 0;

        // Validasi Keranjang
        $cartItems = $this->db->table('cart')->where('user_id', $userId)->get()->getResultArray();
        if (empty($cartItems)) {
            return $this->fail('Keranjang belanja Anda kosong kawan.');
        }

        $this->db->transStart();

        // Generate Order ID unik
        $orderIdUnique = 'PASANGIN-' . time() . '-' . $userId;

        // 1. Simpan ke tabel 'orders'
        $this->db->table('orders')->insert([
            'order_id'         => $orderIdUnique,
            'user_id'          => $userId,
            'recipient_name'   => $recipientName,
            'recipient_phone'  => $recipientPhone,
            'total_price'      => $totalPrice,
            'shipping_fee'     => $shippingFee, // Kolom baru
            'app_fee'          => $appFee,      // Kolom baru
            'tax_amount'       => $taxAmount,   // Kolom baru
            'status'           => 'UNPAID',
            'shipping_address' => $address,
            'latitude'         => $lat,
            'longitude'        => $long,
            'voucher_code'     => $voucher,
            'discount_amount'  => $discount,
            'created_at'       => date('Y-m-d H:i:s')
        ]);

        $dbOrderId = $this->db->insertID();

        // 2. Pindahkan item keranjang ke 'order_items'
        foreach ($cartItems as $item) {
            $product = $this->db->table('products')->where('id', $item['product_id'])->get()->getRow();
            if ($product) {
                $this->db->table('order_items')->insert([
                    'order_id'   => $dbOrderId,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price
                ]);
            }
        }

        // 3. Bersihkan keranjang
        $this->db->table('cart')->where('user_id', $userId)->delete();

        $this->db->transComplete();

        // --- INTEGRASI MIDTRANS ---
        try {
            require_once APPPATH . 'ThirdParty/Midtrans/Midtrans.php';
            \Midtrans\Config::$serverKey = 'SB-Mid-server-UKNiwjL6WD2HSFzQ4vP8oKeg';
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderIdUnique,
                    'gross_amount' => (int)$totalPrice, // Nominal total yang harus dibayar
                ],
                'customer_details' => [
                    'first_name' => $recipientName,
                    'phone'      => $recipientPhone,
                ],
            ];

            $transaction = \Midtrans\Snap::createTransaction($params);

            return $this->respond([
                'status'       => true,
                'order_id'     => $orderIdUnique,
                'redirect_url' => $transaction->redirect_url
            ]);

        } catch (\Exception $e) {
            return $this->fail('Gagal menghubungkan ke Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan riwayat pesanan milik user
     */
    public function history() {
        $userId = $this->request->user->uid;
        $data = $this->db->table('orders')
                         ->where('user_id', $userId)
                         ->orderBy('id', 'DESC')
                         ->get()
                         ->getResultArray();
                         
        return $this->respond([
            'status' => true, 
            'data'   => $data
        ]);
    }

    /**
     * Ambil rincian produk di dalam satu pesanan
     */
    public function detail($orderId) {
        $data = $this->db->table('order_items')
            ->select('order_items.*, products.name, products.photo')
            ->join('products', 'products.id = order_items.product_id')
            ->where('order_id', $orderId)
            ->get()->getResultArray();

        foreach ($data as &$item) {
            $item['image_url'] = base_url('uploads/products/' . ($item['photo'] ?? 'default.png'));
        }

        return $this->respond([
            'status' => true, 
            'data'   => $data
        ]);
    }
}