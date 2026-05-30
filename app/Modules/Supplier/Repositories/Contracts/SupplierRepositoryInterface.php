<?php

namespace App\Modules\Supplier\Repositories\Contracts;

/**
 * SupplierRepositoryInterface
 *
 * Mendefinisikan kontrak untuk Supplier Repository.
 */
interface SupplierRepositoryInterface
{
    /**
     * Ambil semua supplier, diurutkan berdasarkan nama (ASC).
     */
    public function findAllSuppliers(): array;
    public function countAll(): int;

    /**
     * Cari supplier berdasarkan ID.
     */
    public function findById(int $id): ?array;
    public function findWithFcmToken(): array;

    /**
     * Simpan data supplier (insert atau update).
     */
    public function save(array $data): bool;

    /**
     * Masukkan supplier baru.
     */
    public function insert(array $data): bool;

    /**
     * Update data supplier berdasarkan ID.
     */
    public function update(int $id, array $data): bool;

    /**
     * Hapus supplier berdasarkan ID.
     */
    public function delete(int $id): bool;

    public function searchForDropdown(string $term): array;

    /**
     * Ambil error dari model.
     */
    public function errors(): array;
}
