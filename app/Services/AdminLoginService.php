<?php

namespace App\Services;

use RuntimeException;

class AdminLoginService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Memverifikasi kredensial login dan mengambil data user beserta permission-nya.
     *
     * @throws RuntimeException
     */
    public function attemptLogin(string $email, string $password): array
    {
        // 1. Cari user di tabel user_admin
        $user = $this->db->table('user_admin')
            ->where('email', $email)
            ->get()->getRowArray();

        if (!$user || !password_verify($password, $user['password'])) {
            throw new RuntimeException('Email atau Password salah.');
        }

        // 2. Ambil permissions dari tabel roles
        $roleData    = $this->db->table('roles')->where('role_name', $user['role'])->get()->getRowArray();
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
