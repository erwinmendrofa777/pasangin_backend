<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationProgressModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationProgressRepositoryInterface;

class RenovationProgressRepository implements RenovationProgressRepositoryInterface
{
    protected RenovationProgressModel $model;

    public function __construct()
    {
        $this->model = new RenovationProgressModel();
    }

    public function findDetailsByRenovationId(int $id): array
    {
        return $this->model
            ->select('renovation_progress.*, rr.group_name, rr.sub_group_name, rr.activity_name')
            ->join('renovation_targets rt', 'rt.id = renovation_progress.id_renovation_targets', 'left')
            ->join('renovation_rabs rr', 'rr.id = rt.id_renovation_rabs', 'left')
            ->where('renovation_progress.renovation_id', $id)
            ->orderBy('renovation_progress.created_at', 'DESC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
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

    public function sumBobotByTargetId(int $targetId): float
    {
        $result = $this->model
            ->selectSum('bobot')
            ->where('id_renovation_targets', $targetId)
            ->where('status', 'APPROVED')
            ->first();

        return (float) ($result['bobot'] ?? 0);
    }

    public function sumBobotByRenovationId(int $renovationId): float
    {
        $result = $this->model
            ->selectSum('bobot')
            ->where('renovation_id', $renovationId)
            ->where('status', 'APPROVED')
            ->first();

        return (float) ($result['bobot'] ?? 0);
    }
}
