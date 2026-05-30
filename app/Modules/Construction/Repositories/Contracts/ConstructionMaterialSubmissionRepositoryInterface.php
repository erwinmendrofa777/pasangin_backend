<?php

namespace App\Modules\Construction\Repositories\Contracts;

/**
 * ConstructionMaterialSubmissionRepositoryInterface
 */
interface ConstructionMaterialSubmissionRepositoryInterface
{
    public function findByConstructionId(int $constructionId): array;
    public function findById(int $id): ?array;
    public function update(int $id, array $data): bool;
    public function save(array $data): bool;
    public function delete(int $id): bool;
}
