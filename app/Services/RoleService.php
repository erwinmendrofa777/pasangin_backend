<?php

namespace App\Services;

use App\Models\RoleModel;
use RuntimeException;

class RoleService
{
    protected RoleModel $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    public function getAllRoles(): array
    {
        return $this->roleModel->orderBy('id', 'DESC')->findAll();
    }

    public function findOrFail(int $id): array
    {
        $role = $this->roleModel->find($id);
        if (!$role) {
            throw new RuntimeException('Role tidak ditemukan');
        }
        return $role;
    }

    /**
     * Struktur menu permission yang tersedia.
     */
    public function getAvailableMenus(): array
    {
        return [
            'MANAJEMEN' => [
                'chat' => [
                    'label' => 'Pesan Masuk',
                    'actions' => [
                        'chat_view' => 'Lihat Pesan Masuk'
                    ]
                ],
                'users' => [
                    'label' => 'Akses Menu Users',
                    'actions' => [
                        'users_edit' => 'Edit User',
                        'users_delete' => 'Hapus User',
                        'users_status' => 'Ubah Status User'
                    ]
                ],
                'suppliers' => [
                    'label' => 'Akses Menu Suppliers',
                    'actions' => [
                        'suppliers_create' => 'Tambah Supplier',
                        'suppliers_edit' => 'Edit Supplier',
                        'suppliers_delete' => 'Hapus Supplier',
                        'suppliers_status' => 'Ubah Status Supplier'
                    ]
                ],
                'products' => [
                    'label' => 'Akses Menu Products',
                    'actions' => [
                        'products_delete' => 'Hapus Produk',
                        'products_status' => 'Ubah Status Produk'
                    ]
                ],
                'orders' => [
                    'label' => 'Akses Menu Order',
                    'actions' => [
                        'orders_status' => 'Ubah Status Order'
                    ]
                ],
                'wallet' => [
                    'label' => 'Akses Menu Wallet',
                    'actions' => [
                        'wallet_manage' => 'Kelola Saldo',
                        'wallet_withdraw_request' => 'Permintaan Tarik Dana'
                    ]
                ],
                'tukang' => [
                    'label' => 'Akses Menu Tukang',
                    'actions' => [
                        'tukang_create' => 'Tambah Tukang',
                        'tukang_delete' => 'Hapus Tukang',
                        'tukang_status' => 'Ubah Status Tukang',
                        'tukang_verify' => 'Verifikasi Tukang'
                    ]
                ],
            ],
            'PROYEK' => [
                'design' => [
                    'label' => 'Akses Menu Desain',
                    'actions' => [
                        'design_detail' => 'Detail Desain',
                        'design_delete' => 'Hapus Desain',
                        'design_pembayaran' => 'Pembayaran Desain',
                        'design_progress' => 'Progres Desain',
                        'design_target' => 'Target Desain',
                        'design_desain' => 'Desain',
                        'design_survey' => 'Survey Desain',
                    ]
                ],
                'construction' => [
                    'label' => 'Akses Menu Konstruksi',
                    'actions' => [
                        'construction_detail' => 'Detail Konstruksi',
                        'construction_pelamar' => 'Pelamar Konstruksi',
                        'construction_target' => 'Target Konstruksi',
                        'construction_survey' => 'Survey Konstruksi',
                        'construction_desain' => 'Desain Konstruksi',
                        'construction_rab' => 'RAB Konstruksi',
                        'construction_addendum' => 'Addendum Konstruksi',
                        'construction_pembayaran' => 'Pembayaran Konstruksi',
                        'construction_progress' => 'Progres Konstruksi',
                        'construction_lowongan' => 'Lowongan Konstruksi',
                    ]
                ],
                'renovation' => [
                    'label' => 'Akses Menu Renovasi',
                    'actions' => [
                        'renovation_detail' => 'Detail Renovasi',
                        'renovation_survey' => 'Survey Renovasi',
                        'renovation_desain' => 'Desain Renovasi',
                        'renovation_target' => 'Target Renovasi',
                        'renovation_rab' => 'RAB Renovasi',
                        'renovation_pembayaran' => 'Pembayaran Renovasi',
                        'renovation_progress' => 'Progres Renovasi',
                        'renovation_lowongan' => 'Lowongan Renovasi',
                    ]
                ],
            ],
            'KONTEN' => [
                'banner' => [
                    'label' => 'Akses Menu Banner',
                    'actions' => [
                        'banner_create' => 'Tambah Banner',
                        'banner_delete' => 'Hapus Banner',
                    ]
                ],
                'banner_supplier' => [
                    'label' => 'Akses Menu Banner Supplier',
                    'actions' => [
                        'banner_supplier_create' => 'Tambah Banner Supplier',
                        'banner_supplier_update' => 'Update Banner Supplier',
                        'banner_supplier_delete' => 'Hapus Banner Supplier',
                        'banner_supplier_status' => 'Ubah Status Banner Supplier',
                    ]
                ],
                'vouchers' => [
                    'label' => 'Akses Menu Voucher',
                    'actions' => [
                        'vouchers_create' => 'Tambah Voucher',
                        'vouchers_delete' => 'Hapus Voucher',
                        'vouchers_status' => 'Ubah Status Voucher',
                    ]
                ],
                'tips' => [
                    'label' => 'Akses Menu Tips',
                    'actions' => [
                        'tips_create' => 'Tambah Tips',
                        'tips_delete' => 'Hapus Tips',
                    ]
                ],
                'promo' => [
                    'label' => 'Akses Menu Promo',
                    'actions' => [
                        'promo_status' => 'Ubah Status Promo',
                        'promo_delete' => 'Hapus Promo',
                    ]
                ],
                'notification' => [
                    'label' => 'Akses Menu Notifikasi',
                    'actions' => [
                        'notification_create' => 'Tambah Notifikasi',
                    ]
                ],
                'price-estimate' => [
                    'label' => 'Akses Menu Estimasi Harga',
                    'actions' => [
                        'price-estimate_create' => 'Tambah Estimasi Harga',
                        'price-estimate_update' => 'Ubah Estimasi Harga',
                        'price-estimate_delete' => 'Hapus Estimasi Harga',
                    ]
                ],
                'syarat_ketentuan' => [
                    'label' => 'Akses Menu Syarat & Ketentuan',
                    'actions' => [
                        'syarat_ketentuan_create' => 'Tambah Syarat & Ketentuan',
                        'syarat_ketentuan_update' => 'Ubah Syarat & Ketentuan',
                        'syarat_ketentuan_delete' => 'Hapus Syarat & Ketentuan',
                    ]
                ],
            ],
            'AKSES' => [
                'roles' => [
                    'label' => 'Akses Menu Role',
                    'actions' => [
                        'roles_create' => 'Tambah Role',
                        'roles_edit' => 'Edit Role',
                        'roles_delete' => 'Hapus Role'
                    ]
                ],
                'admin' => [
                    'label' => 'Akses Menu Admin',
                    'actions' => [
                        'admin_create' => 'Tambah Admin',
                        'admin_edit' => 'Edit Admin',
                        'admin_delete' => 'Hapus Admin'
                    ]
                ],
                'dashboard' => [
                    'label' => 'Akses Menu Dashboard',
                    'actions' => [
                        'dashboard_view' => 'Lihat Dashboard'
                    ]
                ],
            ],
        ];
    }

    public function createRole(array $data): void
    {
        $permissionsJson = !empty($data['permissions']) ? json_encode($data['permissions']) : json_encode([]);

        $this->roleModel->insert([
            'role_name'   => $data['role_name'],
            'permissions' => $permissionsJson
        ]);
    }

    public function updateRole(int $id, array $data): void
    {
        $this->findOrFail($id);

        $permissionsJson = !empty($data['permissions']) ? json_encode($data['permissions']) : json_encode([]);

        $this->roleModel->update($id, [
            'role_name'   => $data['role_name'],
            'permissions' => $permissionsJson
        ]);
    }

    public function deleteRole(int $id): void
    {
        $role = $this->findOrFail($id);

        if (strtolower($role['role_name']) == 'super_admin') {
            throw new RuntimeException('Role Super Admin tidak boleh dihapus.');
        }

        $this->roleModel->delete($id);
    }
}
