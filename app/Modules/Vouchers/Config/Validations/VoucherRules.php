<?php

namespace App\Modules\Vouchers\Config\Validations;

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
            'required'   => 'Kode voucher wajib diisi.',
            'is_unique'  => 'Kode voucher ini sudah digunakan, silakan buat kode lain.',
            'min_length' => 'Kode voucher minimal 3 karakter.',
            'max_length' => 'Kode voucher maksimal 50 karakter.',
        ],
        'name' => [
            'required'   => 'Nama voucher wajib diisi.',
            'min_length' => 'Nama voucher minimal 3 karakter.',
            'max_length' => 'Nama voucher maksimal 255 karakter.',
        ],
        'discount_nominal' => [
            'required'     => 'Nominal diskon wajib diisi.',
            'numeric'      => 'Nominal diskon harus berupa angka.',
            'greater_than' => 'Nominal diskon harus lebih besar dari 0.',
        ],
        'valid_until' => [
            'required'   => 'Tanggal kedaluwarsa wajib diisi.',
            'valid_date' => 'Format tanggal kedaluwarsa tidak valid.',
        ],
        'image' => [
            'uploaded' => 'Gambar voucher wajib diunggah.',
            'is_image' => 'File harus berupa gambar.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, atau WEBP.',
            'max_size' => 'Ukuran gambar maksimal adalah 2MB.',
        ],
    ];
}
