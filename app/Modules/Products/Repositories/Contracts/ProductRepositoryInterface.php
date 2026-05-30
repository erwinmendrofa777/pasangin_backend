<?php

namespace App\Modules\Products\Repositories\Contracts;

/**
 * ProductRepositoryInterface
 *
 * Mendefinisikan kontrak untuk Product Repository.
 */
interface ProductRepositoryInterface
{
    /**
     * Ambil semua produk beserta nama supplier (LEFT JOIN ke tabel suppliers).
     */
    public function findAllWithSupplier(): array;
    public function countAll(): int;

    /**
     * Ambil satu produk beserta nama supplier berdasarkan ID.
     */
    public function findByIdWithSupplier(int $id): ?array;

    /**
     * Ambil satu produk berdasarkan ID (tanpa join).
     */
    public function findById(int $id): ?array;

    /**
     * Tambahkan produk baru.
     */
    public function insert(array $data): bool;

    /**
     * Update field tertentu pada produk berdasarkan ID.
     */
    public function update(int $id, array $data): bool;

    /**
     * Hapus produk berdasarkan ID.
     */
    public function delete(int $id): bool;

    /**
     * Ambil error dari model.
     */
    public function errors(): array;
}
