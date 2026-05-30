<?php

namespace App\Modules\Design\Repositories\Contracts;

/**
 * ProjectDesignsRepositoryInterface
 */
interface ProjectDesignsRepositoryInterface
{
    public function findWithTaskByDesignRequestId(int $id): array;
    public function findById(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function deleteByDesignRequestId(int $id): bool;
    public function getMaxRevisionNumber(int $targetId): int;
    public function updateStatusByTargetId(int $targetId, int $excludeId, string $status, string $note): bool;
}
