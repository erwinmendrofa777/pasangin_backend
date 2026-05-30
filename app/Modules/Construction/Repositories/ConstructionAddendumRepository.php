<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionAddendumModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionAddendumRepositoryInterface;

class ConstructionAddendumRepository implements ConstructionAddendumRepositoryInterface
{
    protected ConstructionAddendumModel $model;

    public function __construct()
    {
        $this->model = new ConstructionAddendumModel();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findByConstructionId(int $constructionId): array
    {
        return $this->model->where('construction_id', $constructionId)
            ->orderBy('roman_number', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function insert(array $data): int
    {
        $this->model->insert($data);
        return (int) $this->model->getInsertID();
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function lockByConstructionId(int $constructionId): bool
    {
        return (bool) $this->model->where('construction_id', $constructionId)->update(null, ['is_locked' => 1]);
    }

    public function unlockByConstructionId(int $constructionId): bool
    {
        return (bool) $this->model->where('construction_id', $constructionId)->update(null, ['is_locked' => 0]);
    }
}
