<?php

namespace App\Modules\Construction\Repositories\Contracts;

/**
 * ConstructionProgressRepositoryInterface
 */
interface ConstructionProgressRepositoryInterface
{
    public function findDetailsByConstructionId(int $constructionId): array;
    public function findById(int $id): ?array;
    public function save(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function sumVolumeByTargetId(int $targetId): float;
    public function sumVolumeByConstructionId(int $constructionId): float;
}
