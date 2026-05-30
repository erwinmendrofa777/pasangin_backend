<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionAddendumMaterialModel extends Model
{
    protected $table            = 'construction_addendum_materials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'addendum_id',
        'product_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tabel ini tidak memiliki kolom updated_at
}
