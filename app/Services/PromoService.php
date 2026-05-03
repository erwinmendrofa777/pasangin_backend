<?php

namespace App\Services;

use App\Models\PromoModel;
use RuntimeException;

class PromoService
{
    protected PromoModel $promoModel;

    public function __construct()
    {
        $this->promoModel = new PromoModel();
    }

    public function getAllWithStats(): array
    {
        $allPromos = $this->promoModel
            ->select('promos.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = promos.supplier_id', 'left')
            ->orderBy('id', 'DESC')
            ->findAll();

        return [
            'promos' => $allPromos,
            'stats'  => [
                'total'    => count($allPromos),
                'active'   => $this->promoModel->where('status', 'active')->countAllResults(),
                'inactive' => $this->promoModel->where('status !=', 'active')->countAllResults(),
            ],
        ];
    }

    public function findDetailOrFail(int $id): array
    {
        $promo = $this->promoModel
            ->select('promos.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = promos.supplier_id', 'left')
            ->find($id);

        if (!$promo) {
            throw new RuntimeException('Promo tidak ditemukan.');
        }

        return $promo;
    }

    public function updateStatus(int $id, string $status): string
    {
        $this->promoModel->update($id, ['status' => $status]);
        return 'Status promo berhasil diperbarui menjadi ' . ucfirst($status);
    }

    public function delete(int $id): void
    {
        $this->promoModel->delete($id);
    }
}
