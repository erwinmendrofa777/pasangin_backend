<?php

namespace App\Modules\Renovation\Models;

use CodeIgniter\Model;

class RenovationModel extends Model
{
    protected $table            = 'renovation_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'full_name',
        'phone',
        'address',
        'latitude',
        'longitude',
        'survey_date',
        'renovation_type',
        'description',
        'location_photo',
        'status',
        'start_date',
        'week',
        'workday',
        'rab_file',
        'survey_cost',
        'discount_amount',
        'total_payment',
        'rab_total',
        'voucher_code',
        'gambar1',
        'gambar2',
        'gambar3',
        'gambar4',
        'gambar5'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Mengambil daftar proyek renovasi untuk satu user.
     */
    public function get_projects_by_user($user_id)
    {
        return $this->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Mengambil detail lengkap satu proyek, join dengan tabel user untuk nama.
     */
    public function get_project_detail($project_id)
    {
        return $this->select('renovation_requests.*, u.full_name, u.phone_number')
            ->join('users u', 'u.id = renovation_requests.user_id', 'left')
            ->find($project_id);
    }
}
