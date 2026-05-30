<?php

namespace App\Modules\Renovation\Models;

use CodeIgniter\Model;

class RenovationSurveyModel extends Model
{
    protected $table = 'renovation_surveys';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_admin_id',
        'request_id',
        'title',
        'description',
        'file_url',
        'comment'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
