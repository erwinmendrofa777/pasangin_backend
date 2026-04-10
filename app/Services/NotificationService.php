<?php

namespace App\Services;

class NotificationService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();helper('notification'); // Panggil helper yang kita buat tadi
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