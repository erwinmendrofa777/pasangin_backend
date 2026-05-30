<?php

namespace App\Modules\Renovation\Models;

use CodeIgniter\Model;

class RenovationMaterialSubmissionModel extends Model
{
    protected $table            = 'renovation_material_submission';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'renovation_id',
        'job_applications_id',
        'type',               // 'bahan' atau 'alat'
        'title',
        'description',        // Detail rincian alat/bahan
        'photo',
        'status',             // 'pending', 'approved', 'rejected'
        'comment',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
