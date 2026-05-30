<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionAddendumMaterialModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionAddendumMaterialRepositoryInterface;

class ConstructionAddendumMaterialRepository implements ConstructionAddendumMaterialRepositoryInterface
{
    protected ConstructionAddendumMaterialModel $model;

    public function __construct()
    {
        $this->model = new ConstructionAddendumMaterialModel();
    }

    public function findByAddendumId(int $addendumId): array
    {
        return $this->model
            ->select('construction_addendum_materials.*, products.name as material_name, products.price')
            ->join('products', 'products.id = construction_addendum_materials.product_id', 'left')
            ->where('addendum_id', $addendumId)
            ->findAll();
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function deleteByAddendumId(int $addendumId): bool
    {
        return (bool) $this->model->where('addendum_id', $addendumId)->delete();
    }
}
