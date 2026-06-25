<?php

namespace App\Modules\Tukang\Repositories\Contracts;

interface TukangRepositoryInterface
{
    public function findById(int $id): ?array;
    public function findWithFcmToken(): array;
    public function findAllWithRatings(): array;
    public function countAll(): int;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function findAllOrderedByName(): array;
    public function save(array $data): bool;
    public function searchForDropdown(string $term): array;
    public function getInsertID(): int;
    public function findGroupConstructionTargets(): array;
}
