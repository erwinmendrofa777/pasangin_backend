<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;

class SupplierOngkirModel extends Model
{

    protected $table = 'supplier_ongkir';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'id_suppliers',
        'ongkir',
        'min_distance',
        'max_distance',
    ];
}