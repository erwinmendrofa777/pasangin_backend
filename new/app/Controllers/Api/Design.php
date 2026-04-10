<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DesignRequestModel;

class Design extends ResourceController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ... (Function SUBMIT biarkan sama) ...
    public function submit()
    {
        $model = new DesignRequestModel();
        $json = $this->request->getJSON();
        $input = $json ? (array)$json : $this->request->getPost();

        if (!$input) return $this->fail('Data tidak ditemukan/Format salah');

        $data = [
            'user_id'            => $input['user_id'] ?? null, 
            'full_name'          => $input['full_name'] ?? '',
            'phone_number'       => $input['phone_number'] ?? '',
            'land_area'          => $input['land_area'] ?? 0,
            'building_area'      => $input['building_area'] ?? 0,
            'design_concept'     => $input['design_concept'] ?? '',
            'other_concept_desc' => $input['other_concept_desc'] ?? null,
            'survey_date'        => $input['survey_date'] ?? date('Y-m-d'),
            'location_address'   => $input['location_address'] ?? '',
            'latitude'           => $input['latitude'] ?? null,
            'longitude'          => $input['longitude'] ?? null,
            'survey_fee'         => $input['survey_fee'] ?? 0,
            'voucher_code'       => $input['voucher_code'] ?? null,
            'discount_amount'    => $input['discount_amount'] ?? 0,
            'total_payment'      => $input['total_payment'] ?? 0,
            'status'             => 'PENDING',
            'created_at'         => date('Y-m-d H:i:s')
        ];

        if (empty($data['user_id'])) return $this->fail('User ID Wajib Diisi!');

        if ($model->insert($data)) {
            return $this->respondCreated(['status' => 200, 'message' => 'Permohonan berhasil dikirim!', 'id' => $model->getInsertID()]);
        } else {
            return $this->fail('Gagal menyimpan data');
        }
    }

    // =========================================================================
    // 2. GET HASIL SURVEY (PATH FIXED: uploads/survey/)
    // =========================================================================
    public function surveys($designRequestId = null)
    {
        $surveys = $this->db->table('project_surveys')
                            ->where('design_request_id', $designRequestId)
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray();
        
        foreach($surveys as &$item) {
            $filename = !empty($item['file']) ? $item['file'] : 'default.jpg';
            
            // PERBAIKAN PATH SESUAI SCREENSHOT
            $item['file_url'] = base_url('uploads/survey/' . $filename);
        }

        return $this->respond(['status' => true, 'data' => $surveys]);
    }

    // =========================================================================
    // 3. GET HASIL DESAIN (PATH FIXED: uploads/design_results/)
    // =========================================================================
    public function designs($designRequestId = null)
    {
        $designs = $this->db->table('project_designs')
                            ->where('design_request_id', $designRequestId)
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray();

        foreach($designs as &$item) {
            $filename = !empty($item['file']) ? $item['file'] : 'default.jpg';
            
            // PERBAIKAN PATH SESUAI SCREENSHOT
            $item['file_url'] = base_url('uploads/design_results/' . $filename);
        }

        return $this->respond(['status' => true, 'data' => $designs]);
    }

    // =========================================================================
    // 4. GET INVOICES
    // =========================================================================
    public function invoices($designRequestId = null)
    {
        $invoices = $this->db->table('project_invoices')
                             ->where('design_request_id', $designRequestId)
                             ->orderBy('created_at', 'DESC')
                             ->get()->getResultArray();

        foreach($invoices as &$item) {
            if(!empty($item['payment_proof'])) {
                 // Asumsi folder payments tetap sama, kalau error cek file manager lagi
                 $item['payment_proof_url'] = base_url('uploads/payments/' . $item['payment_proof']);
            } else {
                 $item['payment_proof_url'] = null;
            }
        }

        return $this->respond(['status' => true, 'data' => $invoices]);
    }

    // =========================================================================
    // 5. GET RIWAYAT PROYEK
    // =========================================================================
    public function history($userId = null)
    {
        if (!$userId) $userId = $this->request->getVar('user_id');
        if (!$userId) return $this->fail('User ID tidak ditemukan.');

        $model = new DesignRequestModel();
        $data = $model->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        return $this->respond([
            'status' => 200,
            'error' => false,
            'message' => $data ? 'Data ditemukan' : 'Belum ada pengajuan',
            'data' => $data ? $data : []
        ]);
    }
}
