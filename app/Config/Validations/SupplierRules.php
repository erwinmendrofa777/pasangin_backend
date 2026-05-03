<?php

namespace Config\Validations;

trait SupplierRules
{
    /**
     * Aturan validasi untuk tambah supplier baru
     */
    public array $supplierSave = [
        'name'           => 'required|min_length[3]|max_length[255]',
        'email'          => 'required|valid_email|is_unique[suppliers.email]',
        'password'       => 'required|min_length[6]',
        'contact_person' => 'required|max_length[255]',
        'phone'          => 'required|numeric|min_length[9]|max_length[15]',
        'address'        => 'required',
        'district'       => 'required|max_length[255]',
        'city'           => 'required|max_length[255]',
        'province'       => 'required|max_length[255]',
        'logo_url'       => 'permit_empty|is_image[logo_url]|mime_in[logo_url,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[logo_url,2048]',
    ];

    public array $supplierSave_errors = [
        'email' => [
            'is_unique' => 'Email ini sudah terdaftar sebagai supplier.',
        ],
        'logo_url' => [
            'is_image' => 'Logo harus berupa gambar.',
        ]
    ];

    /**
     * Aturan validasi untuk update supplier
     */
    public array $supplierUpdate = [
        'id'             => 'permit_empty|numeric',
        'name'           => 'required|min_length[3]|max_length[255]',
        'contact_person' => 'required|max_length[255]',
        'phone'          => 'required|numeric|min_length[9]|max_length[15]',
        'address'        => 'required',
        'logo_url'       => 'permit_empty|is_image[logo_url]|mime_in[logo_url,image/jpg,image/jpeg,image/gif,image/png,image/webp]|max_size[logo_url,2048]',
    ];

    public array $supplierUpdate_errors = [
        'name' => [
            'required' => 'Nama supplier tidak boleh kosong.',
        ],
        'phone' => [
            'numeric' => 'Nomor telepon hanya boleh angka.',
        ]
    ];

    /**
     * Aturan validasi untuk update status supplier oleh admin
     */
    public array $supplierUpdateStatus = [
        'status' => 'required|in_list[approved,rejected,banned,pending]',
    ];

    public array $supplierUpdateStatus_errors = [
        'status' => [
            'in_list' => 'Status tidak valid. Gunakan: approved, rejected, banned, atau pending.',
        ],
    ];
}
