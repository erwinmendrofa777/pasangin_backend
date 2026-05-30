<?php

namespace App\Modules\Autentications\Repositories;

use App\Modules\Autentications\Models\PasswordResetTokenModel;
use App\Modules\Autentications\Repositories\Contracts\PasswordResetTokenRepositoryInterface;

/**
 * PasswordResetTokenRepository
 */
class PasswordResetTokenRepository implements PasswordResetTokenRepositoryInterface
{
    protected PasswordResetTokenModel $model;

    public function __construct()
    {
        $this->model = new PasswordResetTokenModel();
    }

    public function deleteByEmail(string $email): bool
    {
        return (bool) $this->model->where('email', $email)->delete();
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function findByEmailAndToken(string $email, string $token): ?array
    {
        return $this->model
            ->where('email', $email)
            ->where('token', $token)
            ->first() ?: null;
    }
}
