<?php

namespace App\Modules\Supplier\Services;

use App\Modules\Supplier\Repositories\PromoRepository;
use App\Modules\Supplier\Repositories\Contracts\PromoRepositoryInterface;
use RuntimeException;

/**
 * PromoService
 *
 * Mengelola logika bisnis promo dan diskon.
 * Sekarang menggunakan Repository Pattern untuk akses data.
 */
class PromoService
{
    protected PromoRepositoryInterface $promoRepository;

    public function __construct()
    {
        $this->promoRepository = new PromoRepository();
    }

    /**
     * Ambil semua promo beserta statistik aktif/tidak aktif.
     */
    public function getAllWithStats(): array
    {
        $allPromos = $this->promoRepository->findAllWithSupplier();

        return [
            'promos' => $allPromos,
            'stats'  => [
                'total'    => count($allPromos),
                'active'   => $this->promoRepository->countByStatus('active'),
                'inactive' => $this->promoRepository->countInactive(),
            ],
        ];
    }

    /**
     * Ambil detail satu promo.
     * @throws RuntimeException
     */
    public function findDetailOrFail(int $id): array
    {
        $promo = $this->promoRepository->findByIdWithSupplier($id);

        if (!$promo) {
            throw new RuntimeException('Promo tidak ditemukan.');
        }

        return $promo;
    }

    /**
     * Update status promo.
     */
    public function updateStatus(int $id, string $status): string
    {
        $this->promoRepository->update($id, ['status' => $status]);
        return 'Status promo berhasil diperbarui menjadi ' . ucfirst($status);
    }

    /**
     * Hapus promo.
     */
    public function delete(int $id): void
    {
        $this->promoRepository->delete($id);
    }
}
