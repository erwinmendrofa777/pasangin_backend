<?php

namespace App\Models;

use CodeIgniter\Model;

class AlamatUserModel extends Model
{
    protected $table            = 'alamat_user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Field yang boleh diisi
    protected $allowedFields    = ['id_user', 'alamat', 'latitude', 'longitude', 'label', 'is_active'];
}