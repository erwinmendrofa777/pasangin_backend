<?php

namespace App\Modules\AHSP\Models;

use CodeIgniter\Model;

class AHSPBahanModel extends Model
{
    protected $table            = 'ahsp_bahan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['ahsp_id', 'kode', 'uraian', 'satuan', 'koefisien'];

    protected $useTimestamps = false;
}
