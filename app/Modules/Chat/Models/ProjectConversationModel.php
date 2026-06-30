<?php

namespace App\Modules\Chat\Models;

use CodeIgniter\Model;

class ProjectConversationModel extends Model
{
    protected $table            = 'project_conversations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'project_id',
        'project_type',
        'client_id',
        'admin_id',
        'title',
        'status',
        'last_message_preview',
        'last_message_at',
        'last_message_sender_id',
        'last_message_sender_type',
        'unread_by_admin_count',
        'unread_by_client_count',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Mengambil daftar percakapan proyek lengkap dengan detail klien dan proyek untuk Admin
     */
    public function getConversationsWithDetails(): array
    {
        return $this->select('
                project_conversations.*,
                users.full_name as client_name,
                users.avatar as client_avatar,
                COALESCE(project_conversations.last_message_at, project_conversations.created_at) as sort_time,
                COALESCE(
                    project_conversations.title,
                    dr.location_address,
                    cr.address,
                    rr.address,
                    CONCAT(project_conversations.project_type, " #", project_conversations.project_id)
                ) as project_name,
                COALESCE(dr.location_address, cr.address, rr.address) as project_address,
                COALESCE(dr.total_payment, cr.total_payment, rr.total_payment) as project_total_payment
            ', false)
            ->join('users', 'users.id = project_conversations.client_id', 'left')
            ->join('design_requests dr', 'dr.id = project_conversations.project_id AND project_conversations.project_type = "design"', 'left')
            ->join('construction_requests cr', 'cr.id = project_conversations.project_id AND project_conversations.project_type = "construction"', 'left')
            ->join('renovation_requests rr', 'rr.id = project_conversations.project_id AND project_conversations.project_type = "renovation"', 'left')
            ->orderBy('sort_time', 'DESC')
            ->findAll();
    }

}
