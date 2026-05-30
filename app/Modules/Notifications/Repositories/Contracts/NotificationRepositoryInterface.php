<?php

namespace App\Modules\Notifications\Repositories\Contracts;

/**
 * NotificationRepositoryInterface
 */
interface NotificationRepositoryInterface
{
    public function findAllOrderedByCreatedAtDesc(): array;
    public function findByTargetType(string $targetType, int $limit = 20): array;
    public function countByTargetType(string $targetType): int;
    public function insert(array $data): int|string|bool;
}
