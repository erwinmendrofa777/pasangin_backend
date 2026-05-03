<?php

namespace Config\Validations;

trait BannerRules
{
    /**
     * Aturan validasi untuk tambah banner baru
     */
    public array $bannerSave = [
        'image' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
    ];

    public array $bannerSave_errors = [
        'image' => [
            'uploaded' => 'Pilih gambar banner terlebih dahulu.',
            'is_image' => 'File yang Anda pilih bukan gambar.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, atau PNG.',
            'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
        ],
    ];

    /**
     * Aturan validasi untuk tambah banner supplier
     */
    public array $supplierBannerSave = [
        'id_supplier' => 'required|numeric',
        'image'       => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
    ];

    /**
     * Aturan validasi untuk update banner supplier
     */
    public array $supplierBannerUpdate = [
        'id_supplier' => 'required|numeric',
        'image'       => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
    ];

    public array $supplierBannerSave_errors = [
        'id_supplier' => [
            'required' => 'Supplier harus dipilih.',
        ],
        'image' => [
            'uploaded' => 'Pilih gambar banner terlebih dahulu.',
        ]
    ];
}
