<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Repositories\AdminActivityLogRepository;
use App\Modules\Admin\Repositories\Contracts\AdminActivityLogRepositoryInterface;
use Exception;

class ActivityLogService
{
    protected AdminActivityLogRepositoryInterface $repo;

    public function __construct()
    {
        $this->repo = new AdminActivityLogRepository();
    }

    /**
     * Mengambil data log aktivitas admin.
     * Membersihkan log lama secara otomatis.
     */
    public function getLogsForAdmin(): array
    {
        $this->cleanupOldLogs();
        return $this->repo->getLogsWithAdmin();
    }

    /**
     * Menghapus log yang umurnya lebih dari 90 hari.
     */
    public function cleanupOldLogs(): void
    {
        $thresholdDate = date('Y-m-d H:i:s', strtotime('-90 days'));
        $this->repo->deleteOlderThan($thresholdDate);
    }

    /**
     * Mencatat aktivitas admin ke dalam database.
     * 
     * @param int $adminId ID Admin yang melakukan aksi
     * @param string $action Jenis aksi (e.g., 'login', 'create', 'update')
     * @param string $module Nama modul (e.g., 'Tips', 'Users')
     * @param string $description Deskripsi spesifik (e.g., 'Update status Tips ID 5')
     */
    public static function log(int $adminId, string $action, string $module, string $description): void
    {
        try {
            $request = service('request');
            $ipAddress = $request->getIPAddress();
            $userAgent = $request->getUserAgent()->getAgentString();

            $repo = new AdminActivityLogRepository();
            $repo->insert([
                'admin_id' => $adminId,
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);
        } catch (Exception $e) {
            // Log secara internal jika terjadi kegagalan (jangan hentikan aplikasi)
            log_message('error', 'Gagal mencatat Activity Log: ' . $e->getMessage());
        }
    }

    /**
     * Helper cepat untuk log aksi dari admin yang sedang login.
     * 
     * @param string $action Jenis aksi
     * @param string $module Nama modul
     * @param string $description Deskripsi spesifik
     */
    public static function logCurrentUser(string $action, string $module, string $description): void
    {
        $session = session();
        $adminId = $session->get('user_id');

        if ($adminId && $session->get('isLoggedIn')) {
            self::log((int) $adminId, $action, $module, $description);
        }
    }
}
