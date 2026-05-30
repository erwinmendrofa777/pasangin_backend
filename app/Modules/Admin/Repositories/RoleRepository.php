<?php

namespace App\Modules\Admin\Repositories;

use App\Modules\Admin\Models\RoleModel;
use App\Modules\Admin\Repositories\Contracts\RoleRepositoryInterface;

/**
 * RoleRepository
 */
class RoleRepository implements RoleRepositoryInterface
{
    protected RoleModel $model;

    public function __construct()
    {
        $this->model = new RoleModel();
    }

    /**
     * Ambil semua roles.
     */
    public function findAll(): array
    {
        return $this->model->findAll();
    }

    public function findAllOrderedByIdDesc(): array
    {
        return $this->model->orderBy('id', 'DESC')->findAll();
    }

    public function findByName(string $roleName): ?array
    {
        return $this->model->where('role_name', $roleName)->first() ?: null;
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
