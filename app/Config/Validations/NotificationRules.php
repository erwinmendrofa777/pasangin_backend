<?php

namespace Config\Validations;

trait NotificationRules
{
    /**
     * Aturan validasi untuk pengiriman notifikasi broadcast
     */
    public array $notificationSend = [
        'target'  => 'required',
        'title'   => 'required|min_length[3]|max_length[255]',
        'message' => 'required|min_length[5]',
    ];

    public array $notificationSend_errors = [
        'target' => [
            'required' => 'Target penerima notifikasi harus dipilih.',
        ],
        'title' => [
            'required' => 'Judul notifikasi tidak boleh kosong.',
            'min_length' => 'Judul notifikasi minimal 3 karakter.',
        ],
        'message' => [
            'required' => 'Isi pesan tidak boleh kosong.',
            'min_length' => 'Isi pesan minimal 5 karakter.',
        ],
    ];
}
