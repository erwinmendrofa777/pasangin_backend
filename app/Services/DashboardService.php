<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\TukangModel;
use App\Models\SupplierModel;
use App\Models\OrderItemsModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\WithdrawalRequestsModel;

class DashboardService
{
    protected UserModel                $userModel;
    protected TukangModel              $tukangModel;
    protected SupplierModel            $supplierModel;
    protected OrderItemsModel          $orderItemsModel;
    protected OrderModel               $orderModel;
    protected ProductModel             $productModel;
    protected WithdrawalRequestsModel $withdrawalRequestsModel;

    public function __construct()
    {
        $this->userModel                = new UserModel();
        $this->tukangModel              = new TukangModel();
        $this->supplierModel            = new SupplierModel();
        $this->orderItemsModel          = new OrderItemsModel();
        $this->orderModel               = new OrderModel();
        $this->productModel             = new ProductModel();
        $this->withdrawalRequestsModel = new WithdrawalRequestsModel();
    }

    /**
     * Mengumpulkan semua data yang dibutuhkan untuk dashboard admin.
     */
    public function getDashboardStats(): array
    {
        // 1. Statistik Dasar (Counters)
        $counters = [
            'jumlahClient'   => $this->userModel->where('role', 'client')->countAllResults(),
            'jumlahSupplier' => $this->supplierModel->countAllResults(),
            'jumlahTukang'   => $this->tukangModel->countAllResults(),
            'jumlahProduk'   => $this->productModel->countAllResults(),
        ];

        // 2. Data Grafik Penjualan (6 Bulan Terakhir)
        $salesData = $this->getMonthlySalesData();

        // 3. Top 5 Produk Terlaris
        $topProducts = $this->orderItemsModel->select('order_items.*, products.name as product_name, products.photo as product_photo, SUM(order_items.quantity) as total_sales, products.price as product_price, suppliers.name as supplier_name')
            ->join('products', 'products.id = order_items.product_id')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->groupBy('order_items.product_id')
            ->orderBy('total_sales', 'DESC')
            ->limit(5)
            ->findAll();

        // 4. 5 Permintaan Tarik Dana Terbaru
        $tarikDana = $this->withdrawalRequestsModel->select('withdrawal_requests.*, tukang.name as tukang_name')
            ->join('tukang', 'tukang.id = withdrawal_requests.tukang_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        return array_merge($counters, $salesData, [
            'tarikDana'   => $tarikDana,
            'topProducts' => $topProducts,
        ]);
    }

    /**
     * Logika pengolahan data penjualan bulanan untuk Chart.js
     */
    private function getMonthlySalesData(): array
    {
        $sixMonthsAgo = date('Y-m-01', strtotime('-5 months'));
        $salesQuery   = $this->orderModel->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as total_orders, SUM(total_price) as total_revenue")
            ->where('created_at >=', $sixMonthsAgo)
            ->groupBy("month")
            ->orderBy("month", "ASC")
            ->get();

        $salesResults = $salesQuery->getResultArray();
        
        $salesMonthlyData = [];
        foreach ($salesResults as $row) {
            $salesMonthlyData[$row['month']] = [
                'orders'  => $row['total_orders'],
                'revenue' => $row['total_revenue']
            ];
        }

        $salesLabels       = [];
        $salesCountData    = [];
        $salesRevenueData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthKey           = date('Y-m', strtotime("-$i months"));
            $monthName          = date('F Y', strtotime("-$i months"));
            $salesLabels[]      = $monthName;
            $salesCountData[]   = $salesMonthlyData[$monthKey]['orders'] ?? 0;
            $salesRevenueData[] = $salesMonthlyData[$monthKey]['revenue'] ?? 0;
        }

        return [
            'salesLabels'      => $salesLabels,
            'salesCountData'   => $salesCountData,
            'salesRevenueData' => $salesRevenueData,
        ];
    }
}
