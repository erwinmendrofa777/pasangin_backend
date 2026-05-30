<?php

namespace App\Modules\Products\Repositories\Contracts;

/**
 * ProductRatingRepositoryInterface
 *
 * Mendefinisikan kontrak untuk Product Rating Repository.
 */
interface ProductRatingRepositoryInterface
{
    /**
     * Ambil semua rating untuk produk tertentu, diurutkan dari terbaru.
     */
    public function findByProductId(int $productId): array;
}
