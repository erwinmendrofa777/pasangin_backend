<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Models\ProductsRatingModel;
use RuntimeException;

/**
 * ProductService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Produk.
 * Controller hanya bertanggung jawab menerima request dan mengembalikan response.
 * Operasi database murni tetap dikelola oleh ProductModel.
 */
class ProductService
{
    protected ProductModel $productModel;
    protected ProductsRatingModel $ratingModel;

    // Daftar status produk yang sah
    private const ALLOWED_STATUSES = ['aktif', 'tidak aktif', 'habis'];

    // Path direktori upload foto produk
    private const UPLOAD_PATH = 'uploads/products/';

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->ratingModel  = new ProductsRatingModel();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua produk beserta nama supplier-nya (LEFT JOIN).
     */
    public function getAllProducts(): array
    {
        return $this->productModel
            ->select('products.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->findAll();
    }

    /**
     * Ambil satu produk beserta nama supplier dan daftar rating-nya.
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @throws RuntimeException
     */
    public function findProductWithDetails(int $id): array
    {
        $product = $this->productModel
            ->select('products.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->find($id);

        if (!$product) {
            throw new RuntimeException('Produk tidak ditemukan.');
        }

        // Ambil rating produk via ProductsRatingModel
        $ratings = $this->ratingModel
            ->where('id_product', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $product['ratings'] = $ratings;

        return $product;
    }

    /**
     * Ambil satu produk berdasarkan ID.
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @throws RuntimeException
     */
    public function findProductOrFail(int $id): array
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw new RuntimeException('Produk tidak ditemukan.');
        }

        return $product;
    }

    // =========================================================================
    // UPDATE STATUS
    // =========================================================================

    /**
     * Ubah status produk (aktif / tidak aktif / habis).
     *
     * Logika bisnis yang ditangani:
     * - Validasi bahwa status adalah nilai yang sah
     *
     * @throws RuntimeException
     */
    public function updateStatus(int $id, string $status): void
    {
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw new RuntimeException('Status tidak valid: ' . $status);
        }

        // Pastikan produk ada sebelum update
        $this->findProductOrFail($id);

        if (!$this->productModel->update($id, ['status' => $status])) {
            throw new RuntimeException('Gagal mengubah status produk di database.');
        }
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    /**
     * Hapus produk beserta file foto-nya.
     *
     * Logika bisnis yang ditangani:
     * - Pastikan produk ada sebelum menghapus
     * - Hapus file fisik foto produk
     *
     * @throws RuntimeException
     */
    public function deleteProduct(int $id): void
    {
        $product = $this->findProductOrFail($id);

        // Hapus file foto fisik jika ada
        $this->deletePhotoFile($product['photo'] ?? null);

        $this->productModel->delete($id);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Hapus file foto produk dari filesystem secara aman.
     * Tidak melakukan apa-apa jika file tidak ada.
     */
    private function deletePhotoFile(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $filePath = FCPATH . self::UPLOAD_PATH . $filename;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}
