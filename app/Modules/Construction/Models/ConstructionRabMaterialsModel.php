<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionRabMaterialsModel extends Model
{
    protected $table            = 'construction_rab_materials';
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
