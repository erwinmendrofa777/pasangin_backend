<?php

namespace Config\Validations;

trait RoleRules
{
    /**
     * Aturan validasi untuk tambah role baru
     */
    public array $roleStore = [
        'role_name' => 'required|is_unique[roles.role_name]',
    ];

    public array $roleStore_errors = [
        'role_name' => [
            'required'  => 'Nama role wajib diisi.',
            'is_unique' => 'Nama role sudah terdaftar, gunakan nama lain.',
        ],
    ];

    /**
     * Aturan validasi untuk update role
     */
    public array $roleUpdate = [
        'id'        => 'permit_empty|numeric',
        'role_name' => 'required|is_unique[roles.role_name,id,{id}]',
    ];

    public array $roleUpdate_errors = [
        'role_name' => [
            'required'  => 'Nama role wajib diisi.',
            'is_unique' => 'Nama role sudah terdaftar.',
        ],
    ];
}
