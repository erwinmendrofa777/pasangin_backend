<?php

namespace App\Modules\AHSP\Repositories\Contracts;

interface AHSPRepositoryInterface
{
    public function findAllOrderedByIdDesc(): array;
    public function findById(int $id): ?array;
    public function findWithChildren(int $id): ?array;
    public function insert(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function saveChildren(int $ahspId, array $bahan, array $tenagaKerja): void;
}
