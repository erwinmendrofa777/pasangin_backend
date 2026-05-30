<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationTargetModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationTargetRepositoryInterface;

class RenovationTargetRepository implements RenovationTargetRepositoryInterface
{
    protected RenovationTargetModel $model;

    public function __construct()
    {
        $this->model = new RenovationTargetModel();
    }

    public function findByRenovationId(int $id): array
    {
        return $this->model->where('renovation_id', $id)->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findByRenovationAndRab(int $renovationId, int $rabId): ?array
    {
        return $this->model
            ->where('renovation_id', $renovationId)
            ->where('id_renovation_rabs', $rabId)
            ->first() ?: null;
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
}
