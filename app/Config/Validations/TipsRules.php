<?php

namespace Config\Validations;

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
            'required' => 'Judul tips wajib diisi.',
        ],
        'content' => [
            'required' => 'Konten tips tidak boleh kosong.',
        ],
        'image' => [
            'uploaded' => 'Gambar sampul tips wajib diunggah.',
            'is_image' => 'File yang diunggah bukan gambar yang valid.',
            'max_size' => 'Ukuran gambar maksimal adalah 5MB.',
        ],
    ];
}
