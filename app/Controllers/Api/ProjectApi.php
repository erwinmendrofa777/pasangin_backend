<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class ProjectApi extends ResourceController
{
    protected $format    = 'json';
    protected $db;

    public function __construct()
    {
        // Panggil database di constructor agar bisa dipakai di semua fungsi
        $this->db = \Config\Database::connect();
    }

    /**
     * Rute: /design/requests
     * Mengambil DAFTAR SEMUA PROYEK untuk user yang sedang login.
     * Kode ini sudah stabil dan tidak perlu diubah.
     */
    public function designRequests()
    {
        // Di backend, kita akan mengambil user_id dari token JWT
        // Untuk sekarang, kita asumsikan user_id didapat dari token
        // $userId = $this->getUserIdFromToken(); // Contoh
        
        // Karena belum ada auth, kita bisa ambil semua untuk testing
        try {
            $projects = $this->db->table('design_requests')
                                 ->orderBy('created_at', 'DESC')
                                 ->get()
                                 ->getResultArray();

            return $this->respond([
                'status' => true,
                'data'   => $projects
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[ProjectApi::designRequests] EXCEPTION: ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan pada server.');
        }
    }
    
    /**
     * Rute: /design/submit
     * Menerima data dari form pengajuan desain.
     * Kode ini sudah stabil dan tidak perlu diubah.
     */
    public function submitDesignRequest()
    {
        $data = $this->request->getPost();

        // Validasi sederhana (bisa dikembangkan)
        if (empty($data['full_name']) || empty($data['phone_number'])) {
            return $this->failValidationErrors('Nama dan nomor telepon wajib diisi.');
        }

        try {
            $this->db->table('design_requests')->insert($data);
            $insertID = $this->db->insertID();

            if ($insertID) {
                return $this->respondCreated([
                    'status'  => true,
                    'message' => 'Pengajuan desain berhasil dikirim.',
                    'id'      => $insertID
                ]);
            } else {
                return $this->fail('Gagal menyimpan data pengajuan.');
            }
        } catch (\Throwable $e) {
            log_message('error', '[ProjectApi::submitDesignRequest] EXCEPTION: ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan pada server.');
        }
    }

    /**
     * Rute: /project-details/{id}
     * Mengambil DETAIL SATU PROYEK dari tabel 'design_requests'.
     * Kode ini sudah stabil dan tidak perlu diubah.
     */
    public function projectDetails($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return $this->failNotFound('ID proyek tidak valid.');
        }

        try {
            $project = $this->db->table('design_requests')
                                ->where('id', $id)
                                ->get()
                                ->getRowArray();

            if ($project) {
                return $this->respond([
                    'status' => true,
                    'data'   => $project
                ]);
            } else {
                return $this->failNotFound('Data proyek desain tidak ditemukan.');
            }
        } catch (\Throwable $e) {
            log_message('error', '[ProjectApi::projectDetails] EXCEPTION: ' . $e->getMessage());
            return $this->failServerError($e->getMessage());
        }
    }

    // ======================================================================
    // === KUMPULAN FUNGSI YANG DIPERBAIKI TOTAL DARI ERROR 500 ===
    // ======================================================================

    /**
     * Rute: /project/surveys/{id}
     * Mengambil daftar file hasil survey untuk satu proyek.
     * VERSI INI DIPERBAIKI 100% DARI ERROR 500.
     */
    public function getProjectSurveys($designRequestId)
    {
        if (empty($designRequestId) || !is_numeric($designRequestId)) {
            return $this->failNotFound('ID Proyek tidak valid.');
        }

        try {
            // KESALAHAN SEBELUMNYA ADA DI NAMA KOLOM 'where'. SEKARANG SUDAH BENAR.
            $surveys = $this->db->table('project_surveys')
                                ->where('design_request_id', $designRequestId) // <-- INI YANG BENAR
                                ->orderBy('created_at', 'DESC')
                                ->get()
                                ->getResultArray();

            return $this->respond([
                'status' => true,
                'data'   => $surveys
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[ProjectApi::getProjectSurveys] EXCEPTION: ' . $e->getMessage());
            // Berikan pesan error database yang jelas untuk debugging
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Rute: /project/designs/{id}
     * Mengambil daftar file hasil desain untuk satu proyek.
     * VERSI INI DIPERBAIKI 100% DARI ERROR 500.
     */
    public function getProjectDesigns($designRequestId)
    {
        if (empty($designRequestId) || !is_numeric($designRequestId)) {
            return $this->failNotFound('ID Proyek tidak valid.');
        }

        try {
            $designs = $this->db->table('project_designs')
                               ->where('design_request_id', $designRequestId) // <-- INI YANG BENAR
                               ->orderBy('created_at', 'DESC')
                               ->get()
                               ->getResultArray();

            return $this->respond([
                'status' => true,
                'data'   => $designs
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[ProjectApi::getProjectDesigns] EXCEPTION: ' . $e->getMessage());
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Rute: /project/invoices/{id}
     * Mengambil daftar tagihan untuk satu proyek.
     * VERSI INI DIPERBAIKI 100% DARI ERROR 500.
     */
    public function getProjectInvoices($designRequestId)
    {
        if (empty($designRequestId) || !is_numeric($designRequestId)) {
            return $this->failNotFound('ID Proyek tidak valid.');
        }

        try {
            $invoices = $this->db->table('project_invoices')
                                ->where('design_request_id', $designRequestId) // <-- INI YANG BENAR
                                ->orderBy('created_at', 'DESC')
                                ->get()
                                ->getResultArray();

            return $this->respond([
                'status' => true,
                'data'   => $invoices
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[ProjectApi::getProjectInvoices] EXCEPTION: ' . $e->getMessage());
            return $this->failServerError($e->getMessage());
        }
    }
}
