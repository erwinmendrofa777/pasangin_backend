<?php

namespace App\Modules\Chat\Models;

use CodeIgniter\Model;

class SupplierMessageModel extends Model
{
    protected $table            = 'supplier_messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'supplier_conversation_id',
        'sender_id',
        'sender_type',
        'body',
        'file_url',
        'message_type',
        'latitude',
        'longitude',
        'is_read_by_client',
        'is_read_by_supplier'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
