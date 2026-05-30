<?php

namespace App\Modules\Design\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Design\Services\DesignRequestService;
use App\Modules\Notifications\Services\NotificationService;
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
 * Semua itu ada di App\Modules\Design\Services\DesignRequestService.
 */
class DesignRequests extends BaseController
{
    protected DesignRequestService $designService;
    protected NotificationService $notifService;
    protected $validation;

    public function __construct()
    {
        $this->designService = new DesignRequestService();
        $this->notifService = new NotificationService();
        $this->validation = \Config\Services::validation();
    }

    // -------------------------------------------------------------------------
    // 1. LIST PERMOHONAN DESAIN
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('design')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat desain.');
        }

        // Cek apakah user yang login adalah desainer
        $isAdminDesigner = in_array(strtolower(session()->get('role') ?? ''), ['kepala divisi desain', 'drafter', 'arsitek']);
        $adminId = $isAdminDesigner ? (int) session()->get('user_id') : null;

        return view('App\Modules\Design\Views\index', [
            'title' => 'Permohonan Desain',
            'requests' => $this->designService->getAllRequests(),
            'workStats' => $this->designService->getDesignerWorkStats($adminId),
            'designerTasks' => $this->designService->getDesignerTasks($adminId),
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

        return view('App\Modules\Design\Views\detail', array_merge($details, [
            'title' => 'Detail Proyek',
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

        if (!$this->validate('designProgressUpdate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->designService->updateProgress((int) $id, $this->request->getPost());

            log_admin_activity('update', 'Design Requests', 'Update Progress Proyek ' . $id);
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

        $status = $this->request->getPost('status');

        // Ambil data request untuk mendapatkan user_id
        $details = $this->designService->findRequestWithDetails((int) $id);
        $requestData = $details['request'];

        // Update status di database
        $this->designService->updateStatus((int) $id, $status);

        // Kirim Notifikasi via NotificationService
        if (!empty($requestData['user_id'])) {
            $title = "Status Desain: " . strtoupper($status);
            $concept = !empty($requestData['design_concept']) ? $requestData['design_concept'] : 'Anda';
            $message = "Status proyek permohonan desain {$concept} telah diperbarui menjadi " . strtoupper($status) . ".";

            $this->notifService->sendPersonal('client', (int) $requestData['user_id'], $title, $message);
        }

        log_admin_activity('update_status', 'Design Requests', 'Update Status Proyek ' . $id);

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
        log_admin_activity('delete', 'Design Requests', 'Hapus Proyek ' . $id);
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

        if (!$this->validate('designSurveyAdd')) {
            return redirect()->to('/admin/design/show/' . $id)->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->designService->addSurvey(
                (int) $id,
                $this->request->getPost(),
                $this->request->getFile('survey_file')
            );

            // Kirim notifikasi ke klien
            $details = $this->designService->findDetailOrFail((int) $id);
            if (!empty($details['request']['user_id'])) {
                $title = "Laporan Survey Desain Baru";
                $message = "Laporan survey untuk proyek permohonan desain anda telah ditambahkan. Silakan cek detail proyek.";
                $this->notifService->sendPersonal('client', (int) $details['request']['user_id'], $title, $message);
            }

            log_admin_activity('create', 'Design Requests', 'Tambah Survey Proyek ' . $id);
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

            log_admin_activity('delete', 'Design Requests', 'Hapus Survey Proyek ' . $id);
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

        if (!$this->validate('designResultAdd')) {
            return redirect()->to('/admin/design/show/' . $id)->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $nextRev = $this->designService->addDesignResult(
                (int) $id,
                $this->request->getPost(),
                $this->request->getFile('design_file')
            );

            // Kirim notifikasi ke klien
            $details = $this->designService->findDetailOrFail((int) $id);
            if (!empty($details['request']['user_id'])) {
                $title = "Hasil Desain Baru (Rev. " . $nextRev . ")";
                $message = "Hasil desain terbaru untuk proyek permohonan desain anda telah ditambahkan. Silakan cek detail proyek.";
                $this->notifService->sendPersonal('client', (int) $details['request']['user_id'], $title, $message);
            }

            log_admin_activity('create', 'Design Requests', 'Tambah Hasil Desain Proyek ' . $id);
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
            log_admin_activity('delete', 'Design Requests', 'Hapus File Desain Proyek ' . $id);
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

            // Kirim notifikasi ke klien
            $details = $this->designService->findDetailOrFail((int) $result['design_request_id']);
            if (!empty($details['request']['user_id'])) {
                $title = "Desain Disetujui (Rev. " . $result['revision_number'] . ")";
                $message = "Hasil desain untuk proyek permohonan desain Anda telah disetujui. Silakan cek detail proyek.";
                $this->notifService->sendPersonal('client', (int) $details['request']['user_id'], $title, $message);
            }

            log_admin_activity('update', 'Design Requests', 'Approve Desain Proyek ' . $id);
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

            // Kirim notifikasi ke klien
            $details = $this->designService->findDetailOrFail((int) $result['design_request_id']);
            if (!empty($details['request']['user_id'])) {
                $title = "Revisi Desain (Rev. " . $result['revision_number'] . ")";
                $message = "Hasil desain untuk proyek permohonan desain anda membutuhkan revisi. Silakan cek catatan revisi pada detail proyek.";
                $this->notifService->sendPersonal('client', (int) $details['request']['user_id'], $title, $message);
            }

            log_admin_activity('update', 'Design Requests', 'Reject Desain Proyek ' . $id);
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

        if (!$this->validate('designTargetCreate')) {
            return redirect()->to('/admin/design/show/' . $id . '#progress')->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        log_admin_activity('create', 'Design Requests', 'Tambah Target Proyek ' . $id);
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
            log_admin_activity('delete', 'Design Requests', 'Hapus Target Proyek ' . $designId);
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
            log_admin_activity('update', 'Design Requests', 'Update Progress Target Proyek ' . $designRequestId);
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

        if (!$this->validate('designInvoiceAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->designService->addInvoice((int) $id, $this->request->getPost());

        // Kirim notifikasi ke klien
        $details = $this->designService->findDetailOrFail((int) $id);
        if (!empty($details['request']['user_id'])) {
            $title = "Tagihan Baru untuk Desain";
            $message = "tagihan baru telah dibuat untuk proyek permohonan desain Anda. Silakan cek menu pembayaran pada detail proyek.";
            $this->notifService->sendPersonal('client', (int) $details['request']['user_id'], $title, $message);
        }

        log_admin_activity('create', 'Design Requests', 'Tambah Tagihan Proyek ' . $id);
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
            log_admin_activity('delete', 'Design Requests', 'Hapus Tagihan Proyek ' . $designRequestId);
            return redirect()->to('/admin/design/show/' . $designRequestId)->with('success', 'Tagihan berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
