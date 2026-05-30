<?php

namespace App\Modules\Users\Config\Validations;

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
            'required'   => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
            'max_length' => 'Nama lengkap maksimal 100 karakter.',
        ],
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique'   => 'Email sudah terdaftar atau digunakan user lain.',
        ],
        'phone_number' => [
            'required'   => 'Nomor telepon wajib diisi.',
            'numeric'    => 'Nomor telepon harus berupa angka.',
            'min_length' => 'Nomor telepon minimal 10 digit.',
            'max_length' => 'Nomor telepon maksimal 15 digit.',
        ],
        'nik' => [
            'numeric'      => 'NIK harus berupa angka.',
            'exact_length' => 'NIK harus tepat 16 digit.',
            'is_unique'    => 'NIK sudah digunakan user lain.',
        ],
        'gender' => [
            'required' => 'Jenis kelamin wajib dipilih.',
            'in_list'  => 'Pilihan jenis kelamin tidak valid.',
        ],
        'birth_date' => [
            'required'   => 'Tanggal lahir wajib diisi.',
            'valid_date' => 'Format tanggal lahir tidak valid.',
        ],
        'address' => [
            'required'   => 'Alamat wajib diisi.',
            'min_length' => 'Alamat minimal 5 karakter.',
        ],
        'avatar' => [
            'is_image' => 'File yang diupload bukan gambar.',
            'mime_in'  => 'Format gambar harus JPG, JPEG, atau PNG.',
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
