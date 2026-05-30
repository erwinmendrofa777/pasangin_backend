<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Design\Models\DesignRequestModel;
use App\Modules\Construction\Models\ConstructionModel;
use App\Modules\Renovation\Models\RenovationModel;
use App\Modules\Design\Models\ProjectDesignsModel;
use App\Modules\Admin\Models\UserAdminModel;
use App\Modules\Design\Models\DesignTargetsModel;

/**
 * DashboardKadivDesainerService
 *
 * Mengelola data analitis dan beban kerja untuk Kepala Divisi Desain.
 */
class DashboardKadivDesainerService
{
    protected DesignRequestModel $designModel;
    protected ConstructionModel $constructionModel;
    protected RenovationModel $renovationModel;
    protected ProjectDesignsModel $projectDesignModel;
    protected UserAdminModel $userAdminModel;
    protected DesignTargetsModel $designTargetsModel;

    public function __construct()
    {
        $this->designModel = new DesignRequestModel();
        $this->constructionModel = new ConstructionModel();
        $this->renovationModel = new RenovationModel();
        $this->projectDesignModel = new ProjectDesignsModel();
        $this->userAdminModel = new UserAdminModel();
        $this->designTargetsModel = new DesignTargetsModel();
    }

    /**
     * Mengambil statistik pengawasan divisi desainer
     */
    public function getKadivDashboardStats(): array
    {
        // 1. Overview Stats
        // Proyek Aktif = Design Requests (non-completed/cancelled) + Construction (DESIGNING) + Renovation (DESIGNING)
        $activeDesign = $this->designModel
            ->whereNotIn('status', ['COMPLETED', 'CANCELLED'])
            ->countAllResults();

        $activeConstruction = $this->constructionModel
            ->where('status', 'DESIGNING')
            ->countAllResults();

        $activeRenovation = $this->renovationModel
            ->where('status', 'DESIGNING')
            ->countAllResults();

        $totalActiveProjects = $activeDesign + $activeConstruction + $activeRenovation;

        // Antrean Desain Baru
        $pendingDesignRequests = $this->designModel
            ->where('status', 'PENDING')
            ->countAllResults();

        // Kinerja Tim Bulan Ini (Approved Designs in current month)
        $firstDayOfMonth = date('Y-m-01 00:00:00');
        $approvedDesignsThisMonth = $this->projectDesignModel
            ->where('status', 'APPROVED')
            ->where('created_at >=', $firstDayOfMonth)
            ->countAllResults();

        // 2. Beban Kerja Tim (Drafter & Arsitek)
        $designers = $this->userAdminModel
            ->groupStart()
                ->like('role', 'desainer', 'both')
                ->orLike('role', 'drafter', 'both')
                ->orLike('role', 'arsitek', 'both')
                ->orLike('role', 'desain', 'both')
            ->groupEnd()
            ->orderBy('full_name', 'ASC')
            ->findAll();

        $teamWorkload = [];
        foreach ($designers as $designer) {
            $activeTasksCount = $this->designTargetsModel
                ->where('user_admin_id', $designer['id'])
                ->whereIn('status', ['PENDING', 'ON PROGRESS'])
                ->countAllResults();

            $totalApproved = $this->projectDesignModel
                ->where('user_admin_id', $designer['id'])
                ->where('status', 'APPROVED')
                ->countAllResults();

            $teamWorkload[] = [
                'id' => $designer['id'],
                'full_name' => $designer['full_name'],
                'role' => $designer['role'],
                'photo' => $designer['photo'],
                'active_tasks' => $activeTasksCount,
                'completed_designs' => $totalApproved
            ];
        }

        // 3. Proyek Mendesak (Urgent Projects)
        // Ambil design_requests yang aktif (non-completed/cancelled), diurutkan berdasarkan target_date ASC (limit 5)
        $criticalProjects = $this->designModel
            ->whereNotIn('status', ['COMPLETED', 'CANCELLED'])
            ->where('target_date IS NOT NULL')
            ->orderBy('target_date', 'ASC')
            ->limit(5)
            ->findAll();

        $historicalTrends = $this->getHistoricalTrends();

        return [
            'overview' => [
                'active_projects' => $totalActiveProjects,
                'active_projects_breakdown' => [
                    'design' => $activeDesign,
                    'construction' => $activeConstruction,
                    'renovation' => $activeRenovation,
                ],
                'pending_requests' => $pendingDesignRequests,
                'approved_this_month' => $approvedDesignsThisMonth,
            ],
            'team_workload' => $teamWorkload,
            'critical_projects' => $criticalProjects,
            'historical_trends' => $historicalTrends,
        ];
    }

