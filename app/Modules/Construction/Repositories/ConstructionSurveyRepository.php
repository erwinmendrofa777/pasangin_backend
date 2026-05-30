<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionSurveyModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionSurveyRepositoryInterface;

class ConstructionSurveyRepository implements ConstructionSurveyRepositoryInterface
{
    protected ConstructionSurveyModel $model;

    public function __construct()
    {
        $this->model = new ConstructionSurveyModel();
    }

    public function findByConstructionId(int $constructionId): array
    {
        return $this->model
            ->select('construction_surveys.*, ua.full_name as admin_name')
            ->join('user_admin ua', 'ua.id = construction_surveys.user_admin_id', 'left')
            ->where('construction_id', $constructionId)
            ->orderBy('created_at', 'desc')
            ->findAll();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function save(array $data): bool
    {
        return (bool) $this->model->save($data);
    }
}
