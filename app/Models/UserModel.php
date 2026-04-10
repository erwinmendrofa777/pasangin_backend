<?php
// FILE: app/Models/UserModel.php (KODE UNTUK MEMPERBAIKI SEMUANYA)

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // ===================================================================
    // === INI BAGIAN PALING PENTING YANG MENYEBABKAN SEMUA MASALAH ===
    // ===================================================================
    // SET useSoftDeletes MENJADI 'false'
    protected $useSoftDeletes   = false;
    // ===================================================================

    // Kolom yang boleh diisi
    protected $allowedFields    = [
        'nik',
        'full_name',
        'email',
        'password',
        'phone_number',
        'gender',
        'birth_date',
        'address',
        'role',
        'status',
        'avatar',
        'fcm_token'
    ];

    // Konfigurasi Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ===================================================================
    // === HAPUS BARIS INI KARENA BERTENTANGAN DENGAN useSoftDeletes ===
    // INILAH SUMBER DARI SEMUA ERROR 500
    // ===================================================================
    // protected $deletedField  = 'deleted_at'; // PASTIKAN BARIS INI TIDAK ADA ATAU DIKOMENTARI

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}

