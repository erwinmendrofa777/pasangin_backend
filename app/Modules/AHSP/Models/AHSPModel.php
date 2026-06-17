<?php

namespace App\Modules\AHSP\Models;

use CodeIgniter\Model;

class AHSPModel extends Model
{
    protected $table            = 'ahsp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['kode', 'uraian'];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
