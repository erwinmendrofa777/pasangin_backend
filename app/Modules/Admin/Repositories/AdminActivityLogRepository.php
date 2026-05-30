<?php

namespace App\Modules\Admin\Repositories;

use App\Modules\Admin\Models\AdminActivityLogModel;
use App\Modules\Admin\Repositories\Contracts\AdminActivityLogRepositoryInterface;

/**
 * AdminActivityLogRepository
 */
class AdminActivityLogRepository implements AdminActivityLogRepositoryInterface
{
    protected AdminActivityLogModel $model;

    public function __construct()
    {
        $this->model = new AdminActivityLogModel();
    }

    public function getLogsWithAdmin(int $limit = null, int $offset = 0): array
    {
        $builder = $this->model->select('admin_activity_logs.*, user_admin.full_name as admin_name, user_admin.role as role_name')
            ->join('user_admin', 'user_admin.id = admin_activity_logs.admin_id', 'left')
            ->orderBy('admin_activity_logs.created_at', 'DESC');

        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    public function deleteOlderThan(string $date): bool
    {
        return (bool) $this->model->where('created_at <', $date)->delete();
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }
}
