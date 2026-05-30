<?php

namespace App\Modules\Design\Repositories;

use App\Modules\Design\Models\ProjectSurveysModel;
use App\Modules\Design\Repositories\Contracts\ProjectSurveysRepositoryInterface;

/**
 * ProjectSurveysRepository
 */
class ProjectSurveysRepository implements ProjectSurveysRepositoryInterface
{
    protected ProjectSurveysModel $model;

    public function __construct()
    {
        $this->model = new ProjectSurveysModel();
    }

    public function findByDesignRequestId(int $id): array
    {
        return $this->model
            ->select('project_surveys.*, ua.full_name as admin_name')
            ->join('user_admin ua', 'ua.id = project_surveys.user_admin_id', 'left')
            ->where('design_request_id', $id)
            ->orderBy('created_at', 'DESC')
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

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function deleteByDesignRequestId(int $id): bool
    {
        return (bool) $this->model->where('design_request_id', $id)->delete();
    }
}
