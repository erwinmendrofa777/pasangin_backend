<?php

namespace App\Modules\Satuan\Repositories\Contracts;

interface SatuanRepositoryInterface
{
    public function findAllOrderedByIdDesc(): array;
    public function findById(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
