<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\RenovationService;
use RuntimeException;

class Renovation extends BaseController
{
    protected RenovationService $svc;

    public function __construct()
    {
        $this->svc = new RenovationService();
    }

    public function index()
    {
        if (!can('renovation')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat daftar renovasi.');
        }
        return view('admin/renovation/index', ['title' => 'Daftar Proyek Renovasi', 'requests' => $this->svc->getAllRequests()]);
    }

    public function detail($id)
    {
        if (!can('renovation_detail')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk melihat detail renovasi.');
        }
        try {
            $data = $this->svc->findRenovationWithDetails((int)$id);
        } catch (RuntimeException $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException($e->getMessage());
        }
        return view('admin/renovation/detail', array_merge($data, ['title' => 'Detail Renovasi']));
    }

    // -------------------------------------------------------------------------
    // RAB
    // -------------------------------------------------------------------------
    public function save_rab_row()
    {
        if (!can('renovation_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }

        if (!$this->validateData($this->request->getPost(), 'renovationRabSave')) {
            return $this->response->setJSON(['status' => false, 'message' => implode(' ', $this->validator->getErrors())]);
        }

        try {
            $result = $this->svc->saveRabRow($this->request->getPost());
            return $this->response->setJSON(['status' => true, 'id' => $result['id'], 'message' => 'Data RAB berhasil disimpan']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function lock_rab($renovationId)
    {
        if (!can('renovation_rab')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk mengunci RAB.');
        }
        $this->svc->lockRab((int)$renovationId);
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#rab'))->with('success', 'RAB Berhasil Dikunci kawan!');
    }

    public function unlock_rab($renovationId)
    {
        if (!can('renovation_rab')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk membuka kunci RAB.');
        }
        $this->svc->unlockRab((int)$renovationId);
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#rab'))->with('success', 'Kunci RAB dibuka kawan!');
    }

    public function delete_rab_row($id)
    {
        if (!can('renovation_rab')) {
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
        if (!can('renovation_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        return $this->response->setJSON($this->svc->getRabMaterials((int)$rabId));
    }

    public function add_rab_material()
    {
        if (!can('renovation_rab')) {
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
        if (!can('renovation_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        $this->svc->deleteRabMaterial((int)$id);
        return $this->response->setJSON(['status' => true]);
    }

    public function get_renovation_rab_api($renovation_id)
    {
        return $this->response->setJSON($this->svc->getRabApiData((int)$renovation_id));
    }

    // -------------------------------------------------------------------------
    // TARGET
    // -------------------------------------------------------------------------
    public function add_target()
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menambahkan target.');
        }
        if (!$this->validateData($this->request->getPost(), 'renovationTargetSave')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $post = $this->request->getPost();
            $this->svc->addTarget($post);
            return redirect()->to(base_url('admin/renovation/detail/' . $post['renovation_id'] . '#target'))->with('success', 'Target proyek berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan target proyek. Silakan coba lagi.');
        }
    }

    public function update_target_status($id, $status)
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui target.');
        }
        $this->svc->updateTargetStatus((int)$id, $status);
        return redirect()->back()->with('success', 'Status target berhasil diperbarui!');
    }

    public function view_target($id)
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk melihat target.');
        }
        return view('admin/renovation/target', $this->svc->getTargetView((int)$id));
    }

    public function createTarget($id_renovation)
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk membuat target.');
        }
        $msg = $this->svc->createOrUpdateTarget((int)$id_renovation, $this->request->getPost());
        return redirect()->to(base_url('admin/renovation/detail/' . $id_renovation . '#target'))->with('success', $msg);
    }

    public function update_schedule()
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui jadwal.');
        }
        if (!$this->validateData($this->request->getPost(), 'renovationScheduleUpdate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post = $this->request->getPost();
        $this->svc->updateSchedule((int)$post['renovation_id'], $post);
        return redirect()->to(base_url('admin/renovation/detail/' . $post['renovation_id'] . '#target'))->with('success', 'Jadwal proyek berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // STATUS
    // -------------------------------------------------------------------------
    public function update_status()
    {
        if (!can('renovation_detail')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui status.');
        }
        $id = $this->request->getPost('id');
        $this->svc->updateStatus((int)$id, $this->request->getPost('status'));
        return redirect()->to(base_url('admin/renovation/detail/' . $id))->with('success', 'Status proyek berhasil diperbarui');
    }

    // -------------------------------------------------------------------------
    // INVOICE
    // -------------------------------------------------------------------------
    public function create_invoice()
    {
        if (!can('renovation_pembayaran')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menambahkan invoice.');
        }

        if (!$this->validateData($this->request->getPost(), 'renovationInvoiceCreate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->svc->createInvoice($this->request->getPost());
            return redirect()->to('admin/renovation/detail/' . $this->request->getPost('renovation_id') . '#payment')->with('success', 'Tagihan dibuat.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_invoice($id, $renovationId)
    {
        if (!can('renovation_pembayaran')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menghapus invoice.');
        }
        $this->svc->deleteInvoice((int)$id);
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#payment'))->with('success', 'Tagihan dihapus.');
    }

    // -------------------------------------------------------------------------
    // SURVEY
    // -------------------------------------------------------------------------
    public function add_survey($requestId)
    {
        if (!can('renovation_survey')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menambahkan survey.');
        }
        $dataToValidate = $this->request->getPost();
        $dataToValidate['file_url'] = $this->request->getFile('file_url');

        if (!$this->validateData($dataToValidate, 'renovationSurveyAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->svc->addSurvey((int)$requestId, $this->request->getPost(), $this->request->getFile('file_url'));
        return redirect()->to('/admin/renovation/detail/' . $requestId)->with('success', 'Laporan survey ditambahkan.');
    }

    public function delete_survey($id, $renovationId)
    {
        if (!can('renovation_survey')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menghapus survey.');
        }
        $this->svc->deleteSurvey((int)$id);
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#survey'))->with('success', 'Survey dihapus.');
    }

    // -------------------------------------------------------------------------
    // DESIGN
    // -------------------------------------------------------------------------
    public function add_design($requestId)
    {
        if (!can('renovation_desain')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menambahkan desain.');
        }
        $dataToValidate = $this->request->getPost();
        $dataToValidate['file_url'] = $this->request->getFile('file_url');

        if (!$this->validateData($dataToValidate, 'renovationDesignAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $added = $this->svc->addDesign((int)$requestId, $this->request->getPost(), $this->request->getFile('file_url'));
        if ($added) {
            session()->setFlashdata('success', 'Desain ditambahkan.');
        }
        return redirect()->to('/admin/renovation/detail/' . $requestId);
    }

    public function delete_design($id, $renovationId)
    {
        if (!can('renovation_desain')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menghapus desain.');
        }
        $this->svc->deleteDesign((int)$id);
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#design'))->with('success', 'Desain dihapus.');
    }

    // -------------------------------------------------------------------------
    // PROGRESS
    // -------------------------------------------------------------------------
    public function add_progress($renovationId)
    {
        if (!can('renovation_progress')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menambahkan progress.');
        }
        $dataToValidate = $this->request->getPost();
        $dataToValidate['photo_url'] = $this->request->getFile('photo_url');

        if (!$this->validateData($dataToValidate, 'renovationProgressAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->svc->addProgress((int)$renovationId, $this->request->getPost(), $this->request->getFile('photo_url'));
        return redirect()->to('/admin/renovation/detail/' . $renovationId . '#progress')->with('success', 'Progress ditambahkan.');
    }

    public function update_progress_status($id, $status)
    {
        if (!can('renovation_progress')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui progress.');
        }
        try {
            $renovationId = $this->svc->updateProgressStatus((int)$id, $status);
            return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#progress'))->with('success', 'Status laporan progress berhasil diperbarui!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // PELAMAR & JOB INFO
    // -------------------------------------------------------------------------
    public function update_applicant_status()
    {
        if (!can('renovation_lowongan')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui status pelamar.');
        }
        $this->svc->updateApplicantStatus((int)$this->request->getPost('id'), $this->request->getPost('status'));
        return redirect()->back()->with('success', 'Status pelamar renovasi diperbarui!');
    }

    public function update_job_info()
    {
        if (!can('renovation_lowongan')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui lowongan.');
        }
        $post = $this->request->getPost();
        $this->svc->updateJobInfo($post);
        return redirect()->to(base_url('admin/renovation/detail/' . $post['id'] . '#info-pekerjaan'))->with('success', 'Info Pekerjaan & Lokasi berhasil disinkronkan!');
    }
}
