<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectSurveysModel extends Model
{
    protected $table = 'project_surveys';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'design_request_id',
        'title',
        'note',
        'file',
        'comment'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';  // tabel tidak memiliki kolom updated_at

}