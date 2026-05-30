<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionRepositoryInterface;

/**
 * ConstructionRepository
 */
class ConstructionRepository implements ConstructionRepositoryInterface
{
    protected ConstructionModel $model;

    public function __construct()
    {
        $this->model = new ConstructionModel();
    }

    public function findAllOrderedByCreatedAtDesc(): array
    {
        return $this->model->orderBy('created_at', 'DESC')->findAll();
    }

    public function findByIdWithUser(int $id): ?array
    {
        return $this->model
            ->select('construction_requests.*, users.full_name, users.email, users.phone_number')
            ->join('users', 'users.id = construction_requests.user_id', 'left')
            ->find($id) ?: null;
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findContractDetails(int $id): ?array
    {
        return $this->model
            ->select('construction_requests.address as address_construction, 
                      construction_requests.id as construction_id, 
                      construction_requests.start_date, 
                      construction_requests.week, 
                      users.full_name as nama_klien, 
                      users.nik as nik_klien, 
                      users.address as address_klien, 
                      vouchers.discount_nominal')
            ->join('users', 'users.id = construction_requests.user_id', 'left')
            ->join('vouchers', 'vouchers.code = construction_requests.voucher_code', 'left')
            ->where('construction_requests.id', $id)
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
}
