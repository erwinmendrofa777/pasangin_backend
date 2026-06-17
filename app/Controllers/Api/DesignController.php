<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Modules\Design\Models\DesignRequestModel;
use CodeIgniter\API\ResponseTrait;

class DesignController extends ResourceController
{
    protected $db;
    protected $notifService;
    protected $modelName = 'App\Modules\Design\Models\DesignRequestModel';
    protected $format = 'json';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notifService = new \App\Modules\Notifications\Services\NotificationService();
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
            'user_id' => $userId,
            'full_name' => $this->request->getPost('full_name'),
            'phone_number' => $this->request->getPost('phone_number'),
            'land_area' => $this->request->getPost('land_area'),
            'building_area' => $this->request->getPost('building_area'),
            'design_concept' => $this->request->getPost('design_concept'),
            'location_address' => $this->request->getPost('location_address'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'voucher_code' => $this->request->getPost('voucher_code'),
            'discount_amount' => $this->request->getPost('discount_amount'),
            'status' => 'PENDING',
        ];

        $model = new \App\Modules\Design\Models\DesignRequestModel(); // Sesuaikan nama model  

        if ($model->insert($data)) {
            // Kirim notifikasi ke Admin  
            $this->notifService->sendToPermission(
                'design_detail',
                'Pengajuan Desain Baru',
                "Pelanggan atas nama {$data['full_name']} telah mengajukan desain baru. Silakan cek detail."
            );

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

        foreach ($surveys as &$item) {
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

    public function detailSurveys($id = null)
    {
        $surveys = $this->db->table('project_surveys')
            ->select('project_surveys.*')
            ->where('project_surveys.id', $id)
            ->get()->getRow();

        if ($surveys) {
            $filename = !empty($surveys->file) ? $surveys->file : 'default.jpg';
            $surveys->file_url = base_url('uploads/survey/' . $filename);
        }

        unset($surveys->design_request_id);

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

    public function sendCommentSurvey($id)
    {
        $json = $this->request->getJSON(true);
        $comment = $json['comment'] ?? $this->request->getVar('comment');

        if (!$comment) {
            return $this->fail('Data tidak lengkap.');
        }

        $this->db->table('project_surveys')->where('id', $id)->update([
            'comment' => $comment
        ]);

        // Ambil info survey  
        $surveyInfo = $this->db->table('project_surveys ps')
            ->select('dr.full_name, dr.id as design_id')
            ->join('design_requests dr', 'dr.id = ps.design_request_id', 'left')
            ->where('ps.id', $id)
            ->get()->getRowArray();

        $namaKlien = $surveyInfo['full_name'] ?? 'Seorang client';

        // Kirim notifikasi ke Admin  
        $this->notifService->sendToPermission(
            'design_survey',
            'Komentar Survey Baru',
            "Client {$namaKlien} telah memberikan komentar pada hasil survey desain #" . ($surveyInfo['design_id'] ?? $id) . "."
        );

        return $this->respond([
            'status' => true,
            'message' => 'Komentar berhasil ditambahkan.'
        ]);
    }

    // =========================================================================
    // 5. GET HASIL DESAIN
    // =========================================================================
    // public function designs($designRequestId = null)
    // {
    //     $designs = $this->db->table('project_designs')
    //                         ->where('design_request_id', $designRequestId)
    //                         ->orderBy('created_at', 'DESC')
    //                         ->get()->getResultArray();

    //     foreach($designs as &$item) {
    //         $filename = !empty($item['file']) ? $item['file'] : 'default.jpg';
    //         $item['file_url'] = base_url('uploads/design_results/' . $filename);
    //     }

    //     if ($designs) {
    //         return $this->respond([
    //             'status' => true,
    //             'message' => 'Detail permohonan desain ditemukan',
    //             'data' => $designs
    //         ]);
    //     } else {
    //        return $this->respond([
    //             'status' => true,
    //             'message' => 'Belum ada hasil desain untuk permohonan ini',
    //             'data' => $designs
    //         ]);
    //     }
    // }

    // =========================================================================
    // 6. GET TARGET DESAIN (REVISI, TANGGAL MULAI, STATUS)
    // =========================================================================
    public function targets($designRequestId = null)
    {
        if (empty($designRequestId)) {
            return $this->respond([
                'status' => false,
                'message' => 'Design request ID tidak diberikan',
                'data' => []
            ]);
        }

        // Ambil data design request untuk mendapatkan start_date
        $designRequest = $this->db->table('design_requests')
            ->select('start_date')
            ->where('id', $designRequestId)
            ->get()->getRowArray();

        if (!$designRequest) {
            return $this->respond([
                'status' => false,
                'message' => 'Project design tidak ditemukan',
                'data' => []
            ]);
        }

        $startDate = $designRequest['start_date'];

        // Ambil semua target untuk request ini
        $targets = $this->db->table('design_targets')
            ->where('design_request_id', $designRequestId)
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        $result = [];
        foreach ($targets as $target) {
            // Hitung jumlah revisi untuk target ini
            $revisiCount = $this->db->table('project_designs')
                ->where('design_targets_id', $target['id'])
                ->countAllResults();

            // Hitung tanggal mulai target (start_date proyek + (start_week - 1) hari)
            // Asumsi start_week menyimpan "Day X"
            $dayOffset = (int) ($target['start_week'] ?? 1) - 1;

            // Kalkulasi target_start_date
            if (!empty($startDate)) {
                $targetStartDate = date('Y-m-d', strtotime($startDate . " +{$dayOffset} days"));
            } else {
                $targetStartDate = null;
            }

            $result[] = [
                'id' => $target['id'],
                'task_name' => $target['task_name'],
                'jumlah_revisi' => $revisiCount,
                'target_start_date' => $targetStartDate,
                'status' => $target['status']
            ];
        }

        if ($result) {
            return $this->respond([
                'status' => true,
                'message' => 'Data target desain ditemukan',
                'data' => $result
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada target desain untuk permohonan ini',
                'data' => []
            ]);
        }
    }

    public function progress($id = null)
    {
        $progress = $this->db->table('project_designs pd')
            ->select('pd.*, dt.task_name, dr.max_revision')
            ->join('design_targets dt', 'dt.id = pd.design_targets_id', 'left')
            ->join('design_requests dr', 'dr.id = dt.design_request_id', 'left')
            ->where('pd.design_targets_id', $id)
            ->orderBy('pd.created_at', 'DESC')
            ->get()->getResultArray();

        foreach ($progress as &$item) {
            $item['file_url'] = base_url('uploads/design_results/' . $item['file']);

            // Decode JSON array image_revision_note dan bangun URL tiap gambar
            $revImgUrls = [];
            if (!empty($item['image_revision_note'])) {
                $decoded = json_decode($item['image_revision_note'], true);
                if (is_array($decoded)) {
                    foreach ($decoded as $imgFile) {
                        $revImgUrls[] = base_url('uploads/design_results/revision_comment/' . $imgFile);
                    }
                }
            }
            $item['image_revision_note_urls'] = $revImgUrls;
        }

        if ($progress) {
            return $this->respond([
                'status' => true,
                'message' => 'Data progress desain ditemukan',
                'data' => $progress
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada progress desain untuk permohonan ini',
                'data' => []
            ]);
        }
    }

    public function updateProgress($id = null)
    {
        $validasi = $this->validate([
            'status' => 'required|in_list[PENDING,REJECTED,APPROVED]',
        ]);

        if (!$validasi) {
            return $this->respond([
                'status' => false,
                'message' => 'Validasi gagal',
                'data' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'revision_note' => $this->request->getPost('revision_note') ?? $this->request->getJSON(true)['revision_note'] ?? null,
            'status' => $this->request->getPost('status') ?? $this->request->getJSON(true)['status'],
        ];

        // Handle upload multiple gambar untuk image_revision_note
        $uploadedFiles = $this->request->getFileMultiple('image_revision_note');
        if ($uploadedFiles) {
            $uploadPath = FCPATH . 'uploads/design_results/revision_comment/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileNames = [];
            foreach ($uploadedFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $fileNames[] = $newName;
                }
            }

            if (!empty($fileNames)) {
                $data['image_revision_note'] = json_encode($fileNames);
            }
        }

        $update = $this->db->table('project_designs')->update($data, ['id' => $id]);

        if ($update) {
            // Jika status diupdate menjadi APPROVED, otomatis ubah status target menjadi DONE
            if (isset($data['status']) && $data['status'] === 'APPROVED') {
                $design = $this->db->table('project_designs')->where('id', $id)->get()->getRowArray();
                if ($design && !empty($design['design_targets_id'])) {
                    $this->db->table('design_targets')
                        ->where('id', $design['design_targets_id'])
                        ->update(['status' => 'DONE']);

                    // Tolak semua revisi PENDING lain dalam target yang sama
                    $this->db->table('project_designs')
                        ->where('design_targets_id', $design['design_targets_id'])
                        ->where('id !=', $id)
                        ->where('status', 'PENDING')
                        ->update([
                            'status' => 'REJECTED',
                            'revision_note' => 'Revisi lain telah disetujui'
                        ]);
                }
            }

            return $this->respond([
                'status' => true,
                'message' => 'Progress desain berhasil diperbarui',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Gagal memperbarui progress desain',
                'data' => []
            ]);
        }
    }

    // =========================================================================
    // 6. GET INVOICES
    // =========================================================================
    public function invoices($designRequestId = null)
    {
        // Query dengan join ke table vouchers untuk mendapatkan nominal diskon
        $invoices = $this->db->table('project_invoices')
            ->select('project_invoices.*, vouchers.discount_nominal')
            ->join('vouchers', 'vouchers.code = project_invoices.voucher_code', 'left')
            ->where('design_request_id', $designRequestId)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        // Olah data untuk menambahkan kalkulasi nominal
        $formattedInvoices = array_map(function ($invoice) {
            $originalAmount = (int) ($invoice['amount'] ?? 0);
            $discountAmount = (int) ($invoice['discount_nominal'] ?? 0);
            $grossAmount = max(0, $originalAmount - $discountAmount);

            // Tambahkan field baru ke array
            $invoice['original_amount'] = $originalAmount;
            $invoice['discount_amount'] = $discountAmount;
            $invoice['gross_amount'] = $grossAmount;

            return $invoice;
        }, $invoices);

        if ($formattedInvoices) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail permohonan invoice ditemukan',
                'data' => $formattedInvoices
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada invoice untuk permohonan ini',
                'data' => []
            ]);
        }
    }
}
