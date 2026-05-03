<?php

namespace Config\Validations;

trait RenovationRules
{
    // --- VALIDASI RAB ---
    public array $renovationRabSave = [
        'renovation_id' => 'required|numeric',
        'item_name'     => 'required|min_length[3]',
        'volume'        => 'required|numeric',
        'unit'          => 'required',
        'price'         => 'required|numeric',
    ];

    // --- VALIDASI TARGET & JADWAL ---
    public array $renovationTargetSave = [
        'renovation_id' => 'required|numeric',
        'target_name'   => 'required|min_length[3]',
        'deadline'      => 'required|valid_date',
    ];

    public array $renovationScheduleUpdate = [
        'renovation_id' => 'required|numeric',
        'start_date'    => 'required|valid_date',
        'end_date'      => 'required|valid_date',
    ];

    // --- VALIDASI INVOICE ---
    public array $renovationInvoiceCreate = [
        'renovation_id' => 'required|numeric',
        'invoice_name'  => 'required|min_length[3]',
        'total_amount'  => 'required|numeric',
        'due_date'      => 'required|valid_date',
    ];

    // --- VALIDASI SURVEY & DESAIN ---
    public array $renovationSurveyAdd = [
        'title'    => 'required|min_length[3]',
        'file_url' => 'uploaded[file_url]|max_size[file_url,5120]|ext_in[file_url,pdf,jpg,jpeg,png]',
    ];

    public array $renovationDesignAdd = [
        'title'    => 'required|min_length[3]',
        'file_url' => 'uploaded[file_url]|max_size[file_url,5120]|ext_in[file_url,pdf,jpg,jpeg,png]',
    ];

    // --- VALIDASI PROGRESS ---
    public array $renovationProgressAdd = [
        'progress_percent' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'description'      => 'required|min_length[5]',
        'photo_url'        => 'uploaded[photo_url]|is_image[photo_url]|max_size[photo_url,2048]',
    ];

    public array $renovationProgressAdd_errors = [
        'progress_percent' => [
            'greater_than_equal_to' => 'Persentase minimal 0%.',
            'less_than_equal_to'    => 'Persentase maksimal 100%.',
        ],
    ];
}
