<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table= 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Field yang boleh diisi
    protected $allowedFields    = ['supplier_id', 'name'];

    // Timestamps otomatis
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}