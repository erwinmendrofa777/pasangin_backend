<?php

namespace App\Modules\Tukang\Models;

use CodeIgniter\Model;

class TukangSkillModel extends Model
{
    protected $table      = 'tukang_skill';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = ['skill_name'];

    protected $validationRules = [
        'skill_name' => 'required|min_length[2]|max_length[255]',
    ];
    protected $validationMessages = [
        'skill_name' => [
            'required'   => 'Nama skill wajib diisi.',
            'min_length' => 'Nama skill minimal 2 karakter.',
            'max_length' => 'Nama skill maksimal 255 karakter.',
        ],
    ];
}
