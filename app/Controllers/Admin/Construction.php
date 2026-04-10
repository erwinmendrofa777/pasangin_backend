<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ConstructionModel;
use App\Models\RabModel;

class Construction extends BaseController
{
    protected $constructionModel;
    protected $rabModel;
    protected $db;

    public function __construct()
    {
        $this->constructionModel = new ConstructionModel();
        $this->rabModel = new RabModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Tampilkan Daftar Proyek Konstruksi
     */
    public function index()
    {
        $data = [
            'title' => 'Daftar Konstruksi',
            'projects' => $this->db->table('construction_requests')->orderBy('created_at', 'DESC')->get()->getResultArray()
        ];
        return view('admin/construction/index', $data);
    }

    /**
     * Tampilkan Detail Proyek (Termasuk Tab RAB, Target, Pelamar, dll)
     */
    public function detail($id)
    {
        $construction = $this->constructionModel
                            ->select('construction_requests.*, users.full_name, users.email, users.phone_number')
                            ->join('users', 'users.id = construction_requests.user_id', 'left')
                            ->find($id);

        if (!$construction) {
            return redirect()->to(base_url('admin/construction'))->with('error', 'Data tidak ditemukan.');
        }

        // --- 1. AMBIL DATA RELASI ---
        $progressList = $this->db->table('construction_progress')->where('construction_id', $id)->orderBy('week_number', 'DESC')->get()->getResultArray();
        $designList   = $this->db->table('construction_designs')->where('construction_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $surveyList   = $this->db->table('construction_surveys')->where('construction_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $invoiceList  = $this->db->table('construction_invoices')->where('construction_id', $id)->orderBy('created_at', 'ASC')->get()->getResultArray();
        $jobInfo      = $this->db->table('construction_jobs')->where('construction_id', $id)->get()->getRowArray();
        $applicants   = $this->db->table('job_applications')->where('project_id', $id)->where('project_type', 'construction')->orderBy('created_at', 'DESC')->get()->getResultArray();
        $targetList   = $this->db->table('construction_targets')->where('construction_id', $id)->get()->getResultArray();
        
        // list tagihan
        $list_tagihan = $this->db->table('construction_rabs')
                                ->select('group_name, SUM(total_price) as total_price')
                                ->where('construction_rabs.construction_id', $id)
                                ->groupBy('roman_number','group_name')
                                ->orderBy('roman_number', 'ASC')
                                ->get()->getResultArray();

        // --- 2. AMBIL DATA RAB  ---
        $rabList = $this->db->table('construction_rabs')
                            ->where('construction_id', $id)
                            ->orderBy('roman_number', 'ASC')
                            ->orderBy('id', 'ASC')
                            ->get()->getResultArray();

        // Ambil pilihan material untuk setiap baris RAB
        foreach ($rabList as &$item) {
            $item['materials'] = $this->db->table('rab_material_options')
                                          ->select('rab_material_options.*, products.name as material_name, products.price')
                                          ->join('products', 'products.id = rab_material_options.product_id')
                                          ->where('rab_id', $item['id'])
                                          ->get()->getResultArray();
        }

        // --- 3. AMBIL DATA PRODUK UNTUK MODAL SELECTION ---
        $allProducts = $this->db->table('products')->where('status', 'aktif')->get()->getResultArray();

        $data = [
            'title'         => 'Detail Konstruksi',
            'construction'  => $construction,
            'progress_list' => $progressList,
            'design_list'   => $designList,
            'survey_list'   => $surveyList,
            'invoice_list'  => $invoiceList,
            'job_info'      => $jobInfo,
            'applicants'    => $applicants,
            'target_list'   => $targetList,
            'rab_list'      => $rabList,
            'all_products'  => $allProducts,
            'list_tagihan'  => $list_tagihan,
            // Untuk tab Target: list pekerjaan RAB (group, subgroup, activity)
            'rab'           => array_map(fn($r) => [
                'id'             => $r['id'],
                'group_name'     => $r['group_name'],
                'sub_group_name' => $r['sub_group_name'] ?? '',
                'activity_name'  => $r['activity_name'],
                'total_price'    => $r['total_price'],
            ], $rabList),
        ];

        return view('admin/construction/detail', $data);
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
            'construction_id'    => $this->request->getPost('construction_id'),
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
                $this->db->table('construction_rabs')->insert($data);
                $id = $this->db->insertID();
            } else {
                // Proteksi: Jangan update jika sudah dikunci kawan
                $check = $this->db->table('construction_rabs')->where('id', $id)->get()->getRowArray();
                if ($check && $check['is_locked'] == 1) {
                    return $this->response->setJSON(['status' => false, 'message' => 'Baris sudah dikunci!']);
                }
                $this->db->table('construction_rabs')->where('id', $id)->update($data);
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
    public function lock_rab($constructionId)
    {
        $this->db->table('construction_rabs')
                 ->where('construction_id', $constructionId)
                 ->update(['is_locked' => 1]);

        // Update status proyek ke tahap Konstruksi
        $this->constructionModel->update($constructionId, ['status' => 'Construction']);

        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#rab'))
                         ->with('success', 'RAB Berhasil Dikunci kawan!');
    }

    /**
     * Membuka Kunci RAB (Khusus Admin)
     */
    public function unlock_rab($constructionId)
    {
        $this->db->table('construction_rabs')
                 ->where('construction_id', $constructionId)
                 ->update(['is_locked' => 0]);

        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#rab'))
                         ->with('success', 'Kunci RAB dibuka kawan!');
    }

    /**
     * Hapus Satu Baris Pekerjaan RAB & Opsinya
     */
    public function delete_rab_row($id)
    {
        // Cek dulu apakah dikunci kawan
        $check = $this->db->table('construction_rabs')->where('id', $id)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data terkunci!']);
        }

        $this->db->table('construction_rabs')->where('id', $id)->delete();
        $this->db->table('construction_rab_materials')->where('rab_id', $id)->delete();
        return $this->response->setJSON(['status' => true]);
    }

    /**
     * Ambil Daftar Material untuk Modal di Web
     */
    public function get_rab_materials($rabId)
    {
        $data = $this->db->table('construction_rab_materials')
                         ->select('construction_rab_materials.*, products.name as material_name, products.price')
                         ->join('products', 'products.id = construction_rab_materials.product_id')
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
        $check = $this->db->table('construction_rabs')->where('id', $rabId)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            return $this->response->setJSON(['status' => false, 'message' => 'RAB Terkunci!']);
        }

        $productId = $this->request->getPost('product_id');
        $data = [
            'rab_id'     => $rabId,
            'product_id' => $productId
        ];
        
        $this->db->table('construction_rab_materials')->insert($data);
        return $this->response->setJSON(['status' => true, 'message' => 'Material ditambahkan.']);
    }

    /**
     * Hapus Satu Opsi Material
     */
    public function delete_rab_material($id){
        $this->db->table('construction_rab_materials')->where('id', $id)->delete();
        return $this->response->setJSON(['status' => true]);
    }

    /**
     * API UNTUK FLUTTER (Get RAB Ter-Grouping)
     * Dipanggil oleh ApiService.dart kawan
     */
    public function get_construction_rab_api($construction_id)
    {
        $raw = $this->db->table('construction_rabs')
                        ->where('construction_id', $construction_id)
                        ->orderBy('roman_number', 'ASC')
                        ->orderBy('id', 'ASC')
                        ->get()->getResultArray();

        foreach ($raw as &$item) {
            // Mapping key untuk RabDetailItem di Flutter kawan
            $item['item_name']     = $item['activity_name'];
            $item['current_price'] = $item['current_unit_price'];

            // Tarik opsi material untuk MaterialOption di Flutter
            $item['materials'] = $this->db->table('construction_rab_materials')
                ->select('products.name as material_name, products.price, products.description')
                ->join('products', 'products.id = construction_rab_materials.product_id')
                ->where('rab_id', $item['id'])
                ->get()->getResultArray();
        }

        return $this->response->setJSON($raw);
    }

    // =========================================================================
    // === FUNGSI-FUNGSI LAIN (TARGET, STATUS, INVOICE, DLL) ===================
    // =========================================================================

    public function add_target()
    {
        $constructionId = $this->request->getPost('construction_id');
        $this->db->table('construction_targets')->insert([
            'construction_id'       => $constructionId,
            'target_name'           => $this->request->getPost('target_name'),
            'start_date'            => $this->request->getPost('start_date'),
            'end_date'              => $this->request->getPost('end_date'),
            'description'           => $this->request->getPost('description'),
            'status'                => 'Pending'
        ]);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#target'))->with('success', 'Target proyek berhasil ditambahkan!');
    }

    public function update_target_status($id, $status)
    {
        $this->db->table('construction_targets')->where('id', $id)
                 ->update(['status' => $status]);
        return redirect()->back()->with('success', 'Status target berhasil diperbarui!');
    }


    public function delete_target($id, $constructionId)
    {
        $this->db->table('construction_targets')->where('id', $id)->delete();
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#target'))->with('success', 'Target proyek dihapus.');
    }

    public function update_applicant_status()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $this->db->table('job_applications')->where('id', $id)->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
        return redirect()->back()->with('success', 'Status pelamar berhasil diperbarui!');
    }

