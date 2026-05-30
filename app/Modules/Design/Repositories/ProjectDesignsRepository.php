<?php

namespace App\Modules\Design\Repositories;

use App\Modules\Design\Models\ProjectDesignsModel;
use App\Modules\Design\Repositories\Contracts\ProjectDesignsRepositoryInterface;

/**
 * ProjectDesignsRepository
 */
class ProjectDesignsRepository implements ProjectDesignsRepositoryInterface
{
    protected ProjectDesignsModel $model;

    public function __construct()
    {
        $this->model = new ProjectDesignsModel();
    }

    public function findWithTaskByDesignRequestId(int $id): array
    {
        return $this->model
            ->select('project_designs.*, dt.task_name, ua.full_name as admin_name')
            ->join('design_targets dt', 'dt.id = project_designs.design_targets_id', 'left')
            ->join('user_admin ua', 'ua.id = project_designs.user_admin_id', 'left')
            ->where('project_designs.design_request_id', $id)
            ->orderBy('project_designs.created_at', 'DESC')
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

    public function getMaxRevisionNumber(int $targetId): int
    {
        $maxRev = $this->model
            ->selectMax('revision_number')
            ->where('design_targets_id', $targetId)
            ->first();

        return (int) ($maxRev['revision_number'] ?? 0);
    }

    public function updateStatusByTargetId(int $targetId, int $excludeId, string $status, string $note): bool
    {
        return (bool) $this->model
            ->where('design_targets_id', $targetId)
            ->where('id !=', $excludeId)
            ->where('status', 'PENDING')
            ->update(null, ['status' => $status, 'revision_note' => $note]);
    }
}
