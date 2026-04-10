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

        foreach($surveys as &$item) {
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
        
        foreach($designs as &$item) {
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

        $progress = $this->db->table('construction_progress')->where('construction_id', $projectId)->orderBy('week_number', 'DESC')->get()->getResultArray();

        foreach($progress as &$item) {
            $item['image_url'] = !empty($item['photo_url']) ? base_url('uploads/construction/progress/' . $item['photo_url']) : null;
        }

        if ($progress) {
            return $this->respond([
                'status' => true,
                'message' => 'Detail Progress Proyek konstruksi ditemukan',
                'data' => $progress
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada progress untuk Proyek konstruksi ini',
                'data' => $progress
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

        if($invoices){
            return $this->respond([
                'status' => true,
                'message' => 'Detail Invoice Proyek konstruksi ditemukan',
                'data' => $invoices
            ]);
        }else{
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada invoice untuk Proyek konstruksi ini',
                'data' => $invoices
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
            
            $rabMaterials = array_values(array_filter($materials, function($material) use ($rab) {
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
        }else{
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

        try{
        // 1. generate dan upload kontrak.pdf
        helper('terbilang');
        $tanggal_kontrak = date('Y-m-d');

        $data = [
            // construction_rabs - construction_requests - users
            'template_kontrak' => $this->db->table('construction_requests')
                                            ->select('construction_requests.address as address_construction,
                                                    construction_requests.id as construction_id,
                                                    users.full_name as nama_klien,
                                                    users.nik as nik_klien,
                                                    users.address as address_klien,
                                                    vouchers.discount_nominal')
                                            ->join('users', 'users.id = construction_requests.user_id','left')
                                            ->join('vouchers', 'vouchers.code = construction_requests.voucher_code','left')
                                            ->where('construction_requests.id', $projectId)
                                            ->get()->getRowArray(),
            'rab' => $this->db->table('construction_rabs')
                                ->select('group_name, SUM(total_price) as total_price')
                                ->where('construction_rabs.construction_id', $projectId)
                                ->groupBy('roman_number','group_name')
                                ->orderBy('roman_number', 'ASC')
                                ->get()->getResultArray(),
            'kalimat_pembuka' => tanggal_surat_indo($tanggal_kontrak),
            'tanggal_kontrak' => $tanggal_kontrak,
        ];

        // Ambil output HTML dari View
        $html = view('admin/surat/kontrak_template', $data);

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        // Masukkan string HTML ke dalam Dompdf
        $dompdf->loadHtml($html);

        // Atur ukuran kertas dan orientasinya
        $dompdf->setPaper('A4', 'portrait');

        // Render (proses konversi) HTML menjadi PDF
        $dompdf->render();
        ob_end_clean();

        // Save PDF to database dan server
        $output = $dompdf->output();
        $nama_klien = $data['template_kontrak']['nama_klien'] ?? 'user';
        $clean_nama = preg_replace('/[^A-Za-z0-9\-]/', '_', $nama_klien);
        
        // Memastikan nama file unik menggunakan timestamp atau project ID
        $fileName = 'Kontruksi_kontrak_' . $clean_nama .'_'. $projectId . '.pdf';
        
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
                $requestTargets = array_filter($targetsData, function($t) use ($request) {
                    return $t['construction_id'] == $request['id'];
                });
                
                $request['targets'] = array_values($requestTargets);
                $groupedData[] = $request;
            }
        }

        if($groupedData){
            return $this->respond([
                'status' => true,
                'message' => 'Detail Target Proyek konstruksi ditemukan',
                'data' => $groupedData
            ]);
        }else{
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

        $targets = $this->db->table('construction_targets')
            ->join('construction_requests', 'construction_requests.id = construction_targets.construction_id')
            ->where('construction_requests.user_id', $userId)
            ->select('construction_targets.*')
            ->orderBy('construction_targets.created_at', 'ASC')
            ->get()->getResultArray();

        if ($targets) {
            return $this->respond([
                'status' => true,
                'message' => 'Target Proyek konstruksi ditemukan untuk pengguna.',
                'data' => $targets
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

    public function sendCommentSurvey($id_survey){
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

    public function sendCommentDesign($id_design){
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