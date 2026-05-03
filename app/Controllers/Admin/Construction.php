<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\ConstructionService;
use RuntimeException;

class Construction extends BaseController
{
    protected ConstructionService $svc;

    public function __construct()
    {
        $this->svc = new ConstructionService();
    }

    public function index()
    {
        if (!can('construction')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengakses halaman ini.');
        }
        $result = $this->svc->getAllProjectsWithStats();
        return view('admin/construction/index', array_merge($result, ['title' => 'Daftar Konstruksi']));
    }

    public function detail($id)
    {
        if (!can('construction_detail')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengakses halaman ini.');
        }
        try {
            $data = $this->svc->findConstructionWithDetails((int)$id);
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('admin/construction'))->with('error', $e->getMessage());
        }
        return view('admin/construction/detail', array_merge($data, ['title' => 'Detail Konstruksi']));
    }

    // -------------------------------------------------------------------------
    // RAB
    // -------------------------------------------------------------------------
    public function save_rab_row()
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }

        if (!$this->validateData($this->request->getPost(), 'constructionRabSave')) {
            return $this->response->setJSON(['status' => false, 'message' => implode(' ', $this->validator->getErrors())]);
        }

        try {
            $result = $this->svc->saveRabRow($this->request->getPost());
            return $this->response->setJSON(['status' => true, 'id' => $result['id'], 'message' => 'Data RAB berhasil disimpan']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function lock_rab($constructionId)
    {
        if (!can('construction_rab')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah RAB.');
        }
        $this->svc->lockRab((int)$constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#rab'))->with('success', 'RAB Berhasil Dikunci kawan!');
    }

    public function unlock_rab($constructionId)
    {
        if (!can('construction_rab')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah RAB.');
        }
        $this->svc->unlockRab((int)$constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#rab'))->with('success', 'Kunci RAB dibuka kawan!');
    }

    public function delete_rab_row($id)
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->deleteRabRow((int)$id);
            return $this->response->setJSON(['status' => true]);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function get_rab_materials($rabId)
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        return $this->response->setJSON($this->svc->getRabMaterials((int)$rabId));
    }

    public function add_rab_material()
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->addRabMaterial((int)$this->request->getPost('rab_id'), (int)$this->request->getPost('product_id'));
            return $this->response->setJSON(['status' => true, 'message' => 'Material ditambahkan.']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete_rab_material($id)
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        $this->svc->deleteRabMaterial((int)$id);
        return $this->response->setJSON(['status' => true]);
    }

    public function get_construction_rab_api($construction_id)
    {
        return $this->response->setJSON($this->svc->getRabApiData((int)$construction_id));
    }

    // -------------------------------------------------------------------------
    // ADDENDUM
    // -------------------------------------------------------------------------
    public function save_addendum_row()
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $result = $this->svc->saveAddendumRow($this->request->getPost());
            return $this->response->setJSON(['status' => true, 'id' => $result['id'], 'message' => 'Data Addendum berhasil disimpan']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function lock_addendum($constructionId)
    {
        if (!can('construction_addendum')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah addendum.');
        }
        $this->svc->lockAddendum((int)$constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#addendum'))->with('success', 'Addendum Berhasil Dikunci kawan!');
    }

    public function unlock_addendum($constructionId)
    {
        if (!can('construction_addendum')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah addendum.');
        }
        $this->svc->unlockAddendum((int)$constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#addendum'))->with('success', 'Kunci Addendum dibuka kawan!');
    }

    public function delete_addendum_row($id)
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->deleteAddendumRow((int)$id);
            return $this->response->setJSON(['status' => true]);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function get_addendum_materials($addendumId)
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        return $this->response->setJSON($this->svc->getAddendumMaterials((int)$addendumId));
    }

    public function add_addendum_material()
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->addAddendumMaterial((int)$this->request->getPost('addendum_id'), (int)$this->request->getPost('product_id'));
            return $this->response->setJSON(['status' => true, 'message' => 'Material ditambahkan.']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete_addendum_material($id)
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        $this->svc->deleteAddendumMaterial((int)$id);
        return $this->response->setJSON(['status' => true]);
    }

    // -------------------------------------------------------------------------
    // TARGET
    // -------------------------------------------------------------------------
    public function add_target()
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah target.');
        }

        if (!$this->validateData($this->request->getPost(), 'constructionTargetSave')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post = $this->request->getPost();
        $this->svc->addTarget($post);
        return redirect()->to(base_url('admin/construction/detail/' . $post['construction_id'] . '#target'))->with('success', 'Target proyek berhasil ditambahkan!');
    }

    public function update_target_status($id, $status)
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah target.');
        }
        $this->svc->updateTargetStatus((int)$id, $status);
        return redirect()->back()->with('success', 'Status target berhasil diperbarui!');
    }

    public function delete_target($id, $constructionId)
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah target.');
        }
        $this->svc->deleteTarget((int)$id);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#target'))->with('success', 'Target proyek dihapus.');
    }

    public function view_target($id)
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk melihat target.');
        }
        return view('admin/construction/target', $this->svc->getTargetView((int)$id));
    }

    public function createTarget($id_project)
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk membuat target.');
        }
        $msg = $this->svc->createOrUpdateTarget((int)$id_project, $this->request->getPost());
        return redirect()->to(base_url('admin/construction/detail/' . $id_project . '#target'))->with('success', $msg);
    }

    public function update_schedule()
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah jadwal.');
        }

        if (!$this->validateData($this->request->getPost(), 'constructionScheduleUpdate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post = $this->request->getPost();
        $this->svc->updateSchedule((int)$post['construction_id'], $post);
        return redirect()->to(base_url('admin/construction/detail/' . $post['construction_id'] . '#target'))->with('success', 'Jadwal proyek berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // STATUS
    // -------------------------------------------------------------------------
    public function updateStatus()
    {
        if (!can('construction')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengubah status.');
        }
        $id = $this->request->getPost('id');
        $this->svc->updateStatus((int)$id, $this->request->getPost('status'));
        return redirect()->to(base_url('admin/construction/detail/' . $id))->with('success', 'Status diperbarui');
    }

    // -------------------------------------------------------------------------
    // INVOICE
    // -------------------------------------------------------------------------
    public function create_invoice()
    {
        if (!can('construction_pembayaran')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk membuat invoice.');
        }

        if (!$this->validateData($this->request->getPost(), 'constructionInvoiceCreate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->svc->createInvoice($this->request->getPost());
            return redirect()->to(base_url('admin/construction/detail/' . $this->request->getPost('construction_id') . '#payment'))->with('success', 'Tagihan dibuat!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_invoice($id, $constructionId)
    {
        if (!can('construction_pembayaran')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus invoice.');
        }
        $this->svc->deleteInvoice((int)$id);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#payment'))->with('success', 'Tagihan dihapus.');
    }

    // -------------------------------------------------------------------------
    // SURVEY
    // -------------------------------------------------------------------------
    public function uploadSurvey()
    {
        if (!can('construction_survey')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengunggah survey.');
        }

        $dataToValidate = $this->request->getPost();
        $dataToValidate['survey_file'] = $this->request->getFile('survey_file');

        if (!$this->validateData($dataToValidate, 'constructionSurveyUpload')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $constructionId = $this->request->getPost('id');
        $this->svc->uploadSurvey((int)$constructionId, $this->request->getPost(), $this->request->getFile('survey_file'));
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#survey'))->with('success', 'Survey ditambahkan!');
    }

    public function deleteSurvey($id, $constructionId)
    {
        if (!can('construction_survey')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus survey.');
        }
        $this->svc->deleteSurvey((int)$id);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#survey'))->with('success', 'Survey dihapus.');
    }

    // -------------------------------------------------------------------------
    // DESIGN
    // -------------------------------------------------------------------------
    public function uploadDesign()
    {
        if (!can('construction_desain')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengunggah desain.');
        }

        $dataToValidate = $this->request->getPost();
        $dataToValidate['design_2d'] = $this->request->getFile('design_2d');

        if (!$this->validateData($dataToValidate, 'constructionDesignUpload')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $constructionId = $this->request->getPost('id');
        try {
            $this->svc->uploadDesign((int)$constructionId, $this->request->getPost('design_title'), $this->request->getFile('design_2d'));
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#desain'))->with('success', 'Desain ditambahkan!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteDesign($id, $constructionId)
    {
        if (!can('construction_desain')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus desain.');
        }
        $this->svc->deleteDesign((int)$id);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#desain'))->with('success', 'Desain dihapus.');
    }

    // -------------------------------------------------------------------------
    // PROGRESS
    // -------------------------------------------------------------------------
    public function addProgress()
    {
        if (!can('construction_progress')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menambahkan progres.');
        }

        $dataToValidate = $this->request->getPost();
        $dataToValidate['photo'] = $this->request->getFile('photo');

        if (!$this->validateData($dataToValidate, 'constructionProgressAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $constructionId = $this->request->getPost('construction_id');
        $this->svc->addProgress($this->request->getPost(), $this->request->getFile('photo'));
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))->with('success', 'Progress ditambahkan!');
    }

    public function deleteProgress($id, $constructionId)
    {
        if (!can('construction_progress')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus progres.');
        }
        $this->svc->deleteProgress((int)$id);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))->with('success', 'Progress dihapus.');
    }

    public function update_progress_status($id, $status)
    {
        if (!can('construction_progress')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah status progres.');
        }
        try {
            $constructionId = $this->svc->updateProgressStatus((int)$id, $status);
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))->with('success', 'Status laporan progress berhasil diperbarui!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // PELAMAR & JOB INFO
    // -------------------------------------------------------------------------
    public function update_applicant_status()
    {
        if (!can('construction_pelamar')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah pelamar.');
        }
        $this->svc->updateApplicantStatus((int)$this->request->getPost('id'), $this->request->getPost('status'));
        return redirect()->back()->with('success', 'Status pelamar berhasil diperbarui!');
    }

    public function update_job_info()
    {
        if (!can('construction_lowongan')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah lowongan.');
        }
        $this->svc->updateJobInfo($this->request->getPost());
        return redirect()->to(base_url('admin/construction/detail/' . $this->request->getPost('id') . '#info-pekerjaan'))->with('success', 'Info Pekerjaan & Lokasi disinkronkan!');
    }
}
