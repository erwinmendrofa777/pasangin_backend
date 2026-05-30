<?php

namespace App\Modules\Banners\Repositories\Contracts;

/**
 * BannerRepositoryInterface
 */
interface BannerRepositoryInterface
{
    public function findAllOrderedByIdDesc(): array;
    public function findById(int $id): ?array;
    public function insert(array $data): bool;
    public function delete(int $id): bool;
}
