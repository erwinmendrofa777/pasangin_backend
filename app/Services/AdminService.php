<?php

namespace App\Services;

use App\Models\UserAdminModel;
use App\Models\RoleModel;
use RuntimeException;

class AdminService
{
    protected UserAdminModel $adminModel;
    protected RoleModel      $roleModel;

    private const PHOTO_PATH = 'uploads/profile/';

    public function __construct()
    {
        $this->adminModel = new UserAdminModel();
        $this->roleModel  = new RoleModel();
    }

    public function getAllAdmins(): array
    {
        return $this->adminModel->orderBy('id', 'DESC')->findAll();
    }

    public function getAllRoles(): array
    {
        return $this->roleModel->findAll();
    }

    public function findOrFail(int $id): array
    {
        $admin = $this->adminModel->find($id);
        if (!$admin) {
            throw new RuntimeException('Admin tidak ditemukan');
        }
        return $admin;
    }

    /**
     * Create new admin.
     */
    public function createAdmin(array $data, $photoFile = null): void
    {
        $payload = [
            'full_name'    => $data['full_name'],
            'email'        => $data['email'],
            'password'     => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'         => $data['role'],
            'phone_number' => $data['phone_number'] ?? null,
        ];

        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $newName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . self::PHOTO_PATH, $newName);
            $payload['photo'] = $newName;
        }

        $this->adminModel->insert($payload);
    }

    /**
     * Update existing admin.
     */
    public function updateAdmin(int $id, array $data, $photoFile = null): void
    {
        $admin = $this->findOrFail($id);

        $payload = [
            'full_name'    => $data['full_name'],
            'email'        => $data['email'],
            'role'         => $data['role'],
            'phone_number' => $data['phone_number'] ?? null,
        ];

        if (!empty($data['password'])) {
            $payload['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $newName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . self::PHOTO_PATH, $newName);
            $payload['photo'] = $newName;

            // Delete old photo
            if (!empty($admin['photo']) && is_file(FCPATH . self::PHOTO_PATH . $admin['photo'])) {
                unlink(FCPATH . self::PHOTO_PATH . $admin['photo']);
            }
        }

        $this->adminModel->update($id, $payload);
    }

    /**
     * Delete admin.
     */
    public function deleteAdmin(int $id, int $currentUserId): void
    {
        if ($id == $currentUserId || $id == 1) {
            throw new RuntimeException('Tidak bisa menghapus super admin atau akun yang sedang digunakan.');
        }

        $admin = $this->findOrFail($id);

        if (!empty($admin['photo']) && is_file(FCPATH . self::PHOTO_PATH . $admin['photo'])) {
            unlink(FCPATH . self::PHOTO_PATH . $admin['photo']);
        }

        $this->adminModel->delete($id);
    }
}
