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

        return view('App\Modules\Design\Views\index', [
            'title' => 'Permohonan Desain',
            'requests' => $this->designService->getAllRequests(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 1b. EXPORT LAPORAN PDF
    // -------------------------------------------------------------------------
    public function exportPdf()
    {
        if (!can('design')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengeksport desain.');
        }

        helper(['terbilang', 'url']);

        $db = \Config\Database::connect();
        $requests = $db->table('design_requests dr')
            ->select('dr.*, 
                COALESCE(
                    (SELECT SUM(pi.amount - COALESCE(v.discount_nominal, 0)) 
                     FROM project_invoices pi 
                     LEFT JOIN vouchers v ON v.code = pi.voucher_code
                     WHERE pi.design_request_id = dr.id), 
                    0
                ) as total_invoice
            ')
            ->orderBy('dr.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'requests' => $requests,
            'title' => 'Laporan Proyek Desain',
            'tanggal_cetak' => date('Y-m-d')
        ];

        $html = view('App\Modules\Design\Views\export_pdf', $data);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        ob_end_clean();
        $dompdf->stream('Laporan_Proyek_Desain_' . date('Ymd_His') . '.pdf', ['Attachment' => 0]);
        exit();
    }

    // -------------------------------------------------------------------------
    // 2. DETAIL PROYEK
    // -------------------------------------------------------------------------
    public function show($id)
    {
        if (!can('design_detail') && !in_array(strtolower(session()->get('role') ?? ''), ['drafter', 'arsitek'])) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat desain.');
        }

        // Security check for drafter & arsitek
        $role = strtolower(session()->get('role') ?? '');
        if (in_array($role, ['drafter', 'arsitek'])) {
            $db = \Config\Database::connect();
            $hasTarget = $db->table('design_targets')
                ->where('design_request_id', $id)
                ->where('user_admin_id', session()->get('user_id'))
                ->countAllResults() > 0;
            if (!$hasTarget) {
                return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses ke proyek ini.');
            }
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
        if (!can('design_desain') && !in_array(strtolower(session()->get('role') ?? ''), ['drafter', 'arsitek'])) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk menambah hasil desain.');
        }

        if (!$this->validate('designResultAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        // Security check for drafter & arsitek
        $role = strtolower(session()->get('role') ?? '');
        if (in_array($role, ['drafter', 'arsitek'])) {
            $targetId = (int) $this->request->getPost('design_targets_id');
            $db = \Config\Database::connect();
            $target = $db->table('design_targets')->where('id', $targetId)->get()->getRowArray();
            if (!$target || $target['user_admin_id'] != session()->get('user_id')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menambah hasil desain pada tugas ini.');
            }
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
            
            $redirectTo = $this->request->getPost('redirect_to');
            if ($redirectTo === 'managerial') {
                if (in_array($role, ['drafter', 'arsitek'])) {
                    return redirect()->to('/admin/design/tugas')->with('success', 'Hasil desain berhasil diupload (Rev. ' . $nextRev . ')!');
                }
                return redirect()->to('/admin/design/managerial')->with('success', 'Hasil desain berhasil diupload (Rev. ' . $nextRev . ')!');
            }
            
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
        if (!can('design_desain') && !in_array(strtolower(session()->get('role') ?? ''), ['drafter', 'arsitek'])) {
            return redirect()->to('/admin/design')->with('error', 'Anda tidak memiliki akses untuk menghapus desain.');
        }

        // Security check for drafter & arsitek
        $role = strtolower(session()->get('role') ?? '');
        if (in_array($role, ['drafter', 'arsitek'])) {
            $db = \Config\Database::connect();
            $design = $db->table('project_designs')->where('id', $id)->get()->getRowArray();
            if (!$design || $design['user_admin_id'] != session()->get('user_id')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus file desain ini.');
            }
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
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses.']);
            }
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
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => true, 'message' => 'Revisi Rev. ' . $result['revision_number'] . ' berhasil di-approve!']);
            }
            return redirect()->to('/admin/design/show/' . $result['design_request_id'] . '#progress')
                ->with('success', 'Revisi Rev. ' . $result['revision_number'] . ' berhasil di-approve!');
        } catch (RuntimeException $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 11. REJECT DESAIN
    // -------------------------------------------------------------------------
    public function rejectDesign($id)
    {
        if (!can('design_desain')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses.']);
            }
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
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => true, 'message' => 'Revisi Rev. ' . $result['revision_number'] . ' berhasil di-reject.']);
            }
            return redirect()->to('/admin/design/show/' . $result['design_request_id'] . '#progress')
                ->with('success', 'Revisi Rev. ' . $result['revision_number'] . ' berhasil di-reject.');
        } catch (RuntimeException $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
            }
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
        return redirect()->to('/admin/design/show/' . $id . '#target')->with('success', 'Target berhasil ditambahkan!');
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
            return redirect()->to('/admin/design/show/' . $designId . '#target')->with('success', 'Target berhasil dihapus.');
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
            return redirect()->to('/admin/design/show/' . $designRequestId . '#target')
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

    public function managerial()
    {
        if (!can('design') || strtolower(session()->get('role') ?? '') !== 'kepala divisi desain') {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat managerial tugas.');
        }

        $userAdminRepo = new \App\Modules\Admin\Repositories\UserAdminRepository();
        $allDesigners = $userAdminRepo->findAllOrderedByIdDesc();
        $designers = array_filter($allDesigners, function($user) {
            $role = strtolower($user['role'] ?? '');
            return in_array($role, ['kepala divisi desain', 'arsitek', 'drafter']);
        });

        $db = \Config\Database::connect();
        $pendingDesigns = $db->table('project_designs')
            ->select('project_designs.*, ua.full_name as admin_name')
            ->join('user_admin ua', 'ua.id = project_designs.user_admin_id', 'left')
            ->where('project_designs.status', 'PENDING')
            ->orderBy('project_designs.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $pendingDesignsByTarget = [];
        foreach ($pendingDesigns as $pd) {
            $pendingDesignsByTarget[$pd['design_targets_id']][] = $pd;
        }

        return view('App\Modules\Design\Views\managerial', [
            'title' => 'Managerial Tugas',
            'designerTasks' => $this->designService->getDesignerTasksForKanban(null),
            'designers' => $designers,
            'pendingDesignsByTarget' => $pendingDesignsByTarget,
        ]);
    }

    public function tugas()
    {
        $role = strtolower(session()->get('role') ?? '');
        if (!in_array($role, ['drafter', 'arsitek'])) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat tugas.');
        }

        $userId = session()->get('user_id');

        $userAdminRepo = new \App\Modules\Admin\Repositories\UserAdminRepository();
        $allDesigners = $userAdminRepo->findAllOrderedByIdDesc();
        $designers = array_filter($allDesigners, function($user) {
            $role = strtolower($user['role'] ?? '');
            return in_array($role, ['kepala divisi desain', 'arsitek', 'drafter']);
        });

        $db = \Config\Database::connect();
        $pendingDesigns = $db->table('project_designs')
            ->select('project_designs.*, ua.full_name as admin_name')
            ->join('user_admin ua', 'ua.id = project_designs.user_admin_id', 'left')
            ->where('project_designs.status', 'PENDING')
            ->where('project_designs.user_admin_id', $userId)
            ->orderBy('project_designs.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $pendingDesignsByTarget = [];
        foreach ($pendingDesigns as $pd) {
            $pendingDesignsByTarget[$pd['design_targets_id']][] = $pd;
        }

        return view('App\Modules\Design\Views\tugas', [
            'title' => 'Tugas Saya',
            'designerTasks' => $this->designService->getDesignerTasksForKanban($userId),
            'designers' => $designers,
            'pendingDesignsByTarget' => $pendingDesignsByTarget,
        ]);
    }

    public function updateTargetStatusAjax()
    {
        $role = strtolower(session()->get('role') ?? '');
        if (!can('design_progress') && !in_array($role, ['drafter', 'arsitek'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses untuk mengubah progress target.']);
        }

        $targetId = (int) $this->request->getPost('target_id');
        $status = $this->request->getPost('status');

        if (empty($targetId) || empty($status)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Parameter tidak lengkap.']);
        }

        // Security check for drafter & arsitek
        if (in_array($role, ['drafter', 'arsitek'])) {
            $db = \Config\Database::connect();
            $target = $db->table('design_targets')->where('id', $targetId)->get()->getRowArray();
            if (!$target || $target['user_admin_id'] != session()->get('user_id')) {
                return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses ke tugas ini.']);
            }

            // Block marking as DONE directly if task has designs (must go through approval)
            if ($status === 'DONE') {
                $hasDesigns = $db->table('project_designs')->where('design_targets_id', $targetId)->countAllResults() > 0;
                if ($hasDesigns) {
                    return $this->response->setJSON(['status' => false, 'message' => 'Tugas dengan berkas desain harus disetujui oleh Kepala Divisi Desain.']);
                }
            }
        }

        try {
            $this->designService->updateTargetStatus($targetId, $status);
            log_admin_activity('update', 'Design Requests', 'Update Status Target Proyek via AJAX ' . $targetId);
            return $this->response->setJSON(['status' => true, 'message' => 'Status target berhasil diperbarui!']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateTargetDesignerAjax()
    {
        $role = strtolower(session()->get('role') ?? '');
        $isSuperAdmin = in_array('super_admin_override', session()->get('permissions') ?? []);
        if ($role !== 'kepala divisi desain' && !$isSuperAdmin) {
            return $this->response->setJSON(['status' => false, 'message' => 'Hanya Kepala Divisi Desain yang dapat mengubah desainer tugas.']);
        }

        $targetId = (int) $this->request->getPost('target_id');
        $designerId = $this->request->getPost('user_admin_id');

        if (empty($targetId)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Parameter tidak lengkap.']);
        }

        try {
            $this->designService->updateTargetDesigner($targetId, $designerId ? (int) $designerId : null);
            log_admin_activity('update', 'Design Requests', 'Update Desainer Target Proyek via AJAX ' . $targetId);
            return $this->response->setJSON(['status' => true, 'message' => 'Desainer target berhasil diperbarui!']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getTargetDesignsAjax()
    {
        $role = strtolower(session()->get('role') ?? '');
        if (!can('design') && !in_array($role, ['drafter', 'arsitek'])) {
            return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses.']);
        }

        $targetId = (int) $this->request->getPost('target_id');

        if (empty($targetId)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Parameter tidak lengkap.']);
        }

        // Security check for drafter & arsitek
        if (in_array($role, ['drafter', 'arsitek'])) {
            $db = \Config\Database::connect();
            $target = $db->table('design_targets')->where('id', $targetId)->get()->getRowArray();
            if (!$target || $target['user_admin_id'] != session()->get('user_id')) {
                return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses ke tugas ini.']);
            }
        }

        try {
            $db = \Config\Database::connect();
            $designs = $db->table('project_designs pd')
                ->select('pd.*, ua.full_name as admin_name')
                ->join('user_admin ua', 'ua.id = pd.user_admin_id', 'left')
                ->where('pd.design_targets_id', $targetId)
                ->orderBy('pd.revision_number', 'DESC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON(['status' => true, 'data' => $designs]);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateTargetKeteranganAjax()
    {
        $role = strtolower(session()->get('role') ?? '');
        $isSuperAdmin = in_array('super_admin_override', session()->get('permissions') ?? []);
        if ($role !== 'kepala divisi desain' && !$isSuperAdmin) {
            return $this->response->setJSON(['status' => false, 'message' => 'Hanya Kepala Divisi Desain yang dapat mengubah keterangan target.']);
        }

        $targetId = (int) $this->request->getPost('target_id');
        $keterangan = $this->request->getPost('keterangan');

        if (empty($targetId)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Parameter tidak lengkap.']);
        }

        try {
            $db = \Config\Database::connect();
            $db->table('design_targets')
                ->where('id', $targetId)
                ->update(['keterangan' => $keterangan ?: null]);

            log_admin_activity('update', 'Design Requests', 'Update Keterangan Target Proyek via AJAX ' . $targetId);
            return $this->response->setJSON(['status' => true, 'message' => 'Keterangan target berhasil diperbarui!']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
