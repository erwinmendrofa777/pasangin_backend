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

        // 1b. Tambahan Metrik Operasional Baru
        // Tugas Belum Ditugaskan Desainer
        $unassignedTasksCount = $this->designTargetsModel
            ->where('user_admin_id', null)
            ->countAllResults();

        // Antrean Review Desain (project_designs PENDING)
        $db = \Config\Database::connect();
        $awaitingReviews = $db->table('project_designs pd')
            ->select('pd.*, dt.task_name, dt.design_request_id, dr.design_concept, dr.full_name as client_name, ua.full_name as designer_name, ua.photo as designer_photo')
            ->join('design_targets dt', 'dt.id = pd.design_targets_id', 'left')
            ->join('design_requests dr', 'dr.id = pd.design_request_id', 'left')
            ->join('user_admin ua', 'ua.id = pd.user_admin_id', 'left')
            ->where('pd.status', 'PENDING')
            ->orderBy('pd.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $awaitingReviewsCount = $this->projectDesignModel
            ->where('status', 'PENDING')
            ->countAllResults();

        // Statistik Tugas Aktif per Status Kanban
        $tasksBuilder = $db->table('design_targets dt')
            ->select("
                dt.status as target_status,
                COUNT(pd.id) as total_designs,
                SUM(CASE WHEN pd.status = 'APPROVED' THEN 1 ELSE 0 END) as approved_designs,
                SUM(CASE WHEN pd.status = 'PENDING' THEN 1 ELSE 0 END) as pending_designs,
                SUM(CASE WHEN pd.status = 'REJECTED' THEN 1 ELSE 0 END) as rejected_designs
            ")
            ->join('design_requests dr', 'dr.id = dt.design_request_id', 'left')
            ->join('project_designs pd', 'pd.design_targets_id = dt.id', 'left')
            ->whereNotIn('dr.status', ['COMPLETED', 'CANCELLED'])
            ->groupBy('dt.id')
            ->get()
            ->getResultArray();

        $kanbanStatusSummary = [
            'pending' => 0,
            'progress' => 0,
            'review' => 0,
            'done' => 0,
        ];

        foreach ($tasksBuilder as $task) {
            $tStatus = $task['target_status'];
            $totalDesigns = (int)($task['total_designs'] ?? 0);
            $approvedDesigns = (int)($task['approved_designs'] ?? 0);
            $pendingDesigns = (int)($task['pending_designs'] ?? 0);
            $rejectedDesigns = (int)($task['rejected_designs'] ?? 0);

            if ($tStatus === 'DONE' || $approvedDesigns > 0) {
                $kanbanStatusSummary['done']++;
            } elseif ($totalDesigns > 0 && $approvedDesigns == 0 && $pendingDesigns > 0) {
                $kanbanStatusSummary['review']++;
            } elseif ($tStatus === 'ON PROGRESS' || ($totalDesigns > 0 && $rejectedDesigns > 0 && $approvedDesigns == 0)) {
                $kanbanStatusSummary['progress']++;
            } else {
                $kanbanStatusSummary['pending']++;
            }
        }

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

        $submissionTrends = $this->getSubmissionTrends();

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
                'unassigned_tasks' => $unassignedTasksCount,
                'awaiting_reviews_count' => $awaitingReviewsCount,
            ],
            'team_workload' => $teamWorkload,
            'critical_projects' => $criticalProjects,
            'submission_trends' => $submissionTrends,
            'awaiting_reviews' => $awaitingReviews,
            'kanban_status_summary' => $kanbanStatusSummary,
        ];
    }

    /**
     * Mendapatkan total pengajuan proyek desain per bulan dalam 12 bulan terakhir.
     */
    private function getSubmissionTrends(): array
    {
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("-$i months"));
            $monthEnd = date('Y-m-t 23:59:59', strtotime("-$i months"));
            $label = date('M Y', strtotime("-$i months")); // Contoh: "Jun 2026"

            $count = $this->designModel
                ->where('created_at >=', $monthStart)
                ->where('created_at <=', $monthEnd)
                ->countAllResults();

            $monthlyData[] = [
                'label' => $label,
                'count' => (int) $count
            ];
        }

        return $monthlyData;
    }
}
