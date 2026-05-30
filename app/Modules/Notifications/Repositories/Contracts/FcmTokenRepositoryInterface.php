<?php

namespace App\Modules\Notifications\Repositories\Contracts;

interface FcmTokenRepositoryInterface
{
    /**
     * Simpan atau update token untuk user tertentu.
     * Menggunakan fcm_token sebagai kunci unik.
     */
    public function upsertToken(int $userId, string $userType, string $token): bool;

    /**
     * Ambil token admin berdasarkan permission tertentu.
     */
    public function getTokensByPermission(string $permission): array;

    /**
     * Ambil semua token milik satu user berdasarkan ID dan tipe.
     */
    public function getTokens(int $userId, string $userType): array;

    /**
     * Ambil semua token berdasarkan tipe user (untuk broadcast).
     */
    public function getTokensByType(string $userType): array;

    /**
     * Ambil token berdasarkan role (Admin menggunakan user_admin, Client menggunakan users).
     */
    public function getTokensByRole(string $role, string $userType = 'admin'): array;

    /**
     * Hapus token tertentu (saat logout).
     */
    public function deleteToken(string $token): bool;

    /**
     * Aktifkan atau nonaktifkan notifikasi untuk token tertentu.
     */
    public function toggleNotification(string $token, string $userType, bool $status): bool;
}
