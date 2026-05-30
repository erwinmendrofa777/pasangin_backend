<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface ConstructionInvoicesRepositoryInterface
{
    public function findByConstructionId(int $constructionId): array;
    public function save(array $data): bool;
    public function delete(int $id): bool;
}
