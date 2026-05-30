<?php

namespace App\Modules\Design\Repositories\Contracts;

/**
 * DesignTargetsRepositoryInterface
 */
interface DesignTargetsRepositoryInterface
{
    public function findByDesignRequestId(int $id): array;
    public function findById(int $id): ?array;
    public function findByIdAndDesignRequestId(int $id, int $designRequestId): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function deleteByDesignRequestId(int $id): bool;
}
