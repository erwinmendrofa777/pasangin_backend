<?php

namespace App\Services;

class NotificationService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['notification', 'url']);
    }

    /**
     * Ambil riwayat notifikasi beserta statistiknya.
     */
    public function getHistoryWithStats(): array
    {
        $allNotif = $this->db->table('notifications')
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        return [
            'notifications' => $allNotif,
            'stats' => [
                'total'    => count($allNotif),
                'client'   => $this->db->table('notifications')->where('target_type', 'client')->countAllResults(),
                'tukang'   => $this->db->table('notifications')->where('target_type', 'tukang')->countAllResults(),
                'supplier' => $this->db->table('notifications')->where('target_type', 'supplier')->countAllResults(),
            ]
        ];
    }

    /**
     * Proses simpan ke DB dan kirim ke FCM berdasarkan tipe target.
     */
    public function sendBulk(string $target, string $title, string $message): void
    {
        // 1. Simpan ke database history
        $this->db->table('notifications')->insert([
            'target_type' => $target,
            'title'       => $title,
            'message'     => $message,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        // 2. Tentukan tabel target
        $table = ($target == 'client') ? 'users' : (($target == 'tukang') ? 'tukang' : 'suppliers');

        // 3. Ambil user yang memiliki token
        $users = $this->db->table($table)
            ->select('id, fcm_token')
            ->where('fcm_token IS NOT NULL')
            ->get()->getResultArray();

        // 4. Kirim FCM
        foreach ($users as $user) {
            if ($target == 'client') {
                $this->notifyClient($user['id'], $title, $message);
            } elseif ($target == 'tukang') {
                $this->notifyTukang($user['id'], $title, $message);
            } elseif ($target == 'supplier') {
                $this->notifySupplier($user['id'], $title, $message);
            }
        }
    }

    /**
     * Kirim ke Tukang
     */
    public function notifyTukang($tukangId, $title, $body, $extra = [])
    {
        $user = $this->db->table('tukang')->where('id', $tukangId)->get()->getRowArray();
        if ($user && !empty($user['fcm_token'])) {
            return sendFCMNotification($user['fcm_token'], $title, $body, $extra);
        }
        return false;
    }

    /**
     * Kirim ke Klien (Users)
     */
    public function notifyClient($userId, $title, $body, $extra = [])
    {
        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if ($user && !empty($user['fcm_token'])) {
            return sendFCMNotification($user['fcm_token'], $title, $body, $extra);
        }
        return false;
    }

    /**
     * Kirim ke Supplier
     */
    public function notifySupplier($supplierId, $title, $body, $extra = [])
    {
        $user = $this->db->table('suppliers')->where('id', $supplierId)->get()->getRowArray();
        if ($user && !empty($user['fcm_token'])) {
            return sendFCMNotification($user['fcm_token'], $title, $body, $extra);
        }
        return false;
    }
}