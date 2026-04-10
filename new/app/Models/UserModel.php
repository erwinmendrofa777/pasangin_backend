<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Kita set false dulu biar gampang
    protected $protectFields    = true;

    // --------------------------------------------------------------------
    // KOLOM YANG BOLEH DIISI (WAJIB UPDATE DISINI)
    // --------------------------------------------------------------------
    protected $allowedFields    = [
        'full_name',
        'email',
        'password',
        'phone_number',
        'role',
        'profile_image',
        
        // --- INI YANG BARU KITA TAMBAHKAN ---
        'gender',
        'birth_date',
        'address'
    ];

    // Dates
    protected $useTimestamps = true; // Agar created_at & updated_at otomatis terisi
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    // Kita kosongkan saja rules disini karena validasi sudah kita lakukan di Controller Auth.php
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
