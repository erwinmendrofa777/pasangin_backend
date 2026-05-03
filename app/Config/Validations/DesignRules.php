<?php

namespace Config\Validations;

trait DesignRules
{
    /**
     * Aturan validasi untuk laporan survey desain
     */
    public array $designSurveyAdd = [
        'title'       => 'required|min_length[3]',
        'survey_file' => 'uploaded[survey_file]|max_size[survey_file,5120]|ext_in[survey_file,png,jpg,jpeg,pdf]',
    ];

    /**
     * Aturan validasi untuk upload hasil desain (revisi)
     */
    public array $designResultAdd = [
        'design_targets_id' => 'required|numeric',
        'design_name'       => 'required|min_length[3]',
        'design_file'       => 'uploaded[design_file]|max_size[design_file,5120]|ext_in[design_file,png,jpg,jpeg,pdf]',
    ];

    /**
     * Aturan validasi untuk tambah target desain
     */
    public array $designTargetCreate = [
        'target_name' => 'required|min_length[3]',
        'deadline'    => 'required|valid_date',
    ];

    /**
     * Aturan validasi untuk tambah tagihan desain
     */
    public array $designInvoiceAdd = [
        'invoice_name' => 'required|min_length[3]',
        'total_amount' => 'required|numeric',
        'due_date'     => 'required|valid_date',
    ];

    // Pesan error kustom
    public array $designSurveyAdd_errors = [
        'survey_file' => [
            'uploaded' => 'Anda harus memilih file laporan survey.',
            'max_size' => 'Ukuran file maksimal 5MB.',
        ],
    ];

    public array $designResultAdd_errors = [
        'design_file' => [
            'uploaded' => 'Anda harus memilih file hasil desain.',
            'max_size' => 'Ukuran file desain maksimal 5MB.',
        ],
    ];
}
