<?php

namespace App\Modules\Renovation\Models;

use CodeIgniter\Model;

class RenovationProgressModel extends Model
{
    protected $table            = 'renovation_progress';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_renovation_targets',
        'renovation_id',
        'week_number',
        'bobot',
        'description',
        'status',
        'photo_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tabel tidak memiliki kolom updated_at
}
