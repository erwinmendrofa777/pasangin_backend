<?php

namespace App\Modules\Notifications\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Notifications\Services\NotificationService;

class Notification extends BaseController
{
    protected $notifService;

    public function __construct()
    {
        $this->notifService = new NotificationService();
    }

    /**
     * 1. HALAMAN DAFTAR RIWAYAT NOTIFIKASI
     */
    public function index()
    {
        if (!can('notification')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        $result = $this->notifService->getHistoryWithStats();

        return view('App\Modules\Notifications\Views\index', array_merge($result, [
            'title' => 'Riwayat Notifikasi'
        ]));
    }

    /**
     * 2. HALAMAN FORM KIRIM (CREATE)
     */
    public function create()
    {
        if (!can('notification_create')) {
            return redirect()->to('/admin/notification')->with('error', 'Anda tidak memiliki akses untuk membuat notifikasi.');
        }

        return view('App\Modules\Notifications\Views\create', [
            'title' => 'Kirim Notifikasi Baru'
        ]);
    }

    /**
     * 3. PROSES SIMPAN & KIRIM
     */
    public function send()
    {
        if (!can('notification_create')) {
            return redirect()->to('/admin/notification')->with('error', 'Anda tidak memiliki akses untuk membuat notifikasi.');
        }

        $sendType = $this->request->getPost('send_type'); // 'all' atau 'specific'
        $targetType = $this->request->getPost('target'); // 'client', 'tukang', 'supplier'
        $targetId = $this->request->getPost('target_id'); // ID spesifik jika send_type == 'specific'
        $title = $this->request->getPost('title');
        $message = $this->request->getPost('message');
        $file = $this->request->getFile('image');

        try {
            if ($sendType === 'specific' && !empty($targetId)) {
                $result = $this->notifService->sendPersonal($targetType, $targetId, $title, $message, $file);
            } else {
                $result = $this->notifService->sendBulk($targetType, $title, $message, $file);
            }

            log_admin_activity('create', 'Notifikasi', 'Kirim Notifikasi');
            $successMsg = "Notifikasi berhasil dikirim! (Sukses: {$result['success']}, Gagal: {$result['failure']})";
            return redirect()->to(base_url('admin/notification'))->with('success', $successMsg);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * 4. AJAX: CARI USER UNTUK SELECT2
     */
    public function searchUsers()
    {
        $role = $this->request->getGet('role');
        $term = $this->request->getGet('q') ?? '';

        $results = $this->notifService->searchTargets($role, $term);

        return $this->response->setJSON(['results' => $results]);
    }

    /**
     * 5. AJAX: SIMPAN FCM TOKEN ADMIN
     */
    public function saveToken()
    {
        try {
            $token = $this->request->getPost('token');
            $adminId = session()->get('user_id');

            if (!$token || !$adminId) {
                return $this->response->setJSON(['status' => false, 'message' => 'Token atau Admin ID kosong']);
            }

            $fcmModel = new \App\Modules\Notifications\Models\FcmTokenModel();

            // Cek apakah token sudah ada untuk admin ini
            $existing = $fcmModel->where([
                'user_id' => $adminId,
                'user_type' => 'admin',
                'fcm_token' => $token
            ])->first();

            if (!$existing) {
                $fcmModel->insert([
                    'user_id' => $adminId,
                    'user_type' => 'admin',
                    'fcm_token' => $token,
                ]);
            }

            return $this->response->setJSON(['status' => true, 'message' => 'Token berhasil disimpan']);
        } catch (\Throwable $e) {
            log_message('error', '[Notification::saveToken] Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    /**
     * 6. AJAX: AMBIL NOTIFIKASI TERBARU UNTUK NAVBAR
     */
    public function getLatest()
    {
        try {
            $adminId = session()->get('user_id');
            $adminRole = session()->get('role');
            $db = \Config\Database::connect();
            
            $notifications = $db->table('notifications')
                ->groupStart()
                    ->where('target_type', 'admin')
                    ->where('target_id IS NULL')
                ->groupEnd()
                ->orGroupStart()
                    ->where('target_type', 'admin')
                    ->where('target_id', $adminId)
                ->groupEnd()
                ->orGroupStart()
                    ->where('target_type', 'role:' . $adminRole . '(admin)')
                ->groupEnd()
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            return $this->response->setJSON($notifications);
        } catch (\Throwable $e) {
            log_message('error', '[Notification::getLatest] Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}
