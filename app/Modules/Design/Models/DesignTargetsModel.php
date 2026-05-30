<?php

namespace App\Modules\Design\Models;

use CodeIgniter\Model;

class DesignTargetsModel extends Model
{
    protected $table = 'design_targets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_admin_id',
        'design_request_id',
        'task_name',
        'start_week',
        'end_week',
        'keterangan',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';  // tabel tidak memiliki kolom updated_at

}