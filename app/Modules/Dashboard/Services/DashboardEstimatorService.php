<?php

namespace App\Modules\Dashboard\Services;

use Config\Database;

/**
 * DashboardEstimatorService
 *
 * Mengelola data agregat dan statistik untuk peran Estimator (RAB, Target, Addendum).
 */
class DashboardEstimatorService
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Mengambil seluruh data statistik untuk dashboard Estimator
     */
    public function getEstimatorDashboardStats(): array
    {
        // -------------------------------------------------------------
        // 1. QUERY PROJECT DATA (CONSTRUCTION & RENOVATION)
        // -------------------------------------------------------------

        // Query proyek konstruksi yang aktif/berjalan
        $constructionProjects = $this->db->query("
            SELECT 
                cr.id, 
                cr.full_name, 
                'construction' as type, 
                cr.status, 
                cr.created_at, 
                cr.rab_total as total_biaya,
                (SELECT COUNT(id) FROM construction_rabs WHERE construction_id = cr.id) as rab_count,
                (SELECT MIN(is_locked) FROM construction_rabs WHERE construction_id = cr.id) as is_rab_locked,
                (SELECT COUNT(id) FROM construction_targets WHERE construction_id = cr.id) as target_count,
                (SELECT COALESCE(SUM(bobot), 0) FROM construction_targets WHERE construction_id = cr.id) as total_bobot_target,
                (SELECT COUNT(id) FROM construction_addendum WHERE construction_id = cr.id) as addendum_count
            FROM construction_requests cr
            WHERE cr.status IN ('SURVEY', 'DESIGNING', 'RAB', 'CONSTRUCTION')
            ORDER BY cr.created_at DESC
        ")->getResultArray();

        // Query proyek renovasi yang aktif/berjalan
        $renovationProjects = $this->db->query("
            SELECT 
                rr.id, 
                rr.full_name, 
                'renovation' as type, 
                rr.status, 
                rr.created_at, 
                rr.rab_total as total_biaya,
                (SELECT COUNT(id) FROM renovation_rabs WHERE renovation_id = rr.id) as rab_count,
                (SELECT MIN(is_locked) FROM renovation_rabs WHERE renovation_id = rr.id) as is_rab_locked,
                (SELECT COUNT(id) FROM renovation_targets WHERE renovation_id = rr.id) as target_count,
                (SELECT COALESCE(SUM(bobot), 0) FROM renovation_targets WHERE renovation_id = rr.id) as total_bobot_target
            FROM renovation_requests rr
            WHERE rr.status IN ('SURVEY', 'DESIGNING', 'RAB', 'RENOVATION')
            ORDER BY rr.created_at DESC
        ")->getResultArray();

        // Gabungkan semua proyek
        $allProjects = array_merge($constructionProjects, $renovationProjects);

        // Sort gabungan proyek berdasarkan tanggal dibuat (DESC)
        usort($allProjects, function ($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        // -------------------------------------------------------------
        // 2. METRIK KPIs
        // -------------------------------------------------------------
        $queueRabCount = 0;       // Proyek dengan status RAB (Antrean Utama)
        $upcomingRabCount = 0;    // Proyek dengan status SURVEY/DESIGNING (Pra-RAB)
        $totalActiveProject = 0;  // Total proyek aktif berjalan
        $totalEstimatedBudget = 0; // Akumulasi total anggaran terkelola
        $totalAddendumCount = 0;  // Akumulasi jumlah addendum konstruksi

        foreach ($allProjects as $p) {
            if ($p['status'] === 'RAB') {
                $queueRabCount++;
            } elseif (in_array($p['status'], ['SURVEY', 'DESIGNING'])) {
                $upcomingRabCount++;
            }

            if (in_array($p['status'], ['CONSTRUCTION', 'RENOVATION'])) {
                $totalActiveProject++;
            }

            $totalEstimatedBudget += (float) $p['total_biaya'];

            if ($p['type'] === 'construction') {
                $totalAddendumCount += (int) $p['addendum_count'];
            }
        }

        // -------------------------------------------------------------
        // 3. ANTREAN TUGAS (TASK QUEUE) & PROYEK MASA DEPAN (UPCOMING)
        // -------------------------------------------------------------
        $mainQueue = [];
        $upcomingQueue = [];
        $activeProjectsWithTargets = [];

        foreach ($allProjects as $p) {
            if ($p['status'] === 'RAB') {
                // Antrean Utama Pembuatan RAB
                $mainQueue[] = $p;
            } elseif (in_array($p['status'], ['SURVEY', 'DESIGNING'])) {
                // Pra-RAB / Upcoming
                $upcomingQueue[] = $p;
            }

            // Proyek yang sedang konstruksi/renovasi untuk kepatuhan target
            if (in_array($p['status'], ['CONSTRUCTION', 'RENOVATION'])) {
                $activeProjectsWithTargets[] = $p;
            }
        }

        // -------------------------------------------------------------
        // 4. PEMANTAUAN KEPATUHAN TARGET MINGGUAN
        // -------------------------------------------------------------
        $targetCompliance = [
            'complete' => 0,      // Bobot = 100%
            'incomplete' => 0,    // Bobot > 0% dan < 100%
            'none' => 0,          // Bobot = 0%
            'list' => []
        ];

        foreach ($activeProjectsWithTargets as $p) {
            $bobot = (float) $p['total_bobot_target'];
            
            $complianceStatus = 'none';
            if ($bobot >= 100.0) {
                $complianceStatus = 'complete';
                $targetCompliance['complete']++;
            } elseif ($bobot > 0.0) {
                $complianceStatus = 'incomplete';
                $targetCompliance['incomplete']++;
            } else {
                $targetCompliance['none']++;
            }

            $targetCompliance['list'][] = [
                'id' => $p['id'],
                'full_name' => $p['full_name'],
                'type' => $p['type'],
                'status' => $p['status'],
                'total_bobot' => $bobot,
                'target_count' => $p['target_count'],
                'compliance_status' => $complianceStatus
            ];
        }

        // -------------------------------------------------------------
        // 5. DATA GRAFIK: TREN BIAYA 6 BULAN TERAKHIR
        // -------------------------------------------------------------
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[date('Y-m', strtotime("-$i months"))] = [
                'label' => date('M Y', strtotime("-$i months")),
                'rab' => 0,
                'addendum' => 0
            ];
        }

        // Query tren RAB Konstruksi — langsung dari baris RAB (bukan rab_total yang bisa = 0 saat unlock)
        $trenConstRab = $this->db->query("
            SELECT DATE_FORMAT(cr.created_at, '%Y-%m') as month,
                   SUM(rabs.total_price) as total
            FROM construction_requests cr
            INNER JOIN construction_rabs rabs ON rabs.construction_id = cr.id
            WHERE cr.status NOT IN ('PENDING', 'CANCELLED')
              AND cr.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(cr.created_at, '%Y-%m')
        ")->getResultArray();

        foreach ($trenConstRab as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['rab'] += (float) $row['total'];
            }
        }

        // Query tren RAB Renovasi — langsung dari baris RAB
        $trenRenovRab = $this->db->query("
            SELECT DATE_FORMAT(rr.created_at, '%Y-%m') as month,
                   SUM(rabs.total_price) as total
            FROM renovation_requests rr
            INNER JOIN renovation_rabs rabs ON rabs.renovation_id = rr.id
            WHERE rr.status NOT IN ('PENDING', 'CANCELLED')
              AND rr.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(rr.created_at, '%Y-%m')
        ")->getResultArray();

        foreach ($trenRenovRab as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['rab'] += (float) $row['total'];
            }
        }

        // Query tren Addendum Konstruksi — dari baris addendum, grouped by tanggal proyek
        $trenConstAddendum = $this->db->query("
            SELECT DATE_FORMAT(cr.created_at, '%Y-%m') as month,
                   SUM(adm.total_price) as total
            FROM construction_requests cr
            INNER JOIN construction_addendum adm ON adm.construction_id = cr.id
            WHERE cr.status NOT IN ('PENDING', 'CANCELLED')
              AND cr.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(cr.created_at, '%Y-%m')
        ")->getResultArray();

        foreach ($trenConstAddendum as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['addendum'] += (float) $row['total'];
            }
        }

        $chartMonthlyLabels = [];
        $chartMonthlyRab = [];
        $chartMonthlyAddendum = [];

        foreach ($months as $m) {
            $chartMonthlyLabels[] = $m['label'];
            $chartMonthlyRab[] = $m['rab'];
            $chartMonthlyAddendum[] = $m['addendum'];
        }

        // -------------------------------------------------------------
        // 6. DATA GRAFIK: PROPORSI KATEGORI PEKERJAAN RAB (TOP 5 & LAINNYA)
        // -------------------------------------------------------------
        $categoriesMap = [];

        // Ambil grup pekerjaan RAB konstruksi
        $constCategories = $this->db->query("
            SELECT TRIM(group_name) as cat_name, SUM(total_price) as total
            FROM construction_rabs
            WHERE group_name IS NOT NULL AND group_name != ''
            GROUP BY group_name
        ")->getResultArray();

        foreach ($constCategories as $row) {
            $name = strtoupper($row['cat_name']);
            $categoriesMap[$name] = ($categoriesMap[$name] ?? 0) + (float)$row['total'];
        }

        // Ambil grup pekerjaan RAB renovasi
        $renovCategories = $this->db->query("
            SELECT TRIM(group_name) as cat_name, SUM(total_price) as total
            FROM renovation_rabs
            WHERE group_name IS NOT NULL AND group_name != ''
            GROUP BY group_name
        ")->getResultArray();

        foreach ($renovCategories as $row) {
            $name = strtoupper($row['cat_name']);
            $categoriesMap[$name] = ($categoriesMap[$name] ?? 0) + (float)$row['total'];
        }

        // Urutkan kategori berdasarkan total pengeluaran terbanyak
        arsort($categoriesMap);

        $chartCategoryLabels = [];
        $chartCategoryValues = [];

        $index = 0;
        $otherSum = 0;
        foreach ($categoriesMap as $catName => $total) {
            if ($index < 5) {
                $chartCategoryLabels[] = $catName;
                $chartCategoryValues[] = $total;
            } else {
                $otherSum += $total;
            }
            $index++;
        }

        if ($otherSum > 0) {
            $chartCategoryLabels[] = 'LAINNYA';
            $chartCategoryValues[] = $otherSum;
        }

        // -------------------------------------------------------------
        // 7. RETURN PACKAGED STATS
        // -------------------------------------------------------------
        return [
            'kpis' => [
                'queue_rab_count' => $queueRabCount,
                'upcoming_rab_count' => $upcomingRabCount,
                'total_active_project' => $totalActiveProject,
                'total_estimated_budget' => $totalEstimatedBudget,
                'total_addendum_count' => $totalAddendumCount
            ],
            'queues' => [
                'main' => $mainQueue,
                'upcoming' => $upcomingQueue
            ],
            'compliance' => $targetCompliance,
            'charts' => [
                'monthly' => [
                    'labels' => $chartMonthlyLabels,
                    'rab' => $chartMonthlyRab,
                    'addendum' => $chartMonthlyAddendum
                ],
                'categories' => [
                    'labels' => $chartCategoryLabels,
                    'values' => $chartCategoryValues
                ]
            ]
        ];
    }
}
