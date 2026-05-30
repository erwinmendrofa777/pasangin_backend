<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationAttendanceRepositoryInterface
{
    public function findById(int $id): ?array;
    public function findByRenovationId(int $renovationId): array;
    public function insert(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}