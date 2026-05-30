<?php

namespace App\Modules\Supplier\Config\Validations;

trait PromoRules
{
    /**
     * Aturan validasi untuk pembaruan status promo oleh admin
     */
    public array $promoUpdateStatus = [
        'status' => 'required|in_list[active,inactive]',
    ];

    public array $promoUpdateStatus_errors = [
        'status' => [
            'required' => 'Status promo wajib dipilih.',
            'in_list'  => 'Status promo tidak valid (harus active atau inactive).',
        ],
    ];

    /**
     * Aturan validasi untuk tambah promo (API)
     */
    public array $promoSave = [
        'title'          => 'required|min_length[3]|max_length[255]',
        'description'    => 'permit_empty|max_length[500]',
        'discount_type'  => 'required|in_list[percentage,fixed]',
        'discount_value' => 'required|numeric',
        'promo_code'     => 'permit_empty|alpha_numeric_space|max_length[50]',
        'start_date'     => 'permit_empty|valid_date',
        'end_date'       => 'permit_empty|valid_date',
        'photo'          => 'permit_empty|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]|max_size[photo,2048]',
    ];

    public array $promoSave_errors = [
        'title' => [
            'required'   => 'Judul promo wajib diisi.',
            'min_length' => 'Judul promo minimal 3 karakter.',
            'max_length' => 'Judul promo maksimal 255 karakter.',
        ],
        'description' => [
            'max_length' => 'Deskripsi promo maksimal 500 karakter.',
        ],
        'discount_type' => [
            'required' => 'Tipe diskon wajib dipilih.',
            'in_list'  => 'Tipe diskon harus percentage atau fixed.',
        ],
        'discount_value' => [
            'required' => 'Nilai diskon wajib diisi.',
            'numeric'  => 'Nilai diskon harus berupa angka.',
        ],
        'promo_code' => [
            'alpha_numeric_space' => 'Kode promo hanya boleh berisi huruf dan angka.',
            'max_length'          => 'Kode promo maksimal 50 karakter.',
        ],
        'start_date' => [
            'valid_date' => 'Format tanggal mulai tidak valid.',
        ],
        'end_date' => [
            'valid_date' => 'Format tanggal berakhir tidak valid.',
        ],
        'photo' => [
            'is_image' => 'File yang diunggah harus berupa gambar.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, atau WEBP.',
            'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
        ],
    ];
}
