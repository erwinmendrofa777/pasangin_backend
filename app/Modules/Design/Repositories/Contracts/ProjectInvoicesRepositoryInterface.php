<?php

namespace App\Modules\Design\Repositories\Contracts;

/**
 * ProjectInvoicesRepositoryInterface
 */
interface ProjectInvoicesRepositoryInterface
{
    public function findByDesignRequestId(int $id): array;
    public function findByDesignTargetId(int $targetId): ?array;
    public function findById(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function deleteByDesignRequestId(int $id): bool;
    public function deleteByDesignTargetId(int $targetId): bool;
}
