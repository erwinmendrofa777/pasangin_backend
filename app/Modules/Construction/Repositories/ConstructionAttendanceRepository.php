<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionAttendanceModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionAttendanceRepositoryInterface;

class ConstructionAttendanceRepository implements ConstructionAttendanceRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new ConstructionAttendanceModel();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function findByConstructionId(int $constructionId): array
    {
        return $this->model->where('id_construction', $constructionId)
                           ->orderBy('waktu', 'DESC')
                           ->findAll();
    }

    public function insert(array $data): int
    {
        return $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}
