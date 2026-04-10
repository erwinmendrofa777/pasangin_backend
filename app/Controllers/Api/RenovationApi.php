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

        foreach($data as &$item) {
            $item['image_url'] = !empty($item['file_url']) ? base_url('uploads/survey/' . $item['file_url']) : null;
        }

        if($data){
            return $this->respond([
                'status' => true,
                'message' => 'Data survey ditemukan',
                'data' => $data
            ]);
        }else{
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

        foreach($data as &$item) {
            $item['image_url'] = !empty($item['file_url']) ? base_url('uploads/designs/' . $item['file_url']) : null;
        }

        if($data){
            return $this->respond([
                'status' => true,
                'message' => 'Data desain ditemukan',
                'data' => $data
            ]);
        }else{
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

        $db = \Config\Database::connect();
        $data = $db->table('renovation_progress')->where('request_id', $projectId)->get()->getResultArray();

        foreach($data as &$item) {
            $item['image_url'] = !empty($item['photo_url']) ? base_url('uploads/progress/' . $item['photo_url']) : null;
        }

        if($data){
            return $this->respond([
                'status' => true,
                'message' => 'Data progress ditemukan',
                'data' => $data
            ]);
        }else{
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada progress untuk proyek ini',
                'data' => $data
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

        if($data){
            return $this->respond([
                'status' => true,
                'message' => 'Data invoice ditemukan',
                'data' => $data
            ]);
        }else{
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
                'message' => 'Detail RAB Proyek renovasi ditemukan', 
                'data' => $rabData
            ]); 
        }else{
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

        try{
            // 1. generate dan upload kontrak.pdf
            helper('terbilang');
            $tanggal_kontrak = date('Y-m-d');
            
            $data = [
            'template_kontrak' => $this->db->table('renovation_requests')
                                            ->select('renovation_requests.address as address_renovation,
                                                    renovation_requests.id as renovation_id,
                                                    users.full_name as nama_klien,
                                                    users.nik as nik_klien,
                                                    users.address as address_klien,
                                                    vouchers.discount_nominal')
                                            ->join('users', 'users.id = renovation_requests.user_id','left')
                                            ->join('vouchers', 'vouchers.code = renovation_requests.voucher_code','left')
                                            ->where('renovation_requests.id', $projectId)
                                            ->get()->getRowArray(),
            'rab' => $this->db->table('renovation_rabs')
                                ->select('group_name, SUM(total_price) as total_price')
                                ->where('renovation_rabs.renovation_id', $projectId)
                                ->groupBy('roman_number','group_name')
                                ->orderBy('roman_number', 'ASC')
                                ->get()->getResultArray(),
            'kalimat_pembuka' => tanggal_surat_indo($tanggal_kontrak),
            'tanggal_kontrak' => $tanggal_kontrak,
            ];

            // Ambil output HTML dari View
            $html = view('admin/surat/kontrak_template_renovation', $data);

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
            $fileName = 'Renovasi_kontrak_' . $clean_nama .'_'. $projectId . '.pdf';
            
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
        }catch(\Exception $e){
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

        if($targets){
            return $this->respond([
                'status' => true,
                'message' => 'Detail Target Proyek renovasi ditemukan',
                'data' => $targets
            ]);
        }else{
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

        $db = \Config\Database::connect();
        $targets = $db->table('renovation_targets')
            ->join('renovation_requests', 'renovation_requests.id = renovation_targets.renovation_id')
            ->where('renovation_requests.user_id', $userId)
            ->select('renovation_targets.*')
            ->orderBy('renovation_targets.created_at', 'ASC')
            ->get()->getResultArray();

        if ($targets) {
            return $this->respond([
                'status' => true,
                'message' => 'Target Proyek renovasi ditemukan untuk pengguna.',
                'data' => $targets
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada target untuk Proyek renovasi pengguna ini.',
                'data' => []
            ]);
        }
    }

    public function sendCommentSurvey($id_survey){
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

    public function sendCommentDesign($id_design){
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