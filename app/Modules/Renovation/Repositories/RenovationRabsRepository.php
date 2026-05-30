<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationRabsModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationRabsRepositoryInterface;

class RenovationRabsRepository implements RenovationRabsRepositoryInterface
{
    protected RenovationRabsModel $model;

    public function __construct()
    {
        $this->model = new RenovationRabsModel();
    }

    public function findByRenovationId(int $id): array
    {
        return $this->model
            ->where('renovation_id', $id)
            ->orderBy('roman_number', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findGroupedSummaryByRenovationId(int $id): array
    {
        return $this->model
            ->select('group_name, SUM(total_price) as total_price')
            ->where('renovation_id', $id)
            ->groupBy('roman_number, group_name')
            ->orderBy('roman_number', 'ASC')
            ->findAll();
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

    public function lockByRenovationId(int $renovationId): bool
    {
        return (bool) $this->model
            ->where('renovation_id', $renovationId)
            ->set(['is_locked' => 1])
            ->update();
    }

    public function unlockByRenovationId(int $renovationId): bool
    {
        return (bool) $this->model
            ->where('renovation_id', $renovationId)
            ->set(['is_locked' => 0])
            ->update();
    }
}
