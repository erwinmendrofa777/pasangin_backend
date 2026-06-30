<?php

namespace App\Modules\Chat\Models;

use CodeIgniter\Model;

class SupplierConversationModel extends Model
{
    protected $table            = 'supplier_conversations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'client_id',
        'supplier_id',
        'last_message_preview',
        'last_message_at',
        'unread_by_supplier_count',
        'unread_by_client_count',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getConversationsWithDetails(): array
    {
        return $this->select('
                supplier_conversations.*,
                users.full_name as client_name,
                users.avatar as client_avatar,
                suppliers.name as supplier_name,
                COALESCE(supplier_conversations.last_message_at, supplier_conversations.created_at) as sort_time
            ', false)
            ->join('users', 'users.id = supplier_conversations.client_id', 'left')
            ->join('suppliers', 'suppliers.id = supplier_conversations.supplier_id', 'left')
            ->orderBy('sort_time', 'DESC')
            ->findAll();
    }
}
