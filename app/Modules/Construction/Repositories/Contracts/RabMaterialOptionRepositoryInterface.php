<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface RabMaterialOptionRepositoryInterface
{
    public function findAll(): array;
    public function findByRabId(int $rabId): array;
}
