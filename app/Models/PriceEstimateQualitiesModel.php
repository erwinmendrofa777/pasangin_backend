<?php
// FILE: app/Models/UserModel.php (KODE UNTUK MEMPERBAIKI SEMUANYA)

namespace App\Models;

use CodeIgniter\Model;

class PriceEstimateQualitiesModel extends Model
{
    protected $table            = 'price_estimate_qualities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $useSoftDeletes   = false;

    // Kolom yang boleh diisi
    protected $allowedFields    = [
        'concept_id',
        'label',
        'min_price',
        'max_price',
        'description'
    ];

    // Konfigurasi Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}