    /**
     * Mendapatkan tren riwayat proyek aktif, antrean, dan desain disetujui (6 bulan lalu, 1 bulan lalu, sekarang)
     */
    private function getHistoricalTrends(): array
    {
        $now = date('Y-m-d H:i:s');
        $oneMonthAgo = date('Y-m-d H:i:s', strtotime('-1 month'));
        $sixMonthsAgo = date('Y-m-d H:i:s', strtotime('-6 months'));

        $points = [
            [
                'label' => '6 Bulan Lalu',
                'date' => $sixMonthsAgo,
                'start_of_month' => date('Y-m-01 00:00:00', strtotime('-6 months')),
                'end_of_month' => date('Y-m-t 23:59:59', strtotime('-6 months')),
            ],
            [
                'label' => '1 Bulan Lalu',
                'date' => $oneMonthAgo,
                'start_of_month' => date('Y-m-01 00:00:00', strtotime('-1 month')),
                'end_of_month' => date('Y-m-t 23:59:59', strtotime('-1 month')),
            ],
            [
                'label' => 'Sekarang',
                'date' => $now,
                'start_of_month' => date('Y-m-01 00:00:00'),
                'end_of_month' => date('Y-m-t 23:59:59'),
            ]
        ];

        $data = [];
        foreach ($points as $pt) {
            $dateLimit = $pt['date'];

            // Active Projects at $dateLimit
            $activeDesign = $this->designModel
                ->where('created_at <=', $dateLimit)
                ->groupStart()
                    ->whereNotIn('status', ['COMPLETED', 'CANCELLED'])
                    ->orWhere('updated_at >', $dateLimit)
                ->groupEnd()
                ->countAllResults();

            $activeConstruction = $this->constructionModel
                ->where('created_at <=', $dateLimit)
                ->groupStart()
                    ->where('status', 'DESIGNING')
                    ->orWhere('updated_at >', $dateLimit)
                ->groupEnd()
                ->countAllResults();

            $activeRenovation = $this->renovationModel
                ->where('created_at <=', $dateLimit)
                ->groupStart()
                    ->where('status', 'DESIGNING')
                    ->orWhere('updated_at >', $dateLimit)
                ->groupEnd()
                ->countAllResults();

            $activeProjects = $activeDesign + $activeConstruction + $activeRenovation;

            // Pending Requests at $dateLimit
            $pendingRequests = $this->designModel
                ->where('created_at <=', $dateLimit)
                ->groupStart()
                    ->where('status', 'PENDING')
                    ->orWhere('updated_at >', $dateLimit)
                ->groupEnd()
                ->countAllResults();

            // Approved Designs in that month
            $approvedDesigns = $this->projectDesignModel
                ->where('status', 'APPROVED')
                ->where('created_at >=', $pt['start_of_month'])
                ->where('created_at <=', $pt['end_of_month'])
                ->countAllResults();

            $data[] = [
                'label' => $pt['label'],
                'active_projects' => $activeProjects,
                'pending_requests' => $pendingRequests,
                'approved_designs' => $approvedDesigns,
            ];
        }

        return $data;
    }
}
