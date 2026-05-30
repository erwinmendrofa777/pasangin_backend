<?php

namespace App\Modules\Renovation\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Renovation\Services\RenovationService;
use RuntimeException;

class Renovation extends BaseController
{
    protected RenovationService $svc;
    protected \App\Modules\Notifications\Services\NotificationService $notifService;

    public function __construct()
    {
        $this->svc = new RenovationService();
        $this->notifService = new \App\Modules\Notifications\Services\NotificationService();
    }

    public function index()
    {
        if (!can('renovation')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat daftar renovasi.');
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');
        $result = $this->svc->getAllProjectsWithStats($userId, $role);

        return view('App\Modules\Renovation\Views\index', array_merge($result, ['title' => 'Daftar Proyek Renovasi']));
    }

    public function detail($id)
    {
        if (!can('renovation_detail')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk melihat detail renovasi.');
        }
        try {
            $data = $this->svc->findRenovationWithDetails((int) $id);
        } catch (RuntimeException $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException($e->getMessage());
        }
        return view('App\Modules\Renovation\Views\detail', array_merge($data, ['title' => 'Detail Renovasi']));
    }

    public function update_status()
    {
        if (!can('renovation_detail')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui status.');
        }
        $id = (int) $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $this->svc->updateStatus($id, $status);

        // Kirim Notifikasi ke Client
        $details = $this->svc->findRenovationWithDetails($id);
        $project = $details['renovation'] ?? null;

        if ($project && !empty($project['user_id'])) {
            $title = "Update Status Proyek";
            $message = "Status proyek renovasi Anda telah diperbarui menjadi: " . strtoupper($status) . ".";

            $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);
        }

        log_admin_activity('update_status', 'renovation', 'mengubah status untuk ID : ' . $id);
        return redirect()->to(base_url('admin/renovation/detail/' . $id))->with('success', 'Status proyek berhasil diperbarui');
    }


