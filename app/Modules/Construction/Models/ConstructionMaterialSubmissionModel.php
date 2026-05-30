<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class ConstructionMaterialSubmissionModel extends Model
{
    protected $table            = 'construction_material_submission';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Saya memasukkan rekomendasi field tambahan yang sempat kita bahas sebelumnya,
    // Jika ada yang tidak Anda gunakan di tabel, Anda bisa menghapusnya dari list ini.
    protected $allowedFields    = [
        'construction_id',
        'job_applications_id', // Untuk mencatat pendaftar/tukang yang mengajukan
        'type',                // 'bahan' atau 'alat'
        'title',               // Judul pengajuan
        'description',         // Detail alat/bahan
        'photo',               // Foto dokumentasi/bukti
        'status',              // 'pending', 'approved', 'rejected'
        'comment',             // Catatan/tanggapan dari admin
    ];

    // Aktifkan auto-update untuk field created_at dan updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
