<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'suppliers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // === FIELD YANG DIIZINKAN (SUDAH DITAMBAHKAN LATITUDE & LONGITUDE) ===
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'contact_person',
        'phone',
        'address',
        'province',
        'city',
        'district',
        'logo_url',
        'latitude',  // Tambahan untuk Maps
        'longitude', // Tambahan untuk Maps
        'is_active',
        'is_verify',
        'nik',
        'rata_rata_rating',
        'total_ulasan',
        'status',
        'fcm_token'
    ];

    // === TIMESTAMPS (Sesuai Struktur Tabel) ===
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
