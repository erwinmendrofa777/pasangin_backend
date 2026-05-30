<?php

namespace App\Modules\Wallets\Repositories;

use App\Modules\Wallets\Models\WithdrawalRequestsModel;
use App\Modules\Wallets\Repositories\Contracts\WithdrawalRequestsRepositoryInterface;

class WithdrawalRequestsRepository implements WithdrawalRequestsRepositoryInterface
{
    protected WithdrawalRequestsModel $model;

    public function __construct()
    {
        $this->model = new WithdrawalRequestsModel();
    }

    public function findAllWithTukang(): array
    {
        return $this->model
            ->select('withdrawal_requests.*, tukang.name as tukang_name, tukang.phone')
            ->join('tukang', 'tukang.id = withdrawal_requests.tukang_id')
            ->orderBy('withdrawal_requests.created_at', 'DESC')
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

    public function getLatestRequests(int $limit): array
    {
        return $this->model->select('withdrawal_requests.*, tukang.name as tukang_name')
            ->join('tukang', 'tukang.id = withdrawal_requests.tukang_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
