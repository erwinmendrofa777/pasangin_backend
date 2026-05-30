<?php

namespace App\Modules\PriceEstimate\Repositories\Contracts;

/**
 * PriceEstimateQualitiesRepositoryInterface
 */
interface PriceEstimateQualitiesRepositoryInterface
{
    public function findByConceptId(int $conceptId): array;
    public function countAll(): int;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function deleteByConceptId(int $conceptId): bool;
    public function errors(): array;
}
