<?php

namespace App\Modules\AHSP\Config\Validations;

trait AHSPRules
{
    public array $ahspSave = [
        'kode' => 'required|min_length[1]|max_length[20]|is_unique[ahsp.kode]',
        'uraian' => 'required|min_length[1]|max_length[255]',
    ];

    public array $ahspSave_errors = [
        'kode' => [
            'required' => 'Kode AHSP wajib diisi.',
            'min_length' => 'Kode AHSP minimal 1 karakter.',
            'max_length' => 'Kode AHSP maksimal 20 karakter.',
            'is_unique' => 'Kode AHSP tersebut sudah terdaftar.',
        ],
        'uraian' => [
            'required' => 'Uraian AHSP wajib diisi.',
            'min_length' => 'Uraian AHSP minimal 1 karakter.',
            'max_length' => 'Uraian AHSP maksimal 255 karakter.',
        ],
    ];

    public array $ahspUpdate = [
        'id' => 'required|integer',
        'kode' => 'required|min_length[1]|max_length[20]|is_unique[ahsp.kode,id,{id}]',
        'uraian' => 'required|min_length[1]|max_length[255]',
    ];

    public array $ahspUpdate_errors = [
        'kode' => [
            'required' => 'Kode AHSP wajib diisi.',
            'min_length' => 'Kode AHSP minimal 1 karakter.',
            'max_length' => 'Kode AHSP maksimal 20 karakter.',
            'is_unique' => 'Kode AHSP tersebut sudah digunakan oleh data lain.',
        ],
        'uraian' => [
            'required' => 'Uraian AHSP wajib diisi.',
            'min_length' => 'Uraian AHSP minimal 1 karakter.',
            'max_length' => 'Uraian AHSP maksimal 255 karakter.',
        ],
    ];
}
