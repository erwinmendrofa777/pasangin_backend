<?php

namespace App\Modules\AboutApplication\Repositories\Contracts;

/**
 * AboutApplicationPasanginRepositoryInterface
 */
interface AboutApplicationPasanginRepositoryInterface
{
    public function findById(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
}