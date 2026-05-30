<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationJobsRepositoryInterface
{
    public function findByRenovationId(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
}
