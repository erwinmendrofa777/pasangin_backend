<?php

namespace Config\Validations;

trait VoucherRules
{
    /**
     * Aturan validasi untuk tambah voucher baru
     */
    public array $voucherSave = [
        'code'             => 'required|is_unique[vouchers.code]|min_length[3]|max_length[50]',
        'name'             => 'required|min_length[3]|max_length[255]',
        'discount_nominal' => 'required|numeric|greater_than[0]',
        'valid_until'      => 'required|valid_date',
        'image'            => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
    ];

    public array $voucherSave_errors = [
        'code' => [
            'is_unique' => 'Kode voucher ini sudah digunakan, silakan buat kode lain.',
        ],
        'image' => [
            'uploaded' => 'Gambar voucher wajib diunggah.',
            'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
        ],
    ];
}
