<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationJobsModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationJobsRepositoryInterface;

class RenovationJobsRepository implements RenovationJobsRepositoryInterface
{
    protected RenovationJobsModel $model;

    public function __construct()
    {
        $this->model = new RenovationJobsModel();
    }

    public function findByRenovationId(int $id): ?array
    {
        return $this->model->where('renovation_id', $id)->first() ?: null;
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
