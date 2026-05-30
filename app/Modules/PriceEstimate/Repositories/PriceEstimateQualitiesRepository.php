<?php

namespace App\Modules\PriceEstimate\Repositories;

use App\Modules\PriceEstimate\Models\PriceEstimateQualitiesModel;
use App\Modules\PriceEstimate\Repositories\Contracts\PriceEstimateQualitiesRepositoryInterface;

/**
 * PriceEstimateQualitiesRepository
 */
class PriceEstimateQualitiesRepository implements PriceEstimateQualitiesRepositoryInterface
{
    protected PriceEstimateQualitiesModel $model;

    public function __construct()
    {
        $this->model = new PriceEstimateQualitiesModel();
    }

    public function findByConceptId(int $conceptId): array
    {
        return $this->model
            ->where('concept_id', $conceptId)
            ->orderBy('min_price', 'ASC')
            ->findAll();
    }

    public function countAll(): int
    {
        return $this->model->countAllResults();
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

    public function deleteByConceptId(int $conceptId): bool
    {
        return (bool) $this->model->where('concept_id', $conceptId)->delete();
    }

    public function errors(): array
    {
        return $this->model->errors();
    }
}
