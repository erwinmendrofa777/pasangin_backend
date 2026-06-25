<?php

namespace App\Modules\Tukang\Models;

use CodeIgniter\Model;

class TukangGroupMemberModel extends Model
{
    protected $table            = 'tukang_group_members';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'tukang_group_id',
        'tukang_id',
        'status',
        'joined_at'
    ];

    // Timestamps handled manually in code
    protected $useTimestamps = false;
}
