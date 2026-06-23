<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionModel extends Model
{
    protected $table            = 'construction_requests'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'user_id',
        'design_request_id',
        'full_name',
        'phone',
        'land_area',
        'building_area',
        'survey_date',
        'address',
        'latitude',
        'longitude',
        'location_photo',
        'voucher_code',
        'survey_cost',
        'discount_amount',
        'total_payment',
        'status',
        'start_date',
        'week',
        'workday',
        'survey_notes',
        'survey_file',
        'design_file',
        'rab_file',
        'rab_total',
        'gambar1',
        'gambar2',
        'gambar3',
        'gambar4',
        'gambar5'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
