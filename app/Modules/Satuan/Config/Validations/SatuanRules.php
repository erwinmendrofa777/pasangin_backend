<?php

namespace App\Modules\Satuan\Config\Validations;

trait SatuanRules
{
    public array $satuanSave = [
        'nama_satuan' => 'required|min_length[1]|max_length[100]|is_unique[satuan.nama_satuan]',
    ];

    public array $satuanSave_errors = [
        'nama_satuan' => [
            'required'   => 'Nama satuan wajib diisi.',
            'min_length' => 'Nama satuan minimal 1 karakter.',
            'max_length' => 'Nama satuan maksimal 100 karakter.',
            'is_unique'  => 'Nama satuan tersebut sudah terdaftar.',
        ],
    ];

    public array $satuanUpdate = [
        'nama_satuan' => 'required|min_length[1]|max_length[100]|is_unique[satuan.nama_satuan,id,{id}]',
    ];

    public array $satuanUpdate_errors = [
        'nama_satuan' => [
            'required'   => 'Nama satuan wajib diisi.',
            'min_length' => 'Nama satuan minimal 1 karakter.',
            'max_length' => 'Nama satuan maksimal 100 karakter.',
            'is_unique'  => 'Nama satuan tersebut sudah digunakan oleh data lain.',
        ],
    ];
}
