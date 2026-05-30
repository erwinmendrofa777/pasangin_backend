<?php

namespace App\Modules\Renovation\Models;

use CodeIgniter\Model;

class RenovationRabMaterialsModel extends Model
{
    protected $table            = 'renovation_rab_materials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'rab_id',
        'product_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
