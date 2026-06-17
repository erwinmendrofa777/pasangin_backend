<?php

namespace App\Modules\AHSP\Models;

use CodeIgniter\Model;

class AHSPTenagaKerjaModel extends Model
{
    protected $table            = 'ahsp_tenaga_kerja';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['ahsp_id', 'kode', 'uraian', 'satuan', 'koefisien', 'harga_satuan'];

    protected $useTimestamps = false;
}
