<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationTargetRepositoryInterface
{
    public function findByRenovationId(int $id): array;
    public function findById(int $id): ?array;
    public function findByRenovationAndRab(int $renovationId, int $rabId): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
