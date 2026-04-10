<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class NotificationController extends ResourceController
{
    protected $format = 'json';
    protected $db;

    public function __construct(){
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

            // Query cerdas: target_type mengikuti parameter URL ($userType)
            $sql = "SELECT n.*, 
                    (SELECT COUNT(*) FROM notification_reads nr 
                     WHERE nr.notification_id = n.id 
                     AND nr.user_id = ? 
                     AND nr.user_type = ?) as is_read 
                    FROM notifications n 
                    WHERE n.target_type = ? 
                    AND n.id NOT IN (
                        SELECT notification_id FROM notification_deletes 
                        WHERE user_id = ? AND user_type = ?
                    )
                    ORDER BY n.created_at DESC";

            // Parameter: [userId, userType, userType, userId, userType]
            $notifications = $this->db->query($sql, [$userId, $userType, $userType, $userId, $userType])->getResultArray();

            return $this->respond([
                'status'  => true,
                'message' => 'Notifikasi ' . $userType . ' berhasil ditarik.',
                'data'    => $notifications
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
            if (!$json->user_id || !$json->notification_id) return $this->fail('Data tidak lengkap.', 400);

            $this->db->table('notification_reads')->ignore(true)->insert([
                'notification_id' => $json->notification_id,
                'user_id'         => $json->user_id,
                'user_type'       => $userType,
                'read_at'         => date('Y-m-d H:i:s')
            ]);

            return $this->respond(['status' => true, 'message' => 'Tanda baca berhasil.']);
        } catch (\Exception $e) { return $this->failServerError($e->getMessage()); }
    }

    /**
     * 3. Tandai Semua Dibaca (Dinamis)
     */
    public function markAllAsRead($userType = null)
    {
        try {
            $json = $this->request->getJSON();
            if (!$json->user_id) return $this->fail('User ID dibutuhkan.', 400);

            $notifs = $this->db->table('notifications')->where('target_type', $userType)->get()->getResultArray();
            foreach ($notifs as $n) {
                $this->db->table('notification_reads')->ignore(true)->insert([
                    'notification_id' => $n['id'],
                    'user_id'         => $json->user_id,
                    'user_type'       => $userType,
                    'read_at'         => date('Y-m-d H:i:s')
                ]);
            }
            return $this->respond(['status' => true, 'message' => 'Semua sudah dibaca.']);
        } catch (\Exception $e) { return $this->failServerError($e->getMessage()); }
    }

    /**
     * 4. Hapus Notifikasi (Personal)
     */
    public function deleteNotification($userType = null, $notifId = null, $userId = null)
    {
        try {
            $this->db->table('notification_deletes')->ignore(true)->insert([
                'notification_id' => $notifId,
                'user_id'         => $userId,
                'user_type'       => $userType
            ]);
            return $this->respond(['status' => true, 'message' => 'Dihapus.']);
        } catch (\Exception $e) { return $this->failServerError($e->getMessage()); }
    }

    /**
     * 5. Hitung Unread (Dinamis untuk Badge)
     */
    public function unreadCount($userType = null, $userId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as unread FROM notifications n 
                    WHERE n.target_type = ? 
                    AND n.id NOT IN (SELECT notification_id FROM notification_reads WHERE user_id = ? AND user_type = ?)
                    AND n.id NOT IN (SELECT notification_id FROM notification_deletes WHERE user_id = ? AND user_type = ?)";
            
            $result = $this->db->query($sql, [$userType, $userId, $userType, $userId, $userType])->getRowArray();
            return $this->respond(['status' => true, 'unread' => (int)$result['unread']]);
        } catch (\Exception $e) { return $this->failServerError($e->getMessage()); }
    }
}