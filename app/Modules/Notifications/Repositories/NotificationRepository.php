<?php

namespace App\Modules\Notifications\Repositories;

use App\Modules\Notifications\Models\NotificationModel;
use App\Modules\Notifications\Repositories\Contracts\NotificationRepositoryInterface;

/**
 * NotificationRepository
 */
class NotificationRepository implements NotificationRepositoryInterface
{
    protected NotificationModel $model;

    public function __construct()
    {
        $this->model = new NotificationModel();
    }

    public function findAllOrderedByCreatedAtDesc(): array
    {
        return $this->model->orderBy('created_at', 'DESC')->findAll();
    }

    public function findByTargetType(string $targetType, int $limit = 20): array
    {
        return $this->model
            ->where('target_type', $targetType)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    public function countByTargetType(string $targetType): int
    {
        return $this->model->where('target_type', $targetType)->countAllResults();
    }

    public function insert(array $data): int|string|bool
    {
        return $this->model->insert($data);
    }
}
