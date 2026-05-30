<?php

namespace App\Modules\Tips\Config\Validations;

trait TipsRules
{
    /**
     * Aturan validasi untuk tambah tips baru
     */
    public array $tipsSave = [
        'title'   => 'required|min_length[3]|max_length[255]',
        'content' => 'required',
        'image'   => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,5120]',
    ];

    public array $tipsSave_errors = [
        'title' => [
            'required'   => 'Judul tips wajib diisi.',
            'min_length' => 'Judul tips minimal 3 karakter.',
            'max_length' => 'Judul tips maksimal 255 karakter.',
        ],
        'content' => [
            'required' => 'Konten tips tidak boleh kosong.',
        ],
        'image' => [
            'uploaded' => 'Gambar sampul tips wajib diunggah.',
            'is_image' => 'File yang diunggah bukan gambar yang valid.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, atau WEBP.',
            'max_size' => 'Ukuran gambar maksimal adalah 5MB.',
        ],
    ];

    /**
     * Aturan validasi untuk edit tips
     */
    public array $tipsUpdate = [
        'title'   => 'required|min_length[3]|max_length[255]',
        'content' => 'required',
        'image'   => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,5120]',
    ];

    public array $tipsUpdate_errors = [
        'title' => [
            'required'   => 'Judul tips wajib diisi.',
            'min_length' => 'Judul tips minimal 3 karakter.',
            'max_length' => 'Judul tips maksimal 255 karakter.',
        ],
        'content' => [
            'required' => 'Konten tips tidak boleh kosong.',
        ],
        'image' => [
            'is_image' => 'File yang diunggah bukan gambar yang valid.',
            'mime_in'  => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, atau WEBP.',
            'max_size' => 'Ukuran gambar maksimal adalah 5MB.',
        ],
    ];
}
