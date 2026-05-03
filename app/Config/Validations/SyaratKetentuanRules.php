<?php

namespace Config\Validations;

trait SyaratKetentuanRules
{
    /**
     * Aturan validasi untuk tambah/update syarat & ketentuan
     */
    public array $syaratKetentuanSave = [
        'title'      => 'required|min_length[3]|max_length[255]',
        'target_app' => 'required|in_list[CLIENT,TUKANG,SUPPLIER,PROYEK]',
        'content'    => 'required',
    ];

    public array $syaratKetentuanSave_errors = [
        'title' => [
            'required' => 'Judul syarat & ketentuan wajib diisi.',
        ],
        'target_app' => [
            'required' => 'Target aplikasi harus dipilih.',
            'in_list'  => 'Target aplikasi tidak valid.',
        ],
        'content' => [
            'required' => 'Isi konten tidak boleh kosong.',
        ],
    ];
}
