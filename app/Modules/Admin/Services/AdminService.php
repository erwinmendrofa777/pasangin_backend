<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Repositories\UserAdminRepository;
use App\Modules\Admin\Repositories\RoleRepository;
use App\Modules\Admin\Repositories\Contracts\UserAdminRepositoryInterface;
use App\Modules\Admin\Repositories\Contracts\RoleRepositoryInterface;
use RuntimeException;

class AdminService
{
    protected UserAdminRepositoryInterface $adminRepository;
    protected RoleRepositoryInterface      $roleRepository;

    private const PHOTO_PATH = 'uploads/profile/';

    public function __construct()
    {
        $this->adminRepository = new UserAdminRepository();
        $this->roleRepository  = new RoleRepository();
    }

    public function getAllAdmins(): array
    {
        return $this->adminRepository->findAllOrderedByIdDesc();
    }

    public function getAllRoles(): array
    {
        return $this->roleRepository->findAll();
    }

    public function findOrFail(int $id): array
    {
        $admin = $this->adminRepository->findById($id);
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

        $this->adminRepository->insert($payload);
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

        $this->adminRepository->update($id, $payload);
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

        $this->adminRepository->delete($id);
    }
}
