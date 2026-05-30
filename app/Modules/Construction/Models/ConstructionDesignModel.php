<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionDesignModel extends Model
{
    protected $table = 'construction_designs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'user_admin_id',
        'construction_id',
        'design_requests_id',
        'title',
        'file',
        'comment'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = ''; // Tabel ini tidak memiliki kolom updated_at
}
