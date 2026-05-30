<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface ConstructionAddendumRepositoryInterface
{
    public function findById(int $id): ?array;
    public function findByConstructionId(int $constructionId): array;
    public function insert(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function lockByConstructionId(int $constructionId): bool;
    public function unlockByConstructionId(int $constructionId): bool;
}
