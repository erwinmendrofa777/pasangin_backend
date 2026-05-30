<?php

namespace App\Modules\Construction\Repositories\Contracts;

/**
 * ConstructionRepositoryInterface
 */
interface ConstructionRepositoryInterface
{
    public function findAllOrderedByCreatedAtDesc(): array;
    public function findByIdWithUser(int $id): ?array;
    public function findById(int $id): ?array;
    public function findContractDetails(int $id): ?array;
    public function update(int $id, array $data): bool;
    public function save(array $data): bool;
}
