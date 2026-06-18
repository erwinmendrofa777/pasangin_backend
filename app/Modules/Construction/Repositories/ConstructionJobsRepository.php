<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionJobsModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionJobsRepositoryInterface;

class ConstructionJobsRepository implements ConstructionJobsRepositoryInterface
{
    protected ConstructionJobsModel $model;

    public function __construct()
    {
        $this->model = new ConstructionJobsModel();
    }

    public function findByConstructionId(int $constructionId): ?array
    {
        return $this->model->where('construction_id', $constructionId)->first() ?: null;
    }

    public function findAllByConstructionId(int $constructionId): array
    {
        return $this->model->where('construction_id', $constructionId)->findAll();
    }

    public function findByTargetId(int $targetId): ?array
    {
        return $this->model->where('construction_target_id', $targetId)->first() ?: null;
    }

    public function findGlobalByConstructionId(int $constructionId): ?array
    {
        return $this->model
            ->where('construction_id', $constructionId)
            ->where('construction_target_id', null)
            ->first() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }
}
