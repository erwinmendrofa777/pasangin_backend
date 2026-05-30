<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationAttendanceModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationAttendanceRepositoryInterface;

class RenovationAttendanceRepository implements RenovationAttendanceRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new RenovationAttendanceModel();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function findByRenovationId(int $renovationId): array
    {
        return $this->model->where('id_renovation', $renovationId)
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
