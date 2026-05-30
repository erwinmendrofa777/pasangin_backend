<?php

namespace App\Modules\Renovation\Models;

use CodeIgniter\Model;

class RenovationTargetModel extends Model
{
    protected $table            = 'renovation_targets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_renovation_rabs',
        'id_job_applications',
        'renovation_id',
        'start_week',
        'end_week',
        'bobot',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
