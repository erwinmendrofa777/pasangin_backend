<?php

namespace App\Models;

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
        'is_read_by_admin'
    ];

    // Mengaktifkan penggunaan timestamps
    protected $useTimestamps = true;

    // Menentukan nama kolom untuk created_at dan updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = null; // <-- KITA NONAKTIFKAN updated_at karena tidak diperlukan untuk pesan

}
