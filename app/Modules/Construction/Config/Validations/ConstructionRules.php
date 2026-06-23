<?php

namespace App\Modules\Construction\Config\Validations;

trait ConstructionRules
{
    // --- VALIDASI RAB & ADDENDUM ---
    public array $constructionRabSave = [
        'construction_id' => 'required|numeric',
        'item_name'       => 'required|min_length[3]',
        'volume'          => 'required|numeric',
        'unit'            => 'required',
        'price'           => 'required|numeric',
    ];

    public array $constructionRabSave_errors = [
        'construction_id' => [
            'required' => 'ID Konstruksi wajib diisi.',
            'numeric'  => 'ID Konstruksi harus berupa angka.',
        ],
        'item_name' => [
            'required'   => 'Nama item wajib diisi.',
            'min_length' => 'Nama item minimal 3 karakter.',
        ],
        'volume' => [
            'required' => 'Volume wajib diisi.',
            'numeric'  => 'Volume harus berupa angka.',
        ],
        'unit' => [
            'required' => 'Satuan wajib diisi.',
        ],
        'price' => [
            'required' => 'Harga wajib diisi.',
            'numeric'  => 'Harga harus berupa angka.',
        ],
    ];

    // --- VALIDASI TARGET & JADWAL ---
    public array $constructionTargetSave = [
        'construction_id' => 'required|numeric',
        'target_name'     => 'required|min_length[3]',
        'deadline'        => 'required|valid_date',
    ];

    public array $constructionTargetSave_errors = [
        'construction_id' => [
            'required' => 'ID Konstruksi wajib diisi.',
            'numeric'  => 'ID Konstruksi harus berupa angka.',
        ],
        'target_name' => [
            'required'   => 'Nama target wajib diisi.',
            'min_length' => 'Nama target minimal 3 karakter.',
        ],
        'deadline' => [
            'required'   => 'Tenggat waktu wajib diisi.',
            'valid_date' => 'Format tanggal tenggat waktu tidak valid.',
        ],
    ];

    public array $constructionScheduleUpdate = [
        'construction_id' => 'required|numeric',
        'week'            => 'required|numeric',
        'workday'         => 'required|numeric',
        'start_date'      => 'required|valid_date',
    ];

    public array $constructionScheduleUpdate_errors = [
        'construction_id' => [
            'required' => 'ID Konstruksi wajib diisi.',
            'numeric'  => 'ID Konstruksi harus berupa angka.',
        ],
        'week' => [
            'required' => 'Jumlah minggu wajib diisi.',
            'numeric'  => 'Jumlah minggu harus berupa angka.',
        ],
        'workday' => [
            'required' => 'Hari kerja per minggu wajib diisi.',
            'numeric'  => 'Hari kerja harus berupa angka.',
        ],
        'start_date' => [
            'required'   => 'Tanggal mulai wajib diisi.',
            'valid_date' => 'Format tanggal mulai tidak valid.',
        ],
    ];

    // --- VALIDASI INVOICE ---
    public array $constructionInvoiceCreate = [
        'construction_id' => 'required|numeric',
        'rab_id'          => 'permit_empty|numeric',
        'description'     => 'required|min_length[3]',
        'amount'          => 'required|numeric',
        'due_date'        => 'required|valid_date',
    ];

    public array $constructionInvoiceCreate_errors = [
        'construction_id' => [
            'required' => 'ID Konstruksi wajib diisi.',
            'numeric'  => 'ID Konstruksi harus berupa angka.',
        ],
        'rab_id' => [
            'numeric'  => 'ID RAB harus berupa angka.',
        ],
        'description' => [
            'required'   => 'Keterangan tagihan wajib diisi.',
            'min_length' => 'Keterangan tagihan minimal 3 karakter.',
        ],
        'amount' => [
            'required' => 'Nominal tagihan wajib diisi.',
            'numeric'  => 'Nominal tagihan harus berupa angka.',
        ],
        'due_date' => [
            'required'   => 'Tanggal jatuh tempo wajib diisi.',
            'valid_date' => 'Format tanggal jatuh tempo tidak valid.',
        ],
    ];



    // --- VALIDASI PROGRESS ---
    public array $constructionProgressAdd = [
        'construction_id' => 'required|numeric',
        'target_id'       => 'required|numeric',
        'volume'          => 'required|numeric|greater_than_equal_to[0]',
        'description'     => 'required|min_length[5]',
        'photo'           => 'uploaded[photo]|is_image[photo]|max_size[photo,2048]',
    ];

    // --- PESAN ERROR KUSTOM (OPSIONAL) ---
    public array $constructionProgressAdd_errors = [
        'construction_id' => [
            'required' => 'ID Konstruksi wajib diisi.',
            'numeric'  => 'ID Konstruksi harus berupa angka.',
        ],
        'target_id' => [
            'required' => 'Target pekerjaan wajib dipilih.',
            'numeric'  => 'ID Target harus berupa angka.',
        ],
        'volume' => [
            'required'              => 'Volume progres wajib diisi.',
            'numeric'               => 'Volume progres harus berupa angka.',
            'greater_than_equal_to' => 'Volume minimal 0.',
        ],
        'description' => [
            'required'   => 'Deskripsi wajib diisi.',
            'min_length' => 'Deskripsi minimal 5 karakter.',
        ],
        'photo' => [
            'uploaded' => 'Harap unggah foto progres lapangan.',
            'is_image' => 'File yang diunggah harus berupa gambar.',
            'max_size' => 'Ukuran foto maksimal 2MB.',
        ]
    ];
}
