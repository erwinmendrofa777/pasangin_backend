<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface ConstructionAddendumMaterialRepositoryInterface
{
    public function findByAddendumId(int $addendumId): array;
    public function insert(array $data): bool;
    public function delete(int $id): bool;
    public function deleteByAddendumId(int $addendumId): bool;
}
