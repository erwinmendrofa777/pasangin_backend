<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionTargetsModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionTargetsRepositoryInterface;

class ConstructionTargetsRepository implements ConstructionTargetsRepositoryInterface
{
    protected ConstructionTargetsModel $model;

    public function __construct()
    {
        $this->model = new ConstructionTargetsModel();
    }

    public function findByConstructionId(int $constructionId): array
    {
        return $this->model->where('construction_id', $constructionId)->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function findByConstructionAndRab(int $constructionId, int $rabId): ?array
    {
        return $this->model
            ->where('construction_id', $constructionId)
            ->where('id_construction_rabs', $rabId)
            ->first() ?: null;
    }

    public function findByConstructionAndAddendum(int $constructionId, int $addendumId): ?array
    {
        return $this->model
            ->where('construction_id', $constructionId)
            ->where('id_construction_addendum', $addendumId)
            ->first() ?: null;
    }
}
