<?php

namespace App\Modules\Admin\Repositories\Contracts;

/**
 * RoleRepositoryInterface
 */
interface RoleRepositoryInterface
{
    /**
     * Ambil semua roles.
     */
    public function findAll(): array;

    /**
     * Ambil semua roles diurutkan dari terbaru.
     */
    public function findAllOrderedByIdDesc(): array;

    /**
     * Cari role berdasarkan nama role.
     */
    public function findByName(string $roleName): ?array;

    /**
     * Cari role berdasarkan ID.
     */
    public function findById(int $id): ?array;

    /**
     * Tambah role baru.
     */
    public function insert(array $data): bool;

    /**
     * Update role.
     */
    public function update(int $id, array $data): bool;

    /**
     * Hapus role.
     */
    public function delete(int $id): bool;
}
