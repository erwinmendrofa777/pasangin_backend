<?php

namespace App\Modules\Orders\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function findAllOrderedByIdDesc(): array;
    public function findById(int $id): ?array;
    public function update(int $id, array $data): bool;
    public function getMonthlySalesData(string $startDate): array;
}
