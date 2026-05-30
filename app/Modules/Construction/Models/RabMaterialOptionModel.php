<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class RabMaterialOptionModel extends Model
{
    protected $table            = 'rab_material_options';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'rab_id',
        'product_id',
        'is_default'
    ];

    // Dates
    protected $useTimestamps = false;
}
