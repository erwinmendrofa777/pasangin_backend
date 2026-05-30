<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Modules\Notifications\Repositories\FcmTokenRepository;

class NotificationController extends ResourceController
{
    protected $format = 'json';
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * 1. Ambil List Notifikasi (Dinamis Tukang/Client)
     * GET: api/{userType}/notifications/{userId}
     */
    public function index($userType = null, $userId = null)
    {
        try {
            if ($userId === null || $userType === null) {
                return $this->fail('Parameter tidak lengkap.', 400);
            }

            $userType = strtolower($userType);

            // 1. Ambil waktu registrasi user agar tidak melihat notifikasi bulk masa lalu
            $regDate = '2000-01-01 00:00:00';
            $table = null;
            if ($userType === 'client') {
                $table = 'users';
            } elseif ($userType === 'tukang') {
                $table = 'tukang';
            } elseif ($userType === 'supplier') {
                $table = 'suppliers';
            }

            if ($table) {
                $u = $this->db->table($table)->select('created_at')->where('id', $userId)->get()->getRowArray();
                if (!$u) {
                    return $this->failNotFound('User tidak ditemukan di tabel ' . $table);
                }
                if (!empty($u['created_at'])) {
                    $regDate = $u['created_at'];
                }
            }

            // Subquery untuk mengecek notifikasi yang sudah dibaca
            $isReadQuery = $this->db->table('notification_reads nr')
                ->select('COUNT(*)')
                ->where('nr.notification_id = n.id')
                ->where('nr.user_id', $userId)
                ->where('nr.user_type', $userType)
                ->getCompiledSelect();

            // Subquery untuk mengecek notifikasi yang dihapus
            $deletesQuery = $this->db->table('notification_deletes')
                ->select('notification_id')
                ->where('user_id', $userId)
                ->where('user_type', $userType)
                ->getCompiledSelect();

            // Eksekusi Main Query
            $notifications = $this->db->table('notifications n')
                ->select("n.*, ($isReadQuery) as is_read")
                ->where('n.target_type', $userType)
                ->where('n.created_at >=', $regDate)
                ->where("n.id NOT IN ($deletesQuery)", null, false)
                ->orderBy('n.created_at', 'DESC')
                ->get()
                ->getResultArray();

            return $this->respond([
                'status' => true,
                'message' => 'Notifikasi ' . $userType . ' berhasil ditarik.',
                'data' => $notifications
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 2. Tandai Dibaca (Dinamis)
     */
    public function markAsRead($userType = null)
    {
        try {
            $json = $this->request->getJSON();
            if (!$json->user_id || !$json->notification_id)
                return $this->fail('Data tidak lengkap.', 400);

            $this->db->table('notification_reads')->ignore(true)->insert([
                'notification_id' => $json->notification_id,
                'user_id' => $json->user_id,
                'user_type' => $userType,
                'read_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respond(['status' => true, 'message' => 'Tanda baca berhasil.']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 3. Tandai Semua Dibaca (Dinamis)
     */
    public function markAllAsRead($userType = null)
    {
        try {
            $json = $this->request->getJSON();
            if (!$json->user_id)
                return $this->fail('User ID dibutuhkan.', 400);

            $notifs = $this->db->table('notifications')->where('target_type', $userType)->get()->getResultArray();
            foreach ($notifs as $n) {
                $this->db->table('notification_reads')->ignore(true)->insert([
                    'notification_id' => $n['id'],
                    'user_id' => $json->user_id,
                    'user_type' => $userType,
                    'read_at' => date('Y-m-d H:i:s')
                ]);
            }
            return $this->respond(['status' => true, 'message' => 'Semua sudah dibaca.']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 4. Hapus Notifikasi (Personal)
     */
    public function deleteNotification($userType = null, $notifId = null, $userId = null)
    {
        try {
            $this->db->table('notification_deletes')->ignore(true)->insert([
                'notification_id' => $notifId,
                'user_id' => $userId,
                'user_type' => $userType
            ]);
            return $this->respond(['status' => true, 'message' => 'Dihapus.']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 5. Hitung Unread (Dinamis untuk Badge)
     */
    public function unreadCount($userType = null, $userId = null)
    {
        try {
            $userType = strtolower($userType);

            // Ambil waktu registrasi user
            $regDate = '2000-01-01 00:00:00';
            $table = null;
            if ($userType === 'client') {
                $table = 'users';
            } elseif ($userType === 'tukang') {
                $table = 'tukang';
            } elseif ($userType === 'supplier') {
                $table = 'suppliers';
            }
            if ($table) {
                $u = $this->db->table($table)->select('created_at')->where('id', $userId)->get()->getRowArray();
                if ($u && !empty($u['created_at']))
                    $regDate = $u['created_at'];
            }

            $readsQuery = $this->db->table('notification_reads')
                ->select('notification_id')
                ->where('user_id', $userId)
                ->where('user_type', $userType)
                ->getCompiledSelect();

            $deletesQuery = $this->db->table('notification_deletes')
                ->select('notification_id')
                ->where('user_id', $userId)
                ->where('user_type', $userType)
                ->getCompiledSelect();

            $unreadCount = $this->db->table('notifications n')
                ->where('n.target_type', $userType)
                ->where('n.created_at >=', $regDate)
                ->where("n.id NOT IN ($readsQuery)", null, false)
                ->where("n.id NOT IN ($deletesQuery)", null, false)
                ->countAllResults();

            return $this->respond(['status' => true, 'unread' => $unreadCount]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 6. Toggle Notifikasi ON/OFF (Per-Device)
     * POST: api/{userType}/notifications/toggle
     * Body: { "token": "FCM_TOKEN", "status": true/false }
     */
    public function toggleNotification($userType = null)
    {
        try {
            if ($userType === null) {
                return $this->fail('Parameter tidak lengkap.', 400);
            }

            $json = $this->request->getJSON();

            if (empty($json->token) || !isset($json->status)) {
                return $this->fail('Parameter tidak lengkap. Butuh: token, status (true/false).', 400);
            }

            $fcmRepo = new FcmTokenRepository();
            $result = $fcmRepo->toggleNotification($json->token, strtolower($userType), (bool) $json->status);

            if (!$result) {
                return $this->failNotFound('Token FCM tidak ditemukan atau bukan milik tipe ' . $userType . '.');
            }

            $statusLabel = $json->status ? 'diaktifkan' : 'dinonaktifkan';
            return $this->respond([
                'status'  => true,
                'message' => 'Notifikasi berhasil ' . $statusLabel . '.'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}