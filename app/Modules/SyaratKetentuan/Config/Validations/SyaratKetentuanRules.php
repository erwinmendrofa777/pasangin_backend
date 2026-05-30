<?php

namespace App\Modules\SyaratKetentuan\Config\Validations;

trait SyaratKetentuanRules
{
    /**
     * Aturan validasi untuk tambah/update syarat & ketentuan
     */
    public array $syaratKetentuanSave = [
        'title' => 'required|min_length[3]|max_length[255]',
        'target_app' => 'required|in_list[CLIENT,TUKANG,SUPPLIER,PROYEK]',
        'description' => 'required',
    ];

    public array $syaratKetentuanSave_errors = [
        'title' => [
            'required' => 'Judul syarat & ketentuan wajib diisi.',
            'min_length' => 'Judul syarat & ketentuan minimal 3 karakter.',
            'max_length' => 'Judul syarat & ketentuan maksimal 255 karakter.',
        ],
        'target_app' => [
            'required' => 'Target aplikasi harus dipilih.',
            'in_list' => 'Target aplikasi tidak valid.',
        ],
        'description' => [
            'required' => 'Isi konten tidak boleh kosong.',
        ],
    ];
}
