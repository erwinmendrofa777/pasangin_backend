<?php

namespace App\Modules\Notifications\Config\Validations;

trait NotificationRules
{
    /**
     * Aturan validasi untuk pengiriman notifikasi broadcast
     */
    public array $notificationSend = [
        'target'  => 'required',
        'title'   => 'required|min_length[3]|max_length[255]',
        'message' => 'required|min_length[5]',
        'image'   => 'permit_empty|is_image[image]|max_size[image,2048]',
    ];

    public array $notificationSend_errors = [
        'target' => [
            'required' => 'Target penerima notifikasi harus dipilih.',
        ],
        'title' => [
            'required'   => 'Judul notifikasi tidak boleh kosong.',
            'min_length' => 'Judul notifikasi minimal 3 karakter.',
            'max_length' => 'Judul notifikasi maksimal 255 karakter.',
        ],
        'message' => [
            'required'   => 'Isi pesan tidak boleh kosong.',
            'min_length' => 'Isi pesan minimal 5 karakter.',
        ],
        'image' => [
            'is_image' => 'File yang diunggah harus berupa gambar.',
            'max_size' => 'Ukuran gambar maksimal 2MB.',
        ]
    ];
}
