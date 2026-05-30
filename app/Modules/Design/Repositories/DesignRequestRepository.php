<?php

namespace App\Modules\Design\Repositories;

use App\Modules\Design\Models\DesignRequestModel;
use App\Modules\Design\Repositories\Contracts\DesignRequestRepositoryInterface;

/**
 * DesignRequestRepository
 */
class DesignRequestRepository implements DesignRequestRepositoryInterface
{
    protected DesignRequestModel $model;

    public function __construct()
    {
        $this->model = new DesignRequestModel();
    }

    public function findAllOrderedByCreatedAtDesc(): array
    {
        return $this->model->orderBy('created_at', 'DESC')->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
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
