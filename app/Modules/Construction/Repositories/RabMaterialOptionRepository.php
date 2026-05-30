<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\RabMaterialOptionModel;
use App\Modules\Construction\Repositories\Contracts\RabMaterialOptionRepositoryInterface;

class RabMaterialOptionRepository implements RabMaterialOptionRepositoryInterface
{
    protected RabMaterialOptionModel $model;

    public function __construct()
    {
        $this->model = new RabMaterialOptionModel();
    }

    public function findAll(): array
    {
        return $this->model->findAll();
    }

    public function findByRabId(int $rabId): array
    {
        return $this->model
            ->select('rab_material_options.*, products.name as material_name, products.price')
            ->join('products', 'products.id = rab_material_options.product_id')
            ->where('rab_id', $rabId)
            ->findAll();
    }
}
