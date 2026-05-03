<?php

namespace Config\Validations;

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

    // --- VALIDASI TARGET & JADWAL ---
    public array $constructionTargetSave = [
        'construction_id' => 'required|numeric',
        'target_name'     => 'required|min_length[3]',
        'deadline'        => 'required|valid_date',
    ];

    public array $constructionScheduleUpdate = [
        'construction_id' => 'required|numeric',
        'start_date'      => 'required|valid_date',
        'end_date'        => 'required|valid_date',
    ];

    // --- VALIDASI INVOICE ---
    public array $constructionInvoiceCreate = [
        'construction_id' => 'required|numeric',
        'invoice_name'    => 'required|min_length[3]',
        'total_amount'    => 'required|numeric',
        'due_date'        => 'required|valid_date',
    ];

    // --- VALIDASI SURVEY & DESAIN ---
    public array $constructionSurveyUpload = [
        'id'          => 'required|numeric',
        'survey_date' => 'required|valid_date',
        'survey_file' => 'uploaded[survey_file]|max_size[survey_file,5120]|ext_in[survey_file,pdf,jpg,jpeg,png]',
    ];

    public array $constructionDesignUpload = [
        'design_title' => 'required|min_length[3]',
        'design_2d'    => 'uploaded[design_2d]|max_size[design_2d,5120]|ext_in[design_2d,pdf,jpg,jpeg,png]',
    ];

    // --- VALIDASI PROGRESS ---
    public array $constructionProgressAdd = [
        'construction_id' => 'required|numeric',
        'progress_percent'=> 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'description'     => 'required|min_length[5]',
        'photo'           => 'uploaded[photo]|is_image[photo]|max_size[photo,2048]',
    ];

    // --- PESAN ERROR KUSTOM (OPSIONAL) ---
    public array $constructionProgressAdd_errors = [
        'progress_percent' => [
            'greater_than_equal_to' => 'Persentase minimal 0%.',
            'less_than_equal_to'    => 'Persentase maksimal 100%.',
        ],
        'photo' => [
            'uploaded' => 'Harap unggah foto progres lapangan.',
        ]
    ];
}
