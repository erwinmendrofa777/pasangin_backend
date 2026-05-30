<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface ConstructionDesignRepositoryInterface
{
    public function findByConstructionId(int $constructionId): array;
    public function delete(int $id): bool;
    public function save(array $data): bool;
}
