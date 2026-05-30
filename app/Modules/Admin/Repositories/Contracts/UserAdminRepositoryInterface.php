<?php

namespace App\Modules\Admin\Repositories\Contracts;

/**
 * UserAdminRepositoryInterface
 */
interface UserAdminRepositoryInterface
{
    public function findAllOrderedByIdDesc(): array;
    public function findById(int $id): ?array;
    public function findByEmail(string $email): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function updatePasswordByEmail(string $email, string $hashedPassword): bool;
    public function searchForDropdown(string $term): array;
}
