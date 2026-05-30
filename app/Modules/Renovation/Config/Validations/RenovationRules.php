<?php

namespace App\Modules\Renovation\Config\Validations;

trait RenovationRules
{
    // --- VALIDASI RAB ---
    public array $renovationRabSave = [
        'renovation_id' => 'required|numeric',
        'item_name' => 'required|min_length[3]',
        'volume' => 'required|numeric',
        'unit' => 'required',
        'price' => 'required|numeric',
    ];

    public array $renovationRabSave_errors = [
        'renovation_id' => [
            'required' => 'ID Renovasi wajib diisi.',
            'numeric' => 'ID Renovasi harus berupa angka.',
        ],
        'item_name' => [
            'required' => 'Nama item RAB wajib diisi.',
            'min_length' => 'Nama item minimal 3 karakter.',
        ],
        'volume' => [
            'required' => 'Volume wajib diisi.',
            'numeric' => 'Volume harus berupa angka.',
        ],
        'unit' => [
            'required' => 'Satuan wajib diisi.',
        ],
        'price' => [
            'required' => 'Harga satuan wajib diisi.',
            'numeric' => 'Harga satuan harus berupa angka.',
        ],
    ];

    // --- VALIDASI TARGET & JADWAL ---
    public array $renovationTargetSave = [
        'renovation_id' => 'required|numeric',
        'target_name' => 'required|min_length[3]',
        'deadline' => 'required|valid_date',
    ];

    public array $renovationTargetSave_errors = [
        'renovation_id' => [
            'required' => 'ID Renovasi wajib diisi.',
            'numeric' => 'ID Renovasi harus berupa angka.',
        ],
        'target_name' => [
            'required' => 'Nama target wajib diisi.',
            'min_length' => 'Nama target minimal 3 karakter.',
        ],
        'deadline' => [
            'required' => 'Tenggat waktu wajib diisi.',
            'valid_date' => 'Format tenggat waktu tidak valid.',
        ],
    ];

    public array $renovationScheduleUpdate = [
        'renovation_id' => 'required|numeric',
        'start_date' => 'required|valid_date',
    ];

    public array $renovationScheduleUpdate_errors = [
        'renovation_id' => [
            'required' => 'ID Renovasi wajib diisi.',
            'numeric' => 'ID Renovasi harus berupa angka.',
        ],
        'start_date' => [
            'required' => 'Tanggal mulai wajib diisi.',
            'valid_date' => 'Format tanggal mulai tidak valid.',
        ],

    ];

    // --- VALIDASI INVOICE ---
    public array $renovationInvoiceCreate = [
        'renovation_id' => 'required|numeric',
        'description'   => 'required|min_length[3]',
        'amount'        => 'required|numeric',
        'due_date'      => 'required|valid_date',
    ];

    public array $renovationInvoiceCreate_errors = [
        'renovation_id' => [
            'required' => 'ID Renovasi wajib diisi.',
            'numeric'  => 'ID Renovasi harus berupa angka.',
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
            'required' => 'Tanggal jatuh tempo wajib diisi.',
            'valid_date' => 'Format tanggal jatuh tempo tidak valid.',
        ],
    ];

    // --- VALIDASI SURVEY & DESAIN ---
    public array $renovationSurveyAdd = [
        'user_admin_id' => 'required|numeric',
        'title'         => 'required|min_length[3]',
        'file_url'      => 'uploaded[file_url]|max_size[file_url,51200]|ext_in[file_url,pdf,jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv]',
    ];
 
    public array $renovationSurveyAdd_errors = [
        'user_admin_id' => [
            'required' => 'Admin pelaksana wajib dipilih.',
            'numeric'  => 'ID Admin tidak valid.',
        ],
        'title' => [
            'required' => 'Judul laporan survey wajib diisi.',
            'min_length' => 'Judul laporan minimal 3 karakter.',
        ],
        'file_url' => [
            'uploaded' => 'File laporan survey wajib diunggah.',
            'max_size' => 'Ukuran file maksimal 50MB.',
            'ext_in' => 'Format file harus PDF, Gambar (JPG, JPEG, PNG, WEBP), atau Video (MP4, MOV, AVI, WEBM, MKV).',
        ],
    ];
 
    public array $renovationDesignAdd = [
        'user_admin_id' => 'required|numeric',
        'title'         => 'required|min_length[3]',
        'file_url'      => 'uploaded[file_url]|max_size[file_url,51200]|ext_in[file_url,pdf,jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv]',
    ];
 
    public array $renovationDesignAdd_errors = [
        'user_admin_id' => [
            'required' => 'Admin perancang wajib dipilih.',
            'numeric'  => 'ID Admin tidak valid.',
        ],
        'title' => [
            'required' => 'Judul hasil desain wajib diisi.',
            'min_length' => 'Judul hasil desain minimal 3 karakter.',
        ],
        'file_url' => [
            'uploaded' => 'File hasil desain wajib diunggah.',
            'max_size' => 'Ukuran file maksimal 50MB.',
            'ext_in' => 'Format file harus PDF, Gambar (JPG, JPEG, PNG, WEBP), atau Video (MP4, MOV, AVI, WEBM, MKV).',
        ],
    ];

    // --- VALIDASI PROGRESS ---
    public array $renovationProgressAdd = [
        'progress_percent' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'description' => 'required|min_length[5]',
        'photo_url' => 'uploaded[photo_url]|max_size[photo_url,51200]|ext_in[photo_url,pdf,jpg,jpeg,png,webp,mp4,mov,avi,webm,mkv]',
    ];

    public array $renovationProgressAdd_errors = [
        'progress_percent' => [
            'required' => 'Persentase progres wajib diisi.',
            'numeric' => 'Persentase progres harus berupa angka.',
            'greater_than_equal_to' => 'Persentase minimal 0%.',
            'less_than_equal_to' => 'Persentase maksimal 100%.',
        ],
        'description' => [
            'required' => 'Deskripsi progres wajib diisi.',
            'min_length' => 'Deskripsi progres minimal 5 karakter.',
        ],
        'photo_url' => [
            'uploaded' => 'File media progres wajib diunggah.',
            'max_size' => 'Ukuran file media maksimal 50MB.',
            'ext_in' => 'Format file media harus berupa PDF, Gambar (JPG, JPEG, PNG, WEBP), atau Video (MP4, MOV, AVI, WEBM, MKV).',
        ],
    ];
}
