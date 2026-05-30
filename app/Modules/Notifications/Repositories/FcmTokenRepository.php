<?php

namespace App\Modules\Notifications\Repositories;

use App\Modules\Notifications\Models\FcmTokenModel;
use App\Modules\Notifications\Repositories\Contracts\FcmTokenRepositoryInterface;

class FcmTokenRepository implements FcmTokenRepositoryInterface
{
    protected FcmTokenModel $model;

    public function __construct()
    {
        $this->model = new FcmTokenModel();
    }

    /**
     * Simpan atau update token untuk user tertentu.
     * Menggunakan fcm_token sebagai kunci unik.
     */
    public function upsertToken(int $userId, string $userType, string $token): bool
    {
        if (empty($token)) return false;

        $data = [
            'user_id'    => $userId,
            'user_type'  => $userType,
            'fcm_token'  => $token,
        ];

        // Cek apakah token sudah ada
        $existing = $this->model->where('fcm_token', $token)->first();

        if ($existing) {
            // Update jika token sudah ada
            return (bool) $this->model->update($existing['id'], $data);
        } else {
            // Insert baru
            return (bool) $this->model->insert($data);
        }
    }

    /**
     * Ambil semua token milik satu user berdasarkan ID dan tipe.
     * Hanya mengambil token yang notifikasinya aktif (is_notification_enabled = 1).
     */
    public function getTokens(int $userId, string $userType): array
    {
        return $this->model
            ->select('fcm_token')
            ->where('user_id', $userId)
            ->where('user_type', $userType)
            ->where('is_notification_enabled', 1)
            ->findAll();
    }

    /**
     * Ambil semua token berdasarkan tipe user (untuk broadcast).
     * Hanya mengambil token yang notifikasinya aktif (is_notification_enabled = 1).
     */
    public function getTokensByType(string $userType): array
    {
        return $this->model
            ->select('fcm_token')
            ->where('user_type', $userType)
            ->where('is_notification_enabled', 1)
            ->findAll();
    }

    /**
     * Ambil token berdasarkan role (Admin menggunakan user_admin, Client menggunakan users).
     * Hanya mengambil token yang notifikasinya aktif (is_notification_enabled = 1).
     */
    public function getTokensByRole(string $role, string $userType = 'admin'): array
    {
        $table = ($userType === 'admin') ? 'user_admin' : 'users';

        return $this->model->db->table('user_fcm_tokens')
            ->select('fcm_token')
            ->join($table, "$table.id = user_fcm_tokens.user_id")
            ->where('user_fcm_tokens.user_type', $userType)
            ->where('user_fcm_tokens.is_notification_enabled', 1)
            ->where("$table.role", $role)
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil token admin yang memiliki permission tertentu.
     * Secara otomatis menyertakan 'super_admin' yang dianggap memiliki semua akses.
     * Hanya mengambil token yang notifikasinya aktif (is_notification_enabled = 1).
     */
    public function getTokensByPermission(string $permission): array
    {
        return $this->model->db->table('user_fcm_tokens')
            ->select('fcm_token')
            ->join('user_admin', 'user_admin.id = user_fcm_tokens.user_id')
            ->join('roles', 'roles.role_name = user_admin.role')
            ->where('user_fcm_tokens.user_type', 'admin')
            ->where('user_fcm_tokens.is_notification_enabled', 1)
            ->groupStart()
                ->like('roles.permissions', '"' . $permission . '"')
                ->orWhere('roles.role_name', 'super_admin')
                ->orWhere('roles.role_name', 'Super Admin')
            ->groupEnd()
            ->get()
            ->getResultArray();
    }

    /**
     * Hapus token tertentu (saat logout).
     */
    public function deleteToken(string $token): bool
    {
        return (bool) $this->model->where('fcm_token', $token)->delete();
    }

    /**
     * Aktifkan atau nonaktifkan notifikasi untuk token tertentu.
     * $userType digunakan sebagai filter keamanan agar hanya bisa
     * mengubah token yang memang milik tipe user tersebut.
     */
    public function toggleNotification(string $token, string $userType, bool $status): bool
    {
        $existing = $this->model
            ->where('fcm_token', $token)
            ->where('user_type', $userType)
            ->first();

        if (!$existing) {
            return false;
        }

        return (bool) $this->model->update($existing['id'], [
            'is_notification_enabled' => $status ? 1 : 0,
        ]);
    }
}
