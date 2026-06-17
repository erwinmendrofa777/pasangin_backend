<?php

namespace App\Modules\Chat\Models;

use CodeIgniter\Model;

class ConversationModel extends Model
{
    protected $table            = 'conversations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // =========================================================================
    // === PERBAIKAN: MENAMBAHKAN KOLOM TITLE ===
    // =========================================================================
    protected $allowedFields    = [
        'client_id',
        'admin_id',
        'supplier_id',
        'client_type',
        'title',                // BARU: Menyimpan judul/topik chat
        'last_message_preview',
        'last_message_at',
        'unread_by_admin_count',
        'unread_by_supplier_count',
        'unread_by_client_count',
        'status',
        'category'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    /**
     * Fungsi untuk mengambil daftar percakapan untuk admin
     * Saya tambahkan c.title agar admin tahu topik obrolannya
     */
    public function getConversationsWithClientDetails(): array
    {
        $db = \Config\Database::connect();

        $queryClient = $db->table('conversations c')
            ->select('c.id, c.title, c.client_id, c.client_type, c.last_message_preview, c.last_message_at, u.full_name as client_name, u.avatar as client_avatar, c.unread_by_admin_count')
            ->join('users u', 'u.id = c.client_id', 'left')
            ->where('c.client_type', 'client')
            ->getCompiledSelect(false);

        $queryTukang = $db->table('conversations c')
            ->select('c.id, c.title, c.client_id, c.client_type, c.last_message_preview, c.last_message_at, t.name as client_name, "default.png" as client_avatar, c.unread_by_admin_count')
            ->join('tukang t', 't.id = c.client_id', 'left')
            ->where('c.client_type', 'tukang')
            ->getCompiledSelect(false);
        
        $finalQueryString = "({$queryClient}) UNION ALL ({$queryTukang}) ORDER BY last_message_at DESC";

        return $db->query($finalQueryString)->getResultArray();
    }
}