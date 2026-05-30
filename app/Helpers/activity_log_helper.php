<?php

use App\Modules\Admin\Services\ActivityLogService;

if (!function_exists('log_admin_activity')) {
    /**
     * Helper cepat untuk mencatat aktivitas admin saat ini.
     * 
     * @param string $action 'create', 'update', 'delete', 'login', dll.
     * @param string $module Nama modul (e.g., 'Tips', 'Users')
     * @param string $description Penjelasan aktivitas.
     */
    function log_admin_activity(string $action, string $module, string $description)
    {
        ActivityLogService::logCurrentUser($action, $module, $description);
    }
}
