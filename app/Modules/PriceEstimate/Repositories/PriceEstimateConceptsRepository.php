<?php

namespace App\Modules\PriceEstimate\Repositories;

use App\Modules\PriceEstimate\Models\PriceEstimateConceptsModel;
use App\Modules\PriceEstimate\Repositories\Contracts\PriceEstimateConceptsRepositoryInterface;

/**
 * PriceEstimateConceptsRepository
 */
class PriceEstimateConceptsRepository implements PriceEstimateConceptsRepositoryInterface
{
    protected PriceEstimateConceptsModel $model;

    public function __construct()
    {
        $this->model = new PriceEstimateConceptsModel();
    }

    public function findAllOrderedByCreatedAtAsc(): array
    {
        return $this->model->orderBy('created_at', 'ASC')->findAll();
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

    public function errors(): array
    {
        return $this->model->errors();
    }
}
