<?php

namespace App\Modules\Chat\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Kita tidak menggunakan soft delete untuk pesan

    // Kolom yang diizinkan untuk diisi
    protected $allowedFields    = [
        'conversation_id',
        'sender_id',
        'sender_type',
        'body',
        'file_url',
        'message_type',
        'latitude',
        'longitude',
        'is_read_by_admin',
        'is_read_by_client',
        'is_read_by_supplier'
    ];

    // Mengaktifkan penggunaan timestamps
    protected $useTimestamps = true;

    // Menentukan nama kolom untuk created_at dan updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