    public function update_job_info()
    {
        $construction_id = $this->request->getPost('id');    
        $requestData = $this->db->table('construction_requests')->where('id', $construction_id)->get()->getRowArray();
        $data = [
            'construction_id'  => $construction_id,
            'detail_pekerjaan' => $this->request->getPost('detail_pekerjaan'),
            'detail_lokasi'    => $this->request->getPost('detail_lokasi'),
            'tempat_tinggal'   => $this->request->getPost('tempat_tinggal'),
            'tanggal_mulai'    => $this->request->getPost('tanggal_mulai'),
            'tanggal_akhir'    => $this->request->getPost('tanggal_akhir'),
            'upah_per_hari'    => $this->request->getPost('upah_per_hari'),
            'latitude'         => $requestData['latitude'] ?? '0',
            'longitude'        => $requestData['longitude'] ?? '0',
            'updated_at'       => date('Y-m-d H:i:s')
        ];
        $builder = $this->db->table('construction_jobs');
        $exist = $builder->where('construction_id', $construction_id)->get()->getRow();
        if ($exist) { 
            $builder->where('construction_id', $construction_id)->update($data); 
        } else { 
            $data['created_at'] = date('Y-m-d H:i:s'); 
            $builder->insert($data); 
        }
        return redirect()->to(base_url('admin/construction/detail/' . $construction_id . '#info-pekerjaan'))->with('success', 'Info Pekerjaan & Lokasi disinkronkan!');
    }

    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $this->constructionModel->update($id, ['status' => $status]);
        return redirect()->to(base_url('admin/construction/detail/' . $id))->with('success', 'Status diperbarui');
    }

    public function create_invoice()
    {
        $constructionId = $this->request->getPost('construction_id');
        $amount = (int) $this->request->getPost('amount');
        $project = $this->db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();
        if (!$project || !isset($project['user_id'])) return redirect()->back()->with('error', 'Proyek tidak ditemukan.');
        $this->db->table('construction_invoices')->insert([
            'construction_id' => $constructionId,
            'user_id'         => $project['user_id'],
            'description'     => $this->request->getPost('description'),
            'amount'          => $amount,
            'due_date'        => $this->request->getPost('due_date') ?: null,
            'status'          => 'UNPAID',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#payment'))->with('success', 'Tagihan dibuat!');
    }

    public function delete_invoice($id, $constructionId)
    {
        $this->db->table('construction_invoices')->where('id', $id)->delete();
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#payment'))->with('success', 'Tagihan dihapus.');
    }

    public function uploadSurvey()
    {
        $constructionId = $this->request->getPost('id');
        $file = $this->request->getFile('survey_file');
        $fileName = '';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move('uploads/construction/survey', $fileName);
        }
        $this->db->table('construction_surveys')->insert([
            'construction_id' => $constructionId,
            'survey_title'    => $this->request->getPost('survey_title'),
            'survey_notes'    => $this->request->getPost('survey_notes'),
            'survey_file'     => $fileName,
            'created_at'      => date('Y-m-d H:i:s')
        ]);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#survey'))->with('success', 'Survey ditambahkan!');
    }

    public function deleteSurvey($id, $constructionId)
    {
        $this->db->table('construction_surveys')->where('id', $id)->delete();
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#survey'))->with('success', 'Survey dihapus.');
    }

    public function uploadDesign()
    {
        $constructionId = $this->request->getPost('id');
        $file = $this->request->getFile('design_2d');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/construction/designs', $newName);
            $this->db->table('construction_designs')->insert([
                'construction_id' => $constructionId,
                'title'           => $this->request->getPost('design_title'),
                'file'            => $newName,
                'created_at'      => date('Y-m-d H:i:s')
            ]);
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#desain'))->with('success', 'Desain ditambahkan!');
        }
        return redirect()->back()->with('error', 'Gagal upload.');
    }

    public function deleteDesign($id, $constructionId)
    {
        $this->db->table('construction_designs')->where('id', $id)->delete();
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#desain'))->with('success', 'Desain dihapus.');
    }
    
    public function addProgress()
    {
        $constructionId = $this->request->getPost('construction_id');
        $data = [
            'construction_id' => $constructionId,
            'week_number'      => $this->request->getPost('week_number'),
            'percentage'      => $this->request->getPost('percentage'),
            'description'     => $this->request->getPost('description'),
            'created_at'      => date('Y-m-d H:i:s')
        ];
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/construction/progress', $newName);
            $data['photo_url'] = $newName; 
        }
        $this->db->table('construction_progress')->insert($data);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))->with('success', 'Progress ditambahkan!');
    }

    public function deleteProgress($id, $constructionId)
    {
        $this->db->table('construction_progress')->where('id', $id)->delete();
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))->with('success', 'Progress dihapus.');
    }

    public function view_target($id){
        $jangka_waktu = $this->db->table('construction_requests')
                                 ->select('id, start_date, week')
                                 ->where('id', $id)
                                 ->get()->getRowArray();

        $rab = $this->db->table('construction_rabs')
                        ->select('id, group_name, sub_group_name, activity_name, total_price')
                        ->where('construction_id', $id)
                        ->get()->getResultArray();

        $targetList = $this->db->table('construction_targets')
                               ->where('construction_id', $id)
                               ->get()->getResultArray();

        return view('admin/construction/target', [
            'target_list'   => $targetList,
            'rab'           => $rab,
            'construction'  => $jangka_waktu,
        ]);
    }

    public function createTarget($id_project){
        $rab_id    = $this->request->getPost('rab_id');
        $startDate = $this->request->getPost('start_week');
        $week      = $this->request->getPost('end_week');
        $bobot     = $this->request->getPost('bobot');

        $data = [
            'id_job_applications'  => 13,
            'start_week'           => $startDate,
            'end_week'             => $week,
            'bobot'                => $bobot,
        ];

        // Cek apakah target untuk RAB ini sudah ada
        $existing = $this->db->table('construction_targets')
                             ->where('construction_id', $id_project)
                             ->where('id_construction_rabs', $rab_id)
                             ->get()->getRowArray();

        if ($existing) {
            $this->db->table('construction_targets')
                     ->where('id', $existing['id'])
                     ->update($data);
            $msg = 'Target diperbarui!';
        } else {
            $data['construction_id']      = $id_project;
            $data['id_construction_rabs'] = $rab_id;
            $this->db->table('construction_targets')->insert($data);
            $msg = 'Target ditambahkan!';
        }

        return redirect()->to(base_url('admin/construction/detail/' . $id_project . '#target'))->with('success', $msg);
    }

    public function update_schedule()
    {
        $id         = $this->request->getPost('construction_id');
        $startDate  = $this->request->getPost('start_date');
        $week       = (int) $this->request->getPost('week');

        $this->db->table('construction_requests')
                 ->where('id', $id)
                 ->update([
                     'start_date' => $startDate ?: null,
                     'week'       => $week > 0 ? $week : null,
                 ]);

        return redirect()
            ->to(base_url('admin/construction/detail/' . $id.'#target'))
            ->with('success', 'Jadwal proyek berhasil diperbarui!');
    }
}