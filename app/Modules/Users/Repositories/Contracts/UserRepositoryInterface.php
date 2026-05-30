<?php

namespace App\Modules\Users\Repositories\Contracts;

/**
 * UserRepositoryInterface
 *
 * Contract yang mendefinisikan "kemampuan" dari sebuah User Repository.
 * Dengan interface ini, kita bisa mengganti implementasi (MySQL → MongoDB → API)
 * tanpa mengubah satu baris pun di UserService atau Controller.
 */
interface UserRepositoryInterface
{
    /**
     * Ambil semua user dengan role 'client', diurutkan dari terbaru.
     */
    public function findAllClients(): array;
    public function countClients(): int;

    /**
     * Ambil satu user berdasarkan ID.
     * Mengembalikan null jika tidak ditemukan.
     */
    public function findById(int $id): ?array;
    public function findWithFcmToken(): array;

    /**
     * Simpan data user (insert jika baru, update jika ada 'id').
     */
    public function save(array $data): bool;

    /**
     * Hapus user berdasarkan ID.
     */
    public function delete(int $id): bool;

    /**
     * Cari user berdasarkan nama atau nomor HP untuk keperluan dropdown Select2.
     */
    public function searchForDropdown(string $term): array;

    /**
     * Ambil semua error validasi dari operasi terakhir.
     */
    public function errors(): array;
}
