<?php

namespace App\Modules\Design\Repositories;

use App\Modules\Design\Models\DesignTargetsModel;
use App\Modules\Design\Repositories\Contracts\DesignTargetsRepositoryInterface;

/**
 * DesignTargetsRepository
 */
class DesignTargetsRepository implements DesignTargetsRepositoryInterface
{
    protected DesignTargetsModel $model;

    public function __construct()
    {
        $this->model = new DesignTargetsModel();
    }

    public function findByDesignRequestId(int $id): array
    {
        return $this->model
            ->select('design_targets.*, ua.full_name as admin_name')
            ->join('user_admin ua', 'ua.id = design_targets.user_admin_id', 'left')
            ->where('design_request_id', $id)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findByIdAndDesignRequestId(int $id, int $designRequestId): ?array
    {
        return $this->model
            ->where('id', $id)
            ->where('design_request_id', $designRequestId)
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

    public function deleteByDesignRequestId(int $id): bool
    {
        return (bool) $this->model->where('design_request_id', $id)->delete();
    }
}
