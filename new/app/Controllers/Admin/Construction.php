<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ConstructionModel;

class Construction extends BaseController
{
    protected $constructionModel;
    protected $db;

    public function __construct()
    {
        $this->constructionModel = new ConstructionModel();
        $this->db = \Config\Database::connect();
    }

    // =========================================================================
    // 1. HALAMAN LIST DATA & DETAIL
    // =========================================================================

    public function index()
    {
        $data = [
            'title' => 'Daftar Konstruksi',
            'projects' => $this->constructionModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('admin/construction/index', $data);
    }

    public function detail($id)
    {
        // 1. Ambil Data Utama
        $construction = $this->constructionModel->find($id);

        if (!$construction) {
            return redirect()->to(base_url('admin/construction'))->with('error', 'Data tidak ditemukan.');
        }

        // 2. Ambil Data Progress
        $progressList = $this->db->table('construction_progress')
                                 ->where('construction_id', $id)
                                 ->orderBy('week_number', 'DESC')
                                 ->get()->getResultArray();

        // 3. Ambil Data Desain (Galeri)
        $designList = $this->db->table('construction_designs')
                               ->where('construction_id', $id)
                               ->orderBy('created_at', 'DESC')
                               ->get()->getResultArray();
                               
        // 4. Ambil Data Survey (Riwayat)
        $surveyList = $this->db->table('construction_surveys')
                               ->where('construction_id', $id)
                               ->orderBy('created_at', 'DESC')
                               ->get()->getResultArray();

        // 5. Ambil Data Tagihan (Invoices)
        $invoiceList = $this->db->table('construction_invoices')
                                ->where('construction_id', $id)
                                ->orderBy('created_at', 'DESC')
                                ->get()->getResultArray();

        $data = [
            'title' => 'Detail Konstruksi',
            'construction' => $construction,
            'progress_list' => $progressList,
            'design_list'   => $designList,
            'survey_list'   => $surveyList,
            'invoice_list'  => $invoiceList
        ];

        return view('admin/construction/detail', $data);
    }

    // =========================================================================
    // 2. UPDATE STATUS PROYEK
    // =========================================================================

    public function updateStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        
        $this->constructionModel->update($id, ['status' => $status]);
        
        return redirect()->to(base_url('admin/construction/detail/' . $id))->with('success', 'Status berhasil diperbarui');
    }

    // =========================================================================
    // 3. FITUR SURVEY (Upload & Delete)
    // =========================================================================

    public function uploadSurvey()
    {
        $constructionId = $this->request->getPost('id');
        $title = $this->request->getPost('survey_title');
        $notes = $this->request->getPost('survey_notes');
        $file = $this->request->getFile('survey_file');

        $fileName = '';

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            if (!is_dir('uploads/construction/survey/')) { mkdir('uploads/construction/survey/', 0777, true); }
            $file->move('uploads/construction/survey', $fileName);
        }

        $dataInsert = [
            'construction_id' => $constructionId,
            'survey_title'    => $title,
            'survey_notes'    => $notes,
            'survey_file'     => $fileName,
            'created_at'      => date('Y-m-d H:i:s')
        ];

