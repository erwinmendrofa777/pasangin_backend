<?php

namespace App\Modules\Vouchers\Repositories\Contracts;

/**
 * VoucherRepositoryInterface
 */
interface VoucherRepositoryInterface
{
    public function findAllOrderedByIdDesc(): array;
    public function findById(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
