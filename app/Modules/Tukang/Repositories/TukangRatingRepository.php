<?php

namespace App\Modules\Tukang\Repositories;

use App\Modules\Tukang\Models\TukangRatingModel;
use App\Modules\Tukang\Repositories\Contracts\TukangRatingRepositoryInterface;

/**
 * TukangRatingRepository
 */
class TukangRatingRepository implements TukangRatingRepositoryInterface
{
    protected TukangRatingModel $model;

    public function __construct()
    {
        $this->model = new TukangRatingModel();
    }

    public function findByTukangId(int $tukangId): array
    {
        return $this->model
            ->where('id_tukang', $tukangId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
