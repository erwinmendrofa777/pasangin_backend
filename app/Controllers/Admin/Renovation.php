<?php
// FILE: backend/app/Controllers/Admin/Renovation.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RenovationModel;

class Renovation extends BaseController
{
    protected $renovationModel;
    protected $db;

    public function __construct()
    {
        $this->renovationModel = new RenovationModel();
        $this->db = \Config\Database::connect();
    }

    public function index(){
    $data = [
        'title'     => 'Daftar Proyek Renovasi',
        'requests'  => $this->db->table('renovation_requests')
                        ->select('renovation_requests.*, users.full_name AS client_name, users.phone_number') // TAMBAHKAN ALIAS 'AS' DI SINI KAWAN
                        ->join('users', 'users.id = renovation_requests.user_id', 'left')
                        ->orderBy('renovation_requests.created_at', 'DESC')
                        ->get()
                        ->getResultArray(),
    ];
    return view('admin/renovation/index', $data);
    }

    public function detail($id)
    {
        // 1. Ambil data proyek utama
        $projectData = $this->db->table('renovation_requests')
            ->select('renovation_requests.*, users.full_name, users.email, users.phone_number')
            ->join('users', 'users.id = renovation_requests.user_id', 'left')
            ->where('renovation_requests.id', $id)
            ->get()->getRowArray();

        if (empty($projectData)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // 2. Ambil semua data terkait (Survey, Desain, Progress, Invoice)
        $surveyList     = $this->db->table('renovation_surveys')->where('request_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $designList     = $this->db->table('renovation_designs')->where('request_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $progressList   = $this->db->table('renovation_progress')->where('request_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $invoiceList    = $this->db->table('renovation_invoices')->where('renovation_id', $id)->orderBy('created_at', 'ASC')->get()->getResultArray();
        $targetList     = $this->db->table('renovation_targets')->select('renovation_targets.*, job_applications.tukang_name as tukang_name')->join('job_applications', 'job_applications.id = renovation_targets.id_job_applications', 'left')->where('renovation_id', $id)->orderBy('created_at', 'ASC')->get()->getResultArray();
        $jobInfo        = $this->db->table('renovation_jobs')->where('renovation_id', $id)->get()->getRowArray();
        $applicants     = $this->db->table('job_applications')->where('project_id', $id)->where('project_type', 'renovation')->orderBy('created_at', 'DESC')->get()->getResultArray();
        $worker         = $this->db->table('job_applications')->where('project_id', $id)->where('project_type', 'renovation')->where('status', 'Siap Kerja')->orderBy('created_at', 'DESC')->get()->getResultArray();

        $rabList = $this->db->table('renovation_rabs')
                        ->where('renovation_id', $id)
                        ->orderBy('roman_number', 'ASC')
                        ->orderBy('id', 'ASC')
                        ->get()
                        ->getResultArray();

        foreach ($rabList as &$item) {
            $item['materials'] = $this->db->table('rab_material_options')
                                          ->select('rab_material_options.*, products.name as material_name, products.price')
                                          ->join('products', 'products.id = rab_material_options.product_id')
                                          ->where('rab_id', $item['id'])
                                          ->get()->getResultArray();
        }

        $allProducts = $this->db->table('products')->where('status', 'aktif')->get()->getResultArray();

        // 3. Kirim semua data ke view
        $data = [
            'title'         => 'Detail Proyek Renovasi',
            'project'       => $projectData,
            'surveys'       => $surveyList,
            'designs'       => $designList,
            'progress'      => $progressList,
            'invoices'      => $invoiceList,
            'job_info'      => $jobInfo,
            'target_list'   => $targetList,
            'rab_list'      => $rabList,
            'all_products'  => $allProducts,
            'applicants'    => $applicants,
            'worker'        => $worker
        ];

        return view('admin/renovation/detail', $data);
    }

    // =========================================================================
    // === FITUR RAB (SINKRON WEB & FLUTTER) ===================================
    // =========================================================================

    /**
     * Simpan atau Update Baris Pekerjaan RAB
     * Sinkron dengan input Roman, Group, dan Sub Group kawan
     */
    public function save_rab_row() {
        $id = $this->request->getPost('id');
        $vol = (float) $this->request->getPost('volume');
        $price = (float) $this->request->getPost('price');
        
        $data = [
            'renovation_id'    => $this->request->getPost('renovation_id'),
            'roman_number'       => $this->request->getPost('roman_number') ?: 'I',
            'group_name'         => $this->request->getPost('group_name') ?: 'PEKERJAAN',
            'sub_group_name'     => $this->request->getPost('section_group'), 
            'section_group'      => $this->request->getPost('section_group'),
            'section_name'       => $this->request->getPost('section_group'),
            'activity_name'      => $this->request->getPost('task_name'), 
            'volume'             => $vol,
            'unit'               => $this->request->getPost('unit'),
            'current_unit_price' => $price,
            'total_price'        => $vol * $price
        ];

        try {
            if (!$id || $id == "0") {
                $this->db->table('renovation_rabs')->insert($data);
                $id = $this->db->insertID();
            } else {
                // Proteksi: Jangan update jika sudah dikunci kawan
                $check = $this->db->table('renovation_rabs')->where('id', $id)->get()->getRowArray();
                if ($check && $check['is_locked'] == 1) {
                    return $this->response->setJSON(['status' => false, 'message' => 'Baris sudah dikunci!']);
                }
                $this->db->table('renovation_rabs')->where('id', $id)->update($data);
            }

            return $this->response->setJSON([
                'status'  => true,
                'id'      => $id,
                'message' => 'Data RAB berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Mengunci Semua RAB pada Proyek ini
     */
    public function lock_rab($renovationId)
    {
        $this->db->table('renovation_rabs')
                 ->where('renovation_id', $renovationId)
                 ->update(['is_locked' => 1]);

        // Update status proyek ke tahap Konstruksi
        $this->renovationModel->update($renovationId, ['status' => 'Construction']);

        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#rab'))
                         ->with('success', 'RAB Berhasil Dikunci kawan!');
    }

    /**
     * Membuka Kunci RAB (Khusus Admin)
     */
    public function unlock_rab($renovationId)
    {
        $this->db->table('renovation_rabs')
                 ->where('renovation_id', $renovationId)
                 ->update(['is_locked' => 0]);

        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#rab'))
                         ->with('success', 'Kunci RAB dibuka kawan!');
    }

    /**
     * Hapus Satu Baris Pekerjaan RAB & Opsinya
     */
    public function delete_rab_row($id)
    {
        // Cek dulu apakah dikunci kawan
        $check = $this->db->table('renovation_rabs')->where('id', $id)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data terkunci!']);
        }

        $this->db->table('renovation_rabs')->where('id', $id)->delete();
        $this->db->table('renovation_rab_materials')->where('rab_id', $id)->delete();
        return $this->response->setJSON(['status' => true]);
    }

    /**
     * Ambil Daftar Material untuk Modal di Web
     */
    public function get_rab_materials($rabId)
    {
        $data = $this->db->table('renovation_rab_materials')
                         ->select('renovation_rab_materials.*, products.name as material_name, products.price')
                         ->join('products', 'products.id = renovation_rab_materials.product_id')
                         ->where('rab_id', $rabId)->get()->getResultArray();
        return $this->response->setJSON($data);
    }

    /**
     * Tambah Produk ke Pilihan Material RAB
     */
    public function add_rab_material()
    {
        $rabId = $this->request->getPost('rab_id');
        
        // Cek kunci kawan
        $check = $this->db->table('renovation_rabs')->where('id', $rabId)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            return $this->response->setJSON(['status' => false, 'message' => 'RAB Terkunci!']);
        }

        $productId = $this->request->getPost('product_id');
        $data = [
            'rab_id'     => $rabId,
            'product_id' => $productId
        ];
        
        $this->db->table('renovation_rab_materials')->insert($data);
        return $this->response->setJSON(['status' => true, 'message' => 'Material ditambahkan.']);
    }

    /**
     * Hapus Satu Opsi Material
     */
    public function delete_rab_material($id)
    {
        $this->db->table('renovation_rab_materials')->where('id', $id)->delete();
        return $this->response->setJSON(['status' => true]);
    }

    /**
     * API UNTUK FLUTTER (Get RAB Ter-Grouping)
     * Dipanggil oleh ApiService.dart kawan
     */
    public function get_renovation_rab_api($renovation_id)
    {
        $raw = $this->db->table('renovation_rabs')
                        ->where('renovation_id', $renovation_id)
                        ->orderBy('roman_number', 'ASC')
                        ->orderBy('id', 'ASC')
                        ->get()->getResultArray();

        foreach ($raw as &$item) {
            // Mapping key untuk RabDetailItem di Flutter kawan
            $item['item_name']     = $item['activity_name'];
            $item['current_price'] = $item['current_unit_price'];

            // Tarik opsi material untuk MaterialOption di Flutter
            $item['materials'] = $this->db->table('renovation_rab_materials')
                ->select('products.name as material_name, products.price, products.description')
                ->join('products', 'products.id = renovation_rab_materials.product_id')
                ->where('rab_id', $item['id'])
                ->get()->getResultArray();
        }

        return $this->response->setJSON($raw);
    }

    // -------------------------------------------------------------
    // --- BARU: Fungsi untuk menambahkan target proyek renovasi ---
    // -------------------------------------------------------------
    public function add_target(){

        $renovationId = $this->request->getPost('renovation_id');

        try{
            $this->db->table('renovation_targets')->insert([
                'id_job_applications' => $this->request->getPost('id_job_applications'),
                'renovation_id'   => $renovationId,
                'target_name'     => $this->request->getPost('target_name'),
                'target_date'     => $this->request->getPost('target_date'),
                'description'     => $this->request->getPost('description'),
                'status'          => 'Pending'
            ]);
        
            return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#target'))->with('success', 'Target proyek berhasil ditambahkan!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Gagal menambahkan target proyek. Silakan coba lagi.');
        }
    }

    public function update_target_status($id, $status)
    {
        $this->db->table('renovation_targets')->where('id', $id)
                 ->update(['status' => $status]);
        return redirect()->back()->with('success', 'Status target berhasil diperbarui!');
    }

    // --- BARU: Fungsi untuk update status pelamar renovasi ---
    public function update_applicant_status(){
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $this->db->table('job_applications')->where('id', $id)->update([
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Status pelamar renovasi diperbarui!');
    }

    /**
     * Update Lowongan Pekerjaan (Untuk Tukang)
     */
    public function update_job_info(){
    $renovation_id = $this->request->getPost('id');
    
    // --- AMBIL KOORDINAT OTOMATIS DARI TABEL REQUEST ---
    $requestData = $this->db->table('renovation_requests')
                            ->where('id', $renovation_id)
                            ->get()->getRowArray();

    $data = [
        'renovation_id'    => $renovation_id,
        'detail_pekerjaan' => $this->request->getPost('detail_pekerjaan'),
        'detail_lokasi'    => $this->request->getPost('detail_lokasi'),
        'tempat_tinggal'   => $this->request->getPost('tempat_tinggal'),
        'tanggal_mulai'    => $this->request->getPost('tanggal_mulai'),
        'tanggal_akhir'    => $this->request->getPost('tanggal_akhir'),
        'upah_per_hari'    => $this->request->getPost('upah_per_hari'),
        
        // Simpan koordinat dari data request kawan
        'latitude'         => $requestData['latitude'] ?? '0',
        'longitude'        => $requestData['longitude'] ?? '0',
        'updated_at'       => date('Y-m-d H:i:s')
    ];

    $builder = $this->db->table('renovation_jobs');
    $exist = $builder->where('renovation_id', $renovation_id)->get()->getRow();

    if ($exist) {
        $builder->where('renovation_id', $renovation_id)->update($data);
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $builder->insert($data);
    }

    return redirect()->to(base_url('admin/renovation/detail/' . $renovation_id . '#info-pekerjaan'))
                     ->with('success', 'Info Pekerjaan & Lokasi berhasil disinkronkan!');
    }

    public function update_status()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $this->renovationModel->update($id, ['status' => $status]);
        return redirect()->to(base_url('admin/renovation/detail/' . $id))->with('success', 'Status proyek berhasil diperbarui');
    }

    // --- FUNGSI HELPER LAINNYA ---
    public function add_survey($requestId)
    {
        $file = $this->request->getFile('file_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move('uploads/survey/', $fileName);
            $this->db->table('renovation_surveys')->insert([
                'request_id'   => $requestId,
                'title'        => $this->request->getPost('title'),
                'description'  => $this->request->getPost('description'),
                'file_url'     => $fileName
            ]);
        }
        return redirect()->to('/admin/renovation/detail/' . $requestId)->with('success', 'Laporan survey ditambahkan.');
    }

    public function delete_survey($id, $renovationId)
    {
        $this->db->table('renovation_surveys')->where('id', $id)->delete();
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#survey'))->with('success', 'Survey dihapus.');
    }

    public function add_design($requestId)
    {
        $file = $this->request->getFile('file_url');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move('uploads/designs/', $fileName);
            $this->db->table('renovation_designs')->insert([
                'request_id' => $requestId,
                'title'      => $this->request->getPost('title'),
                'file_url'   => $fileName,
            ]);
            session()->setFlashdata('success', 'Desain ditambahkan.');
        }
        return redirect()->to('/admin/renovation/detail/' . $requestId);
    }

    public function add_progress($requestId)
    {
        $photo = $this->request->getFile('photo_url');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move('uploads/progress/', $photoName);
            $data['photo_url'] = $photoName;

            $data = [
            'request_id'  => $requestId,
            'title'       => $this->request->getPost('title'),
            'week_number' => $this->request->getPost('week_number'),
            'description' => $this->request->getPost('description'),
            'photo_url'   => $photoName,
            ];
        }
        $this->db->table('renovation_progress')->insert($data);
        return redirect()->to('/admin/renovation/detail/' . $requestId);
    }

    public function create_invoice()
    {
        $renovationId = $this->request->getPost('renovation_id');
        $project = $this->db->table('renovation_requests')->where('id', $renovationId)->get()->getRowArray();
        $data = [
            'renovation_id' => $renovationId,
            'user_id'       => $project['user_id'],
            'description'   => $this->request->getPost('description'),
            'amount'        => $this->request->getPost('amount'),
            'due_date'      => $this->request->getPost('due_date') ?: null,
            'status'        => 'UNPAID',
        ];
        $this->db->table('renovation_invoices')->insert($data);
        return redirect()->to('admin/renovation/detail/' . $renovationId)->with('success', 'Tagihan dibuat.');
    }

    public function delete_invoice($id, $renovationId)
    {
        $this->db->table('renovation_invoices')->where('id', $id)->delete();
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#payment'))->with('success', 'Tagihan dihapus.');
    }
}
