<?php

namespace App\Modules\Design\Config\Validations;

trait DesignRules
{
    /**
     * Aturan validasi untuk laporan survey desain
     */
    public array $designSurveyAdd = [
        'title' => 'required|min_length[3]',
        'user_admin_id' => 'required|numeric',
        'survey_file' => 'uploaded[survey_file]|max_size[survey_file,51200]|ext_in[survey_file,png,jpg,jpeg,webp,pdf,mp4,mov,avi,webm,mkv]',
    ];

    /**
     * Aturan validasi untuk upload hasil desain (revisi)
     */
    public array $designResultAdd = [
        'design_targets_id' => 'required|numeric',
        'user_admin_id' => 'required|numeric',
        'design_name' => 'required|min_length[3]',
        'design_file' => 'uploaded[design_file]|max_size[design_file,51200]|ext_in[design_file,png,jpg,jpeg,webp,pdf,mp4,mov,avi,webm,mkv]',
    ];

    /**
     * Aturan validasi untuk tambah target desain
     */
    public array $designTargetCreate = [
        'task_name' => 'required|min_length[3]',
        'start_week' => 'required|numeric',
        'end_week' => 'required|numeric',
    ];

    /**
     * Aturan validasi untuk tambah tagihan desain
     */
    public array $designInvoiceAdd = [
        'description' => 'required|min_length[3]',
        'amount' => 'required|numeric',
        'due_date' => 'required|valid_date',
    ];

    /**
     * Aturan validasi untuk update progress/jadwal desain
     */
    public array $designProgressUpdate = [
        'start_date' => 'required|valid_date',
        'target_date' => 'required|valid_date',
    ];

    public array $designProgressUpdate_errors = [
        'start_date' => [
            'required' => 'Tanggal mulai wajib diisi.',
            'valid_date' => 'Format tanggal mulai tidak valid.',
        ],
        'target_date' => [
            'required' => 'Tanggal target selesai wajib diisi.',
            'valid_date' => 'Format tanggal target selesai tidak valid.',
        ],
    ];

    // Pesan error kustom
    public array $designSurveyAdd_errors = [
        'title' => [
            'required' => 'Judul laporan survey wajib diisi.',
            'min_length' => 'Judul laporan survey minimal 3 karakter.',
        ],
        'user_admin_id' => [
            'required' => 'Admin (User) wajib dipilih.',
            'numeric' => 'ID Admin tidak valid.',
        ],
        'survey_file' => [
            'uploaded' => 'Anda harus memilih file laporan survey.',
            'max_size' => 'Ukuran file maksimal 50MB.',
            'ext_in' => 'Format file survey harus PDF, Gambar (PNG, JPG, JPEG, WEBP), atau Video (MP4, MOV, AVI, WEBM, MKV).',
        ],
    ];

    public array $designResultAdd_errors = [
        'design_targets_id' => [
            'required' => 'ID target desain wajib diisi.',
            'numeric' => 'ID target desain harus berupa angka.',
        ],
        'user_admin_id' => [
            'required' => 'Admin (User) wajib dipilih.',
            'numeric' => 'ID Admin tidak valid.',
        ],
        'design_name' => [
            'required' => 'Nama desain wajib diisi.',
            'min_length' => 'Nama desain minimal 3 karakter.',
        ],
        'design_file' => [
            'uploaded' => 'Anda harus memilih file hasil desain.',
            'max_size' => 'Ukuran file desain maksimal 50MB.',
            'ext_in' => 'Format file desain harus PDF, Gambar (PNG, JPG, JPEG, WEBP), atau Video (MP4, MOV, AVI, WEBM, MKV).',
        ],
    ];

    public array $designTargetCreate_errors = [
        'task_name' => [
            'required' => 'Nama tugas desain wajib diisi.',
            'min_length' => 'Nama tugas desain minimal 3 karakter.',
        ],
        'start_week' => [
            'required' => 'Hari mulai wajib diisi.',
            'numeric' => 'Hari mulai harus berupa angka.',
        ],
        'end_week' => [
            'required' => 'Hari selesai wajib diisi.',
            'numeric' => 'Hari selesai harus berupa angka.',
        ],
    ];

    public array $designInvoiceAdd_errors = [
        'description' => [
            'required' => 'Deskripsi tagihan wajib diisi.',
            'min_length' => 'Deskripsi tagihan minimal 3 karakter.',
        ],
        'amount' => [
            'required' => 'Total tagihan wajib diisi.',
            'numeric' => 'Total tagihan harus berupa angka.',
        ],
        'due_date' => [
            'required' => 'Tanggal jatuh tempo wajib diisi.',
            'valid_date' => 'Format tanggal jatuh tempo tidak valid.',
        ],
    ];
}
