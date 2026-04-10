<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class ConstructionApi extends BaseController
{
    use ResponseTrait;

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // =========================================================================
    // HELPER INTERNAL: AMBIL PROYEK AKTIF BERDASARKAN USER ID (JANGAN DIUBAH)
    // =========================================================================
    private function getActiveProject($userId)
    {
        if ($userId == null) {
            return null;
        }
        // Ambil proyek terakhir yang dibuat user
        return $this->db->table('construction_requests')
                        ->where('user_id', $userId)
                        ->orderBy('created_at', 'DESC')
                        ->get()->getRowArray();
    }
    
    // =========================================================================
    // 1. FUNGSI SUBMIT (SUDAH DIPERBAIKI DENGAN KOLOM 'building_area')
    // =========================================================================
    public function submit()
    {
        $data = [
            'user_id'         => $this->request->getPost('user_id'),
            'full_name'       => $this->request->getPost('full_name'),
            'phone'           => $this->request->getPost('phone_number'),
            'land_area'       => $this->request->getPost('land_area'),
            'building_area'   => $this->request->getPost('building_area'), // Perbaikan Kunci
            'survey_date'     => $this->request->getPost('survey_date'),
            'address'         => $this->request->getPost('address'),
            'latitude'        => $this->request->getPost('latitude'),
            'longitude'       => $this->request->getPost('longitude'),
            'voucher_code'    => $this->request->getPost('voucher_code'),
            'survey_cost'     => $this->request->getPost('survey_cost'),
            'discount_amount' => $this->request->getPost('discount_amount'),
            'total_payment'   => $this->request->getPost('total_payment'),
            'status'          => 'PENDING',
            'created_at'      => date('Y-m-d H:i:s')
        ];

        // Handle Foto (Opsional)
        $photo = $this->request->getFile('location_photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            if (!is_dir('uploads/construction/')) { mkdir('uploads/construction/', 0777, true); }
            $photo->move('uploads/construction', $photoName);
            $data['location_photo'] = $photoName;
        }

        $this->db->table('construction_requests')->insert($data);

        return $this->respondCreated([
            'status'  => true,
            'message' => 'Permohonan berhasil dikirim',
            'debug_payload' => $data // DEBUG: Tampilkan data yang di-submit
        ]);
    }

    // =========================================================================
    // 2. FUNGSI GET LIST RIWAYAT PROYEK (SUDAH BENAR)
    // =========================================================================
    public function project($userId = null)
    {
        if ($userId == null) {
            return $this->fail('User ID tidak boleh kosong.');
        }

        $projects = $this->db->table('construction_requests')
                        ->where('user_id', $userId)
                        ->orderBy('created_at', 'DESC')
                        ->get()->getResultArray();

        return $this->respond([
            'status' => true,
            'data'   => $projects,
            'debug_info' => [
                'user_id_received' => $userId,
                'found_projects_count' => count($projects)
            ]
        ]);
    }

    // =========================================================================
    // 3. FUNGSI GET HASIL SURVEY (BARU DITAMBAHKAN SESUAI ROUTE)
    // =========================================================================
    public function surveys($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) {
            return $this->respond(['status' => false, 'message' => 'Proyek aktif tidak ditemukan.', 'data' => []]);
        }

        $surveys = $this->db->table('construction_surveys')
                            ->where('construction_id', $project['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray();
        
        // Menambahkan URL lengkap ke file
        foreach($surveys as &$item) {
            if (!empty($item['survey_file'])) {
                $item['file_url'] = base_url('uploads/construction/survey/' . $item['survey_file']);
            }
        }

        return $this->respond(['status' => true, 'data' => $surveys, 'debug_project_id' => $project['id']]);
    }

    // =========================================================================
    // 4. FUNGSI GET HASIL DESAIN (BARU DITAMBAHKAN SESUAI ROUTE)
    // =========================================================================
    public function designs($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) {
            return $this->respond(['status' => false, 'message' => 'Proyek aktif tidak ditemukan.', 'data' => []]);
        }

        $designs = $this->db->table('construction_designs')
                            ->where('construction_id', $project['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray();

        foreach($designs as &$item) {
             if (!empty($item['file'])) {
                $item['file_url'] = base_url('uploads/construction/design/' . $item['file']);
             }
        }

        return $this->respond(['status' => true, 'data' => $designs, 'debug_project_id' => $project['id']]);
    }

    // =========================================================================
    // 5. FUNGSI GET PROGRESS (BARU DITAMBAHKAN SESUAI ROUTE)
    // =========================================================================
    public function progress($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) {
            return $this->respond(['status' => false, 'message' => 'Proyek aktif tidak ditemukan.', 'data' => []]);
        }

        $progress = $this->db->table('construction_progress')
                            ->where('construction_id', $project['id'])
                            ->orderBy('week', 'DESC')
                            ->get()->getResultArray();

        foreach($progress as &$item) {
             if (!empty($item['photo'])) {
                $item['photo_url'] = base_url('uploads/construction/progress/' . $item['photo']);
             }
        }

        return $this->respond(['status' => true, 'data' => $progress, 'debug_project_id' => $project['id']]);
    }

    // =========================================================================
    // 6. FUNGSI GET INVOICES (BARU DITAMBAHKAN SESUAI ROUTE)
    // =========================================================================
    public function invoices($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) {
            return $this->respond(['status' => false, 'message' => 'Proyek aktif tidak ditemukan.', 'data' => []]);
        }

        $invoices = $this->db->table('construction_invoices')
                             ->where('construction_id', $project['id'])
                             ->orderBy('created_at', 'ASC')
                             ->get()->getResultArray();

        // Di sini Anda bisa menambahkan logika Midtrans jika diperlukan
        
        return $this->respond(['status' => true, 'data' => $invoices, 'debug_project_id' => $project['id']]);
    }

    // =========================================================================
    // 7. FUNGSI GET RAB (BARU DITAMBAHKAN SESUAI ROUTE)
    // =========================================================================
    public function rabs($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) {
            return $this->respond(['status' => false, 'message' => 'Proyek aktif tidak ditemukan.', 'data' => []]);
        }

        // Asumsi data RAB ada di tabel 'construction_rabs'
        $rabData = $this->db->table('construction_rabs')
                            ->where('construction_id', $project['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray(); 

        if (empty($rabData)) {
            return $this->respond(['status' => false, 'message' => 'Data RAB belum tersedia.', 'data' => []]);
        }

        return $this->respond(['status' => true, 'data' => $rabData, 'debug_project_id' => $project['id']]);
    }

    // =========================================================================
    // 8. FUNGSI NOTIFIKASI MIDTRANS (SUDAH ADA, HANYA DIRAPIKAN)
    // =========================================================================
    public function notification()
    {
        // Kode notifikasi Midtrans Anda bisa diletakkan di sini
        // ...
        return $this->respond(['status' => true, 'message' => 'Webhook received.']);
    }
}
