<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface ConstructionJobsRepositoryInterface
{
    public function findByConstructionId(int $constructionId): ?array;
    public function findAllByConstructionId(int $constructionId): array;
    public function findByTargetId(int $targetId): ?array;
    public function findGlobalByConstructionId(int $constructionId): ?array;
    public function update(int $id, array $data): bool;
    public function insert(array $data): bool;
}