        $this->db->table('construction_surveys')->insert($dataInsert);
        
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#survey'))->with('success', 'Laporan survey berhasil ditambahkan!');
    }

    public function deleteSurvey($id, $constructionId)
    {
        $survey = $this->db->table('construction_surveys')->where('id', $id)->get()->getRowArray();
        
        if ($survey && !empty($survey['survey_file'])) {
            $filePath = 'uploads/construction/survey/' . $survey['survey_file'];
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        $this->db->table('construction_surveys')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#survey'))->with('success', 'Laporan survey dihapus.');
    }

    // =========================================================================
    // 4. FITUR DESAIN (Upload & Delete)
    // =========================================================================

    public function uploadDesign()
    {
        $constructionId = $this->request->getPost('id');
        $title = $this->request->getPost('design_title');
        $file = $this->request->getFile('design_2d');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir('uploads/construction/designs/')) { mkdir('uploads/construction/designs/', 0777, true); }
            $file->move('uploads/construction/designs', $newName);
            
            $dataInsert = [
                'construction_id' => $constructionId,
                'title'           => $title,
                'file'            => $newName,
                'created_at'      => date('Y-m-d H:i:s')
            ];

            $this->db->table('construction_designs')->insert($dataInsert);
            
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#desain'))->with('success', 'File Desain berhasil ditambahkan ke galeri');
        }
        
        return redirect()->back()->with('error', 'Gagal upload file desain.');
    }

    public function deleteDesign($id, $constructionId)
    {
        $design = $this->db->table('construction_designs')->where('id', $id)->get()->getRowArray();
        
        if ($design && !empty($design['file'])) {
            $filePath = 'uploads/construction/designs/' . $design['file'];
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        $this->db->table('construction_designs')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#desain'))->with('success', 'File desain dihapus.');
    }

    // =========================================================================
    // 5. FITUR RAB (Upload Only)
    // =========================================================================

    public function uploadRab()
    {
        $id = $this->request->getPost('id');
        $total = $this->request->getPost('rab_total');
        $file = $this->request->getFile('rab_file');

        $dataUpdate = ['rab_total' => $total];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir('uploads/construction/rab/')) { mkdir('uploads/construction/rab/', 0777, true); }
            $file->move('uploads/construction/rab', $newName);
            $dataUpdate['rab_file'] = $newName;
        }

        $this->constructionModel->update($id, $dataUpdate);
        
        return redirect()->to(base_url('admin/construction/detail/' . $id))->with('success', 'Data RAB berhasil disimpan');
    }

    // =========================================================================
    // 6. FITUR TAGIHAN (MIDTRANS) - VERSI STABIL
    // =========================================================================

    // Note: Nama function pakai UNDERSCORE (_) agar cocok dengan routes & URL
    public function create_invoice()
    {
        // 1. SIAPKAN LIBRARY MIDTRANS
        $midtransPath = APPPATH . 'ThirdParty/Midtrans/Midtrans.php';
        if (!file_exists($midtransPath)) {
            $midtransPath = APPPATH . 'ThirdParty/midtrans/Midtrans.php';
        }

        $constructionId = $this->request->getPost('construction_id');
        // Pastikan amount adalah integer murni
        $amount = (int) $this->request->getPost('amount'); 
        $description = $this->request->getPost('description');

        // 2. SIMPAN TAGIHAN KE DATABASE DULU (Agar dapat ID Invoice untuk Order ID)
        $data = [
            'construction_id' => $constructionId,
            'description'     => $description,
            'amount'          => $amount,
            'due_date'        => $this->request->getPost('due_date'),
            'status'          => 'UNPAID',
            'created_at'      => date('Y-m-d H:i:s')
        ];

        $this->db->table('construction_invoices')->insert($data);
        $invoiceId = $this->db->insertID(); 

        // 3. PROSES KE MIDTRANS (Jika Library Ada)
        if (file_exists($midtransPath)) {
            require_once $midtransPath;

            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = 'SB-Mid-server-UKNiwjL6WD2HSFzQ4vP8oKeg'; // Ganti dengan Server Key Anda
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Buat Order ID Unik (CONST-WAKTU-ID)
            $orderIdMidtrans = 'CONST-' . time() . '-' . $invoiceId;

            // Parameter Transaksi
            $params = [
                'transaction_details' => [
                    'order_id' => $orderIdMidtrans,
                    'gross_amount' => $amount,
                ],
                'item_details' => [
                    [
                        'id' => 'INV-' . $invoiceId,
                        'price' => $amount,
                        'quantity' => 1,
                        // Nama item tidak boleh terlalu panjang
                        'name' => substr($description, 0, 45) 
                    ]
                ],
                'customer_details' => [
                    'first_name' => 'Pelanggan',
                    'last_name' => 'Konstruksi',
                    'email' => 'customer@example.com', // Wajib ada format email valid (dummy ok)
                    'phone' => '08123456789',
                ],
            ];

            try {
                // Minta Token ke Midtrans
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                
                // 4. UPDATE DATABASE (SIMPAN TOKEN)
                // Pastikan tabel construction_invoices punya kolom snap_token & order_id
                $this->db->table('construction_invoices')->where('id', $invoiceId)->update([
                    'snap_token' => $snapToken,
                    'order_id'   => $orderIdMidtrans
                ]);

            } catch (\Exception $e) {
                // Tampilkan pesan error detail jika gagal koneksi ke Midtrans
                return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#payment'))
                                 ->with('error', 'Midtrans Error: ' . $e->getMessage());
            }
        } else {
             return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#payment'))
                                 ->with('error', 'Library Midtrans tidak ditemukan. Cek folder ThirdParty.');
        }

        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#payment'))
                         ->with('success', 'Tagihan berhasil dibuat & terhubung ke Midtrans!');
    }

    public function delete_invoice($id, $constructionId)
    {
        $this->db->table('construction_invoices')->where('id', $id)->delete();
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#payment'))
                         ->with('success', 'Tagihan dihapus.');
    }

    // =========================================================================
    // 7. FITUR PROGRESS (Timeline)
    // =========================================================================

    public function addProgress()
    {
        $constructionId = $this->request->getPost('construction_id');
        
        $data = [
            'construction_id' => $constructionId,
            'week_number'     => $this->request->getPost('week_number'),
            'percentage'      => $this->request->getPost('percentage'),
            'description'     => $this->request->getPost('description'),
            'created_at'      => date('Y-m-d H:i:s')
        ];

        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir('uploads/construction/progress/')) { mkdir('uploads/construction/progress/', 0777, true); }
            $file->move('uploads/construction/progress', $newName);
            $data['photo_url'] = $newName;
        } else {
            $data['photo_url'] = '';
        }

        $this->db->table('construction_progress')->insert($data);

        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))
                         ->with('success', 'Update progress berhasil ditambahkan!');
    }

    public function deleteProgress($id, $constructionId)
    {
        $progress = $this->db->table('construction_progress')->where('id', $id)->get()->getRowArray();
        
        if ($progress && !empty($progress['photo_url'])) {
            $filePath = 'uploads/construction/progress/' . $progress['photo_url'];
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        $this->db->table('construction_progress')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))
                         ->with('success', 'Data progress dihapus.');
    }
}
