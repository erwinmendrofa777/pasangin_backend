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

        // 2. Data Grafik Tren Proyek (6 Bulan Terakhir)
        $projectTrends = $this->getMonthlyProjectTrends();

        // 3. Data Distribusi Status Proyek Aktif
        $projectStatuses = $this->getActiveProjectStatuses();

        // 4. Top 5 Produk Terlaris
        $topProducts = $this->orderItemsRepository->getTopSellingProducts(5);

        // 5. 5 Permintaan Tarik Dana Terbaru
        $tarikDana = $this->withdrawalRequestsRepository->getLatestRequests(5);

        return array_merge($counters, $projectTrends, $projectStatuses, [
            'tarikDana'   => $tarikDana,
            'topProducts' => $topProducts,
        ]);
    }

    /**
     * Mengambil data tren pengajuan proyek bulanan untuk Chart.js
     */
    private function getMonthlyProjectTrends(): array
    {
        $db = \Config\Database::connect();
        $sixMonthsAgo = date('Y-m-01 00:00:00', strtotime('-5 months'));

        $designRes = $db->table('design_requests')
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->where('created_at >=', $sixMonthsAgo)
            ->groupBy('month')
            ->get()->getResultArray();

        $constRes = $db->table('construction_requests')
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->where('created_at >=', $sixMonthsAgo)
            ->groupBy('month')
            ->get()->getResultArray();

        $renovRes = $db->table('renovation_requests')
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->where('created_at >=', $sixMonthsAgo)
            ->groupBy('month')
            ->get()->getResultArray();

        $designMap = array_column($designRes, 'total', 'month');
        $constMap = array_column($constRes, 'total', 'month');
        $renovMap = array_column($renovRes, 'total', 'month');

        $labels = [];
        $designData = [];
        $constData = [];
        $renovData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-$i months"));
            $monthName = date('F Y', strtotime("-$i months"));
            $labels[] = $monthName;
            $designData[] = (int)($designMap[$monthKey] ?? 0);
            $constData[] = (int)($constMap[$monthKey] ?? 0);
            $renovData[] = (int)($renovMap[$monthKey] ?? 0);
        }

        return [
            'projectLabels' => $labels,
            'designTrend'   => $designData,
            'constTrend'    => $constData,
            'renovTrend'    => $renovData
        ];
    }

    /**
     * Mengambil jumlah proyek aktif berdasarkan tahapan pengerjaan
     */
    private function getActiveProjectStatuses(): array
    {
        $db = \Config\Database::connect();

        // 1. Survey / Pending
        $surveyCount = 0;
        $surveyCount += $db->table('design_requests')->whereIn('status', ['PENDING', 'SURVEY_SCHEDULED'])->countAllResults();
        $surveyCount += $db->table('construction_requests')->whereIn('status', ['PENDING', 'SURVEY'])->countAllResults();
        $surveyCount += $db->table('renovation_requests')->whereIn('status', ['PENDING', 'SURVEY'])->countAllResults();

        // 2. Tahap Desain
        $designCount = 0;
        $designCount += $db->table('design_requests')->where('status', 'PAYMENT_VERIFIED')->countAllResults();
        $designCount += $db->table('construction_requests')->where('status', 'DESIGNING')->countAllResults();
        $designCount += $db->table('renovation_requests')->where('status', 'DESIGNING')->countAllResults();

        // 3. Tahap RAB
        $rabCount = 0;
        $rabCount += $db->table('construction_requests')->where('status', 'RAB')->countAllResults();
        $rabCount += $db->table('renovation_requests')->where('status', 'RAB')->countAllResults();

        // 4. Masa Pelaksanaan
        $workCount = 0;
        $workCount += $db->table('construction_requests')->where('status', 'CONSTRUCTION')->countAllResults();
        $workCount += $db->table('renovation_requests')->where('status', 'RENOVATION')->countAllResults();

        return [
            'projectStatusLabels' => ['Survey & Pending', 'Tahap Desain', 'Tahap RAB', 'Masa Pelaksanaan'],
            'projectStatusData'   => [$surveyCount, $designCount, $rabCount, $workCount]
        ];
    }
}
