<?php

namespace App\Modules\Supplier\Repositories\Contracts;

/**
 * SupplierBannerRepositoryInterface
 */
interface SupplierBannerRepositoryInterface
{
    public function findAllWithSupplier(): array;
    public function findById(int $id): ?array;
    public function findDetailWithSupplier(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
