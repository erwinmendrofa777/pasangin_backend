<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionInvoicesModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionInvoicesRepositoryInterface;

class ConstructionInvoicesRepository implements ConstructionInvoicesRepositoryInterface
{
    protected ConstructionInvoicesModel $model;

    public function __construct()
    {
        $this->model = new ConstructionInvoicesModel();
    }

    public function findByConstructionId(int $constructionId): array
    {
        return $this->model
            ->select('construction_invoices.*, vouchers.discount_nominal')
            ->join('vouchers', 'vouchers.code = construction_invoices.voucher_code', 'left')
            ->where('construction_id', $constructionId)
            ->findAll();
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
