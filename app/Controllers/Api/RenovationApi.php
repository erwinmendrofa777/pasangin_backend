<?php
// FILE: backend/app/Controllers/Api/RenovationApi.php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\RenovationModel;

// Import class Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

class RenovationApi extends BaseController
{
    use ResponseTrait;
    protected $model;
    protected $db;

    public function __construct()
    {
        $this->model = new RenovationModel();
        $this->db = \Config\Database::connect();
    }

    // =========================================================================
    // 1. API UNTUK MENERIMA PENGAJUAN RENOVASI BARU
    // =========================================================================
    public function submit()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'images'    => 'uploaded[images]|max_size[images,5120]|mime_in[images,image/jpg,image/jpeg,image/png,image/webp]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->fail($validation->getErrors());
        }

        $data = [
            'user_id'         => $this->request->getPost('user_id'),
            'full_name'       => $this->request->getPost('full_name'),
            'phone'           => $this->request->getPost('phone_number'),
            'renovation_type' => $this->request->getPost('renovation_type'),
            'description'     => $this->request->getPost('description'),
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

        // Gunakan getFileMultiple agar otomatis selalu menjadi array
        $images = $this->request->getFileMultiple('images');

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
                return $this->respondCreated([
                    'status'  => true,
                    'message' => 'Permohonan renovasi berhasil dikirim',
                    'data'    => $inserted_id
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

        $db = \Config\Database::connect();
        $data = $db->table('renovation_designs')->where('request_id', $projectId)->get()->getResultArray();

        foreach ($data as &$item) {
            $item['image_url'] = !empty($item['file_url']) ? base_url('uploads/designs/' . $item['file_url']) : null;
        }

        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'Data desain ditemukan',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada desain untuk proyek ini',
                'data' => $data
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
                ja.tukang_name, ja.specialization, t.profile_photo, ja.tukang_id as id_tukang
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
            if (!$tId) continue;

            if (!isset($groupedByTarget[$tId])) {
                // Formatting Pekerjaan
                $sub = !empty($p['rab_subgroup']) ? ' - ' . $p['rab_subgroup'] : '';
                $header_pekerjaan = ($p['rab_group'] ?? '') . $sub;
                $pekerjaan = $p['rab_activity'] ?? '-';

                $groupedByTarget[$tId] = [
                    'id_tukang'        => $p['id_tukang'] ?? '-',
                    'foto_tukang'      => !empty($p['profile_photo']) ? base_url('uploads/tukang/' . $p['profile_photo']) : null,
                    'nama_tukang'      => $p['tukang_name'] ?? '-',
                    'spesialis_tukang' => $p['specialization'] ?? '-',
                    'header_pekerjaan' => trim(trim($header_pekerjaan, ' -')),
                    'pekerjaan'        => $pekerjaan,
                    'persentase'       => 0, // di-kalkulasi di akhir
                    'laporan_terakhir' => null,
                    'foto_progress'    => [],
                    // Temp variable untuk kalkulasi
                    '_target_bobot'         => (float)($p['target_bobot'] ?? 0),
                    '_total_progress_bobot' => 0
                ];
            }

            // Akumulasi bobot progress
            if (strtoupper($p['progress_status']) === 'APPROVED') {
                $groupedByTarget[$tId]['_total_progress_bobot'] += (float)$p['progress_bobot'];
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
                'id_tukang'        => $grp['id_tukang'] ?? '-',
                'foto_tukang'      => $grp['foto_tukang'],
                'nama_tukang'      => $grp['nama_tukang'],
                'spesialis_tukang' => $grp['spesialis_tukang'],
                'header_pekerjaan' => $grp['header_pekerjaan'],
                'pekerjaan'        => $grp['pekerjaan'],
                'persentase'       => number_format($persentase, 2, '.', ''), // output as string decimal suitable for app formatting
                'laporan_terakhir' => date('Y-m-d H:i:s', strtotime($grp['laporan_terakhir'])),
                'foto_progress'    => $grp['foto_progress'],
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

            $item['total_target'] = (float)$item['total_target'];
            $item['total_realisasi'] = (float)$item['total_realisasi'];
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
        // Menggunakan kolom 'renovation_id' sesuai screenshot database kawan
        $data = $db->table('renovation_invoices')
            ->where('renovation_id', $projectId)
            ->get()
            ->getResultArray();

        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'Data invoice ditemukan',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada invoice untuk proyek ini',
                'data' => $data
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
        if (!$product) return $this->fail('Produk tidak ditemukan.');

        // 2. Ambil volume dari item RAB ini
        $rabItem = $db->table('renovation_rabs')->where('id', $rabId)->get()->getRowArray();
        if (!$rabItem) return $this->fail('Item RAB tidak ditemukan.');

        // 3. Update tabel construction_rabs
        $updateData = [
            'selected_material_id' => $productId,
            'current_unit_price'   => $product['price'],
            'total_price'          => (float)$product['price'] * (float)$rabItem['volume'],
            'updated_at'           => date('Y-m-d H:i:s')
        ];

        if ($db->table('renovation_rabs')->where('id', $rabId)->update($updateData)) {
            return $this->respond([
                'status'  => true,
                'message' => 'Material berhasil diperbarui!',
                'data'    => $updateData
            ]);
        } else {
            return $this->fail('Gagal memperbarui material di database.');
        }
    }

    public function finalize_rab()
    {
        $json = $this->request->getJSON(true);
        $projectId = $json['project_id'] ?? $this->request->getVar('project_id');

        if (!$projectId) return $this->fail('Project ID tidak ditemukan.');

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
                    ->groupBy('roman_number', 'group_name')
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
            $row['report_count']    = (int)$row['report_count'];
            $row['approved_count']  = (int)$row['approved_count'];
            $row['rejected_count']  = (int)$row['rejected_count'];
            $row['pending_count']   = (int)$row['pending_count'];
            $row['approved_weight'] = (float)$row['approved_weight'];
            $row['pending_weight']  = (float)$row['pending_weight'];

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

        return $this->respond(['status' => true, 'message' => 'Komentar berhasil ditambahkan.']);
    }
}
