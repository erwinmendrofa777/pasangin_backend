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
            'invoices' => $invoices,
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/design/detail', $data);
    }

    /**
     * FUNGSI BARU: Update Target Selesai & Persentase Progress
     */
    public function updateProgress($id)
    {
        $data = [
            'target_date'      => $this->request->getPost('target_date'),
            'progress_percent' => $this->request->getPost('progress_percent'),
            'status'           => $this->request->getPost('status')
        ];

        if ($this->designModel->update($id, $data)) {
            return redirect()->to('/admin/design/show/' . $id)->with('success', 'Progress Proyek berhasil diperbarui!');
        }
        
        return redirect()->back()->with('error', 'Gagal memperbarui progress.');
    }

    public function addSurvey($id)
    {
        $rules = [
            'title' => 'required',
            'survey_file'  => [
                'rules' => 'uploaded[survey_file]|max_size[survey_file,5120]|ext_in[survey_file,png,jpg,jpeg,pdf]',
                'errors' => [ 'uploaded' => 'Anda harus memilih file.' ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/design/show/' . $id)->withInput();
        }

        $file = $this->request->getFile('survey_file');
        $fileName = $file->getRandomName();
        $file->move('uploads/survey', $fileName);

        $this->db->table('project_surveys')->insert([
            'design_request_id' => $id,
            'title'             => $this->request->getPost('title'),
            'note'              => $this->request->getPost('note'),
            'file'              => $fileName,
            'created_at'        => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/design/show/' . $id)->with('success', 'Laporan survey berhasil ditambahkan!');
    }

    public function addDesignResult($id)
    {
        $rules = [
            'design_name' => 'required',
            'design_file' => [
                'rules' => 'uploaded[design_file]|max_size[design_file,5120]|ext_in[design_file,png,jpg,jpeg,pdf]',
                'errors' => [ 'uploaded' => 'Anda harus memilih file desain.' ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/design/show/' . $id)->withInput();
        }

        $file = $this->request->getFile('design_file');
        $fileName = $file->getRandomName();
        $file->move('uploads/design_results', $fileName); 

        $this->db->table('project_designs')->insert([
            'design_request_id' => $id,
            'design_name'       => $this->request->getPost('design_name'),
            'file'              => $fileName,
            'created_at'        => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/design/show/' . $id)->with('success', 'Hasil desain berhasil diupload!');
    }

    public function updateStatus($id)
    {
        $this->designModel->update($id, ['status' => $this->request->getPost('status')]);
        return redirect()->to('/admin/design/show/' . $id)->with('success', 'Status proyek berhasil diperbarui!');
    }
    
    public function delete($id)
    {
        $this->db->table('project_surveys')->where('design_request_id', $id)->delete();
        $this->db->table('project_designs')->where('design_request_id', $id)->delete();
        $this->db->table('project_invoices')->where('design_request_id', $id)->delete();
        $this->designModel->delete($id);
        return redirect()->to('/admin/designrequests')->with('success', 'Data proyek berhasil dihapus permanen');
    }

    public function addInvoice($id)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID Proyek tidak ditemukan.');
        }

        $this->db->table('project_invoices')->insert([
            'design_request_id' => $id,
            'description'       => $this->request->getPost('description'),
            'amount'            => $this->request->getPost('amount'),
            'due_date'          => $this->request->getPost('due_date'),
            'status'            => 'UNPAID',
            'created_at'        => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->to('/admin/design/show/' . $id)
                         ->with('success', 'Tagihan berhasil dibuat!');
    }

    public function deleteSurvey($id)
    {
        $survey = $this->db->table('project_surveys')->where('id', $id)->get()->getRowArray();
        if ($survey) {
            $designRequestId = $survey['design_request_id'];
            if (!empty($survey['file']) && file_exists('uploads/survey/' . $survey['file'])) {
                unlink('uploads/survey/' . $survey['file']);
            }
            $this->db->table('project_surveys')->where('id', $id)->delete();
            return redirect()->to('/admin/design/show/' . $designRequestId)->with('success', 'Data survey berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Data survey tidak ditemukan.');
    }

    public function deleteInvoice($id)
    {
        $invoice = $this->db->table('project_invoices')->where('id', $id)->get()->getRowArray();
        if ($invoice) {
            $designRequestId = $invoice['design_request_id'];
            $this->db->table('project_invoices')->where('id', $id)->delete();
            return redirect()->to('/admin/design/show/' . $designRequestId)->with('success', 'Tagihan berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Data tagihan tidak ditemukan.');
    }
}