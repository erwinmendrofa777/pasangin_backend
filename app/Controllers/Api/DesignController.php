<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DesignRequestModel;
use CodeIgniter\API\ResponseTrait;

class DesignController extends ResourceController
{
    protected $db;
    protected $modelName = 'App\Models\DesignRequestModel';
    protected $format    = 'json';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    use ResponseTrait;

    // =========================================================================
    // 1. FUNGSI UNTUK MENERIMA PENGAJUAN DESAIN BARU (FINAL & SINKRON)
    // =========================================================================
    public function submit()
    {
    // JANGAN GUNAKAN: $this->request->getJSON()
    // TAPI GUNAKAN: $this->request->getPost()
    
    $userId = $this->request->getPost('user_id');
    
    $data = [
        'user_id'        => $userId,
        'full_name'      => $this->request->getPost('full_name'),
        'phone_number'   => $this->request->getPost('phone_number'),
        'land_area'      => $this->request->getPost('land_area'),
        'building_area'  => $this->request->getPost('building_area'),
        'design_concept' => $this->request->getPost('design_concept'),
        'survey_date'    => $this->request->getPost('survey_date'),
        'location_address' => $this->request->getPost('location_address'),
        'latitude'       => $this->request->getPost('latitude'),
        'longitude'      => $this->request->getPost('longitude'),
        'voucher_code'   => $this->request->getPost('voucher_code'),
        'survey_fee'     => $this->request->getPost('survey_cost'),
        'discount_amount'=> $this->request->getPost('discount_amount'),
        'total_payment'  => $this->request->getPost('total_payment'),
        'status'         => 'PENDING',
    ];

    $model = new \App\Models\DesignRequestModel(); // Sesuaikan nama model kawan
    
    if ($model->insert($data)) {
        return $this->respond([
            'status' => true,
            'message' => 'Pengajuan desain berhasil dikirim!'
        ], 200);
    }

    return $this->fail('Gagal menyimpan data ke database.');
}
    
    // =========================================================================
    // 2. GET RIWAYAT SEMUA PENGAJUAN DESAIN PER USER (FINAL & SINKRON)
    // Fungsi ini akan dipanggil oleh endpoint: /api/design/history/{user_id}
    // =========================================================================
    public function history($userId = null)
    {
        if (empty($userId)) {
            return $this->fail('User ID tidak ditemukan.', 400);
        }

        $model = new DesignRequestModel();
        $data = $model->where('user_id', $userId)
                      ->orderBy('created_at', 'DESC')
                      ->findAll();

        foreach ($data as &$project) {
            $image_urls = [];
            for ($i = 1; $i <= 5; $i++) {
                if (!empty($project['gambar' . $i])) {
                    $image_urls[] = base_url('uploads/designs/' . $project['gambar' . $i]);
                }
            }
            $project['image_urls'] = $image_urls;
        }

        return $this->respond([
            'status' => true,
            'message' => !empty($data) ? 'Data riwayat desain ditemukan' : 'Belum ada pengajuan desain',
            'data' => $data
        ]);
    }
    
    // =========================================================================
    // 3. GET DETAIL SATU PENGAJUAN DESAIN (FINAL & SINKRON)
    // Fungsi ini akan dipanggil oleh endpoint: /api/design/requests/detail/{request_id}
    // =========================================================================
    public function show($id = null)
    {
        if (empty($id)) {
            return $this->fail('ID Permohonan tidak ditemukan.', 400);
        }

        $model = new DesignRequestModel();
        $data = $model->find($id);

        if ($data) { // Pastikan $data tidak null sebelum memproses
            $image_urls = [];
            for ($i = 1; $i <= 5; $i++) {
                if (!empty($data['gambar' . $i])) {
                    $image_urls[] = base_url('uploads/designs/' . $data['gambar' . $i]);
                }
            }
            $data['image_urls'] = $image_urls;

            return $this->respond([
                'status' => true,
                'message' => 'Detail permohonan desain ditemukan',
                'data' => $data
            ]);
        } else {
           return $this->respond([
                'status' => true,
                'message' => 'Belum ada permohonan desain',
                'data' => $data
            ]);
        }
    }


    // =========================================================================
    // 4. GET HASIL SURVEY
    // =========================================================================
    public function surveys($designRequestId = null)
    {
        $surveys = $this->db->table('project_surveys')
                            ->where('design_request_id', $designRequestId)
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray();
        
        foreach($surveys as &$item) {
            $filename = !empty($item['file']) ? $item['file'] : 'default.jpg';
            $item['file_url'] = base_url('uploads/survey/' . $filename);
        }

        if ($surveys) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail permohonan survey ditemukan',
                'data' => $surveys
            ]);
        } else {
           return $this->respond([
                'status' => true,
                'message' => 'Belum ada Survey desain untuk permohonan ini',
                'data' => $surveys
            ]);
        }
    }

    // =========================================================================
    // 5. GET HASIL DESAIN
    // =========================================================================
    public function designs($designRequestId = null)
    {
        $designs = $this->db->table('project_designs')
                            ->where('design_request_id', $designRequestId)
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray();

        foreach($designs as &$item) {
            $filename = !empty($item['file']) ? $item['file'] : 'default.jpg';
            $item['file_url'] = base_url('uploads/design_results/' . $filename);
        }

        if ($designs) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail permohonan desain ditemukan',
                'data' => $designs
            ]);
        } else {
           return $this->respond([
                'status' => true,
                'message' => 'Belum ada hasil desain untuk permohonan ini',
                'data' => $designs
            ]);
        }
    }

    // =========================================================================
    // 6. GET INVOICES
    // =========================================================================
    public function invoices($designRequestId = null)
    {
        $invoices = $this->db->table('project_invoices')
                             ->where('design_request_id', $designRequestId)
                             ->orderBy('created_at', 'DESC')
                             ->get()->getResultArray();

        if ($invoices) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail permohonan invoice ditemukan',
                'data' => $invoices
            ]);
        } else {
           return $this->respond([
                'status' => true,
                'message' => 'Belum ada invoice untuk permohonan ini',
                'data' => $invoices
            ]);
        }
    }
}
