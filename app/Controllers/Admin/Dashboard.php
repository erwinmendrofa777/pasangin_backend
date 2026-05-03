<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\DashboardService;

class Dashboard extends BaseController
{
    protected DashboardService $svc;

    public function __construct()
    {
        $this->svc = new DashboardService();
    }

    public function index()
    {
        if (!can('dashboard_view')) {
            return view('admin/no_access', ['title' => 'Akses Ditolak']);
        }

        $stats = $this->svc->getDashboardStats();

        return view('admin/dashboard', array_merge($stats, [
            'title' => 'Dashboard Admin',
            'name'  => session()->get('full_name')
        ]));
    }
}
