<?php

namespace App\Modules\Design\Models;

use CodeIgniter\Model;

class ProjectDesignsModel extends Model
{
    protected $table = 'project_designs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_admin_id',
        'design_request_id',
        'design_targets_id',
        'revision_number',
        'design_name',
        'file',
        'status',
        'revision_note'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';  // tabel tidak memiliki kolom updated_at

}