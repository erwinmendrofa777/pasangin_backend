<?php

namespace App\Modules\Tukang\Repositories;

use App\Modules\Tukang\Models\TukangTransactionsModel;
use App\Modules\Tukang\Repositories\Contracts\TukangTransactionsRepositoryInterface;

/**
 * TukangTransactionsRepository
 */
class TukangTransactionsRepository implements TukangTransactionsRepositoryInterface
{
    protected TukangTransactionsModel $model;

    public function __construct()
    {
        $this->model = new TukangTransactionsModel();
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }
}
