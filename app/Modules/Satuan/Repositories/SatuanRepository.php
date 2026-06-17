<?php

namespace App\Modules\Satuan\Repositories;

use App\Modules\Satuan\Models\SatuanModel;
use App\Modules\Satuan\Repositories\Contracts\SatuanRepositoryInterface;

class SatuanRepository implements SatuanRepositoryInterface
{
    protected SatuanModel $model;

    public function __construct()
    {
        $this->model = new SatuanModel();
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
