<?php

namespace App\Modules\Vouchers\Repositories;

use App\Modules\Vouchers\Models\VoucherModel;
use App\Modules\Vouchers\Repositories\Contracts\VoucherRepositoryInterface;

/**
 * VoucherRepository
 */
class VoucherRepository implements VoucherRepositoryInterface
{
    protected VoucherModel $model;

    public function __construct()
    {
        $this->model = new VoucherModel();
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
