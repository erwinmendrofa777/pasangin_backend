<?php

namespace App\Models;

use CodeIgniter\Model;

class DesignRequestModel extends Model
{
    protected $table            = 'design_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields = [
    'user_id', // <--- TAMBAHKAN INI (JANGAN SAMPAI LUPA KOMA)
    'full_name', 'phone_number',
    'land_area', 'building_area',
    'design_concept', 'other_concept_desc',
    'survey_date',
    'location_address', 'latitude', 'longitude',
    'survey_fee', 'voucher_code', 'discount_amount', 'total_payment', 'status',
    'gambar1', 'gambar2', 'gambar3', 'gambar4', 'gambar5'
];

    
    protected $useTimestamps    = true;
}
