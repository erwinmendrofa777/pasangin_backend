<?php

namespace App\Modules\Dashboard\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Dashboard\Services\DashboardService;
use App\Modules\Dashboard\Services\DashboardDesainerService;
use App\Modules\Dashboard\Services\DashboardKadivDesainerService;
use App\Modules\Dashboard\Services\DashboardEstimatorService;
use App\Modules\Dashboard\Services\DashboardContentCreatorService;
use App\Modules\Dashboard\Services\DashboardAccountingService;

class Dashboard extends BaseController
{
    protected DashboardService $svc;
    protected DashboardDesainerService $desainerSvc;
    protected DashboardKadivDesainerService $kadivSvc;
    protected DashboardEstimatorService $estimatorSvc;
    protected DashboardContentCreatorService $creatorSvc;
    protected DashboardAccountingService $accountingSvc;

    public function __construct()
    {
        $this->svc = new DashboardService();
        $this->desainerSvc = new DashboardDesainerService();
        $this->kadivSvc = new DashboardKadivDesainerService();
        $this->estimatorSvc = new DashboardEstimatorService();
        $this->creatorSvc = new DashboardContentCreatorService();
        $this->accountingSvc = new DashboardAccountingService();
    }

    public function index()
    {
        $role = strtolower(session()->get('role') ?? '');
        $userId = session()->get('user_id');

        // 1. Admin dengan permission penuh (seperti Super Admin) → dashboard statistik lengkap
        if (can('dashboard_view')) {
            $stats = $this->svc->getDashboardStats();

            return view('App\Modules\Dashboard\Views\dashboard', array_merge($stats, [
                'title' => 'Dashboard Admin',
                'name' => session()->get('full_name')
            ]));
        }

        // 2. Admin dengan role Kepala Divisi Desain ATAU memiliki permission dashboard_kadiv_desainer
        if ($role === 'kepala divisi desain' || can('dashboard_kadiv_desainer')) {
            $kadivStats = $this->kadivSvc->getKadivDashboardStats();

            return view('App\Modules\Dashboard\Views\dashboardKadivDesainer', [
                'title' => 'Dashboard Kepala Divisi Desain',
                'kadivStats' => $kadivStats,
            ]);
        }

        // 3. Admin dengan permission desainer → dashboard desainer
        if (can('dashboard_desainer') || in_array($role, ['drafter', 'arsitek'])) {
            $desainerStats = $this->desainerSvc->getDesainerDashboardStats();

            return view('App\Modules\Dashboard\Views\dashboardDesainer', [
                'title' => 'Dashboard Desainer',
                'desainerStats' => $desainerStats
            ]);
        }

        // 4. Admin dengan role Estimator ATAU memiliki permission dashboard_estimator → dashboard estimator
        if ($role === 'estimator' || can('dashboard_estimator')) {
            $estimatorStats = $this->estimatorSvc->getEstimatorDashboardStats();

            return view('App\Modules\Dashboard\Views\dashboardEstimator', [
                'title' => 'Dashboard Estimator',
                'estimatorStats' => $estimatorStats
            ]);
        }

        // 5. Admin dengan role Content Creator ATAU memiliki permission dashboard_content_creator → dashboard content creator
        if ($role === 'content creator' || can('dashboard_content_creator')) {
            $creatorStats = $this->creatorSvc->getContentCreatorStats();

            return view('App\Modules\Dashboard\Views\dashboardContentCreator', [
                'title' => 'Dashboard Content Creator',
                'creatorStats' => $creatorStats
            ]);
        }

        // 6. Admin dengan role Accounting ATAU memiliki permission dashboard_accounting → dashboard accounting
        if ($role === 'accounting' || can('dashboard_accounting')) {
            $accountingStats = $this->accountingSvc->getAccountingDashboardStats();

            return view('App\Modules\Dashboard\Views\dashboardAccounting', [
                'title' => 'Dashboard Accounting',
                'accountingStats' => $accountingStats
            ]);
        }

        // Tidak punya akses sama sekali
        return view('App\Modules\Autentications\Views\no_access', ['title' => 'Akses Ditolak']);
    }
}

