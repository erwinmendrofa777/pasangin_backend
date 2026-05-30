<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface ConstructionTargetsRepositoryInterface
{
    public function findByConstructionId(int $constructionId): array;
    public function findById(int $id): ?array;
    public function delete(int $id): bool;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function findByConstructionAndRab(int $constructionId, int $rabId): ?array;
    public function findByConstructionAndAddendum(int $constructionId, int $addendumId): ?array;
}
