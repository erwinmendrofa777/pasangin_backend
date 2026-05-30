<?php

namespace App\Modules\Admin\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Admin\Services\ActivityLogService;

class ActivityLog extends BaseController
{
    protected ActivityLogService $svc;

    public function __construct()
    {
        $this->svc = new ActivityLogService();
    }

    public function index()
    {
        // Hanya role yang memiliki akses 'activity_log_view' yang bisa melihat.
        if (!can('activity_log_view')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat log aktivitas.');
        }

        // Mengambil semua data log melalui service
        $logs = $this->svc->getLogsForAdmin();

        return view('App\Modules\Admin\Views\activity_logs/index', [
            'title' => 'Log Aktivitas Admin',
            'logs' => $logs
        ]);
    }
}
