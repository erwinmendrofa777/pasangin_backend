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

    // --- VALIDASI SURVEY & DESAIN ---
    public array $constructionSurveyUpload = [
        'id'            => 'required|numeric',
        'user_admin_id' => 'required|numeric',
        'survey_title'  => 'required|min_length[3]',
        'survey_files'  => 'uploaded[survey_files]|max_size[survey_files,51200]|ext_in[survey_files,pdf,jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv]',
    ];

    public array $constructionSurveyUpload_errors = [
        'id' => [
            'required' => 'ID wajib diisi.',
            'numeric'  => 'ID harus berupa angka.',
        ],
        'user_admin_id' => [
            'required' => 'Admin pelaksana wajib dipilih.',
            'numeric'  => 'ID Admin tidak valid.',
        ],
        'survey_title' => [
            'required'   => 'Judul survey wajib diisi.',
            'min_length' => 'Judul survey minimal 3 karakter.',
        ],
        'survey_files' => [
            'uploaded' => 'File survey wajib diunggah.',
            'max_size' => 'Ukuran file survey maksimal 50MB.',
            'ext_in'   => 'Format file survey harus berupa PDF, Gambar (JPG, JPEG, PNG, WEBP), atau Video (MP4, MOV, AVI, WEBM, MKV).',
        ],
    ];

    public array $constructionDesignUpload = [
        'id'            => 'required|numeric',
        'user_admin_id' => 'required|numeric',
        'design_title'  => 'required|min_length[3]',
        'design_2d'     => 'uploaded[design_2d]|max_size[design_2d,51200]|ext_in[design_2d,pdf,jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv]',
    ];

    public array $constructionDesignUpload_errors = [
        'id' => [
            'required' => 'ID wajib diisi.',
            'numeric'  => 'ID harus berupa angka.',
        ],
        'user_admin_id' => [
            'required' => 'Admin perancang wajib dipilih.',
            'numeric'  => 'ID Admin tidak valid.',
        ],
        'design_title' => [
            'required'   => 'Judul desain wajib diisi.',
            'min_length' => 'Judul desain minimal 3 karakter.',
        ],
        'design_2d' => [
            'uploaded' => 'File desain wajib diunggah.',
            'max_size' => 'Ukuran file desain maksimal 50MB.',
            'ext_in'   => 'Format file desain harus berupa PDF, Gambar (JPG, JPEG, PNG, WEBP), atau Video (MP4, MOV, AVI, WEBM, MKV).',
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
