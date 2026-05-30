<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Repositories\UserAdminRepository;
use App\Modules\Admin\Repositories\RoleRepository;
use App\Modules\Admin\Repositories\Contracts\UserAdminRepositoryInterface;
use App\Modules\Admin\Repositories\Contracts\RoleRepositoryInterface;
use RuntimeException;

/**
 * AdminLoginService
 *
 * Mengelola logika login untuk administrator.
 */
class AdminLoginService
{
    protected UserAdminRepositoryInterface $userAdminRepository;
    protected RoleRepositoryInterface $roleRepository;

    public function __construct()
    {
        $this->userAdminRepository = new UserAdminRepository();
        $this->roleRepository      = new RoleRepository();
    }

    /**
     * Memverifikasi kredensial login dan mengambil data user beserta permission-nya.
     *
     * @throws RuntimeException
     */
    public function attemptLogin(string $email, string $password): array
    {
        // 1. Cari user di tabel user_admin menggunakan Repository
        $user = $this->userAdminRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new RuntimeException('Email atau Password salah.');
        }

        // 2. Ambil permissions dari tabel roles menggunakan Repository
        $roleData    = $this->roleRepository->findByName($user['role']);
        $permissions = [];

        if ($roleData) {
            $permissions = json_decode($roleData['permissions'], true) ?: [];
        }

        // 3. Super Admin Override
        if (strtolower($user['role']) === 'super_admin') {
            $permissions = ['super_admin_override'];
        }

        return [
            'user_id'     => $user['id'],
            'full_name'   => $user['full_name'] ?? 'Admin',
            'email'       => $user['email'],
            'role'        => $user['role'],
            'permissions' => $permissions,
            'isLoggedIn'  => true
        ];
    }
}
