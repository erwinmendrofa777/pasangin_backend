<?php

namespace App\Modules\Dashboard\Services;

class DashboardContentCreatorService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getContentCreatorStats(): array
    {
        // 1. KPI (Statistik Utama)
        $activeBanners = $this->db->table('banners')->where('is_active', 1)->countAllResults();
        $activeTips = $this->db->table('tips')->where('is_active', 1)->countAllResults();
        $totalNotifications = $this->db->table('notifications')->countAllResults();
        
        $inactiveBanners = $this->db->table('banners')->where('is_active', 0)->countAllResults();
        $inactiveTips = $this->db->table('tips')->where('is_active', 0)->countAllResults();
        $draftContent = $inactiveBanners + $inactiveTips;

        // 2. Data Grafik: Distribusi Target Aplikasi (Banners & Tips gabungan)
        $targetAppStats = [
            'client' => 0,
            'tukang' => 0
        ];

        $bannerTargets = $this->db->table('banners')
            ->select('target_app, COUNT(id) as total')
            ->groupBy('target_app')
            ->get()->getResultArray();

        $tipsTargets = $this->db->table('tips')
            ->select('target_app, COUNT(id) as total')
            ->groupBy('target_app')
            ->get()->getResultArray();

        foreach ([$bannerTargets, $tipsTargets] as $targets) {
            foreach ($targets as $row) {
                $target = strtolower($row['target_app']);
                if (strpos($target, 'client') !== false) {
                    $targetAppStats['client'] += $row['total'];
                } elseif (strpos($target, 'tukang') !== false) {
                    $targetAppStats['tukang'] += $row['total'];
                }
            }
        }

        // 3. Data Grafik: Tren Konten 6 Bulan Terakhir
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-$i months"));
            $months[$monthKey] = [
                'label' => date('M Y', strtotime("-$i months")),
                'banners' => 0,
                'tips' => 0
            ];
        }

        // Tren Banners
        $bannerTrends = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as total
            FROM banners
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ")->getResultArray();

        foreach ($bannerTrends as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['banners'] += $row['total'];
            }
        }

        // Tren Tips
        $tipsTrends = $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as total
            FROM tips
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ")->getResultArray();

        foreach ($tipsTrends as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['tips'] += $row['total'];
            }
        }

        $chartMonthlyLabels = [];
        $chartMonthlyBanners = [];
        $chartMonthlyTips = [];

        foreach ($months as $data) {
            $chartMonthlyLabels[] = $data['label'];
            $chartMonthlyBanners[] = $data['banners'];
            $chartMonthlyTips[] = $data['tips'];
        }

        return [
            'kpis' => [
                'active_banners' => $activeBanners,
                'active_tips' => $activeTips,
                'total_notifications' => $totalNotifications,
                'draft_content' => $draftContent
            ],
            'charts' => [
                'target_app' => [
                    'labels' => ['Client', 'Tukang'],
                    'data' => [
                        $targetAppStats['client'],
                        $targetAppStats['tukang']
                    ]
                ],
                'monthly_trend' => [
                    'labels' => $chartMonthlyLabels,
                    'banners' => $chartMonthlyBanners,
                    'tips' => $chartMonthlyTips
                ]
            ]
        ];
    }
}
