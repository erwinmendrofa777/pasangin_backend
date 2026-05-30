<?php

namespace App\Modules\Supplier\Repositories\Contracts;

/**
 * PromoRepositoryInterface
 */
interface PromoRepositoryInterface
{
    public function findAllWithSupplier(): array;
    public function findByIdWithSupplier(int $id): ?array;
    public function countByStatus(string $status): int;
    public function countInactive(): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
