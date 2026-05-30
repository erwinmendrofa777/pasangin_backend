<?php

namespace App\Modules\Products\Repositories;

use App\Modules\Products\Models\ProductsRatingModel;
use App\Modules\Products\Repositories\Contracts\ProductRatingRepositoryInterface;

/**
 * ProductRatingRepository
 *
 * Implementasi konkrit dari ProductRatingRepositoryInterface menggunakan ProductsRatingModel.
 * Mengelola semua query yang berkaitan dengan tabel 'products_rating'.
 */
class ProductRatingRepository implements ProductRatingRepositoryInterface
{
    protected ProductsRatingModel $model;

    public function __construct()
    {
        $this->model = new ProductsRatingModel();
    }

    /**
     * Ambil semua rating untuk produk tertentu, diurutkan dari terbaru.
     * Dipakai untuk menampilkan daftar ulasan di halaman detail produk.
     */
    public function findByProductId(int $productId): array
    {
        return $this->model
            ->where('id_product', $productId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
