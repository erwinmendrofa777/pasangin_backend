<?php

namespace App\Modules\Supplier\Repositories;

use App\Modules\Supplier\Models\PromoModel;
use App\Modules\Supplier\Repositories\Contracts\PromoRepositoryInterface;

/**
 * PromoRepository
 */
class PromoRepository implements PromoRepositoryInterface
{
    protected PromoModel $model;

    public function __construct()
    {
        $this->model = new PromoModel();
    }

    public function findAllWithSupplier(): array
    {
        return $this->model
            ->select('promos.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = promos.supplier_id', 'left')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function findByIdWithSupplier(int $id): ?array
    {
        return $this->model
            ->select('promos.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = promos.supplier_id', 'left')
            ->find($id) ?: null;
    }

    public function countByStatus(string $status): int
    {
        return $this->model->where('status', $status)->countAllResults();
    }

    public function countInactive(): int
    {
        return $this->model->where('status !=', 'active')->countAllResults();
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }
}
