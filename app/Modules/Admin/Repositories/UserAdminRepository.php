<?php

namespace App\Modules\Admin\Repositories;

use App\Modules\Admin\Models\UserAdminModel;
use App\Modules\Admin\Repositories\Contracts\UserAdminRepositoryInterface;

/**
 * UserAdminRepository
 */
class UserAdminRepository implements UserAdminRepositoryInterface
{
    protected UserAdminModel $model;

    public function __construct()
    {
        $this->model = new UserAdminModel();
    }

    public function findAllOrderedByIdDesc(): array
    {
        return $this->model->orderBy('id', 'DESC')->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->model->where('email', $email)->first() ?: null;
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

    public function updatePasswordByEmail(string $email, string $hashedPassword): bool
    {
        return (bool) $this->model->where('email', $email)->set(['password' => $hashedPassword])->update();
    }

    public function searchForDropdown(string $term): array
    {
        return $this->model
            ->select('id, full_name as text')
            ->groupStart()
            ->like('full_name', $term)
            ->orLike('email', $term)
            ->groupEnd()
            ->limit(10)
            ->findAll();
    }
}
