<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationRepositoryInterface
{
    public function findAllWithClient(): array;
    public function findWithClientById(int $id): ?array;
    public function findById(int $id): ?array;
    public function findContractDetails(int $id): ?array;
    public function update(int $id, array $data): bool;
    public function insert(array $data): bool;
    public function delete(int $id): bool;
}
