<?php

namespace App\Modules\Design\Repositories\Contracts;

/**
 * DesignRequestRepositoryInterface
 */
interface DesignRequestRepositoryInterface
{
    public function findAllOrderedByCreatedAtDesc(): array;
    public function findById(int $id): ?array;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
