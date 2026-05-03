<?php

namespace Config\Validations;

trait AdminRules
{
    /**
     * Aturan validasi untuk tambah admin baru
     */
    public array $adminSave = [
        'full_name' => 'required|min_length[3]|max_length[100]',
        'email'     => 'required|valid_email|is_unique[user_admin.email]',
        'password'  => 'required|min_length[6]',
        'role'      => 'required',
        'photo'     => 'permit_empty|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]|max_size[photo,2048]',
    ];

    public array $adminSave_errors = [
        'full_name' => [
            'required' => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique' => 'Email sudah terdaftar untuk admin lain.',
        ],
        'password' => [
            'required' => 'Password wajib diisi.',
            'min_length' => 'Password minimal 6 karakter.',
        ],
        'role' => [
            'required' => 'Role wajib dipilih.',
        ],
    ];

    /**
     * Aturan validasi untuk update data admin
     */
    public array $adminUpdate = [
        'id'        => 'permit_empty|numeric',
        'full_name' => 'required|min_length[3]|max_length[100]',
        'email'     => 'required|valid_email|is_unique[user_admin.email,id,{id}]',
        'password'  => 'permit_empty|min_length[6]',
        'role'      => 'required',
        'photo'     => 'permit_empty|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]|max_size[photo,2048]',
    ];

    public array $adminUpdate_errors = [
        'email' => [
            'is_unique' => 'Email sudah digunakan admin lain.',
        ],
    ];
}
