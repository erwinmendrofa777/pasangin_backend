<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Repositories\ProductRepository;
use App\Modules\Products\Repositories\ProductRatingRepository;
use App\Modules\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Modules\Products\Repositories\Contracts\ProductRatingRepositoryInterface;
use RuntimeException;

/**
 * ProductService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Produk.
 * Service ini menggunakan dua Repository:
 *   - ProductRepository     → query tabel 'products'
 *   - ProductRatingRepository → query tabel 'products_rating'
 *
 * LAPISAN ARSITEKTUR:
 *   Controller → ProductService (logika bisnis) → ProductRepository (query DB) → ProductModel
 *                                               → ProductRatingRepository     → ProductsRatingModel
 */
class ProductService
{
    protected ProductRepositoryInterface $productRepository;
    protected ProductRatingRepositoryInterface $ratingRepository;

    // Daftar status produk yang sah
    private const ALLOWED_STATUSES = ['aktif', 'tidak aktif', 'habis'];

    // Path direktori upload foto produk
    private const UPLOAD_PATH = 'uploads/products/';

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->ratingRepository  = new ProductRatingRepository();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua produk beserta nama supplier-nya (LEFT JOIN).
     */
    public function getAllProducts(): array
    {
        return $this->productRepository->findAllWithSupplier();
    }

    /**
     * Ambil satu produk beserta nama supplier dan daftar rating-nya.
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @throws RuntimeException
     */
    public function findProductWithDetails(int $id): array
    {
        $product = $this->productRepository->findByIdWithSupplier($id);

        if (!$product) {
            throw new RuntimeException('Produk tidak ditemukan.');
        }

        // Ambil rating produk via ProductRatingRepository
        $product['ratings'] = $this->ratingRepository->findByProductId($id);

        return $product;
    }

    /**
     * Ambil satu produk berdasarkan ID (tanpa join).
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @throws RuntimeException
     */
    public function findProductOrFail(int $id): array
    {
        $product = $this->productRepository->findById($id);

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

        if (!$this->productRepository->update($id, ['status' => $status])) {
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

        $this->productRepository->delete($id);
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
