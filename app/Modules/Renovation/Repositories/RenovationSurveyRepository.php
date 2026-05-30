<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationSurveyModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationSurveyRepositoryInterface;

class RenovationSurveyRepository implements RenovationSurveyRepositoryInterface
{
    protected RenovationSurveyModel $model;

    public function __construct()
    {
        $this->model = new RenovationSurveyModel();
    }

    public function findByRequestId(int $id): array
    {
        return $this->model
            ->select('renovation_surveys.*, ua.full_name as admin_name')
            ->join('user_admin ua', 'ua.id = renovation_surveys.user_admin_id', 'left')
            ->where('request_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }
}
