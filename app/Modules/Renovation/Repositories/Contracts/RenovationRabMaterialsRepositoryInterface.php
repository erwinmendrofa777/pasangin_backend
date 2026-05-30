<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationRabMaterialsRepositoryInterface
{
    public function findByRabId(int $rabId): array;
    public function insert(array $data): bool;
    public function delete(int $id): bool;
    public function deleteByRabId(int $rabId): bool;
}
