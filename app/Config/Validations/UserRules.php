<?php

namespace Config\Validations;

trait UserRules
{
    /**
     * Aturan validasi untuk update data user (client)
     */
    public array $userUpdate = [
        'id'           => 'permit_empty|numeric',
        'full_name'    => 'required|min_length[3]|max_length[100]',
        'email'        => 'required|valid_email|is_unique[users.email,id,{id}]',
        'phone_number' => 'required|numeric|min_length[10]|max_length[15]',
        'nik'          => 'permit_empty|numeric|exact_length[16]|is_unique[users.nik,id,{id}]',
        'gender'       => 'required|in_list[Laki - laki,Perempuan]',
        'birth_date'   => 'required|valid_date',
        'address'      => 'required|min_length[5]|max_length[255]',
        'avatar'       => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]|max_size[avatar,2048]',
    ];

    public array $userUpdate_errors = [
        'full_name' => [
            'required' => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique' => 'Email sudah terdaftar.',
        ],
        'nik' => [
            'exact_length' => 'NIK harus 16 digit.',
            'is_unique' => 'NIK sudah digunakan user lain.',
        ],
        'avatar' => [
            'is_image' => 'File yang diupload bukan gambar.',
            'max_size' => 'Ukuran gambar maksimal 2MB.',
        ]
    ];

    /**
     * Aturan validasi untuk update status user oleh admin
     */
    public array $userUpdateStatus = [
        'status' => 'required|in_list[approved,rejected,banned,pending]',
    ];

    public array $userUpdateStatus_errors = [
        'status' => [
            'in_list' => 'Status tidak valid. Gunakan: approved, rejected, banned, atau pending.',
        ],
    ];
}
