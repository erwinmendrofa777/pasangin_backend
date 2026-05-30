<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface ConstructionJobsRepositoryInterface
{
    public function findByConstructionId(int $constructionId): ?array;
    public function update(int $id, array $data): bool;
    public function insert(array $data): bool;
}
