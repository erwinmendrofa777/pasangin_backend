<?php

namespace App\Modules\Design\Repositories;

use App\Modules\Design\Models\ProjectInvoicesModel;
use App\Modules\Design\Repositories\Contracts\ProjectInvoicesRepositoryInterface;

/**
 * ProjectInvoicesRepository
 */
class ProjectInvoicesRepository implements ProjectInvoicesRepositoryInterface
{
    protected ProjectInvoicesModel $model;

    public function __construct()
    {
        $this->model = new ProjectInvoicesModel();
    }

    public function findByDesignRequestId(int $id): array
    {
        return $this->model
            ->select('project_invoices.*, vouchers.discount_nominal')
            ->join('vouchers', 'vouchers.code = project_invoices.voucher_code', 'left')
            ->where('design_request_id', $id)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function deleteByDesignRequestId(int $id): bool
    {
        return (bool) $this->model->where('design_request_id', $id)->delete();
    }
}
