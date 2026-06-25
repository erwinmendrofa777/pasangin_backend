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

        if (!empty($data)) {
            $designIds = array_column($data, 'id');
            $db = \Config\Database::connect();
            $constructions = $db->table('construction_requests')
                ->select('id, design_request_id')
                ->whereIn('design_request_id', $designIds)
                ->get()
                ->getResultArray();

            $designToConstMap = [];
            foreach ($constructions as $c) {
                $designToConstMap[$c['design_request_id']] = $c['id'];
            }

            foreach ($data as &$project) {
                $image_urls = [];
                for ($i = 1; $i <= 5; $i++) {
                    if (!empty($project['gambar' . $i])) {
                        $image_urls[] = base_url('uploads/designs/' . $project['gambar' . $i]);
                    }
                }
                $project['image_urls'] = $image_urls;
                $project['construction_requests_id'] = isset($designToConstMap[$project['id']])
                    ? (int) $designToConstMap[$project['id']]
                    : null;
            }
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

        // Ambil data design request untuk mendapatkan start_date dan max_revision
        $designRequest = $this->db->table('design_requests')
            ->select('start_date, max_revision')
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

        // Hitung kuota revisi
        $maxRevision = (int) ($designRequest['max_revision'] ?? 0);
        $usedRevision = 0;
        foreach ($targets as $target) {
            $maxRevRow = $this->db->table('project_designs')
                ->where('design_targets_id', $target['id'])
                ->selectMax('revision_number')
                ->get()->getRowArray();
            $maxRev = (int) ($maxRevRow['revision_number'] ?? 0);
            if ($maxRev > 1) {
                $usedRevision += ($maxRev - 1);
            }
        }
        $remainingRevision = max(0, $maxRevision - $usedRevision);

        $result = [];
        foreach ($targets as $target) {
            // Ambil semua project_designs untuk target ini
            $projectDesigns = $this->db->table('project_designs')
                ->where('design_targets_id', $target['id'])
                ->orderBy('revision_number', 'DESC')
                ->get()->getResultArray();

            $revisions = [];
            foreach ($projectDesigns as $pd) {
                $revNum = (int) ($pd['revision_number'] ?? 1);
                if (!isset($revisions[$revNum])) {
                    // Decode image_revision_note
                    $revImgUrls = [];
                    if (!empty($pd['image_revision_note'])) {
                        $decoded = json_decode($pd['image_revision_note'], true);
                        if (is_array($decoded)) {
                            foreach ($decoded as $imgFile) {
                                $revImgUrls[] = base_url('uploads/design_results/revision_comment/' . $imgFile);
                            }
                        }
                    }

                    $revisions[$revNum] = [
                        'revision_number' => $revNum,
                        'status' => $pd['status'],
                        'revision_note' => $pd['revision_note'],
                        'image_revision_note' => $revImgUrls,
                        'created_at' => $pd['created_at'],
                        'files' => []
                    ];
                }

                $revisions[$revNum]['files'][] = [
                    'id' => $pd['id'],
                    'design_name' => $pd['design_name'],
                    'file_name' => $pd['file'],
                    'file_url' => base_url('uploads/design_results/' . $pd['file']),
                    'design_type' => $pd['design_type']
                ];
            }

            krsort($revisions);
            $revisionsList = array_values($revisions);

            // Hitung tanggal mulai target (start_date proyek + (start_week - 1) hari)
            // Asumsi start_week menyimpan "Day X"
            $dayOffset = (int) ($target['start_week'] ?? 1) - 1;

            // Kalkulasi target_start_date
            if (!empty($startDate)) {
                $targetStartDate = date('Y-m-d', strtotime($startDate . " +{$dayOffset} days"));
            } else {
                $targetStartDate = null;
            }

            // Ambil data invoice untuk target ini
            $invoice = $this->db->table('project_invoices')
                ->where('design_target_id', $target['id'])
                ->get()->getRowArray();

            $invoiceData = null;
            if ($invoice) {
                $invoiceData = [
                    'id' => (int) $invoice['id'],
                    'amount' => $invoice['amount'] !== null ? (float) $invoice['amount'] : null,
                    'due_date' => $invoice['due_date'],
                    'payment_status' => $invoice['payment_status'] ?? $invoice['status'] ?? 'UNPAID',
                    'snap_token' => $invoice['snap_token'],
                    'payment_url' => $invoice['payment_url'],
                ];
            }

            $result[] = [
                'id' => $target['id'],
                'task_name' => $target['task_name'],
                'jumlah_revisi' => count($revisionsList),
                'target_start_date' => $targetStartDate,
                'status' => $target['status'],
                'revisions' => $revisionsList,
                'invoice' => $invoiceData
            ];
        }

        if ($result) {
            return $this->respond([
                'status' => true,
                'message' => 'Data target desain ditemukan',
                'max_revision' => $maxRevision,
                'used_revision' => $usedRevision,
                'remaining_revision' => $remainingRevision,
                'data' => $result
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada target desain untuk permohonan ini',
                'max_revision' => $maxRevision,
                'used_revision' => $usedRevision,
                'remaining_revision' => $remainingRevision,
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

        // Ambil data invoice untuk target ini
        $invoice = $this->db->table('project_invoices')
            ->where('design_target_id', $id)
            ->get()->getRowArray();

        $invoiceData = null;
        if ($invoice) {
            $invoiceData = [
                'id' => (int) $invoice['id'],
                'amount' => $invoice['amount'] !== null ? (float) $invoice['amount'] : null,
                'due_date' => $invoice['due_date'],
                'payment_status' => $invoice['payment_status'] ?? $invoice['status'] ?? 'UNPAID',
                'snap_token' => $invoice['snap_token'],
                'payment_url' => $invoice['payment_url'],
            ];
        }

        if ($progress) {
            return $this->respond([
                'status' => true,
                'message' => 'Data progress desain ditemukan',
                'invoice' => $invoiceData,
                'data' => $progress
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada progress desain untuk permohonan ini',
                'invoice' => $invoiceData,
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

        $design = $this->db->table('project_designs')->where('id', $id)->get()->getRowArray();
        if (!$design) {
            return $this->respond([
                'status' => false,
                'message' => 'Data desain tidak ditemukan',
                'data' => []
            ]);
        }

        $targetId = (int) $design['design_targets_id'];
        $revNum = (int) $design['revision_number'];

        $status = $this->request->getPost('status') ?? $this->request->getJSON(true)['status'];

        // Cek Kuota Revisi jika klien menolak desain (REJECTED)
        if ($status === 'REJECTED') {
            $designRequestId = (int) $design['design_request_id'];
            $designRequest = $this->db->table('design_requests')
                ->where('id', $designRequestId)
                ->get()->getRowArray();

            if ($designRequest) {
                $maxRevision = (int) ($designRequest['max_revision'] ?? 0);

                $targets = $this->db->table('design_targets')
                    ->where('design_request_id', $designRequestId)
                    ->get()->getResultArray();

                $usedRevision = 0;
                foreach ($targets as $target) {
                    $maxRevRow = $this->db->table('project_designs')
                        ->where('design_targets_id', $target['id'])
                        ->selectMax('revision_number')
                        ->get()->getRowArray();
                    $maxRev = (int) ($maxRevRow['revision_number'] ?? 0);
                    if ($maxRev > 1) {
                        $usedRevision += ($maxRev - 1);
                    }
                }

                if ($usedRevision >= $maxRevision) {
                    return $this->respond([
                        'status' => false,
                        'message' => 'Kuota revisi Anda sudah habis (' . $usedRevision . '/' . $maxRevision . '). Silakan lakukan pembayaran untuk menambah kuota revisi.',
                        'data' => [
                            'max_revision' => $maxRevision,
                            'used_revision' => $usedRevision,
                        ]
                    ], 400);
                }
            }
        }

        $data = [
            'revision_note' => $this->request->getPost('revision_note') ?? $this->request->getJSON(true)['revision_note'] ?? null,
            'status' => $status,
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

        // Update semua data project_designs dalam target dan nomor revisi yang sama
        $update = $this->db->table('project_designs')
            ->where('design_targets_id', $targetId)
            ->where('revision_number', $revNum)
            ->update($data);

        if ($update) {
            // Jika status diupdate menjadi APPROVED, otomatis ubah status target menjadi DONE
            if (isset($data['status']) && $data['status'] === 'APPROVED') {
                if (!empty($targetId)) {
                    $this->db->table('design_targets')
                        ->where('id', $targetId)
                        ->update(['status' => 'DONE']);

                    // Tolak semua revisi PENDING lain dalam target yang sama (yang nomor revisinya berbeda)
                    $this->db->table('project_designs')
                        ->where('design_targets_id', $targetId)
                        ->where('revision_number !=', $revNum)
                        ->where('status', 'PENDING')
                        ->update([
                            'status' => 'REJECTED',
                            'revision_note' => 'Revisi lain telah disetujui'
                        ]);

                    // Update status permohonan utama jika semua target sudah selesai
                    $designService = new \App\Modules\Design\Services\DesignRequestService();
                    $designService->checkAndUpdateDesignRequestStatus((int) $design['design_request_id']);
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
        if (empty($designRequestId)) {
            return $this->fail('Design Request ID tidak ditemukan.', 400);
        }

        // Join vouchers (diskon) dan design_targets (info target terhubung)
        $invoices = $this->db->table('project_invoices')
            ->select('
                project_invoices.*,
                vouchers.discount_nominal,
                dt.task_name  AS target_task_name,
                dt.status     AS target_status
            ')
            ->join('vouchers', 'vouchers.code = project_invoices.voucher_code', 'left')
            ->join('design_targets dt', 'dt.id = project_invoices.design_target_id', 'left')
            ->where('project_invoices.design_request_id', $designRequestId)
            // Tampilkan tagihan target lebih dulu (design_target_id IS NOT NULL), lalu manual
            ->orderBy('project_invoices.design_target_id IS NULL', 'ASC', false)
            ->orderBy('project_invoices.design_target_id', 'ASC')
            ->orderBy('project_invoices.id', 'ASC')
            ->get()->getResultArray();

        // Olah data: kalkulasi nominal, flag jenis tagihan
        $formattedInvoices = array_map(function ($invoice) {
            // Nominal bisa NULL (tagihan target yang belum diisi)
            $originalAmount = $invoice['amount'] !== null ? (float) $invoice['amount'] : null;
            $discountAmount = (int) ($invoice['discount_nominal'] ?? 0);
            $grossAmount = $originalAmount !== null
                ? max(0, $originalAmount - $discountAmount)
                : null;

            $invoice['original_amount'] = $originalAmount;
            $invoice['discount_amount'] = $discountAmount;
            $invoice['gross_amount'] = $grossAmount;

            // Flag: apakah tagihan ini terhubung ke target desain
            $invoice['is_target_linked'] = !empty($invoice['design_target_id']);

            // Pastikan design_target_id bertipe int atau null
            $invoice['design_target_id'] = $invoice['design_target_id']
                ? (int) $invoice['design_target_id']
                : null;

            return $invoice;
        }, $invoices);

        return $this->respond([
            'status' => true,
            'message' => !empty($formattedInvoices)
                ? 'Daftar invoice ditemukan'
                : 'Belum ada invoice untuk permohonan ini',
            'data' => $formattedInvoices,
        ]);
    }

    // =========================================================================
    // 5b. GET RAB
    // =========================================================================
    public function rabs($designRequestId = null)
    {
        if ($designRequestId == null) {
            return $this->fail('Design Request ID tidak boleh kosong.');
        }

        $romanNumber = $this->request->getGet('roman_number') ?? $this->request->getVar('roman_number');

        try {
            $query = $this->db->table('rabs')->where('design_request_id', $designRequestId);
            if (!empty($romanNumber)) {
                $query->where('LOWER(TRIM(roman_number))', strtolower(trim($romanNumber)));
            }
            $rabData = $query->orderBy('created_at', 'DESC')->get()->getResultArray();

            foreach ($rabData as &$rab) {
                $rab['image_url'] = !empty($rab['file']) ? base_url('uploads/design/rab/' . $rab['file']) : null;

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
                'message' => 'Detail RAB Proyek desain ditemukan',
                'data' => $rabData
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada RAB untuk Proyek desain ini',
                'data' => $rabData
            ]);
        }
    }

    public function buyRevisionQuota()
    {
        $designRequestId = $this->request->getPost('design_request_id') ?? $this->request->getJSON(true)['design_request_id'] ?? null;
        if (empty($designRequestId)) {
            return $this->fail('Design Request ID wajib dikirim.', 400);
        }

        $designRequest = $this->db->table('design_requests')->where('id', $designRequestId)->get()->getRowArray();
        if (!$designRequest) {
            return $this->failNotFound('Proyek desain tidak ditemukan.');
        }

        $quantity = (int) ($this->request->getPost('quantity') ?? $this->request->getJSON(true)['quantity'] ?? 1);
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Ambil harga revisi dinamis dari system_settings
        $settingsModel = new \App\Modules\Admin\Models\SystemSettingModel();
        $revisionPrice = (float) $settingsModel->getVal('design_revision_price', 100000.00);
        $totalPrice = $revisionPrice * $quantity;

        // Buat invoice baru
        $invoiceData = [
            'design_request_id' => $designRequestId,
            'description' => "Tambahan Kuota Revisi ({$quantity}x)",
            'amount' => $totalPrice,
            'due_date' => date('Y-m-d', strtotime('+1 day')),
            'status' => 'UNPAID',
            'payment_status' => 'UNPAID',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->db->table('project_invoices')->insert($invoiceData)) {
            $invoiceId = $this->db->insertID();
            return $this->respond([
                'status' => true,
                'message' => 'Invoice pembelian tambahan kuota revisi berhasil dibuat.',
                'data' => [
                    'invoice_id' => (int) $invoiceId,
                    'amount' => $totalPrice,
                    'quantity' => $quantity,
                ]
            ]);
        }

        return $this->failServerError('Gagal membuat invoice baru.');
    }

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

        // Cek lock dinonaktifkan untuk modul desain

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
}
