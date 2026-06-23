<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionRabMaterialsModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionRabMaterialsRepositoryInterface;

class ConstructionRabMaterialsRepository implements ConstructionRabMaterialsRepositoryInterface
{
    protected ConstructionRabMaterialsModel $model;

    public function __construct()
    {
        $this->model = new ConstructionRabMaterialsModel();
    }

    public function findByRabId(int $rabId): array
    {
        return $this->model
            ->select('rab_materials.*, products.name as material_name, products.price')
            ->join('products', 'products.id = rab_materials.product_id', 'left')
            ->where('rab_id', $rabId)
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

    public function deleteByRabId(int $rabId): bool
    {
        return (bool) $this->model->where('rab_id', $rabId)->delete();
    }
}
