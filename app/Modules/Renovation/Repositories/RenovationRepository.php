<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationRepositoryInterface;

class RenovationRepository implements RenovationRepositoryInterface
{
    protected RenovationModel $model;

    public function __construct()
    {
        $this->model = new RenovationModel();
    }

    public function findAllWithClient(): array
    {
        return $this->model
            ->select('renovation_requests.*, users.full_name AS client_name, users.phone_number')
            ->join('users', 'users.id = renovation_requests.user_id', 'left')
            ->orderBy('renovation_requests.created_at', 'DESC')
            ->findAll();
    }

    public function findWithClientById(int $id): ?array
    {
        return $this->model
            ->select('renovation_requests.*, users.full_name AS client_name, users.phone_number')
            ->join('users', 'users.id = renovation_requests.user_id', 'left')
            ->find($id) ?: null;
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findContractDetails(int $id): ?array
    {
        return $this->model
            ->select('renovation_requests.address as address_renovation, 
                      renovation_requests.id as renovation_id, 
                      renovation_requests.start_date, 
                      renovation_requests.week, 
                      users.full_name as nama_klien, 
                      users.nik as nik_klien, 
                      users.address as address_klien, 
                      vouchers.discount_nominal')
            ->join('users', 'users.id = renovation_requests.user_id', 'left')
            ->join('vouchers', 'vouchers.code = renovation_requests.voucher_code', 'left')
            ->where('renovation_requests.id', $id)
            ->first() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
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
