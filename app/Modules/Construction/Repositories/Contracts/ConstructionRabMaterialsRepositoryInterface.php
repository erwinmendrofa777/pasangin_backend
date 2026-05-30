<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface ConstructionRabMaterialsRepositoryInterface
{
    public function findByRabId(int $rabId): array;
    public function insert(array $data): bool;
    public function delete(int $id): bool;
    public function deleteByRabId(int $rabId): bool;
}
