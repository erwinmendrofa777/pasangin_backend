<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionRabsModel extends Model
{
    protected $table            = 'rabs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'construction_id',
        'design_request_id',
        'roman_number',
        'group_name',
        'sub_group_name',
        'section_group',
        'section_name',
        'ahsp_id',
        'volume',
        'unit',
        'selected_material_id',
        'current_unit_price',
        'total_price',
        'is_locked'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
