<?php

namespace App\Modules\Renovation\Repositories\Contracts;

/**
 * RenovationMaterialSubmissionRepositoryInterface
 */
interface RenovationMaterialSubmissionRepositoryInterface
{
    public function findByRenovationId(int $renovationId): array;
    public function findById(int $id): ?array;
    public function update(int $id, array $data): bool;
    public function save(array $data): bool;
    public function delete(int $id): bool;
}
