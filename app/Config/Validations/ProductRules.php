<?php

namespace Config\Validations;

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
        'category_id' => ['required' => 'Kategori produk harus dipilih.'],
        'name'        => ['min_length' => 'Nama produk minimal 3 karakter.'],
        'price'       => ['numeric' => 'Harga harus berupa angka.'],
        'photo'       => ['is_image' => 'File yang diunggah harus gambar.'],
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

    /**
     * Aturan validasi untuk update status produk oleh admin
     */
    public array $productUpdateStatus = [
        'status' => 'required|in_list[aktif,tidak aktif,habis]',
    ];
}
