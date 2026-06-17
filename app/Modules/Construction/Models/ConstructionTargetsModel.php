<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionTargetsModel extends Model
{
    protected $table            = 'construction_targets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_job_applications',
        'id_construction_rabs',
        'id_construction_addendum',
        'construction_id',
        'start_week',
        'end_week',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tabel tidak memiliki kolom updated_at
}
