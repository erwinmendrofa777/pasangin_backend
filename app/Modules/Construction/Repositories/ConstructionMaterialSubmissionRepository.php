<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionMaterialSubmissionModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionMaterialSubmissionRepositoryInterface;

/**
 * ConstructionMaterialSubmissionRepository
 */
class ConstructionMaterialSubmissionRepository implements ConstructionMaterialSubmissionRepositoryInterface
{
    protected ConstructionMaterialSubmissionModel $model;

    public function __construct()
    {
        $this->model = new ConstructionMaterialSubmissionModel();
    }

    public function findByConstructionId(int $constructionId): array
    {
        return $this->model
            ->select('construction_material_submission.*, job_applications.tukang_name')
            ->join('job_applications', 'job_applications.id = construction_material_submission.job_applications_id', 'left')
            ->where('construction_material_submission.construction_id', $constructionId)
            ->orderBy('construction_material_submission.created_at', 'DESC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model
            ->select('construction_material_submission.*, job_applications.tukang_name')
            ->join('job_applications', 'job_applications.id = construction_material_submission.job_applications_id', 'left')
            ->where('construction_material_submission.id', $id)
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
