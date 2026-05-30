<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationInvoicesRepositoryInterface
{
    public function findByRenovationId(int $id): array;
    public function countByDescription(int $renovationId, string $description): int;
    public function insert(array $data): bool;
    public function delete(int $id): bool;
}
