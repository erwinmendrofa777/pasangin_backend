<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\Model;

class AdminActivityLogModel extends Model
{
    protected $table = 'admin_activity_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'admin_id',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = ''; // Kita tidak memakai updated_at di tabel ini
    protected $deletedField = '';

}
