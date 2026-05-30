<?php

namespace App\Modules\Supplier\Config\Validations;

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
        'name' => [
            'required'   => 'Nama supplier wajib diisi.',
            'min_length' => 'Nama supplier minimal 3 karakter.',
            'max_length' => 'Nama supplier maksimal 255 karakter.',
        ],
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique'   => 'Email ini sudah terdaftar sebagai supplier.',
        ],
        'password' => [
            'required'   => 'Password wajib diisi.',
            'min_length' => 'Password minimal 6 karakter.',
        ],
        'contact_person' => [
            'required'   => 'Nama narahubung (contact person) wajib diisi.',
            'max_length' => 'Nama narahubung maksimal 255 karakter.',
        ],
        'phone' => [
            'required'   => 'Nomor telepon wajib diisi.',
            'numeric'    => 'Nomor telepon hanya boleh angka.',
            'min_length' => 'Nomor telepon minimal 9 digit.',
            'max_length' => 'Nomor telepon maksimal 15 digit.',
        ],
        'address' => [
            'required' => 'Alamat wajib diisi.',
        ],
        'district' => [
            'required'   => 'Kecamatan wajib diisi.',
            'max_length' => 'Kecamatan maksimal 255 karakter.',
        ],
        'city' => [
            'required'   => 'Kota/Kabupaten wajib diisi.',
            'max_length' => 'Kota/Kabupaten maksimal 255 karakter.',
        ],
        'province' => [
            'required'   => 'Provinsi wajib diisi.',
            'max_length' => 'Provinsi maksimal 255 karakter.',
        ],
        'logo_url' => [
            'is_image' => 'Logo harus berupa gambar.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, GIF, atau WEBP.',
            'max_size' => 'Ukuran logo maksimal 2MB.',
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
        'id' => [
            'numeric' => 'ID supplier harus berupa angka.',
        ],
        'name' => [
            'required'   => 'Nama supplier tidak boleh kosong.',
            'min_length' => 'Nama supplier minimal 3 karakter.',
            'max_length' => 'Nama supplier maksimal 255 karakter.',
        ],
        'contact_person' => [
            'required'   => 'Nama narahubung wajib diisi.',
            'max_length' => 'Nama narahubung maksimal 255 karakter.',
        ],
        'phone' => [
            'required'   => 'Nomor telepon wajib diisi.',
            'numeric'    => 'Nomor telepon hanya boleh angka.',
            'min_length' => 'Nomor telepon minimal 9 digit.',
            'max_length' => 'Nomor telepon maksimal 15 digit.',
        ],
        'address' => [
            'required' => 'Alamat wajib diisi.',
        ],
        'logo_url' => [
            'is_image' => 'Logo harus berupa gambar.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, GIF, atau WEBP.',
            'max_size' => 'Ukuran logo maksimal 2MB.',
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
            'required' => 'Status wajib dipilih.',
            'in_list'  => 'Status tidak valid. Gunakan: approved, rejected, banned, atau pending.',
        ],
    ];
}
