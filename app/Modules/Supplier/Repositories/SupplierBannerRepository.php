<?php

namespace App\Modules\Supplier\Repositories;

use App\Modules\Supplier\Models\SupplierBannerModel;
use App\Modules\Supplier\Repositories\Contracts\SupplierBannerRepositoryInterface;

/**
 * SupplierBannerRepository
 */
class SupplierBannerRepository implements SupplierBannerRepositoryInterface
{
    protected SupplierBannerModel $model;

    public function __construct()
    {
        $this->model = new SupplierBannerModel();
    }

    public function findAllWithSupplier(): array
    {
        return $this->model
            ->select('supplier_banner.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = supplier_banner.id_supplier')
            ->orderBy('supplier_banner.id', 'DESC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findDetailWithSupplier(int $id): ?array
    {
        return $this->model
            ->select('supplier_banner.*, suppliers.name as supplier_name, suppliers.email as supplier_email, suppliers.phone as supplier_phone')
            ->join('suppliers', 'suppliers.id = supplier_banner.id_supplier')
            ->where('supplier_banner.id', $id)
            ->first() ?: null;
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
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
