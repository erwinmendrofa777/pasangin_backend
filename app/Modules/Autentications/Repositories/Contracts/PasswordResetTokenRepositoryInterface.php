<?php

namespace App\Modules\Autentications\Repositories\Contracts;

/**
 * PasswordResetTokenRepositoryInterface
 */
interface PasswordResetTokenRepositoryInterface
{
    public function deleteByEmail(string $email): bool;
    public function insert(array $data): bool;
    public function findByEmailAndToken(string $email, string $token): ?array;
}
