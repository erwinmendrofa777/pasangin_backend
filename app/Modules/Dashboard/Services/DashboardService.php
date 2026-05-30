<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Users\Repositories\UserRepository;
use App\Modules\Tukang\Repositories\TukangRepository;
use App\Modules\Supplier\Repositories\SupplierRepository;
use App\Modules\Orders\Repositories\OrderItemsRepository;
use App\Modules\Orders\Repositories\OrderRepository;
use App\Modules\Products\Repositories\ProductRepository;
use App\Modules\Wallets\Repositories\WithdrawalRequestsRepository;

use App\Modules\Users\Repositories\Contracts\UserRepositoryInterface;
use App\Modules\Tukang\Repositories\Contracts\TukangRepositoryInterface;
use App\Modules\Supplier\Repositories\Contracts\SupplierRepositoryInterface;
use App\Modules\Orders\Repositories\Contracts\OrderItemsRepositoryInterface;
use App\Modules\Orders\Repositories\Contracts\OrderRepositoryInterface;
use App\Modules\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Modules\Wallets\Repositories\Contracts\WithdrawalRequestsRepositoryInterface;

/**
 * DashboardService
 *
 * Mengumpulkan statistik dan data agregat untuk dashboard admin.
 * Menggunakan Repository Pattern untuk memisahkan query SQL dari logika bisnis.
 */
class DashboardService
{
    protected UserRepositoryInterface               $userRepository;
    protected TukangRepositoryInterface             $tukangRepository;
    protected SupplierRepositoryInterface           $supplierRepository;
    protected OrderItemsRepositoryInterface         $orderItemsRepository;
    protected OrderRepositoryInterface              $orderRepository;
    protected ProductRepositoryInterface            $productRepository;
    protected WithdrawalRequestsRepositoryInterface $withdrawalRequestsRepository;

    public function __construct()
    {
        $this->userRepository               = new UserRepository();
        $this->tukangRepository             = new TukangRepository();
        $this->supplierRepository           = new SupplierRepository();
        $this->orderItemsRepository         = new OrderItemsRepository();
        $this->orderRepository              = new OrderRepository();
        $this->productRepository            = new ProductRepository();
        $this->withdrawalRequestsRepository = new WithdrawalRequestsRepository();
    }

    /**
     * Mengumpulkan semua data yang dibutuhkan untuk dashboard admin.
     */
    public function getDashboardStats(): array
    {
        // 1. Statistik Dasar (Counters)
        $counters = [
            'jumlahClient'   => $this->userRepository->countClients(),
            'jumlahSupplier' => $this->supplierRepository->countAll(),
            'jumlahTukang'   => $this->tukangRepository->countAll(),
            'jumlahProduk'   => $this->productRepository->countAll(),
        ];

        // 2. Data Grafik Penjualan (6 Bulan Terakhir)
        $salesData = $this->getMonthlySalesData();

        // 3. Top 5 Produk Terlaris
        $topProducts = $this->orderItemsRepository->getTopSellingProducts(5);

        // 4. 5 Permintaan Tarik Dana Terbaru
        $tarikDana = $this->withdrawalRequestsRepository->getLatestRequests(5);

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
        $salesResults = $this->orderRepository->getMonthlySalesData($sixMonthsAgo);
        
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
