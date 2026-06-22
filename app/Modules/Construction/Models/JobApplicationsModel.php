<?php

namespace App\Modules\Construction\Models;

use CodeIgniter\Model;

class JobApplicationsModel extends Model
{
    protected $table            = 'job_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'tukang_id',
        'project_id',
        'project_type',
        'tukang_name',
        'email',
        'phone',
        'dob',
        'address',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $afterFind      = ['populateSpecializationCallback'];

    protected function populateSpecializationCallback(array $data)
    {
        if (empty($data['data'])) {
            return $data;
        }

        $db = \Config\Database::connect();

        if ($data['singleton']) {
            $application = &$data['data'];
            if (isset($application['tukang_id'])) {
                $skills = $db->table('tukang_skill_map m')
                    ->select('s.skill_name')
                    ->join('tukang_skill s', 's.id = m.tukang_skill_id')
                    ->where('m.tukang_id', $application['tukang_id'])
                    ->get()
                    ->getResultArray();

                $names = array_column($skills, 'skill_name');
                $application['specialization'] = implode(', ', $names);
            } else {
                $application['specialization'] = '';
            }
        } else {
            $tukangIds = array_filter(array_unique(array_column($data['data'], 'tukang_id')));
            if (!empty($tukangIds)) {
                $skills = $db->table('tukang_skill_map m')
                    ->select('m.tukang_id, s.skill_name')
                    ->join('tukang_skill s', 's.id = m.tukang_skill_id')
                    ->whereIn('m.tukang_id', $tukangIds)
                    ->get()
                    ->getResultArray();

                $skillsByTukang = [];
                foreach ($skills as $s) {
                    $skillsByTukang[$s['tukang_id']][] = $s['skill_name'];
                }

                foreach ($data['data'] as &$application) {
                    if (isset($application['tukang_id'])) {
                        $application['specialization'] = isset($skillsByTukang[$application['tukang_id']])
                            ? implode(', ', $skillsByTukang[$application['tukang_id']])
                            : '';
                    } else {
                        $application['specialization'] = '';
                    }
                }
            } else {
                foreach ($data['data'] as &$application) {
                    $application['specialization'] = '';
                }
            }
        }

        return $data;
    }
}
