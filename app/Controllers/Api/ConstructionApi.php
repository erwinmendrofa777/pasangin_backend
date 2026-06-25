<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Modules\Construction\Models\ConstructionMaterialSubmissionModel;

// Import class Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

class ConstructionApi extends BaseController
{
    use ResponseTrait;
    protected $db;
    protected $notifService;
    protected $constructionMaterialSubmissionModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notifService = new \App\Modules\Notifications\Services\NotificationService();
        $this->constructionMaterialSubmissionModel = new ConstructionMaterialSubmissionModel();
    }

    // =========================================================================
    // 1. FUNGSI UNTUK MENERIMA PENGAJUAN KONSTRUKSI BARU
    // =========================================================================
    public function submit()
    {
        // Cek apakah ada file gambar yang diunggah
        $hasUploadedImages = false;
        $tempImages = $this->request->getFileMultiple('images');
        if ($tempImages !== null) {
            foreach ($tempImages as $img) {
                if ($img && $img->getError() !== UPLOAD_ERR_NO_FILE) {
                    $hasUploadedImages = true;
                    break;
                }
            }
        } else {
            $singleImage = $this->request->getFile('images');
            if ($singleImage && $singleImage->getError() !== UPLOAD_ERR_NO_FILE) {
                $hasUploadedImages = true;
            }
        }

        $validationRules = [];
        $validationMessages = [];

        if ($hasUploadedImages) {
            $validationRules['images'] = 'max_size[images,5120]|mime_in[images,image/jpg,image/jpeg,image/png,image/webp]';
            $validationMessages['images'] = [
                'max_size' => 'Ukuran salah satu gambar melebihi 5MB.',
                'mime_in' => 'Format salah satu gambar tidak valid. Gunakan JPG, PNG, atau WebP.'
            ];
        }

        // Mencegah Error Undefined Array
        if (!empty($validationRules)) {
            if (!$this->validate($validationRules, $validationMessages)) {
                return $this->failValidationErrors($this->validator->getErrors());
            }
        }

        // 3. Ambil data yang sudah pasti tervalidasi aman
        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'design_request_id' => $this->request->getPost('design_requests_id') ?: null,
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone_number'),
            'land_area' => $this->request->getPost('land_area'),
            'building_area' => $this->request->getPost('building_area'),
            'survey_date' => $this->request->getPost('survey_date'),
            'address' => $this->request->getPost('address'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'voucher_code' => $this->request->getPost('voucher_code'),
            'survey_cost' => $this->request->getPost('survey_cost'),
            'discount_amount' => $this->request->getPost('discount_amount'),
            'total_payment' => $this->request->getPost('total_payment'),
            'status' => 'PENDING',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Gunakan getFileMultiple agar otomatis selalu menjadi array, fallback ke getFile jika single file
        $images = $this->request->getFileMultiple('images');
        if ($images === null) {
            $singleImage = $this->request->getFile('images');
            $images = ($singleImage && $singleImage->isValid()) ? [$singleImage] : [];
        }

        // Cek batasan maksimal 5 gambar
        if (count($images) > 5) {
            return $this->failValidationErrors('Anda hanya boleh mengunggah maksimal 5 gambar.');
        }

        $uploadedFileNames = [];

        // Pastikan folder tujuan ada, jika tidak, buat folder tersebut
        $uploadPath = FCPATH . 'uploads/construction/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // 4. Proses dan Pindahkan Setiap Gambar
        foreach ($images as $img) {
            if ($img->isValid() && !$img->hasMoved()) {
                $newName = $img->getRandomName();
                $img->move($uploadPath, $newName);
                $uploadedFileNames[] = $newName;
            }
        }

        // 5. Simpan ke database
        if (!empty($uploadedFileNames)) {
            foreach ($uploadedFileNames as $index => $fileName) {
                $data['gambar' . ($index + 1)] = $fileName;
            }
        }

        if ($this->db->table('construction_requests')->insert($data)) {
            $constructionId = $this->db->insertID();

            // Kirim notifikasi ke Admin  
            $this->notifService->sendToPermission(
                'construction_detail',
                'Permohonan Konstruksi Baru',
                "Pelanggan atas nama {$data['full_name']} telah mengirim permohonan konstruksi baru. Silakan cek detail."
            );

            return $this->respondCreated([
                'status' => true,
                'message' => 'Permohonan berhasil dikirim',
                'construction_id' => $constructionId
            ]);
        } else {
            return $this->fail('Gagal memperbarui data di database. Pastikan kolom gambar1-5 ada di allowedFields model.');
        }
    }

    public function submitConstructionAndDesignRequests()
    {
        // Cek apakah ada file gambar konstruksi yang diunggah
        $hasUploadedImages = false;
        $tempImages = $this->request->getFileMultiple('images');
        if ($tempImages !== null) {
            foreach ($tempImages as $img) {
                if ($img && $img->getError() !== UPLOAD_ERR_NO_FILE) {
                    $hasUploadedImages = true;
                    break;
                }
            }
        } else {
            $singleImage = $this->request->getFile('images');
            if ($singleImage && $singleImage->getError() !== UPLOAD_ERR_NO_FILE) {
                $hasUploadedImages = true;
            }
        }

        // 1. Validasi gambar konstruksi dan berkas desain
        $validationRules = [
            'design_files' => 'uploaded[design_files]|max_size[design_files,10240]|mime_in[design_files,application/pdf,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        $validationMessages = [
            'design_files' => [
                'uploaded' => 'Setidaknya satu berkas desain harus diunggah.',
                'max_size' => 'Ukuran salah satu berkas desain melebihi 10MB.',
                'mime_in' => 'Format salah satu berkas desain tidak valid. Gunakan PDF, JPG, PNG, atau WebP.'
            ]
        ];

        if ($hasUploadedImages) {
            $validationRules['images'] = 'max_size[images,5120]|mime_in[images,image/jpg,image/jpeg,image/png,image/webp]';
            $validationMessages['images'] = [
                'max_size' => 'Ukuran salah satu gambar konstruksi melebihi 5MB.',
                'mime_in' => 'Format salah satu gambar konstruksi tidak valid. Gunakan JPG, PNG, atau WebP.'
            ];
        }

        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Ambil dan proses gambar konstruksi
        $images = $this->request->getFileMultiple('images');
        if ($images === null) {
            $singleImage = $this->request->getFile('images');
            $images = ($singleImage && $singleImage->isValid()) ? [$singleImage] : [];
        }

        if (count($images) > 5) {
            return $this->failValidationErrors('Anda hanya boleh mengunggah maksimal 5 gambar konstruksi.');
        }

        $uploadedFileNames = [];
        $uploadPath = FCPATH . 'uploads/construction/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        foreach ($images as $img) {
            if ($img->isValid() && !$img->hasMoved()) {
                $newName = $img->getRandomName();
                $img->move($uploadPath, $newName);
                $uploadedFileNames[] = $newName;
            }
        }

        // Ambil dan proses berkas-berkas desain klien
        $designFiles = $this->request->getFileMultiple('design_files');
        if ($designFiles === null) {
            $singleDesign = $this->request->getFile('design_files');
            $designFiles = ($singleDesign && $singleDesign->isValid()) ? [$singleDesign] : [];
        }

        if (count($designFiles) > 5) {
            // Hapus file gambar konstruksi yang terlanjur diunggah
            foreach ($uploadedFileNames as $fileName) {
                if (file_exists($uploadPath . $fileName)) {
                    unlink($uploadPath . $fileName);
                }
            }
            return $this->failValidationErrors('Anda hanya boleh mengunggah maksimal 5 berkas desain.');
        }

        $uploadedDesignFileNames = [];
        $designUploadPath = FCPATH . 'uploads/design_results/';
        if (!is_dir($designUploadPath)) {
            mkdir($designUploadPath, 0777, true);
        }

        foreach ($designFiles as $designFile) {
            if ($designFile->isValid() && !$designFile->hasMoved()) {
                $newName = $designFile->getRandomName();
                $designFile->move($designUploadPath, $newName);
                $uploadedDesignFileNames[] = $newName;
            }
        }

        if (empty($uploadedDesignFileNames)) {
            // Hapus file gambar konstruksi yang terlanjur diunggah
            foreach ($uploadedFileNames as $fileName) {
                if (file_exists($uploadPath . $fileName)) {
                    unlink($uploadPath . $fileName);
                }
            }
            return $this->failValidationErrors('Gagal mengunggah berkas desain.');
        }

        // 2. Mulai transaksi database
        $this->db->transStart();

        try {
            // A. Simpan data Desain (Design Request)
            $designData = [
                'user_id' => $this->request->getPost('user_id'),
                'full_name' => $this->request->getPost('full_name'),
                'phone_number' => $this->request->getPost('phone_number'),
                'land_area' => $this->request->getPost('land_area'),
                'building_area' => $this->request->getPost('building_area'),
                'design_concept' => $this->request->getPost('design_concept'),
                'location_address' => $this->request->getPost('location_address') ?: $this->request->getPost('address'),
                'latitude' => $this->request->getPost('latitude'),
                'longitude' => $this->request->getPost('longitude'),
                'voucher_code' => $this->request->getPost('design_voucher_code') ?: $this->request->getPost('voucher_code'),
                'discount_amount' => $this->request->getPost('design_discount_amount') ?: $this->request->getPost('discount_amount') ?: 0,
                'status' => 'PENDING',
            ];

            $designModel = new \App\Modules\Design\Models\DesignRequestModel();
            if (!$designModel->insert($designData)) {
                throw new \RuntimeException('Gagal menyimpan data desain ke database.');
            }
            $designRequestId = $designModel->getInsertID();

            // Simpan setiap berkas desain hasil upload klien ke design_targets dan project_designs (1 target = 1 desain)
            $firstDesignFile = '';
            $firstDesignName = '';

            foreach ($uploadedDesignFileNames as $index => $fileName) {
                $num = $index + 1;

                // Memproses nama target (task_names[] / task_name)
                $taskNamesPost = $this->request->getPost('task_names') ?: $this->request->getPost('task_name');
                if (is_array($taskNamesPost)) {
                    $currentTaskName = isset($taskNamesPost[$index]) ? $taskNamesPost[$index] : (($this->request->getPost('task_name') ?: 'Desain Pengaju') . ($index > 0 ? ' ' . $num : ''));
                } else {
                    $currentTaskName = ($this->request->getPost('task_name') ?: 'Desain Pengaju') . ($index > 0 ? ' ' . $num : '');
                }

                // Memproses nama desain (design_names[] / design_name)
                $designNamesPost = $this->request->getPost('design_names') ?: $this->request->getPost('design_name');
                if (is_array($designNamesPost)) {
                    $currentDesignName = isset($designNamesPost[$index]) ? $designNamesPost[$index] : (($this->request->getPost('design_name') ?: 'Desain Klien') . ($index > 0 ? ' ' . $num : ''));
                } else {
                    $currentDesignName = ($this->request->getPost('design_name') ?: 'Desain Klien') . ($index > 0 ? ' ' . $num : '');
                }

                // A1. Simpan target desain untuk berkas ini
                $targetData = [
                    'design_request_id' => $designRequestId,
                    'user_admin_id' => null,
                    'task_name' => $currentTaskName,
                    'start_week' => $this->request->getPost('start_week') ?: 1,
                    'end_week' => $this->request->getPost('end_week') ?: 4,
                    'keterangan' => $this->request->getPost('keterangan') ?: 'Pengajuan desain diunggah secara manual oleh klien.',
                    'status' => 'PENDING',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if (!$this->db->table('design_targets')->insert($targetData)) {
                    throw new \RuntimeException("Gagal menyimpan target desain ke-{$num} ke database.");
                }
                $targetId = $this->db->insertID();

                // A2. Simpan berkas ke project_designs (revisi 1 untuk target baru ini)
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $detectedType = 'general';
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $detectedType = 'image';
                } elseif ($ext === 'pdf') {
                    $detectedType = 'pdf';
                } elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv'])) {
                    $detectedType = 'video';
                } elseif (in_array($ext, ['obj', 'fbx', 'glb', 'gltf', 'dwg', 'rvt'])) {
                    $detectedType = '3d';
                }

                $projectDesignData = [
                    'user_admin_id' => null,
                    'design_request_id' => $designRequestId,
                    'design_targets_id' => $targetId,
                    'revision_number' => 1,
                    'design_name' => $currentDesignName,
                    'file' => $fileName,
                    'status' => 'PENDING',
                    'revision_note' => null,
                    'design_type' => $detectedType,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if (!$this->db->table('project_designs')->insert($projectDesignData)) {
                    throw new \RuntimeException("Gagal menyimpan berkas desain ke-{$num} ke database.");
                }

                if ($index === 0) {
                    $firstDesignFile = $fileName;
                    $firstDesignName = $currentDesignName;
                }
            }

            // B. Simpan data Konstruksi (Construction Request)
            $constructionData = [
                'user_id' => $this->request->getPost('user_id'),
                'design_request_id' => $designRequestId,
                'full_name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone_number'),
                'land_area' => $this->request->getPost('land_area'),
                'building_area' => $this->request->getPost('building_area'),
                'address' => $this->request->getPost('address') ?: $this->request->getPost('location_address'),
                'latitude' => $this->request->getPost('latitude'),
                'longitude' => $this->request->getPost('longitude'),
                'voucher_code' => $this->request->getPost('construction_voucher_code') ?: $this->request->getPost('voucher_code'),
                'discount_amount' => $this->request->getPost('construction_discount_amount') ?: $this->request->getPost('discount_amount') ?: 0,
                'status' => 'PENDING',
                'created_at' => date('Y-m-d H:i:s')
            ];

            foreach ($uploadedFileNames as $index => $fileName) {
                $constructionData['gambar' . ($index + 1)] = $fileName;
            }

            if (!$this->db->table('construction_requests')->insert($constructionData)) {
                throw new \RuntimeException('Gagal menyimpan data konstruksi ke database.');
            }
            $constructionId = $this->db->insertID();

            // D. Kirim notifikasi ke Admin
            $this->notifService->sendToPermission(
                'design_detail',
                'Pengajuan Desain Baru',
                "Pelanggan atas nama {$designData['full_name']} telah mengajukan desain baru beserta berkas desain. Silakan cek detail."
            );

            $this->notifService->sendToPermission(
                'construction_detail',
                'Permohonan Konstruksi Baru',
                "Pelanggan atas nama {$constructionData['full_name']} telah mengirim permohonan konstruksi baru. Silakan cek detail."
            );

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal diselesaikan.');
            }

            return $this->respondCreated([
                'status' => true,
                'message' => 'Permohonan konstruksi dan desain berhasil dikirim secara bersamaan',
                'data' => [
                    'construction_id' => $constructionId,
                    'design_requests_id' => $designRequestId
                ]
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();

            // Hapus file gambar konstruksi yang sudah diunggah jika transaksi gagal
            foreach ($uploadedFileNames as $fileName) {
                if (file_exists($uploadPath . $fileName)) {
                    unlink($uploadPath . $fileName);
                }
            }

            // Hapus semua file desain yang sudah diunggah jika transaksi gagal
            foreach ($uploadedDesignFileNames as $fileName) {
                if (file_exists($designUploadPath . $fileName)) {
                    unlink($designUploadPath . $fileName);
                }
            }

            return $this->fail('Gagal memproses permohonan: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 2. FUNGSI UNTUK MENDAPATKAN LIST RIWAYAT PROYEK
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

        foreach ($projects as &$project) {
            $image_urls = [];
            for ($i = 1; $i <= 5; $i++) {
                if (!empty($project['gambar' . $i])) {
                    $image_urls[] = base_url('uploads/construction/' . $project['gambar' . $i]);
                }
            }
            $project['image_urls'] = $image_urls;
        }

        if ($projects) {
            return $this->respond([
                'status' => true,
                'message' => 'Proyek konstruksi ditemukan',
                'data' => $projects
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada permohonan konstruksi',
                'data' => $projects
            ]);
        }
    }

    // =========================================================================
    // 3. FUNGSI UNTUK MENDAPATKAN DETAIL PROYEK KONSTRUKSI
    // =========================================================================
    public function detail($projectId = null)
    {
        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }
        $project = $this->db->table('construction_requests')->where('id', $projectId)->get()->getRowArray();

        if ($project) {
            $image_urls = [];
            for ($i = 1; $i <= 5; $i++) {
                if (!empty($project['gambar' . $i])) {
                    $image_urls[] = base_url('uploads/construction/' . $project['gambar' . $i]);
                }
            }
            $project['image_urls'] = $image_urls;


            return $this->respond([
                'status' => true,
                'message' => 'Detail Proyek konstruksi ditemukan',
                'data' => $project
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada permohonan konstruksi',
                'data' => $project
            ]);
        }
    }

    // =========================================================================
    // 4. FUNGSI UNTUK MENDAPATKAN HASIL SURVEY PROYEK KONSTRUKSI
    // =========================================================================
    public function surveys($projectId = null)
    {
        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }

        $construction = $this->db->table('construction_requests')
            ->select('design_request_id')
            ->where('id', $projectId)
            ->get()
            ->getRowArray();

        if (!$construction || empty($construction['design_request_id'])) {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada survey untuk Proyek konstruksi ini',
                'data' => []
            ]);
        }

        $designRequestId = (int) $construction['design_request_id'];

        $psRepo = new \App\Modules\Design\Repositories\ProjectSurveysRepository();
        $surveys = $psRepo->findByDesignRequestId($designRequestId);

        foreach ($surveys as &$item) {
            $item['survey_title'] = $item['title'] ?? 'Survey';
            $item['survey_notes'] = $item['note'] ?? null;
            $item['survey_file'] = $item['file'] ?? null;

            $item['survey_files'] = !empty($item['file']) ? [base_url('uploads/survey/' . $item['file'])] : [];
            $item['image_url'] = !empty($item['file']) ? base_url('uploads/survey/' . $item['file']) : null;
        }

        if ($surveys) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail Survey Proyek konstruksi ditemukan',
                'data' => $surveys
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada survey untuk Proyek konstruksi ini',
                'data' => $surveys
            ]);
        }
    }

    // =========================================================================
    // 5. FUNGSI UNTUK MENDAPATKAN HASIL DESAIN PROYEK KONSTRUKSI
    // =========================================================================
    public function designs($projectId = null)
    {
        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }

        $construction = $this->db->table('construction_requests')
            ->select('design_request_id')
            ->where('id', $projectId)
            ->get()
            ->getRowArray();

        if (!$construction || empty($construction['design_request_id'])) {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada desain untuk Proyek konstruksi ini',
                'data' => []
            ]);
        }

        $designRequestId = (int) $construction['design_request_id'];

        $pdRepo = new \App\Modules\Design\Repositories\ProjectDesignsRepository();
        $designs = $pdRepo->findWithTaskByDesignRequestId($designRequestId);
        $designs = array_filter($designs, fn($item) => ($item['status'] ?? '') === 'APPROVED');

        foreach ($designs as &$item) {
            $item['title'] = $item['design_name'] ?? 'Desain';
            $item['design_requests_id'] = $item['design_request_id'];
            $item['comment'] = $item['revision_note'] ?? null;
            $item['image_url'] = !empty($item['file']) ? base_url('uploads/design_results/' . $item['file']) : null;
        }

        $designs = array_values($designs);

        if ($designs) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail Desain Proyek konstruksi ditemukan',
                'data' => $designs
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada desain untuk Proyek konstruksi ini',
                'data' => $designs
            ]);
        }
    }

    // =========================================================================
    // 6. FUNGSI UNTUK MENDAPATKAN PROGRESS PROYEK KONSTRUKSI
    // =========================================================================
    public function progress($projectId = null)
    {
        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }

        $progressListRaw = $this->db->table('construction_progress cp')
            ->select('
                cp.id as progress_id, cp.volume as progress_bobot, cp.photo_url as progress_photo, cp.created_at as progress_date, cp.status as progress_status,
                ct.id as target_id, COALESCE(cr.volume, ca.volume) as target_bobot,
                COALESCE(cr.group_name, ca.group_name) as rab_group, COALESCE(cr.sub_group_name, ca.sub_group_name) as rab_subgroup, COALESCE(ah.uraian, ca.activity_name) as rab_activity,
                ja.tukang_name, (SELECT GROUP_CONCAT(ts.skill_name SEPARATOR \', \') FROM tukang_skill_map tsm JOIN tukang_skill ts ON ts.id = tsm.tukang_skill_id WHERE tsm.tukang_id = ja.tukang_id) as specialization, t.profile_photo, ja.tukang_id as id_tukang,
                tr.id as rating_id, tr.skill_score, tr.behavior_score, tr.comment as rating_comment, tr.created_at as rating_created_at
            ')
            ->join('construction_targets ct', 'ct.id = cp.id_construction_targets', 'inner')
            ->join('rabs cr', 'cr.id = ct.id_construction_rabs', 'left')
            ->join('ahsp ah', 'ah.id = cr.ahsp_id', 'left')
            ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum', 'left')
            ->join('job_applications ja', 'ja.id = ct.id_job_applications', 'left')
            ->join('tukang t', 't.id = ja.tukang_id', 'left')
            ->join('tukang_rating tr', "tr.target_id = ct.id AND tr.id_tukang = t.id AND tr.project_type = 'construction'", 'left')
            ->where('cp.construction_id', $projectId)
            ->whereIn('cp.status', ['APPROVED', 'PENDING_CLIENT'])
            ->orderBy('cp.created_at', 'ASC')
            ->get()->getResultArray();

        $groupedByTarget = [];

        foreach ($progressListRaw as $p) {
            $tId = $p['target_id'];
            if (!$tId)
                continue;

            if (!isset($groupedByTarget[$tId])) {
                // Formatting Pekerjaan
                $sub = !empty($p['rab_subgroup']) ? ' - ' . $p['rab_subgroup'] : '';
                $header_pekerjaan = ($p['rab_group'] ?? '') . $sub;
                $pekerjaan = $p['rab_activity'] ?? '-';

                $groupedByTarget[$tId] = [
                    'id_tukang' => $p['id_tukang'] ?? '-',
                    'foto_tukang' => !empty($p['profile_photo']) ? base_url('uploads/tukang/' . $p['profile_photo']) : null,
                    'nama_tukang' => $p['tukang_name'] ?? '-',
                    'spesialis_tukang' => $p['specialization'] ?? '-',
                    'header_pekerjaan' => trim(trim($header_pekerjaan, ' -')),
                    'pekerjaan' => $pekerjaan,
                    'persentase' => 0, // di-kalkulasi di akhir
                    'laporan_terakhir' => null,
                    'foto_progress' => [],
                    'rating' => $p['rating_id'] ?? null,
                    'comment' => $p['rating_comment'] ?? null,
                    'skill_score' => $p['skill_score'] ?? null,
                    'behavior_score' => $p['behavior_score'] ?? null,
                    'created_at_rating' => $p['rating_created_at'],
                    // Temp variable untuk kalkulasi
                    '_target_bobot' => (float) ($p['target_bobot'] ?? 0),
                    '_total_progress_bobot' => 0
                ];
            }

            // Akumulasi bobot progress
            if (strtoupper($p['progress_status']) === 'APPROVED') {
                $groupedByTarget[$tId]['_total_progress_bobot'] += (float) $p['progress_bobot'];
            }

            // Kumpulkan foto progress
            if (!empty($p['progress_photo'])) {
                $groupedByTarget[$tId]['foto_progress'][] = base_url('uploads/construction/progress/' . $p['progress_photo']);
            }

            // Perbarui laporan_terakhir (waktu paling baru)
            $pDate = $p['progress_date'];
            if (!$groupedByTarget[$tId]['laporan_terakhir'] || strtotime($pDate) > strtotime($groupedByTarget[$tId]['laporan_terakhir'])) {
                $groupedByTarget[$tId]['laporan_terakhir'] = $pDate;
            }
        }

        $progressList = [];
        foreach ($groupedByTarget as $tId => $grp) {
            $ttBobot = $grp['_target_bobot'];
            $progBobot = $grp['_total_progress_bobot'];
            // accumulated progress bobot / target bobot * 100
            $persentase = $ttBobot > 0 ? ($progBobot / $ttBobot * 100) : 0;

            $progressList[] = [
                'id_tukang' => $grp['id_tukang'] ?? '-',
                'foto_tukang' => $grp['foto_tukang'],
                'nama_tukang' => $grp['nama_tukang'],
                'spesialis_tukang' => $grp['spesialis_tukang'],
                'header_pekerjaan' => $grp['header_pekerjaan'],
                'pekerjaan' => $grp['pekerjaan'],
                'persentase' => number_format($persentase, 2, '.', ''), // output as string decimal suitable for app formatting
                'laporan_terakhir' => date('Y-m-d H:i:s', strtotime($grp['laporan_terakhir'])),
                'foto_progress' => $grp['foto_progress'],
                'target_id' => $tId,
                'rating' => $grp['rating'],
                'comment' => $grp['comment'],
                'skill_score' => $grp['skill_score'],
                'behavior_score' => $grp['behavior_score'],
                'created_at_rating' => $grp['created_at_rating'],
            ];
        }

        // Urutkan List progress berdasarkan laporan_terakhir paling baru (DESC)
        usort($progressList, function ($a, $b) {
            return strtotime($b['laporan_terakhir']) < strtotime($a['laporan_terakhir']) ? 1 : -1;
        });

        if ($progressList) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail Progress Proyek konstruksi ditemukan',
                'data' => $progressList
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada progress untuk Proyek konstruksi ini',
                'data' => $progressList
            ]);
        }
    }

    public function progressList($projectId = null)
    {
        if ($projectId === null) {
            return $this->fail('Project ID tidak boleh kosong.', 400);
        }

        $progress = $this->db->table('construction_progress cp')
            ->select('
                cp.id, cp.id_construction_targets, cp.construction_id, cp.week_number, cp.volume, cp.description, cp.status, cp.photo_url, cp.created_at,
                COALESCE(cr.group_name, ca.group_name) as rab_group, COALESCE(cr.sub_group_name, ca.sub_group_name) as rab_subgroup, COALESCE(ah.uraian, ca.activity_name) as rab_activity,
                ja.tukang_name
            ')
            ->join('construction_targets ct', 'ct.id = cp.id_construction_targets', 'inner')
            ->join('rabs cr', 'cr.id = ct.id_construction_rabs', 'left')
            ->join('ahsp ah', 'ah.id = cr.ahsp_id', 'left')
            ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum', 'left')
            ->join('job_applications ja', 'ja.id = ct.id_job_applications', 'left')
            ->where('cp.construction_id', $projectId)
            ->orderBy('cp.created_at', 'DESC')
            ->get()->getResultArray();

        foreach ($progress as &$item) {
            $item['photo_url'] = !empty($item['photo_url']) ? base_url('uploads/construction/progress/' . $item['photo_url']) : null;
        }

        return $this->respond([
            'status' => true,
            'message' => 'Daftar Laporan Progress Konstruksi',
            'data' => $progress
        ]);
    }

    public function approveProgress($id = null)
    {
        if ($id === null) {
            return $this->fail('Progress ID tidak boleh kosong.', 400);
        }

        $progress = $this->db->table('construction_progress')->where('id', $id)->get()->getRowArray();
        if (!$progress) {
            return $this->failNotFound('Laporan progress tidak ditemukan.');
        }

        if ($progress['status'] !== 'PENDING_CLIENT') {
            return $this->fail('Laporan progress tidak dalam status menunggu persetujuan client.', 400);
        }

        try {
            $this->db->transStart();

            // Set status to APPROVED
            $this->db->table('construction_progress')->where('id', $id)->update(['status' => 'APPROVED']);

            // Recalculate target & project status using the service logic
            $svc = new \App\Modules\Construction\Services\ConstructionService();
            $svc->updateProgressStatus((int) $id, 'APPROVED');

            // --- PROSES TRANSFER DANA OTOMATIS KE MANDOR ---
            $target = $this->db->table('construction_targets')->where('id', $progress['id_construction_targets'])->get()->getRowArray();
            if ($target && !empty($target['id_job_applications'])) {
                $ja = $this->db->table('job_applications')->where('id', $target['id_job_applications'])->get()->getRowArray();
                if ($ja && !empty($ja['tukang_id'])) {
                    $tukangId = (int) $ja['tukang_id'];
                    $paymentAmount = 0.0;
                    $activityName = 'Pekerjaan Konstruksi';
                    $unit = '';

                    // Jika target terhubung dengan RAB
                    if (!empty($target['id_construction_rabs'])) {
                        $rab = $this->db->table('rabs')->where('id', $target['id_construction_rabs'])->get()->getRowArray();
                        if ($rab) {
                            $unit = $rab['unit'] ?? '';
                            // Dapatkan nama aktivitas dari AHSP
                            if (!empty($rab['ahsp_id'])) {
                                $ahsp = $this->db->table('ahsp')->where('id', $rab['ahsp_id'])->get()->getRowArray();
                                if ($ahsp) {
                                    $activityName = $ahsp['uraian'];
                                }

                                // Hitung total upah tenaga kerja per unit volume dari ahsp_tenaga_kerja
                                $laborTotal = $this->db->table('ahsp_tenaga_kerja')
                                    ->select('SUM(harga_satuan * koefisien) as total')
                                    ->where('ahsp_id', $rab['ahsp_id'])
                                    ->get()->getRowArray();
                                $upahPerVolume = (float) ($laborTotal['total'] ?? 0);
                                $paymentAmount = (float) $progress['volume'] * $upahPerVolume;
                            }
                        }
                    }

                    if ($paymentAmount > 0) {
                        $desc = "Pembayaran upah progress pekerjaan " . $activityName . " volume " . $progress['volume'] . " " . $unit;
 
                        // Cek apakah mandor/tukang ini memiliki grup
                        $group = $this->db->table('tukang_group')->where('tukang_id', $tukangId)->get()->getRowArray();
 
                        if ($group) {
                            // Skenario A: Masuk ke saldo kelompok (group balance)
                            $newGroupBalance = (float)$group['balance'] + $paymentAmount;
                            $this->db->table('tukang_group')
                                ->where('id', $group['id'])
                                ->update(['balance' => $newGroupBalance]);
 
                            // Catat transaksi masuk (inflow) di group_transactions
                            $this->db->table('group_transactions')->insert([
                                'group_id' => $group['id'],
                                'amount' => $paymentAmount,
                                'type' => 'inflow',
                                'source_project_type' => 'construction',
                                'source_invoice_id' => null,
                                'description' => $desc,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        } else {
                            // Skenario B: Masuk ke wallet personal mandor/tukang
                            $walletService = new \App\Modules\Wallets\Services\WalletService();
                            $walletService->updateBalance($tukangId, $paymentAmount, 'income', $desc);
                        }
                    }
                }
            }
            // ----------------------------------------------

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->fail('Gagal menyetujui progress.');
            }

            // Kirim notifikasi ke tukang & admin
            $title = "Progress Disetujui Client";
            $message = "Client telah menyetujui laporan progress untuk target pengerjaan di Proyek #{$progress['construction_id']}.";

            // Notify tukang
            if (isset($target) && !empty($target['id_job_applications'])) {
                $ja = $this->db->table('job_applications')->where('id', $target['id_job_applications'])->get()->getRowArray();
                if ($ja && !empty($ja['tukang_id'])) {
                    $this->notifService->sendPersonal('tukang', (int) $ja['tukang_id'], $title, $message);
                }
            }

            // Notify admin
            $this->notifService->sendToPermission('construction_progress', $title, $message);

            return $this->respond([
                'status' => true,
                'message' => 'Laporan progress berhasil disetujui!'
            ]);

        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->fail($th->getMessage());
        }
    }

    public function rejectProgress($id = null)
    {
        if ($id === null) {
            return $this->fail('Progress ID tidak boleh kosong.', 400);
        }

        $progress = $this->db->table('construction_progress')->where('id', $id)->get()->getRowArray();
        if (!$progress) {
            return $this->failNotFound('Laporan progress tidak ditemukan.');
        }

        if ($progress['status'] !== 'PENDING_CLIENT') {
            return $this->fail('Laporan progress tidak dalam status menunggu persetujuan client.', 400);
        }

        // Set status kembali ke PENDING
        $svc = new \App\Modules\Construction\Services\ConstructionService();
        $svc->updateProgressStatus((int) $id, 'PENDING');

        // Kirim notifikasi ke tukang & admin
        $title = "Progress Ditinjau Kembali (Ditolak Client)";
        $message = "Client menolak laporan progress di Proyek #{$progress['construction_id']}. Laporan progres dikembalikan ke status PENDING.";

        $target = $this->db->table('construction_targets')->where('id', $progress['id_construction_targets'])->get()->getRowArray();
        if ($target && !empty($target['id_job_applications'])) {
            $ja = $this->db->table('job_applications')->where('id', $target['id_job_applications'])->get()->getRowArray();
            if ($ja && !empty($ja['tukang_id'])) {
                $this->notifService->sendPersonal('tukang', (int) $ja['tukang_id'], $title, $message);
            }
        }
        $this->notifService->sendToPermission('construction_progress', $title, $message);

        return $this->respond([
            'status' => true,
            'message' => 'Laporan progress berhasil ditolak dan dikembalikan ke status PENDING.'
        ]);
    }

    // =========================================================================
    // 7. FUNGSI UNTUK MENDAPATKAN INVOICE PROYEK KONSTRUKSI
    // =========================================================================
    public function invoices($projectId = null)
    {
        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }

        $invoices = $this->db->table('construction_invoices')
            ->select('construction_invoices.*, vouchers.discount_nominal')
            ->join('vouchers', 'vouchers.code = construction_invoices.voucher_code', 'left')
            ->where('construction_id', $projectId)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        $formattedInvoices = array_map(function ($invoice) {
            $originalAmount = (int) ($invoice['amount'] ?? 0);
            $discountAmount = (int) ($invoice['discount_nominal'] ?? 0);
            $grossAmount = max(0, $originalAmount - $discountAmount);

            $invoice['original_amount'] = $originalAmount;
            $invoice['discount_amount'] = $discountAmount;
            $invoice['gross_amount'] = $grossAmount;

            $rabId = $invoice['rab_id'] ?? null;
            if ($rabId) {
                $ahspBahan = [];
                $ahspTenaga = [];

                // Get RAB details
                $rab = $this->db->table('rabs')->where('id', $rabId)->get()->getRowArray();
                if ($rab) {
                    $ahspId = $rab['ahsp_id'];
                    $volume = (float) ($rab['volume'] ?? 0);

                    // Get Selected materials with product details
                    $selectedMaterials = $this->db->table('rab_materials crm')
                        ->select('crm.*, p.name as product_name, p.price as product_price, p.unit as product_unit')
                        ->join('products p', 'p.id = crm.product_id', 'left')
                        ->where('crm.rab_id', $rabId)
                        ->where('crm.selected', 1)
                        ->get()
                        ->getResultArray();

                    $selectedMap = [];
                    foreach ($selectedMaterials as $sm) {
                        $selectedMap[$sm['ahsp_bahan_id']] = [
                            'name' => $sm['product_name'],
                            'price' => (float) $sm['product_price'],
                            'unit' => $sm['product_unit']
                        ];
                    }

                    // Get list of materials (ahsp_bahan)
                    $bahanList = $this->db->table('ahsp_bahan')->where('ahsp_id', $ahspId)->get()->getResultArray();
                    foreach ($bahanList as $b) {
                        $selProduct = $selectedMap[$b['id']] ?? null;

                        // Fallback price logic
                        $hargaSatuan = 0.0;
                        if ($selProduct) {
                            $hargaSatuan = $selProduct['price'];
                        } else {
                            $bahanUraianClean = strtolower(trim($b['uraian'] ?? ''));
                            $matchedProduct = $this->db->table('products')
                                ->where('LOWER(TRIM(name))', $bahanUraianClean)
                                ->get()->getRowArray();
                            if ($matchedProduct) {
                                $hargaSatuan = (float) $matchedProduct['price'];
                                $selProduct = [
                                    'name' => $matchedProduct['name'],
                                    'price' => (float) $matchedProduct['price'],
                                    'unit' => $matchedProduct['unit']
                                ];
                            }
                        }

                        $koef = (float) ($b['koefisien'] ?? 0);
                        $jumlahHargaSatuan = $koef * $hargaSatuan;
                        $hargaTotal = $jumlahHargaSatuan * $volume;

                        $ahspBahan[] = [
                            'uraian' => $b['uraian'],
                            'satuan' => $b['satuan'],
                            'koefisien' => $koef,
                            'selected_product' => $selProduct,
                            'jumlah_harga_satuan' => $jumlahHargaSatuan,
                            'harga_total' => $hargaTotal
                        ];
                    }

                    // Get list of workers (ahsp_tenaga_kerja)
                    $tenagaList = $this->db->table('ahsp_tenaga_kerja')->where('ahsp_id', $ahspId)->get()->getResultArray();
                    foreach ($tenagaList as $t) {
                        $koef = (float) ($t['koefisien'] ?? 0);
                        $hargaSatuan = (float) ($t['harga_satuan'] ?? 0);
                        $jumlahHargaSatuan = $koef * $hargaSatuan;
                        $hargaTotal = $jumlahHargaSatuan * $volume;

                        $ahspTenaga[] = [
                            'uraian' => $t['uraian'],
                            'satuan' => $t['satuan'],
                            'koefisien' => $koef,
                            'harga_satuan' => $hargaSatuan,
                            'jumlah_harga_satuan' => $jumlahHargaSatuan,
                            'harga_total' => $hargaTotal
                        ];
                    }
                }

                $invoice['rab_id'] = [
                    'id' => (int) $rabId,
                    'ahsp_bahan' => $ahspBahan,
                    'ahsp_tenaga_kerja' => $ahspTenaga
                ];
            } else {
                $invoice['rab_id'] = null;
            }

            return $invoice;
        }, $invoices);

        if ($formattedInvoices) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail Invoice Proyek konstruksi ditemukan',
                'data' => $formattedInvoices
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada invoice untuk Proyek konstruksi ini',
                'data' => []
            ]);
        }
    }

    public function progressByUser()
    {

        $user_id = $this->request->user->uid ?? null;

        if ($user_id == null) {
            return $this->failUnauthorized('Akses ditolak. Token tidak valid atau tidak ditemukan.');
        }

        $projects = $this->db->table('construction_requests cr')
            ->select('
                cr.id as construction_id,
                u.full_name, 
                cr.start_date, 
                cr.address,
                (SELECT count(id) FROM construction_targets WHERE construction_id = cr.id) as total_target
            ')
            ->join('users u', 'u.id = cr.user_id', 'left')
            ->where('cr.user_id', $user_id)
            ->orderBy('cr.created_at', 'DESC')
            ->get()->getResultArray();

        $today = new \DateTime();

        foreach ($projects as &$item) {
            $cId = $item['construction_id'];

            // Hitung total anggaran (RAB + Addendum)
            $totalRAB = $this->db->table('rabs')
                ->where('construction_id', $cId)
                ->selectSum('total_price')
                ->get()->getRowArray()['total_price'] ?? 0;

            $totalAddendum = $this->db->table('construction_addendum')
                ->where('construction_id', $cId)
                ->selectSum('total_price')
                ->get()->getRowArray()['total_price'] ?? 0;

            $totalBudget = $totalRAB + $totalAddendum;

            // Hitung total realisasi harga (volume disetujui * harga satuan)
            $realizationRAB = $this->db->table('construction_progress cp')
                ->join('construction_targets ct', 'ct.id = cp.id_construction_targets')
                ->join('rabs cr', 'cr.id = ct.id_construction_rabs')
                ->where('cp.construction_id', $cId)
                ->where('cp.status', 'APPROVED')
                ->select('SUM(cp.volume * cr.current_unit_price) as realization')
                ->get()->getRowArray()['realization'] ?? 0;

            $realizationAddendum = $this->db->table('construction_progress cp')
                ->join('construction_targets ct', 'ct.id = cp.id_construction_targets')
                ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum')
                ->where('cp.construction_id', $cId)
                ->where('cp.status', 'APPROVED')
                ->select('SUM(cp.volume * ca.current_unit_price) as realization')
                ->get()->getRowArray()['realization'] ?? 0;

            $totalRealization = $realizationRAB + $realizationAddendum;

            $item['total_realisasi'] = $totalBudget > 0 ? ($totalRealization / $totalBudget) * 100 : 0;

            $item['currentweek'] = 0;
            if (!empty($item['start_date'])) {
                $start = new \DateTime($item['start_date']);
                if ($today >= $start) {
                    $diffDays = $today->diff($start)->days;
                    $item['currentweek'] = floor($diffDays / 7) + 1;
                }
            }

            $item['total_target'] = (float) $item['total_target'];
            $item['total_realisasi'] = (float) $item['total_realisasi'];

            $hasPending = $this->db->table('construction_progress')
                ->where('construction_id', $cId)
                ->where('status', 'PENDING_CLIENT')
                ->countAllResults() > 0;
            $item['hasPendingApproval'] = $hasPending;
        }

        if (!empty($projects)) {
            return $this->respond([
                'status' => true,
                'message' => 'Daftar Proyek renovasi ditemukan',
                'data' => $projects
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada proyek renovasi untuk user ini',
                'data' => []
            ]);
        }
    }

    // =========================================================================
    // 8. FUNGSI UNTUK MEMILIH MATERIAL RAB (UPDATE DATABASE)
    // =========================================================================
    public function select_material()
    {
        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = [
                'id' => $this->request->getVar('id'),
                'rab_id' => $this->request->getVar('rab_id'),
                'product_id' => $this->request->getVar('product_id'),
                'ahsp_bahan_id' => $this->request->getVar('ahsp_bahan_id')
            ];
        }

        $id = $json['id'] ?? null;
        $rabId = $json['rab_id'] ?? null;
        $productId = $json['product_id'] ?? null;
        $ahspBahanId = $json['ahsp_bahan_id'] ?? null;

        if (!$id && (!$rabId || !$productId)) {
            return $this->fail('Parameter id rekomendasi atau (rab_id dan product_id) tidak ditemukan.');
        }

        if (!$id) {
            // Cari row rekomendasi di database
            $query = $this->db->table('rab_materials')
                ->where('rab_id', $rabId)
                ->where('product_id', $productId);
            if ($ahspBahanId) {
                $query->where('ahsp_bahan_id', $ahspBahanId);
            }
            $rec = $query->get()->getRowArray();
            if (!$rec) {
                return $this->fail('Pilihan rekomendasi produk tidak ditemukan untuk item ini.');
            }
            $id = $rec['id'];
            $ahspBahanId = $rec['ahsp_bahan_id'];
        } else {
            $rec = $this->db->table('rab_materials')->where('id', $id)->get()->getRowArray();
            if (!$rec) {
                return $this->fail('Rekomendasi produk tidak ditemukan.');
            }
            $rabId = $rec['rab_id'];
            $ahspBahanId = $rec['ahsp_bahan_id'];
        }

        // Cek lock
        $existing = $this->db->table('rabs')->where('id', $rabId)->get()->getRowArray();
        if ($existing && $existing['is_locked'] == 1) {
            return $this->fail('Tidak dapat memperbarui material, RAB sudah dikunci.');
        }

        try {
            $this->db->transStart();

            // Set semua rekomendasi untuk bahan ini ke selected = 0
            $this->db->table('rab_materials')
                ->where('rab_id', $rabId)
                ->where('ahsp_bahan_id', $ahspBahanId)
                ->update(['selected' => 0]);

            // Set rekomendasi terpilih ke selected = 1
            $this->db->table('rab_materials')
                ->where('id', $id)
                ->update(['selected' => 1]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->fail('Gagal menyimpan pilihan material.');
            }

            // Hitung ulang harga baris RAB
            $newUnitPrice = $this->recalculateRabRowPrice($rabId);

            // Ambil data terbaru baris RAB
            $updatedRab = $this->db->table('rabs')->where('id', $rabId)->get()->getRowArray();

            return $this->respond([
                'status' => true,
                'message' => 'Pilihan produk material berhasil diperbarui!',
                'data' => [
                    'rab_id' => (int) $rabId,
                    'ahsp_bahan_id' => (int) $ahspBahanId,
                    'selected_product_id' => (int) $rec['product_id'],
                    'new_unit_price' => (float) $newUnitPrice,
                    'total_price' => (float) ($updatedRab['total_price'] ?? 0)
                ]
            ]);

        } catch (\Throwable $th) {
            return $this->fail('Gagal memperbarui material dengan error: ' . $th->getMessage());
        }
    }

    private function recalculateRabRowPrice($rabId)
    {
        $rab = $this->db->table('rabs')->where('id', $rabId)->get()->getRowArray();
        if (!$rab) {
            return 0;
        }

        $ahspId = $rab['ahsp_id'];

        // 1. Hitung total tenaga kerja dari ahsp_tenaga_kerja
        $laborSum = $this->db->table('ahsp_tenaga_kerja')
            ->select('SUM(harga_satuan * koefisien) AS total')
            ->where('ahsp_id', $ahspId)
            ->get()->getRowArray();
        $totalTenaga = (float) ($laborSum['total'] ?? 0);

        // 2. Hitung total bahan
        $requiredBahan = $this->db->table('ahsp_bahan')->where('ahsp_id', $ahspId)->get()->getResultArray();

        $allProducts = $this->db->table('products')->select('id, name, price')->get()->getResultArray();

        // Ambil produk terpilih (yang selected = 1) untuk rab_id ini
        $selectedMaterials = $this->db->table('rab_materials')
            ->where('rab_id', $rabId)
            ->where('selected', 1)
            ->get()->getResultArray();

        $selectedMap = [];
        foreach ($selectedMaterials as $sm) {
            $selectedMap[$sm['ahsp_bahan_id']] = $sm['product_id'];
        }

        $productMap = [];
        foreach ($allProducts as $p) {
            $productMap[$p['id']] = $p;
        }

        $totalBahan = 0;
        foreach ($requiredBahan as $rb) {
            $koef = (float) ($rb['koefisien'] ?? 0);

            if (isset($selectedMap[$rb['id']])) {
                $selProdId = $selectedMap[$rb['id']];
                if (isset($productMap[$selProdId])) {
                    $totalBahan += $koef * (float) $productMap[$selProdId]['price'];
                }
            } else {
                // Fallback pencarian produk otomatis berdasarkan nama
                $bahanUraianClean = strtolower(trim($rb['uraian'] ?? ''));
                $matchedProductPrice = 0;

                foreach ($allProducts as $p) {
                    $pNameClean = strtolower(trim($p['name'] ?? ''));
                    if ($pNameClean === $bahanUraianClean || strpos($pNameClean, $bahanUraianClean) !== false || strpos($bahanUraianClean, $pNameClean) !== false) {
                        $matchedProductPrice = (float) $p['price'];
                        break;
                    }
                }

                $totalBahan += $koef * $matchedProductPrice;
            }
        }

        $newUnitPrice = $totalTenaga + $totalBahan;
        $totalPrice = (float) ($rab['volume'] ?? 0) * $newUnitPrice;

        // Update ke database
        $this->db->table('rabs')
            ->where('id', $rabId)
            ->update([
                'current_unit_price' => $newUnitPrice,
                'total_price' => $totalPrice,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return $newUnitPrice;
    }

    // =========================================================================
    // 9. FUNGSI UNTUK MENDAPATKAN DATA RAB PROYEK KONSTRUKSI
    // =========================================================================
    public function rabs($projectId = null)
    {
        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }

        $romanNumber = $this->request->getGet('roman_number') ?? $this->request->getVar('roman_number');

        try {
            $query = $this->db->table('rabs')->where('construction_id', $projectId);
            if (!empty($romanNumber)) {
                $query->where('LOWER(TRIM(roman_number))', strtolower(trim($romanNumber)));
            }
            $rabData = $query->orderBy('created_at', 'DESC')->get()->getResultArray();

            foreach ($rabData as &$rab) {
                $rab['image_url'] = !empty($rab['file']) ? base_url('uploads/construction/rab/' . $rab['file']) : null;

                // Ambil info master AHSP
                $ahspInfo = $this->db->table('ahsp')
                    ->where('id', $rab['ahsp_id'])
                    ->get()->getRowArray();

                if ($ahspInfo) {
                    $ahspInfo['id'] = (int) $ahspInfo['id'];
                } else {
                    $ahspInfo = [
                        'id' => (int) $rab['ahsp_id'],
                        'kode' => '',
                        'uraian' => $rab['activity_name'] ?? '',
                        'satuan' => $rab['unit'] ?? ''
                    ];
                }

                // 1. Ambil data ahsp_tenaga_kerja
                $tenagaKerja = $this->db->table('ahsp_tenaga_kerja')
                    ->where('ahsp_id', $rab['ahsp_id'])
                    ->orderBy('id', 'ASC')
                    ->get()->getResultArray();
                foreach ($tenagaKerja as &$tk) {
                    $tk['id'] = (int) $tk['id'];
                    $tk['ahsp_id'] = (int) $tk['ahsp_id'];
                    $tk['koefisien'] = (float) $tk['koefisien'];
                    $tk['harga_satuan'] = (float) $tk['harga_satuan'];
                }
                $ahspInfo['tenaga_kerja'] = $tenagaKerja;

                // 2. Ambil data ahsp_bahan
                $bahanList = $this->db->table('ahsp_bahan')
                    ->where('ahsp_id', $rab['ahsp_id'])
                    ->orderBy('id', 'ASC')
                    ->get()->getResultArray();

                // 3. Ambil data rekomendasi produk untuk rab_id ini
                $recommendations = $this->db->table('rab_materials crm')
                    ->select('crm.*, p.name as product_name, p.price as product_price, p.unit as product_unit, p.stock as product_stock, p.photo as product_photo, p.description as product_description')
                    ->join('products p', 'p.id = crm.product_id', 'left')
                    ->where('crm.rab_id', $rab['id'])
                    ->get()->getResultArray();

                $recsByBahanId = [];
                foreach ($recommendations as $rec) {
                    $recsByBahanId[$rec['ahsp_bahan_id']][] = [
                        'id' => (int) $rec['id'],
                        'rab_id' => (int) $rec['rab_id'],
                        'product_id' => (int) $rec['product_id'],
                        'ahsp_bahan_id' => (int) $rec['ahsp_bahan_id'],
                        'selected' => (int) $rec['selected'] === 1,
                        'product_name' => $rec['product_name'],
                        'product_price' => (float) $rec['product_price'],
                        'product_unit' => $rec['product_unit'],
                        'product_stock' => (int) $rec['product_stock'],
                        'product_description' => $rec['product_description'],
                        'product_image_url' => !empty($rec['product_photo']) ? (strpos($rec['product_photo'], 'http') === 0 ? $rec['product_photo'] : base_url('uploads/products/' . $rec['product_photo'])) : null,
                    ];
                }

                foreach ($bahanList as &$b) {
                    $b['id'] = (int) $b['id'];
                    $b['ahsp_id'] = (int) $b['ahsp_id'];
                    $b['koefisien'] = (float) $b['koefisien'];
                    $b['recommendations'] = $recsByBahanId[$b['id']] ?? [];
                }
                $ahspInfo['bahan'] = $bahanList;

                $rab['ahsp'] = $ahspInfo;
            }
        } catch (\Throwable $th) {
            return $this->fail('Gagal mendapatkan data RAB dengan error : ' . $th->getMessage());
        }

        if ($rabData) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail RAB Proyek konstruksi ditemukan',
                'data' => $rabData
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada RAB untuk Proyek konstruksi ini',
                'data' => $rabData
            ]);
        }
    }

    // =========================================================================
    // 10. FINALIZE / LOCK RAB
    // =========================================================================
    public function finalize_rab()
    {
        $json = $this->request->getJSON(true);
        $projectId = $json['project_id'] ?? $this->request->getVar('project_id');

        if (!$projectId)
            return $this->fail('Project ID tidak ditemukan.');

        try {
            // 1. generate dan upload kontrak.pdf
            helper('terbilang');
            $tanggal_kontrak = date('Y-m-d');

            $data = [
                // construction_rabs - construction_requests - users
                'template_kontrak' => $this->db->table('construction_requests')
                    ->select('construction_requests.address as address_construction,
                                                    construction_requests.id as construction_id,
                                                    construction_requests.start_date,
                                                    construction_requests.week,
                                                    users.full_name as nama_klien,
                                                    users.nik as nik_klien,
                                                    users.address as address_klien,
                                                    vouchers.discount_nominal')
                    ->join('users', 'users.id = construction_requests.user_id', 'left')
                    ->join('vouchers', 'vouchers.code = construction_requests.voucher_code', 'left')
                    ->where('construction_requests.id', $projectId)
                    ->get()->getRowArray(),
                'rab' => $this->db->table('rabs')
                    ->select('group_name, SUM(total_price) as total_price')
                    ->where('rabs.construction_id', $projectId)
                    ->groupBy(['roman_number', 'group_name'])
                    ->orderBy('roman_number', 'ASC')
                    ->get()->getResultArray(),
                'kalimat_pembuka' => tanggal_surat_indo($tanggal_kontrak),
                'tanggal_kontrak' => $tanggal_kontrak,
            ];

            $romawiBulan = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
            $bulanRomawi = $romawiBulan[date('n', strtotime($tanggal_kontrak))];
            $tahun = date('Y', strtotime($tanggal_kontrak));
            $data['nomor_surat'] = "{$projectId}/PK/PTC/{$bulanRomawi}/{$tahun}";

            if (isset($data['template_kontrak']['week'])) {
                $hari = $data['template_kontrak']['week'] * 7;
                $bulan = floor($hari / 30);
                $data['template_kontrak']['target_waktu'] = $bulan . ' Bulan / ' . $hari . ' hari kalender';
            } else {
                $data['template_kontrak']['target_waktu'] = '- Bulan / - hari kalender';
            }

            // Ambil output HTML dari View
            $html = view('admin/surat/kontrak_template', $data);

            // Konfigurasi Dompdf
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);

            // Masukkan string HTML ke dalam Dompdf
            $dompdf->loadHtml($html);

            // Atur ukuran kertas dan orientasinya
            $dompdf->setPaper('f4', 'portrait');

            // Render (proses konversi) HTML menjadi PDF
            $dompdf->render();
            ob_end_clean();

            // Save PDF to database dan server
            $output = $dompdf->output();
            $nama_klien = $data['template_kontrak']['nama_klien'] ?? 'user';
            $clean_nama = preg_replace('/[^A-Za-z0-9\-]/', '_', $nama_klien);

            // Memastikan nama file unik menggunakan timestamp atau project ID
            $fileName = 'Kontruksi_kontrak_' . $clean_nama . '_' . $projectId . '.pdf';

            $uploadPath = FCPATH . 'uploads/surat_kontrak/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            file_put_contents($uploadPath . $fileName, $output);

            // Update database: push kolom rab_file ke server
            $this->db->table('construction_requests')
                ->where('id', $projectId)
                ->update([
                    'rab_file' => $fileName,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        // 2. tugas update is_locked & buat tagihan otomatis per pekerjaan
        $constructionService = new \App\Modules\Construction\Services\ConstructionService();
        $constructionService->lockRab((int) $projectId);

        // Kirim notifikasi ke Admin  
        $namaKlien = $data['template_kontrak']['nama_klien'] ?? 'Seorang client';
        $this->notifService->sendToPermission(
            'construction_rab',
            'RAB Konstruksi Disubmit',
            "RAB untuk proyek #{$projectId} telah disubmit oleh client {$namaKlien}. Silakan cek dokumen kontrak yang telah digenerate."
        );

        return $this->respond(['status' => true, 'message' => 'RAB berhasil dikunci!']);
    }

    // =========================================================================
    // 11. FUNGSI UNTUK MENDAPATKAN TARGET PROYEK KONSTRUKSI
    // =========================================================================
    public function targets()
    {
        $json = $this->request->getJSON(true);
        $id_tukang = $json['id_tukang'] ?? $this->request->getVar('id_tukang');
        $construction_id = $json['construction_id'] ?? $this->request->getVar('construction_id');

        $builder = $this->db->table('construction_targets')
            ->select('construction_targets.*')
            ->join('job_applications', 'job_applications.id = construction_targets.id_job_applications', 'left');

        if ($id_tukang) {
            $builder->where('job_applications.tukang_id', $id_tukang);
        }

        if ($construction_id) {
            $builder->where('construction_targets.construction_id', $construction_id);
        }

        $targetsData = $builder->orderBy('construction_targets.created_at', 'ASC')->get()->getResultArray();

        $groupedData = [];

        if (!empty($targetsData)) {
            $constructionIds = array_unique(array_column($targetsData, 'construction_id'));

            $requestsData = $this->db->table('construction_requests')
                ->whereIn('id', $constructionIds)
                ->get()->getResultArray();

            foreach ($requestsData as $request) {
                $requestTargets = array_filter($targetsData, function ($t) use ($request) {
                    return $t['construction_id'] == $request['id'];
                });

                $request['targets'] = array_values($requestTargets);
                $groupedData[] = $request;
            }
        }

        if ($groupedData) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail Target Proyek konstruksi ditemukan',
                'data' => $groupedData
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada target untuk Proyek konstruksi ini',
                'data' => $groupedData
            ]);
        }
    }

    // =========================================================================
    // 12. FUNGSI UNTUK MENDAPATKAN TARGET PROYEK KONSTRUKSI BERDASARKAN USERS
    // =========================================================================
    public function targetsByUser()
    {
        $userId = $this->request->user->uid ?? null;

        if ($userId == null) {
            return $this->failUnauthorized('Akses ditolak. Token tidak valid atau tidak ditemukan.');
        }

        $data = $this->db->table('construction_targets ct')
            ->select("COALESCE(crab.group_name, ca.group_name) as group_name, 
        COALESCE(crab.sub_group_name, ca.sub_group_name) as sub_group_name, 
        COALESCE(ahsp.uraian, ca.activity_name) as activity_name, 
        creq.id as construction_id, 
        NULL as renovation_id, 
        ct.start_week, 
        ct.end_week, 
        COALESCE(crab.volume, ca.volume) as bobot, 
        ct.status as target_status, 
        creq.status as construction_status, 
        creq.start_date, 
        (SELECT COUNT(id) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id) as report_count, 
        (SELECT status FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id ORDER BY created_at DESC LIMIT 1) as last_report_status, 
        (SELECT COUNT(id) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND status = 'APPROVED') as approved_count, 
        (SELECT COUNT(id) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND status = 'REJECTED') as rejected_count, 
        (SELECT COUNT(id) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND status = 'PENDING') as pending_count, 
        (SELECT SUM(volume) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND status = 'APPROVED') as approved_weight, 
        (SELECT SUM(volume) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND status = 'PENDING') as pending_weight", false)
            ->join('rabs crab', 'crab.id = ct.id_construction_rabs', 'left')
            ->join('ahsp', 'ahsp.id = crab.ahsp_id', 'left')
            ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum', 'left')
            ->join('construction_requests creq', 'creq.id = ct.construction_id')
            ->join('users u', 'u.id = creq.user_id')
            ->where('u.id', $userId)
            ->orderBy('ct.start_week', 'ASC')
            ->get()
            ->getResultArray();

        $today = new \DateTime();
        foreach ($data as &$row) {
            // Kalkulasi current_project_week
            $row['current_project_week'] = 0;
            if (!empty($row['start_date'])) {
                $start = new \DateTime($row['start_date']);
                if ($today >= $start) {
                    $diffDays = $today->diff($start)->days;
                    $row['current_project_week'] = floor($diffDays / 7) + 1;
                }
            }

            // Rapikan respon JSON
            unset($row['start_date']);
            $row['report_count'] = (int) $row['report_count'];
            $row['approved_count'] = (int) $row['approved_count'];
            $row['rejected_count'] = (int) $row['rejected_count'];
            $row['pending_count'] = (int) $row['pending_count'];
            $row['approved_weight'] = (float) $row['approved_weight'];
            $row['pending_weight'] = (float) $row['pending_weight'];

            if ($row['last_report_status']) {
                $row['last_report_status'] = strtoupper($row['last_report_status']);
            }
        }

        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'Target Proyek konstruksi ditemukan untuk pengguna.',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada target untuk Proyek konstruksi pengguna ini.',
                'data' => []
            ]);
        }
    }

    // =========================================================================
    // 13. FUNGSI NOTIFIKASI MIDTRANS
    // =========================================================================
    public function notification()
    {
        return $this->respond(['status' => true, 'message' => 'Webhook received.']);
    }

    public function sendCommentSurvey($id_survey)
    {
        $json = $this->request->getJSON(true);
        $comment = $json['comment'] ?? $this->request->getVar('comment');

        if (!$comment) {
            return $this->fail('Data tidak lengkap.');
        }

        $this->db->table('project_surveys')->where('id', $id_survey)->update([
            'comment' => $comment
        ]);

        // Ambil info survey  
        $surveyInfo = $this->db->table('project_surveys ps')
            ->select('dr.full_name as design_client, cr.full_name as const_client, cr.id as construction_id')
            ->join('design_requests dr', 'dr.id = ps.design_request_id', 'left')
            ->join('construction_requests cr', 'cr.design_request_id = dr.id', 'left')
            ->where('ps.id', $id_survey)
            ->get()->getRowArray();

        $namaKlien = $surveyInfo['const_client'] ?? $surveyInfo['design_client'] ?? 'Seorang client';

        // Kirim notifikasi ke Admin  
        $this->notifService->sendToPermission(
            'construction_survey',
            'Komentar Survey Baru',
            "Client {$namaKlien} telah memberikan komentar pada hasil survey konstruksi #" . ($surveyInfo['construction_id'] ?? $id_survey) . "."
        );

        return $this->respond(['status' => true, 'message' => 'Komentar berhasil ditambahkan.']);
    }

    public function sendCommentDesign($id_design)
    {
        $json = $this->request->getJSON(true);
        $comment = $json['comment'] ?? $this->request->getVar('comment');

        if (!$comment) {
            return $this->fail('Data tidak lengkap.');
        }

        $this->db->table('project_designs')->where('id', $id_design)->update([
            'revision_note' => $comment
        ]);

        // Ambil info desain  
        $designInfo = $this->db->table('project_designs pd')
            ->select('dr.full_name as design_client, cr.full_name as const_client, cr.id as construction_id')
            ->join('design_requests dr', 'dr.id = pd.design_request_id', 'left')
            ->join('construction_requests cr', 'cr.design_request_id = dr.id', 'left')
            ->where('pd.id', $id_design)
            ->get()->getRowArray();

        $namaKlien = $designInfo['const_client'] ?? $designInfo['design_client'] ?? 'Seorang client';

        // Kirim notifikasi ke Admin  
        $this->notifService->sendToPermission(
            'construction_desain',
            'Komentar Desain Baru',
            "Client {$namaKlien} telah memberikan komentar pada hasil desain konstruksi #" . ($designInfo['construction_id'] ?? $id_design) . "."
        );

        return $this->respond(['status' => true, 'message' => 'Komentar berhasil ditambahkan.']);
    }

    // =========================================================================
    // 14. FUNGSI ABSEN MASUK (CHECK-IN) KONSTRUKSI
    // =========================================================================
    public function SendAttendance($id_construction)
    {
        if (!$id_construction) {
            return $this->fail('ID konstruksi tidak boleh kosong.');
        }

        // Validasi input
        $validationRules = [
            'file' => 'uploaded[file]|max_size[file,30720]|mime_in[file,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm]',
            'longitude' => 'required',
            'latitude' => 'required',
            'waktu' => 'required',
            'jumlah_tukang' => 'required'
        ];

        $validationMessages = [
            'file' => [
                'uploaded' => 'Video absensi wajib diunggah.',
                'max_size' => 'Ukuran video tidak boleh melebihi 30MB.',
                'mime_in' => 'Format video tidak valid. Gunakan MP4, MOV, AVI, MKV, atau WebM.',
            ],
            'longitude' => ['required' => 'Longitude wajib diisi.'],
            'latitude' => ['required' => 'Latitude wajib diisi.'],
            'waktu' => ['required' => 'Waktu absen wajib diisi.'],
            'jumlah_tukang' => ['required' => 'Jumlah tukang wajib diisi.']
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Upload video absensi
        $foto = $this->request->getFile('file');
        $uploadPath = 'uploads/construction/absen_tukang/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $foto->getRandomName();
        $foto->move($uploadPath, $newName);

        // Simpan ke database
        $attendanceModel = new \App\Modules\Construction\Models\ConstructionAttendanceModel();

        $data = [
            'id_construction' => $id_construction,
            'type' => 'masuk',
            'file' => $newName,
            'jumlah_tukang' => $this->request->getPost('jumlah_tukang'),
            'longitude' => $this->request->getPost('longitude'),
            'latitude' => $this->request->getPost('latitude'),
            'waktu' => $this->request->getPost('waktu'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        if ($attendanceModel->insert($data)) {
            // Kirim notifikasi ke Admin
            $notifService = new \App\Modules\Notifications\Services\NotificationService();
            $notifService->sendToPermission(
                'construction_absensi',
                'Absensi Konstruksi (Masuk)',
                "Tukang telah mengirim absensi masuk untuk proyek #{$id_construction}. Jumlah tukang: {$data['jumlah_tukang']}."
            );

            return $this->respondCreated([
                'status' => true,
                'message' => 'Absen masuk berhasil dikirim.',
                'data' => [
                    'id' => $attendanceModel->getInsertID(),
                    'id_construction' => (int) $id_construction,
                    'type' => 'masuk',
                    'file' => $newName,
                    'jumlah_tukang' => $data['jumlah_tukang'],
                    'file_url' => base_url($uploadPath . $newName),
                    'longitude' => $data['longitude'],
                    'latitude' => $data['latitude'],
                    'waktu' => $data['waktu'],
                    'deskripsi' => $data['deskripsi'],
                ],
            ]);
        }

        return $this->fail('Gagal menyimpan data absensi masuk.');
    }

    // =========================================================================
    // 15. FUNGSI ABSEN KELUAR (CHECK-OUT) KONSTRUKSI
    // =========================================================================
    public function SendCheckoutAttendance($id_construction)
    {
        if (!$id_construction) {
            return $this->fail('ID konstruksi tidak boleh kosong.');
        }

        // Validasi input
        $validationRules = [
            'file' => 'uploaded[file]|max_size[file,30720]|mime_in[file,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm]',
            'longitude' => 'required',
            'latitude' => 'required',
            'waktu' => 'required',
            'jumlah_tukang' => 'required'
        ];

        $validationMessages = [
            'file' => [
                'uploaded' => 'Video absensi wajib diunggah.',
                'max_size' => 'Ukuran video tidak boleh melebihi 30MB.',
                'mime_in' => 'Format video tidak valid. Gunakan MP4, MOV, AVI, MKV, atau WebM.',
            ],
            'longitude' => ['required' => 'Longitude wajib diisi.'],
            'latitude' => ['required' => 'Latitude wajib diisi.'],
            'waktu' => ['required' => 'Waktu absen wajib diisi.'],
            'jumlah_tukang' => ['required' => 'Jumlah tukang wajib diisi.']
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Upload video absensi
        $foto = $this->request->getFile('file');
        $uploadPath = 'uploads/construction/absen_tukang/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $foto->getRandomName();
        $foto->move($uploadPath, $newName);

        // Simpan ke database
        $attendanceModel = new \App\Modules\Construction\Models\ConstructionAttendanceModel();

        $data = [
            'id_construction' => $id_construction,
            'type' => 'keluar',
            'file' => $newName,
            'jumlah_tukang' => $this->request->getPost('jumlah_tukang'),
            'longitude' => $this->request->getPost('longitude'),
            'latitude' => $this->request->getPost('latitude'),
            'waktu' => $this->request->getPost('waktu'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        if ($attendanceModel->insert($data)) {
            // Kirim notifikasi ke Admin
            $notifService = new \App\Modules\Notifications\Services\NotificationService();
            $notifService->sendToPermission(
                'construction_absensi',
                'Absensi Konstruksi (Keluar)',
                "Tukang telah mengirim absensi keluar untuk proyek #{$id_construction}. Jumlah tukang: {$data['jumlah_tukang']}."
            );

            return $this->respondCreated([
                'status' => true,
                'message' => 'Absen keluar berhasil dikirim.',
                'data' => [
                    'id' => $attendanceModel->getInsertID(),
                    'id_construction' => (int) $id_construction,
                    'type' => 'keluar',
                    'file' => $newName,
                    'jumlah_tukang' => $data['jumlah_tukang'],
                    'file_url' => base_url($uploadPath . $newName),
                    'longitude' => $data['longitude'],
                    'latitude' => $data['latitude'],
                    'waktu' => $data['waktu'],
                    'deskripsi' => $data['deskripsi'],
                ],
            ]);
        }

        return $this->fail('Gagal menyimpan data absensi keluar.');
    }

    // =========================================================================
    // CRUD PENGAJUAN BAHAN DAN ALAT (CONSTRUCTION_MATERIAL_SUBMISSION)
    // =========================================================================

    /**
     * Get list of material/tool submissions.
     * Optional filters: construction_id, status, type
     */
    public function getMaterialSubmissions()
    {
        $constructionId = $this->request->getVar('construction_id') ?? $this->request->getGet('construction_id');
        $status = $this->request->getVar('status') ?? $this->request->getGet('status');
        $type = $this->request->getVar('type') ?? $this->request->getGet('type');

        $query = $this->constructionMaterialSubmissionModel;

        if ($constructionId) {
            $query = $query->where('construction_id', $constructionId);
        }
        if ($status) {
            $query = $query->where('status', $status);
        }
        if ($type) {
            $query = $query->where('type', $type);
        }

        $submissions = $query->orderBy('created_at', 'DESC')->findAll();

        foreach ($submissions as &$item) {
            $item['photo_url'] = !empty($item['photo']) ? base_url('uploads/construction/material_submissions/' . $item['photo']) : null;
        }

        return $this->respond([
            'status' => true,
            'message' => 'Data pengajuan bahan/alat berhasil diambil.',
            'data' => $submissions
        ]);
    }

    /**
     * Get detail of a specific material/tool submission.
     */
    public function getMaterialSubmission($id = null)
    {
        if ($id === null) {
            return $this->fail('ID pengajuan tidak boleh kosong.');
        }

        $submission = $this->constructionMaterialSubmissionModel->find($id);

        if (!$submission) {
            return $this->failNotFound('Data pengajuan tidak ditemukan.');
        }

        $submission['photo_url'] = !empty($submission['photo']) ? base_url('uploads/construction/material_submissions/' . $submission['photo']) : null;

        return $this->respond([
            'status' => true,
            'message' => 'Detail pengajuan bahan/alat berhasil diambil.',
            'data' => $submission
        ]);
    }

    /**
     * Create a new material/tool submission.
     */
    public function createMaterialSubmission()
    {
        // Mendukung request JSON maupun Form Data
        $json = [];
        try {
            $body = $this->request->getBody();
            if (!empty($body)) {
                $json = $this->request->getJSON(true) ?: [];
            }
        } catch (\Throwable $e) {
            $json = [];
        }

        $constructionId = $json['construction_id'] ?? $this->request->getPost('construction_id');
        $jobApplicationsId = $json['job_applications_id'] ?? $this->request->getPost('job_applications_id');
        $type = $json['type'] ?? $this->request->getPost('type');
        $title = $json['title'] ?? $this->request->getPost('title');
        $description = $json['description'] ?? $this->request->getPost('description');

        // Validasi input
        $validationRules = [
            'construction_id' => 'required|numeric',
            'job_applications_id' => 'permit_empty|numeric',
            'type' => 'required|in_list[bahan,alat]',
            'title' => 'permit_empty|max_length[255]',
            'description' => 'required',
        ];

        $validationMessages = [
            'construction_id' => [
                'required' => 'Construction ID wajib diisi.',
                'numeric' => 'Construction ID harus berupa angka.'
            ],
            'job_applications_id' => [
                'numeric' => 'Job Applications ID harus berupa angka.'
            ],
            'type' => [
                'required' => 'Tipe pengajuan wajib diisi.',
                'in_list' => 'Tipe pengajuan harus berupa "bahan" atau "alat".'
            ],
            'title' => [
                'max_length' => 'Judul pengajuan maksimal 255 karakter.'
            ],
            'description' => [
                'required' => 'Deskripsi/List pengajuan wajib diisi.'
            ]
        ];

        // Validasi file photo jika diunggah
        $photoFile = $this->request->getFile('photo');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $validationRules['photo'] = 'max_size[photo,5120]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]';
            $validationMessages['photo'] = [
                'max_size' => 'Ukuran foto tidak boleh melebihi 5MB.',
                'is_image' => 'File yang diunggah harus berupa gambar.',
                'mime_in' => 'Format foto tidak valid. Gunakan JPG, PNG, atau WebP.'
            ];
        }

        // Jalankan validasi manual
        $validationData = [
            'construction_id' => $constructionId,
            'job_applications_id' => $jobApplicationsId,
            'type' => $type,
            'title' => $title,
            'description' => $description
        ];

        if (!$this->validateData($validationData, $validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Cek apakah proyek konstruksi ada
        $construction = $this->db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();
        if (!$construction) {
            return $this->failNotFound('Proyek konstruksi tidak ditemukan.');
        }

        // Cek jika job_applications_id diisi, apakah datanya ada
        if (!empty($jobApplicationsId)) {
            $jobApp = $this->db->table('job_applications')->where('id', $jobApplicationsId)->get()->getRowArray();
            if (!$jobApp) {
                return $this->failNotFound('Job application/Tukang tidak ditemukan.');
            }
        }

        $photoName = null;
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $uploadPath = 'uploads/construction/material_submissions/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $photoName = $photoFile->getRandomName();
            $photoFile->move($uploadPath, $photoName);
        }

        $data = [
            'construction_id' => (int) $constructionId,
            'job_applications_id' => !empty($jobApplicationsId) ? (int) $jobApplicationsId : null,
            'type' => $type,
            'title' => !empty($title) ? $title : null,
            'description' => $description,
            'photo' => $photoName,
            'status' => 'pending',
        ];

        if ($this->constructionMaterialSubmissionModel->insert($data)) {
            $insertedId = $this->constructionMaterialSubmissionModel->getInsertID();
            $insertedData = $this->constructionMaterialSubmissionModel->find($insertedId);
            $insertedData['photo_url'] = !empty($insertedData['photo']) ? base_url('uploads/construction/material_submissions/' . $insertedData['photo']) : null;

            // Kirim notifikasi ke Admin
            $this->notifService->sendToPermission(
                'construction_detail',
                'Pengajuan Bahan/Alat Baru',
                "Tukang mengirim pengajuan {$type} baru untuk proyek #{$constructionId}."
            );

            return $this->respondCreated([
                'status' => true,
                'message' => 'Pengajuan bahan/alat berhasil dikirim.',
                'data' => $insertedData
            ]);
        }

        return $this->fail('Gagal menyimpan pengajuan bahan/alat.');
    }

    /**
     * Update a specific material/tool submission (Used by Tukang).
     * Tukang can only update type, title, description, and photo, and only when the status is still 'pending'.
     */
    public function updateMaterialSubmission($id = null)
    {
        // Mendukung request JSON maupun Form Data
        $json = [];
        try {
            $body = $this->request->getBody();
            if (!empty($body)) {
                $json = $this->request->getJSON(true) ?: [];
            }
        } catch (\Throwable $e) {
            $json = [];
        }

        if ($id === null) {
            return $this->fail('ID pengajuan tidak boleh kosong.');
        }

        $submission = $this->constructionMaterialSubmissionModel->find($id);
        if (!$submission) {
            return $this->failNotFound('Data pengajuan tidak ditemukan.');
        }

        // Tukang hanya boleh mengedit jika statusnya masih pending  
        if ($submission['status'] !== 'pending') {
            return $this->fail('Pengajuan tidak dapat diubah karena sudah diproses oleh admin.');
        }

        // Ambil data perubahan (bisa JSON, PUT, atau POST)
        // Note: $json sudah didefinisikan secara aman di bagian atas method dengan try-catch
        $input = !empty($json) ? $json : array_merge(
            $this->request->getRawInput() ?: [],
            $this->request->getVar() ?: []
        );

        $validationRules = [];
        $validationMessages = [];

        if (isset($input['type'])) {
            $validationRules['type'] = 'in_list[bahan,alat]';
            $validationMessages['type'] = ['in_list' => 'Tipe pengajuan harus berupa "bahan" atau "alat".'];
        }

        if (isset($input['title'])) {
            $validationRules['title'] = 'max_length[255]';
            $validationMessages['title'] = ['max_length' => 'Judul pengajuan maksimal 255 karakter.'];
        }

        // Validasi file photo baru jika diunggah
        $photoFile = $this->request->getFile('photo');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $validationRules['photo'] = 'max_size[photo,5120]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]';
            $validationMessages['photo'] = [
                'max_size' => 'Ukuran foto tidak boleh melebihi 5MB.',
                'is_image' => 'File yang diunggah harus berupa gambar.',
                'mime_in' => 'Format foto tidak valid. Gunakan JPG, PNG, atau WebP.'
            ];
        }

        if (!empty($validationRules)) {
            if (!$this->validateData($input, $validationRules, $validationMessages)) {
                return $this->failValidationErrors($this->validator->getErrors());
            }
        }

        $dataUpdate = [];
        if (isset($input['type'])) {
            $dataUpdate['type'] = $input['type'];
        }
        if (isset($input['title'])) {
            $dataUpdate['title'] = $input['title'];
        }
        if (isset($input['description'])) {
            $dataUpdate['description'] = $input['description'];
        }

        // Upload photo baru dan hapus photo lama jika ada
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $uploadPath = 'uploads/construction/material_submissions/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            if (!empty($submission['photo']) && file_exists($uploadPath . $submission['photo'])) {
                unlink($uploadPath . $submission['photo']);
            }

            $photoName = $photoFile->getRandomName();
            $photoFile->move($uploadPath, $photoName);
            $dataUpdate['photo'] = $photoName;
        }

        if (empty($dataUpdate)) {
            return $this->fail('Tidak ada data yang diubah.');
        }

        if ($this->constructionMaterialSubmissionModel->update($id, $dataUpdate)) {
            $updatedData = $this->constructionMaterialSubmissionModel->find($id);
            $updatedData['photo_url'] = !empty($updatedData['photo']) ? base_url('uploads/construction/material_submissions/' . $updatedData['photo']) : null;

            return $this->respond([
                'status' => true,
                'message' => 'Data pengajuan berhasil diperbarui.',
                'data' => $updatedData
            ]);
        }

        return $this->fail('Gagal memperbarui data pengajuan.');
    }

    /**
     * Delete a specific material/tool submission.
     */
    public function deleteMaterialSubmission($id = null)
    {
        if ($id === null) {
            return $this->fail('ID pengajuan tidak boleh kosong.');
        }

        $submission = $this->constructionMaterialSubmissionModel->find($id);
        if (!$submission) {
            return $this->failNotFound('Data pengajuan tidak ditemukan.');
        }

        // Hapus file photo dari disk jika ada
        if (!empty($submission['photo'])) {
            $uploadPath = 'uploads/construction/material_submissions/';
            if (file_exists($uploadPath . $submission['photo'])) {
                unlink($uploadPath . $submission['photo']);
            }
        }

        if ($this->constructionMaterialSubmissionModel->delete($id)) {
            return $this->respond([
                'status' => true,
                'message' => 'Data pengajuan berhasil dihapus.'
            ]);
        }

        return $this->fail('Gagal menghapus data pengajuan.');
    }
}
