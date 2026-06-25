<?php

namespace App\Modules\Tukang\Models;

use CodeIgniter\Model;

class TukangGroupModel extends Model
{
    protected $table            = 'tukang_group';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'name_group',
        'tukang_id',
        'referral_code',
        'balance',
        'created_at'
    ];

    // Timestamps handled manually in code
    protected $useTimestamps = false;
}
