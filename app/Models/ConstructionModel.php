<?php

namespace App\Models;

use CodeIgniter\Model;

class ConstructionModel extends Model
{
    protected $table            = 'construction_requests'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'user_id', 'full_name', 'phone', 'address', 'land_area', 'building_area', 
        'survey_date', 'location_photo', 'voucher_code', 'status',
        'rab_file', 'rab_total', 'created_at', 'updated_at',
        'gambar1', 'gambar2', 'gambar3', 'gambar4', 'gambar5'
    ];

    protected $useTimestamps = true;
}
