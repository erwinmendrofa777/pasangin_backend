<?php

namespace App\Modules\PriceEstimate\Repositories\Contracts;

/**
 * PriceEstimateConceptsRepositoryInterface
 */
interface PriceEstimateConceptsRepositoryInterface
{
    public function findAllOrderedByCreatedAtAsc(): array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function errors(): array;
}
