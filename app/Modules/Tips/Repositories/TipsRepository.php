<?php

namespace App\Modules\Tips\Repositories;

use App\Modules\Tips\Models\TipsModel;
use App\Modules\Tips\Repositories\Contracts\TipsRepositoryInterface;

/**
 * TipsRepository
 */
class TipsRepository implements TipsRepositoryInterface
{
    protected TipsModel $model;

    public function __construct()
    {
        $this->model = new TipsModel();
    }

    public function findAllOrderedByIdDesc(): array
    {
        return $this->model->orderBy('id', 'DESC')->findAll();
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
}
