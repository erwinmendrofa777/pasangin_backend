<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\TukangModel;
use App\Models\SupplierModel;
use App\Models\OrderItemsModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\WithdrawalRequestsModel;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{ 
    public function index()
    {
        $userModel = new UserModel();
        $tukangModel = new TukangModel();
        $supplierModel = new SupplierModel();
        $orderItemsModel = new OrderItemsModel();
        $orderModel = new OrderModel();
        $productModel = new ProductModel();
        $withdrawalRequestsModel = new WithdrawalRequestsModel();

        // --- START: Data untuk Grafik total penjualan produk (6 Bulan Terakhir) ---
        $sixMonthsAgo = date('Y-m-01', strtotime('-5 months'));
        $salesQuery = $orderModel->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as total_orders, SUM(total_price) as total_revenue")
                                    ->where('created_at >=', $sixMonthsAgo)
                                    ->groupBy("month")
                                    ->orderBy("month", "ASC")
                                    ->get();

        $salesResults = $salesQuery->getResultArray();
        // Siapkan array untuk menampung data penjualan
        $salesMonthlyData = [];
        foreach ($salesResults as $row) {
            $salesMonthlyData[$row['month']] = [
                'orders' => $row['total_orders'],
                'revenue' => $row['total_revenue']
            ];
        }

        $salesLabels = [];
        $salesCountData = [];
        $salesRevenueData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-$i months"));
            $monthName = date('F Y', strtotime("-$i months"));
            $salesLabels[] = $monthName;
            $salesCountData[] = $salesMonthlyData[$monthKey]['orders'] ?? 0;
            $salesRevenueData[] = $salesMonthlyData[$monthKey]['revenue'] ?? 0;
        }
        // --- END: Data untuk Grafik Penjualan ---

        // Data untuk Top 5 Produk Terlaris
        $topProducts = $orderItemsModel->select('order_items.*,products.name as product_name, products.photo as product_photo,SUM(order_items.quantity) as total_sales, products.price as product_price,suppliers.name as supplier_name')   
                                   ->join('products', 'products.id = order_items.product_id')
                                   ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
                                   ->groupBy('order_items.product_id')
                                   ->orderBy('total_sales', 'DESC')
                                   ->limit(5)
                                   ->findAll();

        // Data untuk 5 Permintaan Tarik Dana Terbaru
        $tarikDana = $withdrawalRequestsModel->select('withdrawal_requests.*,tukang.name as tukang_name')
                                            ->join('tukang', 'tukang.id = withdrawal_requests.tukang_id', 'left')
                                            ->orderBy('created_at', 'DESC')
                                            ->limit(5)
                                            ->findAll();
                                   
        $data = [
            'title'             => 'Dashboard Admin',
            'jumlahClient'      => $userModel->where('role', 'client')->countAllResults(),
            'jumlahSupplier'    => $supplierModel->countAllResults(),
            'jumlahTukang'      => $tukangModel->countAllResults(),
            'jumlahProduk'      => $productModel->countAllResults(),
            'salesLabels'       => $salesLabels,
            'salesCountData'    => $salesCountData,
            'salesRevenueData'  => $salesRevenueData,
            'tarikDana'         => $tarikDana,
            'topProducts'       => $topProducts,
            'name'              => session()->get('full_name')
        ];

        return view('admin/dashboard', $data);
    }
}