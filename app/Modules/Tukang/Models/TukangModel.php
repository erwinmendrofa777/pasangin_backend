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
        'status',
        'balance',          // BARU: Untuk menyimpan saldo  
        'last_login_at',
        'fcm_token',
        'registration_step',
        'is_verify',
        'rata_rata_rating',
        'total_ulasan',
        'role'
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
    protected $afterFind = ['populateSpecializationCallback'];

    protected function populateSpecializationCallback(array $data)
    {
        if (empty($data['data'])) {
            return $data;
        }

        $db = \Config\Database::connect();

        if ($data['singleton']) {
            $tukang = &$data['data'];
            if (isset($tukang['id'])) {
                $skills = $db->table('tukang_skill_map m')
                    ->select('s.skill_name')
                    ->join('tukang_skill s', 's.id = m.tukang_skill_id')
                    ->where('m.tukang_id', $tukang['id'])
                    ->get()
                    ->getResultArray();

                $names = array_column($skills, 'skill_name');
                $tukang['specialization'] = implode(', ', $names);
            }
        } else {
            $ids = array_column($data['data'], 'id');
            if (!empty($ids)) {
                $skills = $db->table('tukang_skill_map m')
                    ->select('m.tukang_id, s.skill_name')
                    ->join('tukang_skill s', 's.id = m.tukang_skill_id')
                    ->whereIn('m.tukang_id', $ids)
                    ->get()
                    ->getResultArray();

                $skillsByTukang = [];
                foreach ($skills as $s) {
                    $skillsByTukang[$s['tukang_id']][] = $s['skill_name'];
                }

                foreach ($data['data'] as &$tukang) {
                    if (isset($tukang['id'])) {
                        $tukang['specialization'] = isset($skillsByTukang[$tukang['id']]) 
                            ? implode(', ', $skillsByTukang[$tukang['id']]) 
                            : '';
                    }
                }
            }
        }

        return $data;
    }
}