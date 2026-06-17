<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionProgressModel extends Model
{
    protected $table            = 'construction_progress';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_construction_targets',
        'construction_id',
        'week_number',
        'volume',
        'description',
        'status',
        'photo_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tabel ini tidak memiliki kolom updated_at
}
