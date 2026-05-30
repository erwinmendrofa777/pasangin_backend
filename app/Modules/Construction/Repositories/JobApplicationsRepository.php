<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\JobApplicationsModel;
use App\Modules\Construction\Repositories\Contracts\JobApplicationsRepositoryInterface;

class JobApplicationsRepository implements JobApplicationsRepositoryInterface
{
    protected JobApplicationsModel $model;

    public function __construct()
    {
        $this->model = new JobApplicationsModel();
    }

    public function findByProjectIdAndType(int $projectId, string $projectType): array
    {
        return $this->model
            ->select('job_applications.*, tukang.name as tukang_name')
            ->join('tukang', 'tukang.id = job_applications.tukang_id', 'left')
            ->where('project_id', $projectId)
            ->where('project_type', $projectType)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function findApprovedByProjectIdAndType(int $projectId, string $projectType): array
    {
        return $this->model
            ->select('job_applications.*, tukang.name as tukang_name')
            ->join('tukang', 'tukang.id = job_applications.tukang_id', 'left')
            ->where('project_id', $projectId)
            ->where('project_type', $projectType)
            ->where('status', 'Approved')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }
}
