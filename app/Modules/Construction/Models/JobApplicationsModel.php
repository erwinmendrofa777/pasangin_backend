<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class JobApplicationsModel extends Model
{
    protected $table            = 'job_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'tukang_id',
        'project_id',
        'project_type',
        'tukang_name',
        'email',
        'phone',
        'dob',
        'address',
        'specialization',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
