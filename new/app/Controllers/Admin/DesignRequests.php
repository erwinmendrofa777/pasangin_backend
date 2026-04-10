<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DesignRequestModel;

class DesignRequests extends BaseController
{
    protected $designModel;
    protected $db;

    public function __construct()
    {
        $this->designModel = new DesignRequestModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Permohonan Desain',
            'requests' => $this->designModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('admin/design/index', $data);
    }

    public function show($id)
    {
        $request = $this->designModel->find($id);

        if (!$request) {
            return redirect()->to('/admin/designrequests')->with('error', 'Data tidak ditemukan');
        }

        $surveys = $this->db->table('project_surveys')->where('design_request_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $designs = $this->db->table('project_designs')->where('design_request_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray();
        $invoices = $this->db->table('project_invoices')->where('design_request_id', $id)->orderBy('id', 'ASC')->get()->getResultArray();

        $data = [
            'title' => 'Detail Proyek',
            'request' => $request,
            'surveys' => $surveys,
            'design_results' => $designs,
            'invoices' => $invoices
        ];
        
        return view('admin/design/detail', $data);
    }

    public function updateStatus($id)
    {
        $this->designModel->update($id, ['status' => $this->request->getPost('status')]);
        return redirect()->to('/admin/designrequests/show/' . $id)->with('success', 'Status proyek berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->db->table('project_surveys')->where('design_request_id', $id)->delete();
        $this->db->table('project_designs')->where('design_request_id', $id)->delete();
        $this->db->table('project_invoices')->where('design_request_id', $id)->delete();
        $this->designModel->delete($id);
        return redirect()->to('/admin/designrequests')->with('success', 'Data proyek berhasil dihapus permanen');
    }

    // =========================================================================
    // FUNGSI INVOICE YANG SUDAH DIPERBAIKI TOTAL
    // =========================================================================
        // =========================================================================    // FUNGSI INVOICE YANG DIKEMBALIKAN KE BENTUK YANG BENAR (SESUAI ROUTE ANDA)
    // =========================================================================
        // =========================================================================
    // FUNGSI FINAL YANG SEHARUSNYA DARI AWAL (MENGIKUTI LOGIKA ANDA)
    // =========================================================================
    public function addInvoice($id)
    {
        // Validasi ID dari URL, ini sudah benar
        if (empty($id)) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID Proyek tidak ditemukan.');
        }

        // Simpan data tagihan baru ke database.
        // Kolom 'midtrans_order_id' dan 'snap_token' sengaja tidak diisi.
        $this->db->table('project_invoices')->insert([
            'design_request_id' => $id,
            'description'       => $this->request->getPost('description'),
            'amount'            => $this->request->getPost('amount'),
            'due_date'          => $this->request->getPost('due_date'),
            'status'            => 'UNPAID', // Status awal selalu UNPAID
            'created_at'        => date('Y-m-d H:i:s')
        ]);
        
        // Setelah berhasil menyimpan, langsung redirect ke halaman detail
        // dengan pesan sukses. Gunakan URL '/admin/design/show/' yang sudah benar.
        return redirect()->to('/admin/design/show/' . $id)
                         ->with('success', 'Tagihan berhasil dibuat!');
    }


    
    // ... Sisa fungsi Anda seperti addSurvey, deleteInvoice, dll ...
}
