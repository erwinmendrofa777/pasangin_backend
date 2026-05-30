<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationInvoicesModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationInvoicesRepositoryInterface;

class RenovationInvoicesRepository implements RenovationInvoicesRepositoryInterface
{
    protected RenovationInvoicesModel $model;

    public function __construct()
    {
        $this->model = new RenovationInvoicesModel();
    }

    public function findByRenovationId(int $id): array
    {
        return $this->model
            ->select('renovation_invoices.*, vouchers.discount_nominal')
            ->join('vouchers', 'vouchers.code = renovation_invoices.voucher_code', 'left')
            ->where('renovation_id', $id)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    public function countByDescription(int $renovationId, string $description): int
    {
        return $this->model
            ->where('renovation_id', $renovationId)
            ->where('LOWER(description)', strtolower($description))
            ->countAllResults();
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
