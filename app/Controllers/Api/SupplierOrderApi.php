<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Modules\Notifications\Services\NotificationService;
use Exception;

class SupplierOrderApi extends BaseController
{
    use ResponseTrait;
    protected $db;
    protected NotificationService $notifService;

    /**
     * Kunci JWT  
     */
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notifService = new NotificationService();
    }

    /**
     * HELPER: Mendapatkan ID Supplier dari Token JWT
     */
    private function getSupplierId()
    {
        if (isset($this->request->user) && $this->request->user->role === 'supplier') {
            return $this->request->user->uid;
        }
        return null;
    }

    /**
     * --- 1. LIST PESANAN SAYA ---
     */
    public function index()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized('Sesi berakhir, silakan login ulang.');

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
                ->select('order_items.*, products.name as product_name, products.photo, products.unit as product_unit')
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
    public function stats()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized();

        // A. Hitung Total Pendapatan Produk (Sudah Bayar/Proses/Kirim/Selesai)
        $incomeRow = $this->db->table('order_items')
            ->select('SUM(order_items.price * order_items.quantity) as total', false)
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->whereIn('orders.status', ['PAID', 'PROCESSED', 'SHIPPED', 'COMPLETED'])
            ->get()->getRow();
        $totalIncome = $incomeRow ? ($incomeRow->total ?? 0) : 0;

        // B. Hitung Total Penarikan (Pending & Approved)
        $withdrawRow = $this->db->table('supplier_withdrawals')
            ->where('supplier_id', $supplierId)
            ->whereIn('status', ['pending', 'approved'])
            ->selectSum('amount')
            ->get()->getRow();
        $totalWithdrawn = $withdrawRow ? ($withdrawRow->amount ?? 0) : 0;

        // C. Saldo Bersih
        $availableBalance = (float) $totalIncome - (float) $totalWithdrawn;

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
                'total_saldo' => $availableBalance,
                'today_orders' => $todayOrders,
                'total_orders' => $totalOrders,
                'total_products' => $totalProducts,
            ]
        ]);
    }

    /**
     * --- 2b. ANALITIK PENJUALAN ---
     */
    public function salesAnalytics()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized('Sesi berakhir, silakan login ulang.');

        // 1. Total Pendapatan (Hanya pesanan yang sukses/dibayar)
        $revenueRow = $this->db->table('order_items')
            ->select('SUM(order_items.price * order_items.quantity) as total', false)
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->whereIn('orders.status', ['PAID', 'PROCESSED', 'SHIPPED', 'COMPLETED'])
            ->get()->getRow();
        $totalRevenue = $revenueRow ? ($revenueRow->total ?? 0) : 0;

        // 2. Total Pesanan (Pesanan sukses/telah dibayar yang mengandung produk milik supplier ini)
        $totalOrdersQuery = $this->db->table('order_items')
            ->select('orders.id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->whereIn('orders.status', ['PAID', 'PROCESSED', 'SHIPPED', 'COMPLETED'])
            ->groupBy('orders.id')
            ->get();
        $totalOrders = $totalOrdersQuery->getNumRows();

        // 3. Total Produk Terjual
        $soldRow = $this->db->table('order_items')
            ->select('SUM(order_items.quantity) as total', false)
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->whereIn('orders.status', ['PAID', 'PROCESSED', 'SHIPPED', 'COMPLETED'])
            ->get()->getRow();
        $totalProductsSold = $soldRow ? ($soldRow->total ?? 0) : 0;

        // 4. Jumlah Pembeli Unik (Distinct user_id dari orders sukses)
        $totalBuyersQuery = $this->db->table('order_items')
            ->select('orders.user_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->whereIn('orders.status', ['PAID', 'PROCESSED', 'SHIPPED', 'COMPLETED'])
            ->groupBy('orders.user_id')
            ->get();
        $totalBuyers = $totalBuyersQuery->getNumRows();

        // 5. Data Grafik 7 Hari Terakhir
        $startDate = date('Y-m-d', strtotime('-6 days'));
        $endDate = date('Y-m-d');

        $rawChart = $this->db->table('order_items')
            ->select('DATE(orders.created_at) as order_date, SUM(order_items.price * order_items.quantity) as total', false)
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->where('DATE(orders.created_at) >=', $startDate)
            ->where('DATE(orders.created_at) <=', $endDate)
            ->whereIn('orders.status', ['PAID', 'PROCESSED', 'SHIPPED', 'COMPLETED'])
            ->groupBy('DATE(orders.created_at)')
            ->get()->getResultArray();

        $revenueMap = [];
        foreach ($rawChart as $row) {
            $revenueMap[$row['order_date']] = (float) $row['total'];
        }

        $chartData = [];
        $dayNamesIndo = [
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayEng = date('D', strtotime($date));
            $dayIndo = $dayNamesIndo[$dayEng] ?? $dayEng;

            $chartData[] = [
                'date' => $date,
                'day' => $dayIndo,
                'day_en' => $dayEng,
                'revenue' => $revenueMap[$date] ?? 0.0
            ];
        }

        return $this->respond([
            'status' => true,
            'data' => [
                'summary' => [
                    'total_revenue' => (float) $totalRevenue,
                    'total_orders' => (int) $totalOrders,
                    'total_products_sold' => (int) $totalProductsSold,
                    'total_buyers' => (int) $totalBuyers
                ],
                'sales_chart' => $chartData
            ]
        ]);
    }

    /**
     * --- 3. REQUEST TARIK DANA (WITHDRAW) ---
     */
    public function withdraw()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized();

        $amount = (float)$this->request->getVar('amount');
        $bankName = $this->request->getVar('bank_name');
        $accountNumber = $this->request->getVar('account_number');
        $accountName = $this->request->getVar('account_name');

        // 1. Cek apakah saldo mencukupi
        $supplier = $this->db->table('suppliers')->where('id', $supplierId)->get()->getRow();
        if (!$supplier) {
            return $this->failNotFound('Data supplier tidak ditemukan.');
        }

        $currentBalance = (float)$supplier->balance;
        if ($currentBalance < $amount) {
            return $this->fail('Gagal! Saldo Anda tidak cukup.');
        }

        try {
            $this->db->transStart();

            // 2. Insert ke supplier_withdrawals dengan status 'approved'
            $this->db->table('supplier_withdrawals')->insert([
                'supplier_id' => $supplierId,
                'amount' => $amount,
                'bank_name' => $bankName,
                'account_number' => $accountNumber,
                'account_name' => $accountName,
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // 3. Potong saldo supplier di tabel suppliers
            $this->db->table('suppliers')
                ->where('id', $supplierId)
                ->set('balance', 'balance - ' . $amount, false)
                ->update();

            // 4. Catat riwayat transaksi supplier ke tabel supplier_transactions
            $this->db->table('supplier_transactions')->insert([
                'supplier_Id' => $supplierId,
                'amount' => $amount,
                'type' => 'withdraw',
                'description' => "Penarikan dana ke {$bankName} - {$accountNumber} a/n {$accountName}",
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal memproses penarikan dana di database.');
            }

            return $this->respondCreated([
                'status' => true,
                'message' => 'Penarikan dana berhasil disetujui secara otomatis dan saldo telah dipotong.'
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->fail('Gagal melakukan penarikan dana: ' . $e->getMessage());
        }
    }

    /**
     * --- 4. RIWAYAT PENARIKAN ---
     */
    public function withdrawalHistory()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized();

        $data = $this->db->table('supplier_withdrawals')
            ->where('supplier_id', $supplierId)
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        return $this->respond(['status' => true, 'data' => $data]);
    }

    /**
     * --- 4b. RIWAYAT TRANSAKSI SUPPLIER ---
     */
    public function transactionHistory()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized('Sesi berakhir, silakan login ulang.');

        $data = $this->db->table('supplier_transactions')
            ->where('supplier_Id', $supplierId)
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        return $this->respond(['status' => true, 'data' => $data]);
    }



    /**
     * --- 5. UPDATE STATUS PESANAN ---
     */
    public function updateStatus($id = null)
    {
        $status = $this->request->getVar('status');

        // Ambil data order untuk mendapatkan user_id dan order_id string
        $order = $this->db->table('orders')->where('id', $id)->get()->getRow();
        if (!$order) {
            return $this->failNotFound('Pesanan tidak ditemukan');
        }

        $this->db->table('orders')->where('id', $id)->update(['status' => $status]);

        // Kirim notifikasi ke client
        $this->notifService->sendPersonal(
            'client',
            (int) $order->user_id,
            'Update Status Pesanan',
            "Status pesanan {$order->order_id} Anda telah diperbarui menjadi: " . strtoupper($status)
        );

        return $this->respond(['status' => true, 'message' => 'Status pesanan berhasil diperbarui']);
    }


}