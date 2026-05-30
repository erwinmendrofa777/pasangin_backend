<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionJobsModel extends Model
{
    protected $table            = 'construction_jobs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'construction_id',
        'detail_pekerjaan',
        'detail_lokasi',
        'tempat_tinggal',
        'tanggal_mulai',
        'tanggal_akhir',
        'upah_per_hari',
        'latitude',
        'longitude'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
