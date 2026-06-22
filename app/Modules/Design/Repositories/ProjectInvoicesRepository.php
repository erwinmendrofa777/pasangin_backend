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
            ->select('project_invoices.*, vouchers.discount_nominal, design_targets.task_name as target_task_name')
            ->join('vouchers', 'vouchers.code = project_invoices.voucher_code', 'left')
            ->join('design_targets', 'design_targets.id = project_invoices.design_target_id', 'left')
            ->where('project_invoices.design_request_id', $id)
            ->orderBy('project_invoices.design_target_id', 'ASC')
            ->orderBy('project_invoices.id', 'ASC')
            ->findAll();
    }

    public function findByDesignTargetId(int $targetId): ?array
    {
        return $this->model
            ->where('design_target_id', $targetId)
            ->first() ?: null;
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function deleteByDesignRequestId(int $id): bool
    {
        return (bool) $this->model->where('design_request_id', $id)->delete();
    }

    public function deleteByDesignTargetId(int $targetId): bool
    {
        return (bool) $this->model->where('design_target_id', $targetId)->delete();
    }
}
