<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationMaterialSubmissionModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationMaterialSubmissionRepositoryInterface;

/**
 * RenovationMaterialSubmissionRepository
 */
class RenovationMaterialSubmissionRepository implements RenovationMaterialSubmissionRepositoryInterface
{
    protected RenovationMaterialSubmissionModel $model;

    public function __construct()
    {
        $this->model = new RenovationMaterialSubmissionModel();
    }

    public function findByRenovationId(int $renovationId): array
    {
        return $this->model
            ->select('renovation_material_submission.*, job_applications.tukang_name')
            ->join('job_applications', 'job_applications.id = renovation_material_submission.job_applications_id', 'left')
            ->where('renovation_material_submission.renovation_id', $renovationId)
            ->orderBy('renovation_material_submission.created_at', 'DESC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model
            ->select('renovation_material_submission.*, job_applications.tukang_name')
            ->join('job_applications', 'job_applications.id = renovation_material_submission.job_applications_id', 'left')
            ->where('renovation_material_submission.id', $id)
            ->first() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function save(array $data): bool
    {
        return (bool) $this->model->save($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }
}
