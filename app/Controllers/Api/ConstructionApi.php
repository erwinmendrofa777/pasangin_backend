<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

// Import class Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

class ConstructionApi extends BaseController
{
    use ResponseTrait;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // =========================================================================
    // 1. FUNGSI UNTUK MENERIMA PENGAJUAN KONSTRUKSI BARU
    // =========================================================================
    public function submit()
    {
        //validasi input
        $validationRules = [
            'images'    => 'uploaded[images]|max_size[images,5120]|mime_in[images,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        $validationMessages = [
            'images' => [
                'uploaded' => 'Setidaknya satu gambar harus diunggah.',
                'max_size' => 'Ukuran salah satu gambar melebihi 5MB.',
                'mime_in'  => 'Format salah satu gambar tidak valid. Gunakan JPG, PNG, atau WebP.'
            ]
        ];

        // Mencegah Error Undefined Array
        if (!$this->validate($validationRules, $validationMessages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // 3. Ambil data yang sudah pasti tervalidasi aman
        $data = [
            'user_id'         => $this->request->getPost('user_id'),
            'full_name'       => $this->request->getPost('full_name'),
            'phone'           => $this->request->getPost('phone_number'),
            'land_area'       => $this->request->getPost('land_area'),
            'building_area'   => $this->request->getPost('building_area'),
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
        $uploadPath = 'uploads/construction/';
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

        // 5. Simpan ke database jika ada gambar yang berhasil diproses
        if (!empty($uploadedFileNames)) {
            foreach ($uploadedFileNames as $index => $fileName) {
                $data['gambar' . ($index + 1)] = $fileName;
            }

            if ($this->db->table('construction_requests')->insert($data)) {
                return $this->respondCreated([
                    'status'  => true,
                    'message' => 'Permohonan berhasil dikirim',
                ]);
            } else {
                return $this->fail('Gagal memperbarui data di database. Pastikan kolom gambar1-5 ada di allowedFields model.');
            }
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

        $surveys = $this->db->table('construction_surveys')->where('construction_id', $projectId)->orderBy('created_at', 'DESC')->get()->getResultArray();

        foreach ($surveys as &$item) {
            $item['image_url'] = !empty($item['survey_file']) ? base_url('uploads/construction/survey/' . $item['survey_file']) : null;
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

        $designs = $this->db->table('construction_designs')->where('construction_id', $projectId)->orderBy('created_at', 'DESC')->get()->getResultArray();

        foreach ($designs as &$item) {
            $item['image_url'] = !empty($item['file']) ? base_url('uploads/construction/designs/' . $item['file']) : null;
        }

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
                cp.id as progress_id, cp.bobot as progress_bobot, cp.photo_url as progress_photo, cp.created_at as progress_date, cp.status as progress_status,
                ct.id as target_id, ct.bobot as target_bobot,
                cr.group_name as rab_group, cr.sub_group_name as rab_subgroup, cr.activity_name as rab_activity,
                ja.tukang_name, ja.specialization, t.profile_photo, ja.tukang_id as id_tukang,
                tr.id as rating_id, tr.skill_score, tr.behavior_score, tr.comment as rating_comment, tr.created_at as rating_created_at
            ')
            ->join('construction_targets ct', 'ct.id = cp.id_construction_targets', 'inner')
            ->join('construction_rabs cr', 'cr.id = ct.id_construction_rabs', 'left')
            ->join('job_applications ja', 'ja.id = ct.id_job_applications', 'left')
            ->join('tukang t', 't.id = ja.tukang_id', 'left')
            ->join('tukang_rating tr', "tr.target_id = ct.id AND tr.id_tukang = t.id AND tr.project_type = 'construction'", 'left')
            ->where('cp.construction_id', $projectId)
            ->orderBy('cp.created_at', 'ASC')
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
                    'rating'           => $p['rating_id'] ?? null,
                    'comment'          => $p['rating_comment'] ?? null,
                    'skill_score'      => $p['skill_score'] ?? null,
                    'behavior_score'   => $p['behavior_score'] ?? null,
                    'created_at_rating'       => $p['rating_created_at'],
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
                'id_tukang'        => $grp['id_tukang'] ?? '-',
                'foto_tukang'      => $grp['foto_tukang'],
                'nama_tukang'      => $grp['nama_tukang'],
                'spesialis_tukang' => $grp['spesialis_tukang'],
                'header_pekerjaan' => $grp['header_pekerjaan'],
                'pekerjaan'        => $grp['pekerjaan'],
                'persentase'       => number_format($persentase, 2, '.', ''), // output as string decimal suitable for app formatting
                'laporan_terakhir' => date('Y-m-d H:i:s', strtotime($grp['laporan_terakhir'])),
                'foto_progress'    => $grp['foto_progress'],
                'target_id'        => $tId,
                'rating'           => $grp['rating'],
                'comment'          => $grp['comment'],
                'skill_score'      => $grp['skill_score'],
                'behavior_score'   => $grp['behavior_score'],
                'created_at_rating'       => $grp['created_at_rating'],
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

    // =========================================================================
    // 7. FUNGSI UNTUK MENDAPATKAN INVOICE PROYEK KONSTRUKSI
    // =========================================================================
    public function invoices($projectId = null)
    {
        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }

        $invoices = $this->db->table('construction_invoices')->where('construction_id', $projectId)->orderBy('created_at', 'ASC')->get()->getResultArray();

        if ($invoices) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail Invoice Proyek konstruksi ditemukan',
                'data' => $invoices
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada invoice untuk Proyek konstruksi ini',
                'data' => $invoices
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
                (SELECT count(id) FROM construction_targets WHERE construction_id = cr.id) as total_target,
                (SELECT SUM(cp.bobot) FROM construction_progress cp 
                 JOIN construction_targets ct ON ct.id = cp.id_construction_targets 
                 WHERE ct.construction_id = cr.id AND LOWER(cp.status) = \'approved\') as total_realisasi
            ')
            ->join('users u', 'u.id = cr.user_id', 'left')
            ->where('cr.user_id', $user_id)
            ->orderBy('cr.created_at', 'DESC')
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
    // 8. FUNGSI UNTUK MEMILIH MATERIAL RAB (UPDATE DATABASE)
    // =========================================================================
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

        // 1. Ambil info harga produk terbaru dari tabel products
        $product = $this->db->table('products')->where('id', $productId)->get()->getRowArray();
        if (!$product) return $this->fail('Produk tidak ditemukan.');

        // 2. Ambil volume dari item RAB ini
        $rabItem = $this->db->table('construction_rabs')->where('id', $rabId)->get()->getRowArray();
        if (!$rabItem) return $this->fail('Item RAB tidak ditemukan.');

        // 3. Update tabel construction_rabs
        $updateData = [
            'selected_material_id' => $productId,
            'current_unit_price'   => $product['price'],
            'total_price'          => (float)$product['price'] * (float)$rabItem['volume'],
            'updated_at'           => date('Y-m-d H:i:s')
        ];

        if ($this->db->table('construction_rabs')->where('id', $rabId)->update($updateData)) {
            return $this->respond([
                'status'  => true,
                'message' => 'Material berhasil diperbarui!',
                'data'    => $updateData
            ]);
        } else {
            return $this->fail('Gagal memperbarui material di database.');
        }
    }

    // =========================================================================
    // 9. FUNGSI UNTUK MENDAPATKAN DATA RAB PROYEK KONSTRUKSI
    // =========================================================================
    public function rabs($projectId = null)
    {
        if ($projectId == null) {
            return $this->fail('Project ID tidak boleh kosong.');
        }

        $rabData = $this->db->table('construction_rabs')->where('construction_id', $projectId)->orderBy('created_at', 'DESC')->get()->getResultArray();

        $rabIds = array_column($rabData, 'id');
        $materials = [];
        if (!empty($rabIds)) {
            $materials = $this->db->table('construction_rab_materials crm')
                ->select('crm.*, p.name as product_name, p.price as product_price, p.unit as product_unit, p.photo as product_photo')
                ->join('products p', 'p.id = crm.product_id', 'left')
                ->whereIn('crm.rab_id', $rabIds)
                ->get()->getResultArray();
        }

        foreach ($rabData as &$rab) {
            $rab['image_url'] = !empty($rab['file']) ? base_url('uploads/construction/rab/' . $rab['file']) : null;

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

        if (!$projectId) return $this->fail('Project ID tidak ditemukan.');

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
                'rab' => $this->db->table('construction_rabs')
                    ->select('group_name, SUM(total_price) as total_price')
                    ->where('construction_rabs.construction_id', $projectId)
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

        // 2. tugas update is_locked
        $this->db->table('construction_rabs')
            ->where('construction_id', $projectId)
            ->update([
                'is_locked' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

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
            ->select("crab.group_name, 
        crab.sub_group_name, 
        crab.activity_name, 
        creq.id as construction_id, 
        NULL as renovation_id, 
        ct.start_week, 
        ct.end_week, 
        ct.bobot, 
        ct.status as target_status, 
        creq.status as construction_status, 
        creq.start_date, 
        (SELECT COUNT(id) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id) as report_count, 
        (SELECT status FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id ORDER BY created_at DESC LIMIT 1) as last_report_status, 
        (SELECT COUNT(id) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND LOWER(status) = 'approved') as approved_count, 
        (SELECT COUNT(id) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND LOWER(status) = 'rejected') as rejected_count, 
        (SELECT COUNT(id) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND LOWER(status) = 'pending') as pending_count, 
        (SELECT SUM(bobot) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND LOWER(status) = 'approved') as approved_weight, 
        (SELECT SUM(bobot) FROM construction_progress WHERE construction_progress.id_construction_targets = ct.id AND LOWER(status) = 'pending') as pending_weight", false)
            ->join('construction_rabs crab', 'crab.id = ct.id_construction_rabs')
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

        $this->db->table('construction_surveys')->where('id', $id_survey)->update([
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

        $this->db->table('construction_designs')->where('id', $id_design)->update([
            'comment' => $comment
        ]);

        return $this->respond(['status' => true, 'message' => 'Komentar berhasil ditambahkan.']);
    }
}
