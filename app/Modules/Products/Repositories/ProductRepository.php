<?php

namespace App\Modules\Products\Repositories;

use App\Modules\Products\Models\ProductModel;
use App\Modules\Products\Repositories\Contracts\ProductRepositoryInterface;

/**
 * ProductRepository
 *
 * Implementasi konkrit dari ProductRepositoryInterface menggunakan ProductModel.
 * Semua query SQL yang berkaitan dengan tabel 'products' dikumpulkan di sini.
 *
 * Perhatikan: query JOIN ke 'suppliers' ada di repository ini, bukan di Service.
 * Service cukup memanggil method yang sudah jadi.
 */
class ProductRepository implements ProductRepositoryInterface
{
    protected ProductModel $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }

    // =========================================================================
    // READ OPERATIONS
    // =========================================================================

    /**
     * Ambil semua produk beserta nama supplier (LEFT JOIN).
     * Dipakai untuk halaman list produk di admin.
     */
    public function findAllWithSupplier(): array
    {
        return $this->model
            ->select('products.*, suppliers.name as supplier_name, suppliers.city as supplier_city')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->findAll();
    }

    public function countAll(): int
    {
        return $this->model->countAllResults();
    }

    /**
     * Ambil satu produk beserta nama supplier berdasarkan ID.
     * Dipakai untuk halaman detail produk.
     */
    public function findByIdWithSupplier(int $id): ?array
    {
        return $this->model
            ->select('products.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->find($id) ?: null;
    }

    /**
     * Ambil satu produk berdasarkan ID (tanpa join).
     * Dipakai ketika hanya butuh data produk saja (misal: cek keberadaan produk).
     */
    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    // =========================================================================
    // WRITE OPERATIONS
    // =========================================================================

    /**
     * Tambahkan produk baru ke database.
     */
    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    /**
     * Update field tertentu pada produk berdasarkan ID.
     */
    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    /**
     * Hapus produk berdasarkan ID.
     */
    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    // =========================================================================
    // UTILITY
    // =========================================================================

    /**
     * Ambil error validasi dari model.
     */
    public function errors(): array
    {
        return $this->model->errors();
    }
}
