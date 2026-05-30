<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationRabMaterialsModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationRabMaterialsRepositoryInterface;

class RenovationRabMaterialsRepository implements RenovationRabMaterialsRepositoryInterface
{
    protected RenovationRabMaterialsModel $model;

    public function __construct()
    {
        $this->model = new RenovationRabMaterialsModel();
    }

    public function findByRabId(int $rabId): array
    {
        return $this->model
            ->select('renovation_rab_materials.*, products.name as material_name, products.price')
            ->join('products', 'products.id = renovation_rab_materials.product_id')
            ->where('rab_id', $rabId)->findAll();
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
