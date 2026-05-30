<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;

class PromoModel extends Model
{
    protected $table            = 'promos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'supplier_id',
        'title',
        'description',
        'discount_type', 
        'discount_value',
        'promo_code',
        'start_date',
        'end_date',
        'status',
        'photo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}