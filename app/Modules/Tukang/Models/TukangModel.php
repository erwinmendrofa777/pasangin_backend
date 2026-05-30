<?php

namespace App\Modules\Tukang\Models;

use CodeIgniter\Model;

class TukangModel extends Model
{
    // Nama tabel di database  
    protected $table = 'tukang';

    // Nama primary key
    protected $primaryKey = 'id';

    // Autoincrement aktif
    protected $useAutoIncrement = true;

    // Format return data sebagai array
    protected $returnType = 'array';

    // Jangan gunakan soft deletes (data dihapus permanen)
    protected $useSoftDeletes = false;

    /**
     * List kolom yang diizinkan untuk diisi (Mass Assignment).
     * Saya sudah menambahkan kolom untuk Wallet dan Penilaian  .
     */
    protected $allowedFields = [
        'agent_code',
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'dob',
        'address',
        'ktp_address',
        'domicile_address',
        'profile_photo',
        'ktp_photo',
        'selfie_photo',
        'specialization',
        'status',
        'balance',          // BARU: Untuk menyimpan saldo  
        'last_login_at',
        'fcm_token',
        'registration_step',
        'is_verify',
        'rata_rata_rating',
        'total_ulasan'
    ];

    // Otomatis mengisi created_at dan updated_at  
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation Rules (Opsional)
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
}