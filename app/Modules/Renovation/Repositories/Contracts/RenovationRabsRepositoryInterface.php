<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationRabsRepositoryInterface
{
    public function findByRenovationId(int $id): array;
    public function findById(int $id): ?array;
    public function findGroupedSummaryByRenovationId(int $id): array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function lockByRenovationId(int $renovationId): bool;
    public function unlockByRenovationId(int $renovationId): bool;
}