    // -------------------------------------------------------------------------
    // RAB
    // -------------------------------------------------------------------------
    public function save_rab_row()
    {
        if (!can('renovation_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }

        if (!$this->validate('renovationRabSave')) {
            return $this->response->setJSON(['status' => false, 'message' => implode(' ', $this->validator->getErrors())]);
        }

        try {
            $result = $this->svc->saveRabRow($this->request->getPost());
            log_admin_activity('create', 'renovation', 'membuat RAB');
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
        $this->svc->lockRab((int) $renovationId);
        log_admin_activity('update', 'renovation', 'mengunci RAB untuk ID : ' . $renovationId);
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#rab'))->with('success', 'RAB Berhasil Dikunci  !');
    }

    public function unlock_rab($renovationId)
    {
        if (!can('renovation_rab')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk membuka kunci RAB.');
        }
        $this->svc->unlockRab((int) $renovationId);
        log_admin_activity('update', 'renovation', 'membuka kunci RAB untuk ID : ' . $renovationId);
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#rab'))->with('success', 'Kunci RAB dibuka  !');
    }

    public function delete_rab_row($id)
    {
        if (!can('renovation_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->deleteRabRow((int) $id);
            log_admin_activity('delete', 'renovation', 'menghapus RAB untuk ID : ' . $id);
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
        return $this->response->setJSON($this->svc->getRabMaterials((int) $rabId));
    }

    public function add_rab_material()
    {
        if (!can('renovation_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->addRabMaterial((int) $this->request->getPost('rab_id'), (int) $this->request->getPost('product_id'));
            log_admin_activity('create', 'renovation', 'menambahkan material RAB');
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
        $this->svc->deleteRabMaterial((int) $id);
        log_admin_activity('delete', 'renovation', 'menghapus material RAB');
        return $this->response->setJSON(['status' => true]);
    }

    public function get_renovation_rab_api($renovation_id)
    {
        return $this->response->setJSON($this->svc->getRabApiData((int) $renovation_id));
    }

    // -------------------------------------------------------------------------
    // TARGET
    // -------------------------------------------------------------------------
    public function add_target()
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menambahkan target.');
        }
        if (!$this->validate('renovationTargetSave')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $post = $this->request->getPost();
            $this->svc->addTarget($post);
            log_admin_activity('create', 'renovation', 'menambahkan target proyek');
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
        $this->svc->updateTargetStatus((int) $id, $status);
        log_admin_activity('update_status', 'renovation', 'mengubah status target');
        return redirect()->back()->with('success', 'Status target berhasil diperbarui!');
    }

    public function view_target($id)
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk melihat target.');
        }
        return view('App\Modules\Renovation\Views\target', $this->svc->getTargetView((int) $id));
    }

    public function createTarget($id_renovation)
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk membuat target.');
        }

        $post = $this->request->getPost();
        $msg = $this->svc->createOrUpdateTarget((int) $id_renovation, $post);

        // Kirim Notifikasi ke Tukang
        if (!empty($post['id_job_applications'])) {
            $applicantId = (int) $post['id_job_applications'];
            // Cari data pendaftar untuk mendapatkan user_id tukang
            $details = $this->svc->findRenovationWithDetails((int) $id_renovation);
            $applicants = $details['applicants'] ?? [];

            $targetApplicant = null;
            foreach ($applicants as $app) {
                if ((int) $app['id'] === $applicantId) {
                    $targetApplicant = $app;
                    break;
                }
            }

            if ($targetApplicant && !empty($targetApplicant['tukang_id'])) {
                $title = "Tugas Pekerjaan Baru";
                $message = "Anda telah ditugaskan untuk pekerjaan baru di proyek renovasi. Silakan cek menu tugas untuk melihat detailnya.";

                $this->notifService->sendPersonal('tukang', (int) $targetApplicant['tukang_id'], $title, $message);
            }
        }

        log_admin_activity('create', 'renovation', 'menambahkan target proyek');
        return redirect()->to(base_url('admin/renovation/detail/' . $id_renovation . '#target'))->with('success', $msg);
    }

    public function update_schedule()
    {
        if (!can('renovation_target')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui jadwal.');
        }
        if (!$this->validate('renovationScheduleUpdate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post = $this->request->getPost();

        $this->svc->updateSchedule((int) $post['renovation_id'], $post);
        log_admin_activity('update', 'renovation', 'memperbarui jadwal proyek');
        return redirect()->to(base_url('admin/renovation/detail/' . $post['renovation_id'] . '#target'))->with('success', 'Jadwal proyek berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // INVOICE
    // -------------------------------------------------------------------------
    public function create_invoice()
    {
        if (!can('renovation_pembayaran')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menambahkan invoice.');
        }

        if (!$this->validate('renovationInvoiceCreate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $post = $this->request->getPost();
            $this->svc->createInvoice($post);

            // Kirim Notifikasi ke Client
            $renovationId = (int) $post['renovation_id'];
            $details = $this->svc->findRenovationWithDetails($renovationId);
            $project = $details['renovation'] ?? null;

            if ($project && !empty($project['user_id'])) {
                $title = "Tagihan Baru";
                $message = "Tagihan baru telah dibuat untuk proyek renovasi Anda. Silakan cek menu pembayaran untuk melihat detail dan melakukan pembayaran.";

                $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);
            }

            log_admin_activity('create', 'renovation', 'menambahkan tagihan proyek');
            return redirect()->to('admin/renovation/detail/' . $renovationId . '#payment')->with('success', 'Tagihan dibuat.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_invoice($id, $renovationId)
    {
        if (!can('renovation_pembayaran')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menghapus invoice.');
        }
        $this->svc->deleteInvoice((int) $id);
        log_admin_activity('delete', 'renovation', 'menghapus tagihan proyek');
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

        if (!$this->validate('renovationSurveyAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->svc->addSurvey((int) $requestId, $this->request->getPost(), $this->request->getFile('file_url'));

        // Kirim Notifikasi ke Client
        $details = $this->svc->findRenovationWithDetails((int) $requestId);
        $project = $details['renovation'] ?? null;

        if ($project && !empty($project['user_id'])) {
            $title = "Laporan Survey Baru";
            $message = "Admin telah mengunggah laporan survey untuk proyek Anda. Silakan cek detail proyek untuk melihat informasi lengkapnya.";

            $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);
        }

        log_admin_activity('create', 'renovation', 'menambahkan laporan survey proyek');
        return redirect()->to('/admin/renovation/detail/' . $requestId)->with('success', 'Laporan survey ditambahkan.');
    }

    public function delete_survey($id, $renovationId)
    {
        if (!can('renovation_survey')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menghapus survey.');
        }
        $this->svc->deleteSurvey((int) $id);
        log_admin_activity('delete', 'renovation', 'menghapus survey proyek');
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

        if (!$this->validate('renovationDesignAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $added = $this->svc->addDesign((int) $requestId, $this->request->getPost(), $this->request->getFile('file_url'));
        if ($added) {
            // Kirim Notifikasi ke Client
            $details = $this->svc->findRenovationWithDetails((int) $requestId);
            $project = $details['renovation'] ?? null;

            if ($project && !empty($project['user_id'])) {
                $title = "Hasil Desain Diunggah";
                $message = "hasil desain renovasi Anda telah diunggah. Silakan cek detail proyek untuk melihat desain tersebut.";

                $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);
            }

            log_admin_activity('create', 'renovation', 'menambahkan desain proyek');
            session()->setFlashdata('success', 'Desain ditambahkan.');
        }
        return redirect()->to('/admin/renovation/detail/' . $requestId);
    }

    public function delete_design($id, $renovationId)
    {
        if (!can('renovation_desain')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menghapus desain.');
        }
        $this->svc->deleteDesign((int) $id);
        log_admin_activity('delete', 'renovation', 'menghapus desain proyek');
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

        if (!$this->validate('renovationProgressAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->svc->addProgress((int) $renovationId, $this->request->getPost(), $this->request->getFile('photo_url'));
        log_admin_activity('create', 'renovation', 'menambahkan progress proyek');
        return redirect()->to('/admin/renovation/detail/' . $renovationId . '#progress')->with('success', 'Progress ditambahkan.');
    }

    public function update_progress_status($id, $status)
    {
        if (!can('renovation_progress')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui progress.');
        }
        try {
            // Dapatkan detail progress sebelum diupdate untuk tahu siapa tukangnya
            $details = $this->svc->findRenovationWithDetailsByProgressId((int) $id);
            $renovationId = $this->svc->updateProgressStatus((int) $id, $status);

            // Kirim Notifikasi ke Tukang
            $tukangId = $details['progress']['tukang_id'] ?? null;
            if ($tukangId) {
                $title = "Update Status Progress";
                $statusLabel = ($status === 'APPROVED') ? 'DISETUJUI' : 'DITOLAK';
                $message = "Laporan progress Anda untuk proyek renovasi telah {$statusLabel} oleh admin.";

                $this->notifService->sendPersonal('tukang', (int) $tukangId, $title, $message);
            }

            log_admin_activity('update_status', 'renovation', 'memperbarui status progress proyek');
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

        $id = (int) $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $application = $this->svc->updateApplicantStatus($id, $status);

        // Kirim Notifikasi ke Tukang
        if ($application && !empty($application['tukang_id'])) {
            $title = "Update Status Lamaran";

            // Mapping label status yang ramah user
            $statusLabels = [
                'Berkas Diproses' => 'sedang diproses (pemeriksaan berkas)',
                'Proses Test' => 'memasuki tahap tes/seleksi',
                'Proses Aktivasi' => 'memasuki tahap aktivasi akun',
                'Siap Kerja' => 'dinyatakan SIAP KERJA (Diterima)',
                'Ditolak' => 'DITOLAK',
            ];

            $label = $statusLabels[$status] ?? $status;
            $message = "Lamaran Anda untuk proyek renovasi saat ini {$label}. Silakan cek aplikasi Anda untuk detail lebih lanjut.";

            $this->notifService->sendPersonal('tukang', (int) $application['tukang_id'], $title, $message);
        }

        log_admin_activity('update_status', 'renovation', 'memperbarui status pelamar renovasi');
        return redirect()->back()->with('success', 'Status pelamar renovasi diperbarui!');
    }

    public function update_job_info()
    {
        if (!can('renovation_lowongan')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk memperbarui lowongan.');
        }
        $post = $this->request->getPost();
        $this->svc->updateJobInfo($post);

        // Kirim Notifikasi ke Seluruh Tukang (Broadcast)
        $title = "Update Info Pekerjaan Renovasi";
        $message = "ada proyek renovasi baru yang tersedia! Silakan cek aplikasi untuk informasi terbaru.";
        $this->notifService->sendBulk('tukang', $title, $message);

        log_admin_activity('update', 'renovation', 'Update Info Pekerjaan Renovasi');
        return redirect()->to(base_url('admin/renovation/detail/' . $post['id'] . '#info-pekerjaan'))->with('success', 'Info Pekerjaan & Lokasi berhasil disinkronkan dan notifikasi dikirim ke seluruh tukang!');
    }

    public function delete_attendance($id, $renovationId)
    {
        if (!can('renovation_absensi')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menghapus absensi.');
        }
        $this->svc->deleteAttendance((int) $id);
        log_admin_activity('delete', 'renovation', 'menghapus absensi proyek');
        return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#absensi'))->with('success', 'Data absensi dihapus.');
    }

    // -------------------------------------------------------------------------
    // MATERIAL SUBMISSION
    // -------------------------------------------------------------------------

    public function update_material_submission_status($id)
    {
        if (!can('renovation')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk mengubah status pengajuan.');
        }

        $status = $this->request->getPost('status');
        $comment = $this->request->getPost('comment');
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        try {
            $submission = $this->svc->getMaterialSubmission((int) $id);
            if (!$submission) {
                return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan.');
            }

            $renovationId = (int) $submission['renovation_id'];

            $this->svc->updateMaterialSubmissionStatus((int) $id, $status, $comment);

            // Kirim Notifikasi ke Tukang jika ada yang terdaftar
            $db = \Config\Database::connect();
            $jobApplication = $db->table('job_applications')
                ->where('project_id', $renovationId)
                ->where('project_type', 'renovation')
                ->where('status', 'Siap Kerja')
                ->get()->getRowArray();

            if ($jobApplication && !empty($jobApplication['tukang_id'])) {
                $title = "Status Pengajuan Bahan/Alat";
                $message = "Pengajuan {$submission['type']} Anda untuk proyek renovasi #{$renovationId} telah di-{$status} oleh admin.";
                $this->notifService->sendPersonal('tukang', (int) $jobApplication['tukang_id'], $title, $message);
            }

            log_admin_activity('update_status', 'renovation', 'Update Status Pengajuan Bahan/Alat ' . $id . ' menjadi ' . $status);

            return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#material'))->with('success', 'Status pengajuan berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function add_material_submission()
    {
        if (!can('renovation')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk membuat pengajuan.');
        }

        $post = $this->request->getPost();
        $renovationId = (int) $post['renovation_id'];
        $photoFile = $this->request->getFile('photo');

        try {
            $this->svc->saveMaterialSubmission($post, $photoFile);
            log_admin_activity('create', 'renovation', 'Tambah Pengajuan Bahan/Alat Proyek ' . $renovationId);
            return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#material'))->with('success', 'Pengajuan bahan/alat berhasil dibuat.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update_material_submission($id)
    {
        if (!can('renovation')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk mengubah pengajuan.');
        }

        $post = $this->request->getPost();
        $post['id'] = $id;
        $renovationId = (int) $post['renovation_id'];
        $photoFile = $this->request->getFile('photo');

        try {
            $this->svc->saveMaterialSubmission($post, $photoFile);
            log_admin_activity('update', 'renovation', 'Ubah Pengajuan Bahan/Alat ID ' . $id);
            return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#material'))->with('success', 'Pengajuan bahan/alat berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_material_submission($id)
    {
        if (!can('renovation')) {
            return redirect()->to('/admin/renovation')->with('error', 'Anda tidak memiliki akses untuk menghapus pengajuan.');
        }

        try {
            $submission = $this->svc->getMaterialSubmission((int) $id);
            if (!$submission) {
                return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan.');
            }
            $renovationId = (int) $submission['renovation_id'];

            $this->svc->deleteMaterialSubmission((int) $id);
            log_admin_activity('delete', 'renovation', 'Hapus Pengajuan Bahan/Alat ID ' . $id);
            return redirect()->to(base_url('admin/renovation/detail/' . $renovationId . '#material'))->with('success', 'Pengajuan bahan/alat berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
