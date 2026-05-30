<?php

namespace App\Modules\Products\Config\Validations;

trait ProductRules
{
    /**
     * Aturan validasi untuk tambah produk baru
     */
    public array $productSave = [
        'category_id' => 'required|numeric',
        'name'        => 'required|min_length[3]|max_length[255]',
        'price'       => 'required|numeric',
        'stock'       => 'required|numeric',
        'unit'        => 'permit_empty|max_length[50]',
        'photo'       => 'permit_empty|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]|max_size[photo,2048]',
    ];

    public array $productSave_errors = [
        'category_id' => [
            'required' => 'Kategori produk wajib dipilih.',
            'numeric'  => 'Kategori produk harus berupa angka.'
        ],
        'name' => [
            'required'   => 'Nama produk wajib diisi.',
            'min_length' => 'Nama produk minimal 3 karakter.',
            'max_length' => 'Nama produk maksimal 255 karakter.'
        ],
        'price' => [
            'required' => 'Harga produk wajib diisi.',
            'numeric'  => 'Harga produk harus berupa angka.'
        ],
        'stock' => [
            'required' => 'Stok produk wajib diisi.',
            'numeric'  => 'Stok produk harus berupa angka.'
        ],
        'unit' => [
            'max_length' => 'Satuan produk maksimal 50 karakter.'
        ],
        'photo' => [
            'is_image' => 'File yang diunggah harus berupa gambar.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, atau WEBP.',
            'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
        ],
    ];

    /**
     * Aturan validasi untuk update produk
     */
    public array $productUpdate = [
        'id'          => 'permit_empty|numeric',
        'category_id' => 'permit_empty|numeric',
        'name'        => 'permit_empty|min_length[3]|max_length[255]',
        'price'       => 'permit_empty|numeric',
        'stock'       => 'permit_empty|numeric',
        'photo'       => 'permit_empty|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]|max_size[photo,2048]',
    ];

    public array $productUpdate_errors = [
        'id' => [
            'numeric' => 'ID produk harus berupa angka.'
        ],
        'category_id' => [
            'numeric' => 'Kategori produk harus berupa angka.'
        ],
        'name' => [
            'min_length' => 'Nama produk minimal 3 karakter.',
            'max_length' => 'Nama produk maksimal 255 karakter.'
        ],
        'price' => [
            'numeric' => 'Harga produk harus berupa angka.'
        ],
        'stock' => [
            'numeric' => 'Stok produk harus berupa angka.'
        ],
        'photo' => [
            'is_image' => 'File yang diunggah harus berupa gambar.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, atau WEBP.',
            'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
        ],
    ];

    /**
     * Aturan validasi untuk update status produk oleh admin
     */
    public array $productUpdateStatus = [
        'status' => 'required|in_list[aktif,tidak aktif,habis]',
    ];

    public array $productUpdateStatus_errors = [
        'status' => [
            'required' => 'Status produk wajib dipilih.',
            'in_list'  => 'Status produk tidak valid. Gunakan: aktif, tidak aktif, atau habis.'
        ]
    ];
}
