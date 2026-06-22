<?php
// FILE: backend/app/Controllers/Api/RenovationApi.php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Modules\Renovation\Models\RenovationModel;
use App\Modules\Renovation\Models\RenovationMaterialSubmissionModel;

// Import class Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

class RenovationApi extends BaseController
{
    use ResponseTrait;
    protected $model;
    protected $db;
    protected $notifService;
    protected $renovationMaterialSubmissionModel;

    public function __construct()
    {
        $this->model = new RenovationModel();
        $this->db = \Config\Database::connect();
        $this->notifService = new \App\Modules\Notifications\Services\NotificationService();
        $this->renovationMaterialSubmissionModel = new RenovationMaterialSubmissionModel();
    }

    // =========================================================================
    // 1. API UNTUK MENERIMA PENGAJUAN RENOVASI BARU
    // =========================================================================
    public function submit()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'images' => 'uploaded[images]|max_size[images,5120]|mime_in[images,image/jpg,image/jpeg,image/png,image/webp]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->fail($validation->getErrors());
        }

        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone_number'),
            'renovation_type' => $this->request->getPost('renovation_type'),
            'description' => $this->request->getPost('description'),
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
        $uploadPath = 'uploads/renovation/';
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

        if (!empty($uploadedFileNames)) {
            foreach ($uploadedFileNames as $index => $fileName) {
                $data['gambar' . ($index + 1)] = $fileName;
            }

            $inserted_id = $this->model->insert($data);

            if ($inserted_id) {
                // Hubungkan ke desain yang sudah dipilih jika ada
                $designRequestsId = $this->request->getPost('design_requests_id');
                if (!empty($designRequestsId)) {
                    // Cari file desain disetujui (APPROVED) di project_designs berdasarkan design_request_id
                    $designProject = $this->db->table('project_designs')
                        ->where('design_request_id', $designRequestsId)
                        ->where('status', 'APPROVED')
                        ->orderBy('created_at', 'DESC')
                        ->get()
                        ->getRowArray();

                    // Fallback: Cari yang non-APPROVED jika tidak ada yang APPROVED
                    if (!$designProject) {
                        $designProject = $this->db->table('project_designs')
                            ->where('design_request_id', $designRequestsId)
                            ->orderBy('created_at', 'DESC')
                            ->get()
                            ->getRowArray();
                    }

                    $renovationDesignData = [
                        'request_id' => $inserted_id,
                        'user_admin_id' => $designProject ? ($designProject['user_admin_id'] ?: null) : null,
                        'design_requests_id' => $designRequestsId,
                        'title' => $designProject ? ($designProject['design_name'] ?: 'Desain Terpilih') : 'Desain Terpilih',
                        'file_url' => $designProject ? $designProject['file'] : '',
                        'comment' => 'Desain dipilih oleh pelanggan saat pengajuan renovasi.',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('renovation_designs')->insert($renovationDesignData);
                }

                // Kirim notifikasi ke Admin  
                $this->notifService->sendToPermission(
                    'renovation_detail',
                    'Permohonan Renovasi Baru',
                    "Pelanggan atas nama {$data['full_name']} telah mengirim permohonan renovasi baru. Silakan cek detail  ."
                );

                return $this->respondCreated([
                    'status' => true,
                    'message' => 'Permohonan renovasi berhasil dikirim',
                    'data' => $inserted_id
                ]);
            } else {
                return $this->failServerError('Gagal menyimpan permohonan renovasi.');
            }
        }
    }

    // =========================================================================
    // 2. API UNTUK MENDAPATKAN RIWAYAT PENGAJUAN RENOVASI PER USER
    // =========================================================================
    public function projects($userId = null)
    {
        if ($userId === null) {
            return $this->fail('User ID tidak boleh kosong.', 400);
        }

        $data = $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($data as &$project) {
            $image_urls = [];
            for ($i = 1; $i <= 5; $i++) {
                if (!empty($project['gambar' . $i])) {
                    $image_urls[] = base_url('uploads/renovation/' . $project['gambar' . $i]);
                }
            }
            $project['image_urls'] = $image_urls;
        }

        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'data proyek renovasi ditemukan',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada proyek renovasi untuk saat ini',
                'data' => $data
            ]);
        }
    }

    // =========================================================================
    // 3. API UNTUK MENDAPATKAN DETAIL PROYEK RENOVASI
    // =========================================================================
    public function detail($id = null)
    {
        if ($id === null) {
            return $this->fail('ID Proyek tidak boleh kosong.', 400);
        }

        $data = $this->model->where('id', $id)->get()->getRowArray();

        $image_urls = [];
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($data['gambar' . $i])) {
                $image_urls[] = base_url('uploads/renovation/' . $data['gambar' . $i]);
            }
        }
        $data['image_urls'] = $image_urls;

        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail proyek renovasi ditemukan',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada proyek renovasi untuk saat ini',
                'data' => []
            ]);
        }
    }

    // =========================================================================
    // 4. API UNTUK MENDAPATKAN DATA SURVEY RENOVASI
    // =========================================================================
    public function surveys($projectId = null)
    {
        if ($projectId === null) {
            return $this->fail('ID Proyek tidak boleh kosong.', 400);
        }

        $db = \Config\Database::connect();
        $data = $db->table('renovation_surveys')->where('request_id', $projectId)->get()->getResultArray();

        foreach ($data as &$item) {
            $item['image_url'] = !empty($item['file_url']) ? base_url('uploads/survey/' . $item['file_url']) : null;
        }

        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'Data survey ditemukan',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada survey untuk proyek ini',
                'data' => $data
            ]);
        }
    }

    // =========================================================================
    // 5. API UNTUK MENDAPATKAN DATA DESIGN RENOVASI
    // =========================================================================
    public function designs($projectId = null)
    {
        if ($projectId === null) {
            return $this->fail('ID Proyek tidak boleh kosong.', 400);
        }

        $designRepo = new \App\Modules\Renovation\Repositories\RenovationDesignRepository();
        $designs = $designRepo->findByRequestId((int) $projectId);

        foreach ($designs as &$item) {
            $item['image_url'] = !empty($item['file_url'])
                ? (!empty($item['design_requests_id'])
                    ? base_url('uploads/design_results/' . $item['file_url'])
                    : base_url('uploads/designs/' . $item['file_url']))
                : null;
        }

        if ($designs) {
            return $this->respond([
                'status' => true,
                'message' => 'Data desain ditemukan',
                'data' => $designs
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada desain untuk proyek ini',
                'data' => $designs
            ]);
        }
    }

    // =========================================================================
    // 6. API UNTUK MENDAPATKAN DATA PROGRESS RENOVASI
    // =========================================================================
    public function progress($projectId = null)
    {
        if ($projectId === null) {
            return $this->fail('ID Proyek tidak boleh kosong.', 400);
        }

        $progressListRaw = $this->db->table('renovation_progress rp')
            ->select('
                rp.id as progress_id, rp.bobot as progress_bobot, rp.photo_url as progress_photo, rp.created_at as progress_date, rp.status as progress_status,
                rt.id as target_id, rt.bobot as target_bobot,
                rr.group_name as rab_group, rr.sub_group_name as rab_subgroup, rr.activity_name as rab_activity,
                ja.tukang_name, (SELECT GROUP_CONCAT(ts.skill_name SEPARATOR \', \') FROM tukang_skill_map tsm JOIN tukang_skill ts ON ts.id = tsm.tukang_skill_id WHERE tsm.tukang_id = ja.tukang_id) as specialization, t.profile_photo, ja.tukang_id as id_tukang
            ')
            ->join('renovation_targets rt', 'rt.id = rp.id_renovation_targets', 'inner')
            ->join('renovation_rabs rr', 'rr.id = rt.id_renovation_rabs', 'left')
            ->join('job_applications ja', 'ja.id = rt.id_job_applications', 'left')
            ->join('tukang t', 't.id = ja.tukang_id', 'left')
            ->where('rp.renovation_id', $projectId)
            ->orderBy('rp.created_at', 'DESC')
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
                $groupedByTarget[$tId]['foto_progress'][] = base_url('uploads/renovation/progress/' . $p['progress_photo']);
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
                'id_target' => $p['target_id'] ?? '-',
                'foto_tukang' => $grp['foto_tukang'],
                'nama_tukang' => $grp['nama_tukang'],
                'spesialis_tukang' => $grp['spesialis_tukang'],
                'header_pekerjaan' => $grp['header_pekerjaan'],
                'pekerjaan' => $grp['pekerjaan'],
                'persentase' => number_format($persentase, 2, '.', ''), // output as string decimal suitable for app formatting
                'laporan_terakhir' => date('Y-m-d H:i:s', strtotime($grp['laporan_terakhir'])),
                'foto_progress' => $grp['foto_progress'],
            ];
        }

        // Urutkan List progress berdasarkan laporan_terakhir paling baru (DESC)
        usort($progressList, function ($a, $b) {
            return strtotime($b['laporan_terakhir']) < strtotime($a['laporan_terakhir']) ? 1 : -1;
        });

        if ($progressList) {
            return $this->respond([
                'status' => true,
                'message' => 'Data progress ditemukan',
                'data' => $progressList
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada progress untuk proyek ini',
                'data' => $progressList
            ]);
        }
    }

    public function progressByUser()
    {

        $user_id = $this->request->user->uid ?? null;

        if ($user_id == null) {
            return $this->failUnauthorized('Akses ditolak. Token tidak valid atau tidak ditemukan.');
        }

        $projects = $this->db->table('renovation_requests rr')
            ->select('
                rr.id as renovation_id,
                u.full_name, 
                rr.start_date, 
                rr.address,
                (SELECT count(id) FROM renovation_targets WHERE renovation_id = rr.id) as total_target,
                (SELECT SUM(rp.bobot) FROM renovation_progress rp 
                 JOIN renovation_targets rt ON rt.id = rp.id_renovation_targets 
                 WHERE rt.renovation_id = rr.id AND LOWER(rp.status) = \'approved\') as total_realisasi
            ')
            ->join('users u', 'u.id = rr.user_id', 'left')
            ->where('rr.user_id', $user_id)
            ->orderBy('rr.created_at', 'DESC')
            ->get()->getResultArray();

        $today = new \DateTime();

        foreach ($projects as &$item) {
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
    // 7. API UNTUK MENDAPATKAN DATA INVOICE RENOVASI
    // =========================================================================
    public function invoices($projectId = null)
    {
        if ($projectId === null) {
            return $this->fail('Project ID tidak boleh kosong.', 400);
        }

        $db = \Config\Database::connect();

        // Query dengan join ke table vouchers untuk mendapatkan nominal diskon
        $data = $db->table('renovation_invoices')
            ->select('renovation_invoices.*, vouchers.discount_nominal')
            ->join('vouchers', 'vouchers.code = renovation_invoices.voucher_code', 'left')
            ->where('renovation_id', $projectId)
            ->get()
            ->getResultArray();

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
        }, $data);

        if ($formattedInvoices) {
            return $this->respond([
                'status' => true,
                'message' => 'Data invoice ditemukan',
                'data' => $formattedInvoices
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada invoice untuk proyek ini',
                'data' => []
            ]);
        }
    }

    // =========================================================================
    // 8. FUNGSI UNTUK MENDAPATKAN DATA RAB PROYEK RENOVASI
    // =========================================================================
    public function rabs($projectId = null)
    {

        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }

        $db = \Config\Database::connect();

        $rabData = $db->table('renovation_rabs')->where('renovation_id', $projectId)->orderBy('created_at', 'DESC')->get()->getResultArray();

        $rabIds = array_column($rabData, 'id');
        $materials = [];
        if (!empty($rabIds)) {
            $materials = $db->table('renovation_rab_materials rrm')
                ->select('rrm.*, p.name as product_name, p.price as product_price, p.unit as product_unit, p.photo as product_photo')
                ->join('products p', 'p.id = rrm.product_id', 'left')
                ->whereIn('rrm.rab_id', $rabIds)
                ->get()->getResultArray();
        }

        foreach ($rabData as &$rab) {
            // Asumsi kolom file bernama 'file'
            $rab['image_url'] = !empty($rab['file']) ? base_url('uploads/renovation/rab/' . $rab['file']) : null;

            $rabMaterials = array_values(array_filter($materials, function ($material) use ($rab) {
                return $material['rab_id'] == $rab['id'];
            }));
            foreach ($rabMaterials as &$material) {
                $material['product_image_url'] = !empty($material['product_photo']) ? base_url('uploads/products/' . $material['product_photo']) : null;
            }
            $rab['materials'] = $rabMaterials;
        }

        if ($rabData) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail RAB Proyek renovasi ditemukan',
                'data' => $rabData
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada RAB untuk Proyek renovasi ini',
                'data' => $rabData
            ]);
        }
    }

    public function select_material()
    {
        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = [
                'rab_id' => $this->request->getVar('rab_id'),
                'product_id' => $this->request->getVar('product_id')
            ];
        }

        $rabId = $json['rab_id'] ?? null;
        $productId = $json['product_id'] ?? null;

        if (!$rabId || !$productId) {
            return $this->fail('Parameter rab_id atau product_id tidak ditemukan.');
        }

        $db = \Config\Database::connect();

        // 1. Ambil info harga produk terbaru dari tabel products
        $product = $db->table('products')->where('id', $productId)->get()->getRowArray();
        if (!$product)
            return $this->fail('Produk tidak ditemukan.');

        // 2. Ambil volume dari item RAB ini
        $rabItem = $db->table('renovation_rabs')->where('id', $rabId)->get()->getRowArray();
        if (!$rabItem)
            return $this->fail('Item RAB tidak ditemukan.');

        // 3. Update tabel construction_rabs
        $updateData = [
            'selected_material_id' => $productId,
            'current_unit_price' => $product['price'],
            'total_price' => (float) $product['price'] * (float) $rabItem['volume'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($db->table('renovation_rabs')->where('id', $rabId)->update($updateData)) {
            return $this->respond([
                'status' => true,
                'message' => 'Material berhasil diperbarui!',
                'data' => $updateData
            ]);
        } else {
            return $this->fail('Gagal memperbarui material di database.');
        }
    }

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
                'template_kontrak' => $this->db->table('renovation_requests')
                    ->select('renovation_requests.address as address_renovation,
                                                    renovation_requests.id as renovation_id,
                                                    renovation_requests.start_date,
                                                    renovation_requests.week,
                                                    users.full_name as nama_klien,
                                                    users.nik as nik_klien,
                                                    users.address as address_klien,
                                                    vouchers.discount_nominal')
                    ->join('users', 'users.id = renovation_requests.user_id', 'left')
                    ->join('vouchers', 'vouchers.code = renovation_requests.voucher_code', 'left')
                    ->where('renovation_requests.id', $projectId)
                    ->get()->getRowArray(),
                'rab' => $this->db->table('renovation_rabs')
                    ->select('group_name, SUM(total_price) as total_price')
                    ->where('renovation_rabs.renovation_id', $projectId)
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
            $html = view('admin/surat/kontrak_template_renovation', $data);

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
            $fileName = 'Renovasi_kontrak_' . $clean_nama . '_' . $projectId . '.pdf';

            $uploadPath = FCPATH . 'uploads/surat_kontrak/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Simpan file PDF ke server
            file_put_contents($uploadPath . $fileName, $output);

            // Update database: push kolom rab_file ke server
            $this->db->table('renovation_requests')
                ->where('id', $projectId)
                ->update([
                    'rab_file' => $fileName,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        // 2. tugas update is_locked
        $this->db->table('renovation_rabs')
            ->where('renovation_id', $projectId)
            ->update([
                'is_locked' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        // Kirim notifikasi ke Admin  
        $namaKlien = $data['template_kontrak']['nama_klien'] ?? 'Seorang client';
        $this->notifService->sendToPermission(
            'renovation_rab',
            'RAB Renovasi Disubmit',
            "RAB untuk proyek Renovasi #{$projectId} telah disubmit oleh client {$namaKlien}. Silakan cek dokumen kontrak yang telah digenerate."
        );

        return $this->respond(['status' => true, 'message' => 'RAB berhasil dikunci!']);
    }

    // =========================================================================
    // 9. FUNGSI UNTUK MENDAPATKAN TARGET PROYEK RENOVASI
    // =========================================================================
    public function targets()
    {
        $db = \Config\Database::connect();
        $targets = $db->table('renovation_targets')->orderBy('created_at', 'ASC')->get()->getResultArray();

        if ($targets) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail Target Proyek renovasi ditemukan',
                'data' => $targets
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada target untuk Proyek renovasi ini',
                'data' => $targets
            ]);
        }
    }

    // =========================================================================
    // 10. FUNGSI UNTUK MENDAPATKAN TARGET PROYEK RENOVASI BERDASARKAN USERS
    // =========================================================================
    public function targetsByUser()
    {
        // Ambil user ID dari JWT/session yang sudah diproses oleh Auth Filter
        $userId = $this->request->user->uid ?? null;

        if ($userId == null) {
            return $this->failUnauthorized('Akses ditolak. Token tidak valid atau tidak ditemukan.');
        }

        $data = $this->db->table('renovation_targets rt')
            ->select("rrab.group_name, 
        rrab.sub_group_name, 
        rrab.activity_name, 
        NULL as construction_id, 
        rreq.id as renovation_id, 
        rt.start_week, 
        rt.end_week, 
        rt.bobot, 
        rt.status as target_status, 
        rreq.status as renovation_status, 
        rreq.start_date, 
        (SELECT COUNT(id) FROM renovation_progress WHERE renovation_progress.id_renovation_targets = rt.id) as report_count, 
        (SELECT status FROM renovation_progress WHERE renovation_progress.id_renovation_targets = rt.id ORDER BY created_at DESC LIMIT 1) as last_report_status, 
        (SELECT COUNT(id) FROM renovation_progress WHERE renovation_progress.id_renovation_targets = rt.id AND LOWER(status) = 'approved') as approved_count, 
        (SELECT COUNT(id) FROM renovation_progress WHERE renovation_progress.id_renovation_targets = rt.id AND LOWER(status) = 'rejected') as rejected_count, 
        (SELECT COUNT(id) FROM renovation_progress WHERE renovation_progress.id_renovation_targets = rt.id AND LOWER(status) = 'pending') as pending_count, 
        (SELECT SUM(bobot) FROM renovation_progress WHERE renovation_progress.id_renovation_targets = rt.id AND LOWER(status) = 'approved') as approved_weight, 
        (SELECT SUM(bobot) FROM renovation_progress WHERE renovation_progress.id_renovation_targets = rt.id AND LOWER(status) = 'pending') as pending_weight", false)
            ->join('renovation_rabs rrab', 'rrab.id = rt.id_renovation_rabs')
            ->join('renovation_requests rreq', 'rreq.id = rt.renovation_id')
            ->join('users u', 'u.id = rreq.user_id')
            ->where('u.id', $userId)
            ->orderBy('rt.start_week', 'ASC')
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
                'message' => 'Target Proyek renovasi ditemukan untuk pengguna.',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada target untuk Proyek renovasi pengguna ini.',
                'data' => []
            ]);
        }
    }

    public function sendCommentSurvey($id_survey)
    {
        $json = $this->request->getJSON(true);
        $comment = $json['comment'] ?? $this->request->getVar('comment');

        if (!$comment) {
            return $this->fail('Data tidak lengkap.');
        }

        $this->db->table('renovation_surveys')->where('id', $id_survey)->update([
            'comment' => $comment
        ]);

        // Ambil info survey  
        $surveyInfo = $this->db->table('renovation_surveys rs')
            ->select('rr.full_name, rr.id as renovation_id')
            ->join('renovation_requests rr', 'rr.id = rs.renovation_id', 'left')
            ->where('rs.id', $id_survey)
            ->get()->getRowArray();

        $namaKlien = $surveyInfo['full_name'] ?? 'Seorang client';

        // Kirim notifikasi ke Admin  
        $this->notifService->sendToPermission(
            'renovation_survey',
            'Komentar Survey Baru',
            "Client {$namaKlien} telah memberikan komentar pada hasil survey renovasi #" . ($surveyInfo['renovation_id'] ?? $id_survey) . "."
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

        $this->db->table('renovation_designs')->where('id', $id_design)->update([
            'comment' => $comment
        ]);

        // Ambil info desain  
        $designInfo = $this->db->table('renovation_designs rd')
            ->select('rr.full_name, rr.id as renovation_id')
            ->join('renovation_requests rr', 'rr.id = rd.renovation_id', 'left')
            ->where('rd.id', $id_design)
            ->get()->getRowArray();

        $namaKlien = $designInfo['full_name'] ?? 'Seorang client';

        // Kirim notifikasi ke Admin  
        $this->notifService->sendToPermission(
            'renovation_desain',
            'Komentar Desain Baru',
            "Client {$namaKlien} telah memberikan komentar pada hasil desain renovasi #" . ($designInfo['renovation_id'] ?? $id_design) . "."
        );

        return $this->respond(['status' => true, 'message' => 'Komentar berhasil ditambahkan.']);
    }

    // =========================================================================
    // FUNGSI ABSEN MASUK (CHECK-IN) RENOVASI
    // =========================================================================
    public function SendAttendance($id_renovation)
    {
        if (!$id_renovation) {
            return $this->fail('ID renovasi tidak boleh kosong.');
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
            'jumlah_tukang' => ['required' => 'Jumlah Tukang wajib diisi.']
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Upload video absensi
        $foto = $this->request->getFile('file');
        $uploadPath = 'uploads/renovation/absen_tukang/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $foto->getRandomName();
        $foto->move($uploadPath, $newName);

        // Simpan ke database
        $attendanceModel = new \App\Modules\Renovation\Models\RenovationAttendanceModel();

        $data = [
            'id_renovation' => $id_renovation,
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
                'renovation_absensi',
                'Absensi Renovasi (Masuk)',
                "Tukang telah mengirim absensi masuk untuk proyek Renovasi #{$id_renovation}. Jumlah tukang: {$data['jumlah_tukang']}."
            );

            return $this->respondCreated([
                'status' => true,
                'message' => 'Absen masuk berhasil dikirim.',
                'data' => [
                    'id' => $attendanceModel->getInsertID(),
                    'id_renovation' => (int) $id_renovation,
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
    // FUNGSI ABSEN KELUAR (CHECK-OUT) RENOVASI
    // =========================================================================
    public function SendCheckoutAttendance($id_renovation)
    {
        if (!$id_renovation) {
            return $this->fail('ID renovasi tidak boleh kosong.');
        }

        // Validasi input
        $validationRules = [
            'file' => 'uploaded[file]|max_size[file,30720]|mime_in[file,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm]',
            'longitude' => 'required',
            'jumlah_tukang' => 'required',
            'latitude' => 'required',
            'waktu' => 'required',
        ];

        $validationMessages = [
            'file' => [
                'uploaded' => 'Video absensi wajib diunggah.',
                'max_size' => 'Ukuran video tidak boleh melebihi 30MB.',
                'mime_in' => 'Format video tidak valid. Gunakan MP4, MOV, AVI, MKV, atau WebM.',
            ],
            'jumlah_tukang' => ['required' => 'Jumlah Tukang wajib diisi.'],
            'longitude' => ['required' => 'Longitude wajib diisi.'],
            'latitude' => ['required' => 'Latitude wajib diisi.'],
            'waktu' => ['required' => 'Waktu absen wajib diisi.'],
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Upload video absensi
        $foto = $this->request->getFile('file');
        $uploadPath = 'uploads/renovation/absen_tukang/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $foto->getRandomName();
        $foto->move($uploadPath, $newName);

        // Simpan ke database
        $attendanceModel = new \App\Modules\Renovation\Models\RenovationAttendanceModel();

        $data = [
            'id_renovation' => $id_renovation,
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
                'renovation_absensi',
                'Absensi Renovasi (Keluar)',
                "Tukang telah mengirim absensi keluar untuk proyek Renovasi #{$id_renovation}. Jumlah tukang: {$data['jumlah_tukang']}."
            );

            return $this->respondCreated([
                'status' => true,
                'message' => 'Absen keluar berhasil dikirim.',
                'data' => [
                    'id' => $attendanceModel->getInsertID(),
                    'id_renovation' => (int) $id_renovation,
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
    // CRUD PENGAJUAN BAHAN DAN ALAT (RENOVATION_MATERIAL_SUBMISSION)
    // =========================================================================

    /**
     * Get list of material/tool submissions.
     * Optional filters: renovation_id, status, type
     */
    public function getMaterialSubmissions()
    {
        $renovationId = $this->request->getVar('renovation_id') ?? $this->request->getGet('renovation_id');
        $status = $this->request->getVar('status') ?? $this->request->getGet('status');
        $type = $this->request->getVar('type') ?? $this->request->getGet('type');

        $query = $this->renovationMaterialSubmissionModel;

        if ($renovationId) {
            $query = $query->where('renovation_id', $renovationId);
        }
        if ($status) {
            $query = $query->where('status', $status);
        }
        if ($type) {
            $query = $query->where('type', $type);
        }

        $submissions = $query->orderBy('created_at', 'DESC')->findAll();

        foreach ($submissions as &$sub) {
            $sub['photo_url'] = !empty($sub['photo']) ? base_url('uploads/renovation/material_submissions/' . $sub['photo']) : null;
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

        $submission = $this->renovationMaterialSubmissionModel->find($id);

        if (!$submission) {
            return $this->failNotFound('Data pengajuan tidak ditemukan.');
        }

        $submission['photo_url'] = !empty($submission['photo']) ? base_url('uploads/renovation/material_submissions/' . $submission['photo']) : null;

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

        $renovationId = $json['renovation_id'] ?? $this->request->getPost('renovation_id');
        $jobApplicationsId = $json['job_applications_id'] ?? $this->request->getPost('job_applications_id');
        $type = $json['type'] ?? $this->request->getPost('type');
        $title = $json['title'] ?? $this->request->getPost('title');
        $description = $json['description'] ?? $this->request->getPost('description');

        // Validasi input
        $validationRules = [
            'renovation_id' => 'required|numeric',
            'job_applications_id' => 'permit_empty|numeric',
            'type' => 'required|in_list[bahan,alat]',
            'title' => 'permit_empty|max_length[255]',
            'description' => 'required',
        ];

        $validationMessages = [
            'renovation_id' => [
                'required' => 'Renovation ID wajib diisi.',
                'numeric' => 'Renovation ID harus berupa angka.'
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
            'renovation_id' => $renovationId,
            'job_applications_id' => $jobApplicationsId,
            'type' => $type,
            'title' => $title,
            'description' => $description
        ];

        if (!$this->validateData($validationData, $validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Cek apakah proyek renovasi ada
        $renovation = $this->db->table('renovation_requests')->where('id', $renovationId)->get()->getRowArray();
        if (!$renovation) {
            return $this->failNotFound('Proyek renovasi tidak ditemukan.');
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
            $uploadPath = 'uploads/renovation/material_submissions/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $photoName = $photoFile->getRandomName();
            $photoFile->move($uploadPath, $photoName);
        }

        $data = [
            'renovation_id' => (int) $renovationId,
            'job_applications_id' => !empty($jobApplicationsId) ? (int) $jobApplicationsId : null,
            'type' => $type,
            'title' => !empty($title) ? $title : null,
            'description' => $description,
            'photo' => $photoName,
            'status' => 'pending',
        ];

        if ($this->renovationMaterialSubmissionModel->insert($data)) {
            $insertedId = $this->renovationMaterialSubmissionModel->getInsertID();
            $insertedData = $this->renovationMaterialSubmissionModel->find($insertedId);
            $insertedData['photo_url'] = !empty($insertedData['photo']) ? base_url('uploads/renovation/material_submissions/' . $insertedData['photo']) : null;

            // Kirim notifikasi ke Admin
            $this->notifService->sendToPermission(
                'renovation_detail',
                'Pengajuan Bahan/Alat Baru',
                "Tukang mengirim pengajuan {$type} baru untuk proyek Renovasi #{$renovationId}."
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

        $submission = $this->renovationMaterialSubmissionModel->find($id);
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
            $uploadPath = 'uploads/renovation/material_submissions/';
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

        if ($this->renovationMaterialSubmissionModel->update($id, $dataUpdate)) {
            $updatedData = $this->renovationMaterialSubmissionModel->find($id);
            $updatedData['photo_url'] = !empty($updatedData['photo']) ? base_url('uploads/renovation/material_submissions/' . $updatedData['photo']) : null;

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

        $submission = $this->renovationMaterialSubmissionModel->find($id);
        if (!$submission) {
            return $this->failNotFound('Data pengajuan tidak ditemukan.');
        }

        // Hapus file photo dari disk jika ada
        if (!empty($submission['photo'])) {
            $uploadPath = 'uploads/renovation/material_submissions/';
            if (file_exists($uploadPath . $submission['photo'])) {
                unlink($uploadPath . $submission['photo']);
            }
        }

        if ($this->renovationMaterialSubmissionModel->delete($id)) {
            return $this->respond([
                'status' => true,
                'message' => 'Data pengajuan berhasil dihapus.'
            ]);
        }

        return $this->fail('Gagal menghapus data pengajuan.');
    }

    public function submitRenovationAndDesignRequests()
    {
        // 1. Validasi gambar renovasi dan berkas desain
        $validationRules = [
            'images' => 'uploaded[images]|max_size[images,5120]|mime_in[images,image/jpg,image/jpeg,image/png,image/webp]',
            'design_files' => 'uploaded[design_files]|max_size[design_files,10240]|mime_in[design_files,application/pdf,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        $validationMessages = [
            'images' => [
                'uploaded' => 'Setidaknya satu gambar renovasi harus diunggah.',
                'max_size' => 'Ukuran salah satu gambar renovasi melebihi 5MB.',
                'mime_in' => 'Format salah satu gambar renovasi tidak valid. Gunakan JPG, PNG, atau WebP.'
            ],
            'design_files' => [
                'uploaded' => 'Setidaknya satu berkas desain harus diunggah.',
                'max_size' => 'Ukuran salah satu berkas desain melebihi 10MB.',
                'mime_in' => 'Format salah satu berkas desain tidak valid. Gunakan PDF, JPG, PNG, atau WebP.'
            ]
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Ambil dan proses gambar renovasi
        $images = $this->request->getFileMultiple('images');
        if ($images === null) {
            $singleImage = $this->request->getFile('images');
            $images = ($singleImage && $singleImage->isValid()) ? [$singleImage] : [];
        }

        if (count($images) > 5) {
            return $this->failValidationErrors('Anda hanya boleh mengunggah maksimal 5 gambar renovasi.');
        }

        $uploadedFileNames = [];
        $uploadPath = FCPATH . 'uploads/renovation/';
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

        if (empty($uploadedFileNames)) {
            return $this->failValidationErrors('Gagal mengunggah gambar renovasi.');
        }

        // Ambil dan proses berkas-berkas desain klien
        $designFiles = $this->request->getFileMultiple('design_files');
        if ($designFiles === null) {
            $singleDesign = $this->request->getFile('design_files');
            $designFiles = ($singleDesign && $singleDesign->isValid()) ? [$singleDesign] : [];
        }

        if (count($designFiles) > 5) {
            // Hapus file gambar renovasi yang terlanjur diunggah
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
            // Hapus file gambar renovasi yang terlanjur diunggah
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
                $projectDesignData = [
                    'user_admin_id' => null,
                    'design_request_id' => $designRequestId,
                    'design_targets_id' => $targetId,
                    'revision_number' => 1,
                    'design_name' => $currentDesignName,
                    'file' => $fileName,
                    'status' => 'PENDING',
                    'revision_note' => null,
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

            // B. Simpan data Renovasi (Renovation Request)
            $renovationData = [
                'user_id' => $this->request->getPost('user_id'),
                'full_name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone_number'),
                'renovation_type' => $this->request->getPost('renovation_type'),
                'description' => $this->request->getPost('description'),
                'address' => $this->request->getPost('address') ?: $this->request->getPost('location_address'),
                'latitude' => $this->request->getPost('latitude'),
                'longitude' => $this->request->getPost('longitude'),
                'voucher_code' => $this->request->getPost('renovation_voucher_code') ?: $this->request->getPost('voucher_code'),
                'discount_amount' => $this->request->getPost('renovation_discount_amount') ?: $this->request->getPost('discount_amount') ?: 0,
                'status' => 'PENDING',
                'created_at' => date('Y-m-d H:i:s')
            ];

            foreach ($uploadedFileNames as $index => $fileName) {
                $renovationData['gambar' . ($index + 1)] = $fileName;
            }

            $renovationId = $this->model->insert($renovationData);
            if (!$renovationId) {
                throw new \RuntimeException('Gagal menyimpan data renovasi ke database.');
            }

            // C. Simpan Relasi di renovation_designs (Menunjuk ke berkas desain pertama klien)
            // Kolom berkas pada renovation_designs bernama `file_url`
            $renovationDesignData = [
                'request_id' => $renovationId,
                'user_admin_id' => null,
                'design_requests_id' => $designRequestId,
                'title' => $firstDesignName,
                'file_url' => $firstDesignFile,
                'comment' => 'Desain dipilih oleh pelanggan saat pengajuan renovasi.',
                'created_at' => date('Y-m-d H:i:s')
            ];

            if (!$this->db->table('renovation_designs')->insert($renovationDesignData)) {
                throw new \RuntimeException('Gagal menyimpan relasi renovasi dan desain ke database.');
            }

            // D. Kirim notifikasi ke Admin
            $this->notifService->sendToPermission(
                'design_detail',
                'Pengajuan Desain Baru',
                "Pelanggan atas nama {$designData['full_name']} telah mengajukan desain baru beserta berkas desain. Silakan cek detail."
            );

            $this->notifService->sendToPermission(
                'renovation_detail',
                'Permohonan Renovasi Baru',
                "Pelanggan atas nama {$renovationData['full_name']} telah mengirim permohonan renovasi baru. Silakan cek detail."
            );

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal diselesaikan.');
            }

            return $this->respondCreated([
                'status' => true,
                'message' => 'Permohonan renovasi dan desain berhasil dikirim secara bersamaan',
                'data' => [
                    'renovation_id' => $renovationId,
                    'design_requests_id' => $designRequestId
                ]
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();

            // Hapus file gambar renovasi yang sudah diunggah jika transaksi gagal
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
}
