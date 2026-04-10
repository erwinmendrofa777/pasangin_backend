<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Exception;

class SupplierOrderApi extends BaseController {
    use ResponseTrait;
    protected $db;
    
    /**
     * Kunci JWT kawan
     */
    private $jwtKey = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma';

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * HELPER: Mendapatkan ID Supplier dari Token JWT
     */
    private function getSupplierId()
    {
        try {
            $authHeader = $this->request->getHeaderLine('Authorization');
            if (empty($authHeader)) return null;

            $token = str_replace('Bearer ', '', $authHeader);
            $tokenParts = explode('.', $token);
            if (count($tokenParts) != 3) return null;

            $payload = json_decode(base64_decode($tokenParts[1]), true);
            return $payload['uid'] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * --- 1. LIST PESANAN SAYA ---
     */
    public function index() {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized('Sesi berakhir, silakan login ulang.');

        // Mengambil semua data order (termasuk fee) yang berisi produk supplier ini
        $orders = $this->db->table('orders')
            ->select('orders.*')
            ->join('order_items', 'order_items.order_id = orders.id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->groupBy('orders.id')
            ->orderBy('orders.id', 'DESC')
            ->get()->getResultArray();

        foreach ($orders as &$order) {
            // Ambil item produk khusus milik supplier ini di tiap order
            $order['items'] = $this->db->table('order_items')
                ->select('order_items.*, products.name as product_name, products.photo')
                ->join('products', 'products.id = order_items.product_id')
                ->where('order_items.order_id', $order['id'])
                ->where('products.supplier_id', $supplierId)
                ->get()->getResultArray();
        }

        return $this->respond(['status' => true, 'data' => $orders]);
    }

    /**
     * --- 2. STATISTIK DASHBOARD ---
     */
    public function stats() {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized();

        // A. Hitung Total Pendapatan Produk (Sudah Bayar/Proses/Kirim/Selesai)
        $totalIncome = $this->db->table('order_items')
            ->select('SUM(order_items.price * order_items.quantity) as total', false)
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->whereIn('orders.status', ['PAID', 'SETTLEMENT', 'PROCESSED', 'SHIPPED', 'COMPLETED'])
            ->get()->getRow()->total ?? 0;

        // B. Hitung Total Penarikan (Pending & Approved)
        $totalWithdrawn = $this->db->table('supplier_withdrawals')
            ->where('supplier_id', $supplierId)
            ->whereIn('status', ['pending', 'approved'])
            ->selectSum('amount')
            ->get()->getRow()->amount ?? 0;

        // C. Saldo Bersih
        $availableBalance = (float)$totalIncome - (float)$totalWithdrawn;

        // D. Hitung Pesanan Hari Ini
        $todayOrdersQuery = $this->db->table('order_items')
            ->select('orders.id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->where('DATE(orders.created_at)', date('Y-m-d'))
            ->groupBy('orders.id')
            ->get();
        $todayOrders = $todayOrdersQuery->getNumRows();

        // E. Hitung Total Semua Pesanan
        $totalOrdersQuery = $this->db->table('order_items')
            ->select('orders.id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->groupBy('orders.id')
            ->get();
        $totalOrders = $totalOrdersQuery->getNumRows();

        // F. Hitung Total Produk
        $totalProducts = $this->db->table('products')
            ->where('supplier_id', $supplierId)
            ->countAllResults();

        return $this->respond([
            'status' => true,
            'data' => [
                'total_saldo'    => $availableBalance,
                'today_orders'   => $todayOrders,
                'total_orders'   => $totalOrders,
                'total_products' => $totalProducts,
            ]
        ]);
    }

    /**
     * --- 3. REQUEST TARIK DANA (WITHDRAW) ---
     */
    public function withdraw() {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized();

        $amount = $this->request->getVar('amount');
        
        $this->db->table('supplier_withdrawals')->insert([
            'supplier_id'    => $supplierId,
            'amount'         => $amount,
            'bank_name'      => $this->request->getVar('bank_name'),
            'account_number' => $this->request->getVar('account_number'),
            'account_name'   => $this->request->getVar('account_name'),
            'status'         => 'pending',
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        return $this->respondCreated(['status' => true, 'message' => 'Permintaan penarikan dana berhasil dikirim.']);
    }

    /**
     * --- 4. RIWAYAT PENARIKAN ---
     */
    public function withdrawalHistory() {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized();

        $data = $this->db->table('supplier_withdrawals')
            ->where('supplier_id', $supplierId)
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        return $this->respond(['status' => true, 'data' => $data]);
    }

    /**
     * --- 5. UPDATE STATUS PESANAN ---
     */
    public function updateStatus($id = null) {
        $status = $this->request->getVar('status');
        $this->db->table('orders')->where('id', $id)->update(['status' => $status]);
        return $this->respond(['status' => true, 'message' => 'Status pesanan berhasil diperbarui']);
    }
}