<?php

namespace App\Modules\Design\Models;

use CodeIgniter\Model;

class DesignRequestModel extends Model
{
    protected $table            = 'design_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // =========================================================================
    // === PENTING: Daftar kolom yang boleh diisi (WAJIB COCOK DENGAN DB) ===
    // =========================================================================
    protected $allowedFields = [
        'user_id', 
        'full_name', 
        'phone_number', 
        'land_area', 
        'building_area', 
        'design_concept', 
        'survey_date', 
        'location_address',
        'latitude', 
        'longitude', 
        'voucher_code', 
        'survey_fee', 
        'discount_amount', 
        'total_payment', 
        'status', 
        'start_date',
        'target_date',      
        'progress_percent', 
        'created_at', 
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Mengambil detail proyek gabungan dengan data user (jika diperlukan)
     */
    public function get_project_detail($id)
    {
        return $this->select('design_requests.*, users.email as user_email')
                    ->join('users', 'users.id = design_requests.user_id', 'left')
                    ->where('design_requests.id', $id)
                    ->first();
    }
}