<?php

namespace App\Modules\Chat\Models;

use CodeIgniter\Model;

class ProjectMessageModel extends Model
{
    protected $table            = 'project_messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'project_conversation_id',
        'sender_id',
        'sender_type',
        'body',
        'file_url',
        'file_name',
        'file_size',
        'message_type',
        'latitude',
        'longitude',
        'is_read_by_admin',
        'is_read_by_client',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
