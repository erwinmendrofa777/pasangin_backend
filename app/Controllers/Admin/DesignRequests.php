<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\DesignRequestService;
use RuntimeException;

/**
 * DesignRequests Controller — Admin
 *
 * Berperan sebagai "polisi lalu lintas":
 *   1. Terima request dari user
 *   2. Cek permission
 *   3. Validasi input dasar (HTTP layer)
 *   4. Delegasikan ke DesignRequestService untuk logika bisnis
 *   5. Kembalikan response (redirect / view)
 *
 * TIDAK ADA raw query, file handling, atau logika bisnis di sini.
 * Semua itu ada di App\Services\DesignRequestService.
 */
class DesignRequests extends BaseController
{
    protected DesignRequestService $designService;

    public function __construct()
    {
        $this->designService = new DesignRequestService();
    }

    // -------------------------------------------------------------------------
    // 1. LIST PERMOHONAN DESAIN
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('design')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat desain.');
        }

        return view('admin/design/index', [
            'title'    => 'Permohonan Desain',
            'requests' => $this->designService->getAllRequests(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 2. DETAIL PROYEK
    // -------------------------------------------------------------------------
    public function show($id)
    {
        if (!can('design_detail')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat desain.');
        }

        try {
            $details = $this->designService->findRequestWithDetails((int) $id);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/designrequests')->with('error', $e->getMessage());
        }

        return view('admin/design/detail', array_merge($details, [
            'title'      => 'Detail Proyek',
            'validation' => \Config\Services::validation(),
        ]));
    }

    // -------------------------------------------------------------------------
    // 3. UPDATE PROGRESS / JADWAL
    // -------------------------------------------------------------------------
    public function updateProgress($id)
    {
        if (!can('design_progress')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengubah progress.');
        }

        try {
            $this->designService->updateProgress((int) $id, $this->request->getPost());
            return redirect()->to('/admin/design/show/' . $id)->with('success', 'Jadwal/Progress Proyek berhasil diperbarui!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 4. UPDATE STATUS PROYEK
    // -------------------------------------------------------------------------
    public function updateStatus($id)
    {
        if (!can('design_detail')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk mengubah status.');
        }

        $this->designService->updateStatus((int) $id, $this->request->getPost('status'));
        return redirect()->to('/admin/design/show/' . $id)->with('success', 'Status proyek berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // 5. HAPUS PROYEK (CASCADE)
    // -------------------------------------------------------------------------
    public function delete($id)
    {
        if (!can('design_delete')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk menghapus desain.');
        }

        $this->designService->deleteRequest((int) $id);
        return redirect()->to('/admin/designrequests')->with('success', 'Data proyek berhasil dihapus permanen.');
    }

    // -------------------------------------------------------------------------
    // 6. TAMBAH SURVEY
    // -------------------------------------------------------------------------
    public function addSurvey($id)
    {
        if (!can('design_survey')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk menambah survey.');
        }

        $dataToValidate = $this->request->getPost();
        $dataToValidate['survey_file'] = $this->request->getFile('survey_file');

        if (!$this->validateData($dataToValidate, 'designSurveyAdd')) {
            return redirect()->to('/admin/design/show/' . $id)->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->designService->addSurvey(
                (int) $id,
                $this->request->getPost(),
                $this->request->getFile('survey_file')
            );
            return redirect()->to('/admin/design/show/' . $id)->with('success', 'Laporan survey berhasil ditambahkan!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 7. HAPUS SURVEY
    // -------------------------------------------------------------------------
    public function deleteSurvey($id)
    {
        if (!can('design_survey')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk menghapus survey.');
        }

        try {
            $designRequestId = $this->designService->deleteSurvey((int) $id);
            return redirect()->to('/admin/design/show/' . $designRequestId)->with('success', 'Data survey berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 8. UPLOAD HASIL DESAIN
    // -------------------------------------------------------------------------
    public function addDesignResult($id)
    {
        if (!can('design_desain')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk menambah hasil desain.');
        }

        $dataToValidate = $this->request->getPost();
        $dataToValidate['design_file'] = $this->request->getFile('design_file');

        if (!$this->validateData($dataToValidate, 'designResultAdd')) {
            return redirect()->to('/admin/design/show/' . $id)->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $nextRev = $this->designService->addDesignResult(
                (int) $id,
                $this->request->getPost(),
                $this->request->getFile('design_file')
            );
            return redirect()->to('/admin/design/show/' . $id)->with('success', 'Hasil desain berhasil diupload (Rev. ' . $nextRev . ')!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 9. HAPUS HASIL DESAIN
    // -------------------------------------------------------------------------
    public function deleteDesign($id)
    {
        if (!can('design_desain')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk menghapus desain.');
        }

        try {
            $designRequestId = $this->designService->deleteDesignResult((int) $id);
            return redirect()->to('/admin/design/show/' . $designRequestId)->with('success', 'File desain berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 10. APPROVE DESAIN
    // -------------------------------------------------------------------------
    public function approveDesign($id)
    {
        if (!can('design_desain')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk mengubah desain.');
        }

        try {
            $result = $this->designService->approveDesign((int) $id);
            return redirect()->to('/admin/design/show/' . $result['design_request_id'] . '#progress')
                ->with('success', 'Revisi Rev. ' . $result['revision_number'] . ' berhasil di-approve!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 11. REJECT DESAIN
    // -------------------------------------------------------------------------
    public function rejectDesign($id)
    {
        if (!can('design_desain')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk mengubah desain.');
        }

        try {
            $result = $this->designService->rejectDesign((int) $id, $this->request->getPost('revision_note') ?? '');
            return redirect()->to('/admin/design/show/' . $result['design_request_id'] . '#progress')
                ->with('success', 'Revisi Rev. ' . $result['revision_number'] . ' berhasil di-reject.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 12. TAMBAH TARGET
    // -------------------------------------------------------------------------
    public function createTarget($id)
    {
        if (!can('design_target')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk menambah target.');
        }

        if (!$this->validateData($this->request->getPost(), 'designTargetCreate')) {
            return redirect()->to('/admin/design/show/' . $id . '#progress')->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->designService->createTarget((int) $id, $this->request->getPost());
        return redirect()->to('/admin/design/show/' . $id . '#progress')->with('success', 'Target berhasil ditambahkan!');
    }

    // -------------------------------------------------------------------------
    // 13. HAPUS TARGET
    // -------------------------------------------------------------------------
    public function deleteTarget($targetId, $designId)
    {
        if (!can('design_target')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk menghapus target.');
        }

        try {
            $this->designService->deleteTarget((int) $targetId, (int) $designId);
            return redirect()->to('/admin/design/show/' . $designId . '#progress')->with('success', 'Target berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 14. UPDATE PROGRESS TARGET
    // -------------------------------------------------------------------------
    public function updateTargetProgress($targetId)
    {
        if (!can('design_progress')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk mengubah progress target.');
        }

        try {
            $designRequestId = $this->designService->updateTargetProgress(
                (int) $targetId,
                $this->request->getPost()
            );
            return redirect()->to('/admin/design/show/' . $designRequestId . '#progress')
                ->with('success', 'Progress target berhasil diupdate!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 15. TAMBAH INVOICE
    // -------------------------------------------------------------------------
    public function addInvoice($id)
    {
        if (!can('design_pembayaran')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk menambah pembayaran.');
        }

        if (empty($id)) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ID Proyek tidak ditemukan.');
        }

        if (!$this->validateData($this->request->getPost(), 'designInvoiceAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->designService->addInvoice((int) $id, $this->request->getPost());
        return redirect()->to('/admin/design/show/' . $id)->with('success', 'Tagihan berhasil dibuat!');
    }

    // -------------------------------------------------------------------------
    // 16. HAPUS INVOICE
    // -------------------------------------------------------------------------
    public function deleteInvoice($id)
    {
        if (!can('design_pembayaran')) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk menghapus tagihan.');
        }

        try {
            $designRequestId = $this->designService->deleteInvoice((int) $id);
            return redirect()->to('/admin/design/show/' . $designRequestId)->with('success', 'Tagihan berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
