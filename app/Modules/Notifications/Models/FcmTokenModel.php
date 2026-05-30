<?php

namespace App\Modules\Notifications\Models;

use CodeIgniter\Model;

class FcmTokenModel extends Model
{
    protected $table = 'user_fcm_tokens';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'user_type',
        'fcm_token',
        'is_notification_enabled',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
