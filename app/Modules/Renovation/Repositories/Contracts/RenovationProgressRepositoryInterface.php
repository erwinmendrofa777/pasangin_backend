<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationProgressRepositoryInterface
{
    public function findDetailsByRenovationId(int $id): array;
    public function findById(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function sumBobotByTargetId(int $targetId): float;
    public function sumBobotByRenovationId(int $renovationId): float;
}
