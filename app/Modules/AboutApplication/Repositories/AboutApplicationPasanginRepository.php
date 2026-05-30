<?php

namespace App\Modules\AboutApplication\Repositories;

use App\Modules\AboutApplication\Models\AboutApplicationPasanginModel;
use App\Modules\AboutApplication\Repositories\Contracts\AboutApplicationPasanginRepositoryInterface;

/**
 * AboutApplicationPasanginRepository
 */
class AboutApplicationPasanginRepository implements AboutApplicationPasanginRepositoryInterface
{
    protected AboutApplicationPasanginModel $model;

    public function __construct()
    {
        $this->model = new AboutApplicationPasanginModel();
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
}
