<?php

namespace App\Modules\PriceEstimate\Config\Validations;

trait PriceEstimateRules
{
    /**
     * Aturan validasi untuk tambah/update konsep estimasi
     */
    public array $estimateConceptSave = [
        'name' => 'required|min_length[3]|max_length[255]',
    ];

    public array $estimateConceptSave_errors = [
        'name' => [
            'required'   => 'Nama konsep wajib diisi.',
            'min_length' => 'Nama konsep minimal 3 karakter.',
            'max_length' => 'Nama konsep maksimal 255 karakter.',
        ],
    ];

    /**
     * Aturan validasi untuk tambah/update kualitas estimasi
     */
    public array $estimateQualitySave = [
        'concept_id'  => 'required|numeric',
        'label'       => 'required|max_length[255]',
        'description' => 'required|max_length[255]',
        'min_price'   => 'required|numeric',
        'max_price'   => 'required|numeric',
    ];

    public array $estimateQualitySave_errors = [
        'concept_id' => [
            'required' => 'Konsep estimasi wajib dipilih.',
            'numeric'  => 'ID Konsep harus berupa angka.',
        ],
        'label' => [
            'required'   => 'Label kualitas wajib diisi.',
            'max_length' => 'Label kualitas maksimal 255 karakter.',
        ],
        'description' => [
            'required'   => 'Deskripsi wajib diisi.',
            'max_length' => 'Deskripsi maksimal 255 karakter.',
        ],
        'min_price' => [
            'required' => 'Harga minimum wajib diisi.',
            'numeric'  => 'Harga minimum harus berupa angka.',
        ],
        'max_price' => [
            'required' => 'Harga maksimum wajib diisi.',
            'numeric'  => 'Harga maksimum harus berupa angka.',
        ],
    ];
}
