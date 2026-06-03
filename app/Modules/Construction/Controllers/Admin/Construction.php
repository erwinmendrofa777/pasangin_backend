<?php

namespace App\Modules\Construction\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Construction\Services\ConstructionService;
use App\Modules\Notifications\Services\NotificationService;
use RuntimeException;

class Construction extends BaseController
{
    protected $validation;
    protected ConstructionService $svc;
    protected NotificationService $notifService;

    public function __construct()
    {
        $this->svc = new ConstructionService();
        $this->notifService = new NotificationService();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        if (!can('construction')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengakses halaman ini.');
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');
        $result = $this->svc->getAllProjectsWithStats($userId, $role);
        
        return view('App\Modules\Construction\Views\index', array_merge($result, ['title' => 'Daftar Konstruksi']));
    }

    public function exportPdf()
    {
        if (!can('construction')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengeksport konstruksi.');
        }

        helper(['terbilang', 'url']);

        $db = \Config\Database::connect();
        $projects = $db->table('construction_requests cr')
            ->select('cr.*, 
                COALESCE(
                    (SELECT SUM(ci.amount - COALESCE(v.discount_nominal, 0)) 
                     FROM construction_invoices ci 
                     LEFT JOIN vouchers v ON v.code = ci.voucher_code
                     WHERE ci.construction_id = cr.id), 
                    0
                ) as total_invoice
            ')
            ->orderBy('cr.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'projects' => $projects,
            'title' => 'Laporan Proyek Konstruksi',
            'tanggal_cetak' => date('Y-m-d')
        ];

        $html = view('App\Modules\Construction\Views\export_pdf', $data);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        ob_end_clean();
        $dompdf->stream('Laporan_Proyek_Konstruksi_' . date('Ymd_His') . '.pdf', ['Attachment' => 0]);
        exit();
    }

    public function detail($id)
    {
        if (!can('construction_detail')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengakses halaman ini.');
        }
        try {
            $data = $this->svc->findConstructionWithDetails((int) $id);
        } catch (\Throwable $e) {
            return redirect()->to(base_url('admin/construction'))->with('error', $e->getMessage());
        }
        return view('App\Modules\Construction\Views\detail', array_merge($data, ['title' => 'Detail Konstruksi']));
    }

    public function updateStatus()
    {
        if (!can('construction')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengubah status.');
        }
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        // Ambil detail proyek untuk mendapatkan user_id
        $details = $this->svc->findConstructionWithDetails((int) $id);
        $project = $details['construction'] ?? null;

        $this->svc->updateStatus((int) $id, $status);

        // Kirim Notifikasi via NotificationService
        if ($project && !empty($project['user_id'])) {
            $title = "Status Proyek: " . strtoupper($status);
            $message = "Status proyek renovasi/konstruksi Anda telah diperbarui menjadi " . strtoupper($status) . ".";

            $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);
        }

        log_admin_activity('update_status', 'Construction', 'Update Status Konstruksi ' . $id);

        return redirect()->to(base_url('admin/construction/detail/' . $id))->with('success', 'Status diperbarui');
    }

    // -------------------------------------------------------------------------
    // RAB
    // -------------------------------------------------------------------------
    public function save_rab_row()
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }

        if (!$this->validate('constructionRabSave')) {
            return $this->response->setJSON(['status' => false, 'message' => implode(' ', $this->validator->getErrors())]);
        }

        try {
            $result = $this->svc->saveRabRow($this->request->getPost());
            log_admin_activity('create', 'Construction', 'Tambah Data RAB');
            return $this->response->setJSON(['status' => true, 'id' => $result['id'], 'message' => 'Data RAB berhasil disimpan']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function lock_rab($constructionId)
    {
        if (!can('construction_rab')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah RAB.');
        }
        $this->svc->lockRab((int) $constructionId);

        log_admin_activity('update', 'Construction', 'Lock RAB Konstruksi ' . $constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#rab'))->with('success', 'RAB Berhasil Dikunci!');
    }

    public function unlock_rab($constructionId)
    {
        if (!can('construction_rab')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah RAB.');
        }
        $this->svc->unlockRab((int) $constructionId);

        log_admin_activity('update', 'Construction', 'Unlock RAB Konstruksi ' . $constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#rab'))->with('success', 'Kunci RAB dibuka!');
    }

    public function delete_rab_row($id)
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->deleteRabRow((int) $id);
            log_admin_activity('delete', 'Construction', 'Delete RAB Konstruksi ' . $id);
            return $this->response->setJSON(['status' => true]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function get_rab_materials($rabId)
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        return $this->response->setJSON($this->svc->getRabMaterials((int) $rabId));
    }

    public function add_rab_material()
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->addRabMaterial($this->request->getPost());
            log_admin_activity('create', 'Construction', 'Tambah Material RAB');
            return $this->response->setJSON(['status' => true, 'message' => 'Material ditambahkan.']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete_rab_material($id)
    {
        if (!can('construction_rab')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        $this->svc->deleteRabMaterial((int) $id);
        log_admin_activity('delete', 'Construction', 'Delete Material RAB ' . $id);
        return $this->response->setJSON(['status' => true]);
    }

    public function get_construction_rab_api($construction_id)
    {
        return $this->response->setJSON($this->svc->getRabApiData((int) $construction_id));
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
            log_admin_activity('create', 'Construction', 'Tambah Addendum ' . $result['id']);
            return $this->response->setJSON(['status' => true, 'id' => $result['id'], 'message' => 'Data Addendum berhasil disimpan']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function lock_addendum($constructionId)
    {
        if (!can('construction_addendum')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah addendum.');
        }
        $this->svc->lockAddendum((int) $constructionId);
        log_admin_activity('update', 'Construction', 'Lock Addendum ' . $constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#addendum'))->with('success', 'Addendum Berhasil Dikunci!');
    }

    public function unlock_addendum($constructionId)
    {
        if (!can('construction_addendum')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah addendum.');
        }
        $this->svc->unlockAddendum((int) $constructionId);

        // Kembalikan rab_total ke nilai RAB murni (tanpa addendum)
        // agar saat dikunci ulang nanti tidak double-count
        $db = \Config\Database::connect();
        $rabRow = $db->query(
            "SELECT COALESCE(SUM(total_price), 0) as rab_sum FROM construction_rabs WHERE construction_id = ?",
            [(int) $constructionId]
        )->getRowArray();

        $db->table('construction_requests')
           ->where('id', $constructionId)
           ->update(['rab_total' => (float) ($rabRow['rab_sum'] ?? 0)]);

        log_admin_activity('update', 'Construction', 'Unlock Addendum ' . $constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#addendum'))->with('success', 'Kunci Addendum dibuka!');
    }

    public function delete_addendum_row($id)
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->deleteAddendumRow((int) $id);
            log_admin_activity('delete', 'Construction', 'Delete Addendum ' . $id);
            return $this->response->setJSON(['status' => true]);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function get_addendum_materials($addendumId)
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        return $this->response->setJSON($this->svc->getAddendumMaterials((int) $addendumId));
    }

    public function add_addendum_material()
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        try {
            $this->svc->addAddendumMaterial($this->request->getPost());
            log_admin_activity('create', 'Construction', 'Tambah Material Addendum ' . $this->request->getPost('addendum_id'));
            return $this->response->setJSON(['status' => true, 'message' => 'Material ditambahkan.']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete_addendum_material($id)
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }
        $this->svc->deleteAddendumMaterial((int) $id);
        log_admin_activity('delete', 'Construction', 'Delete Material Addendum ' . $id);
        return $this->response->setJSON(['status' => true]);
    }

    /**
     * SIMPAN SEMUA BARIS ADDENDUM (DRAF ATAU LOCK)
     * Saat dikunci: rab_total = rab_total_lama + addendum_total
     */
    public function save_all_addendum($constructionId)
    {
        if (!can('construction_addendum')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }

        $db = \Config\Database::connect();
        $rows = $this->request->getPost('rows') ?: [];
        $shouldLock = $this->request->getPost('lock') === 'true';

        $db->transStart();

        $addendumTotal = 0;
        $savedIds = [];

        foreach ($rows as $row) {
            $id        = $row['id'] ?? '0';
            $roman     = $row['roman_number'] ?: 'I';
            $group     = $row['group_name'] ?: 'PEKERJAAN';
            $section   = $row['section_group'];
            $taskName  = $row['task_name'];
            $volume    = (float) ($row['volume'] ?? 0);
            $unit      = $row['unit'];
            $price     = (float) ($row['price'] ?? 0);
            $totalPrice = $volume * $price;

            $addendumTotal += $totalPrice;

            $data = [
                'construction_id'    => $constructionId,
                'roman_number'       => $roman,
                'group_name'         => $group,
                'section_group'      => $section,
                'activity_name'      => $taskName,
                'volume'             => $volume,
                'unit'               => $unit,
                'current_unit_price' => $price,
                'total_price'        => $totalPrice,
                'updated_at'         => date('Y-m-d H:i:s'),
            ];

            if ($shouldLock) {
                $data['is_locked'] = 1;
            }

            if (empty($id) || $id == '0' || $id == 0) {
                $data['created_at'] = date('Y-m-d H:i:s');
                $db->table('construction_addendum')->insert($data);
                $savedIds[] = $db->insertID();
            } else {
                $db->table('construction_addendum')->where('id', $id)->update($data);
                $savedIds[] = $id;
            }
        }

        if ($shouldLock) {
            // Lock semua baris addendum proyek ini
            $db->table('construction_addendum')
               ->where('construction_id', $constructionId)
               ->update(['is_locked' => 1]);

            // Hitung ulang RAB murni dari tabel construction_rabs (bukan baca rab_total lama)
            // agar tidak double saat buka kunci lalu kunci ulang
            $rabRow = $db->query(
                "SELECT COALESCE(SUM(total_price), 0) as rab_sum FROM construction_rabs WHERE construction_id = ?",
                [(int) $constructionId]
            )->getRowArray();

            $rabTotal  = (float) ($rabRow['rab_sum'] ?? 0);
            $newTotal  = $rabTotal + $addendumTotal;

            // Update rab_total = RAB murni + Addendum
            $db->table('construction_requests')
               ->where('id', $constructionId)
               ->update(['rab_total' => $newTotal]);

            log_admin_activity('update', 'Construction', 'Lock Addendum & Update RAB Total ' . $constructionId);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => false, 'message' => 'Gagal menyimpan data Addendum!']);
        }

        return $this->response->setJSON([
            'status'    => true,
            'message'   => $shouldLock ? 'Addendum Berhasil Disimpan dan Dikunci!' : 'Draf Addendum Berhasil Disimpan!',
            'saved_ids' => $savedIds,
        ]);
    }

    // -------------------------------------------------------------------------
    // TARGET
    // -------------------------------------------------------------------------
    public function add_target()
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah target.');
        }

        if (!$this->validate('constructionTargetSave')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post = $this->request->getPost();
        $this->svc->addTarget($post);
        log_admin_activity('create', 'Construction', 'Tambah Target ' . $post['construction_id']);
        return redirect()->to(base_url('admin/construction/detail/' . $post['construction_id'] . '#target'))->with('success', 'Target proyek berhasil ditambahkan!');
    }

    public function update_target_status($id, $status)
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah target.');
        }
        $this->svc->updateTargetStatus((int) $id, $status);
        log_admin_activity('update_status', 'Construction', 'Update Target ' . $id);
        return redirect()->back()->with('success', 'Status target berhasil diperbarui!');
    }

    public function delete_target($id, $constructionId)
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah target.');
        }
        $this->svc->deleteTarget((int) $id);
        log_admin_activity('delete', 'Construction', 'Delete Target ' . $id);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#target'))->with('success', 'Target proyek dihapus.');
    }

    public function view_target($id)
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk melihat target.');
        }
        return view('App\Modules\Construction\Views\target', $this->svc->getTargetView((int) $id));
    }

    public function createTarget($id_project)
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk membuat target.');
        }

        $post = $this->request->getPost();
        $msg = $this->svc->createOrUpdateTarget((int) $id_project, $post);

        // Kirim Notifikasi ke Tukang
        if (!empty($post['id_job_applications'])) {
            $applicantId = (int) $post['id_job_applications'];
            // Cari data pendaftar untuk mendapatkan user_id tukang
            $details = $this->svc->findConstructionWithDetails((int) $id_project);
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
                $message = "Anda telah ditugaskan untuk pekerjaan baru di proyek konstruksi. Silakan cek menu tugas untuk melihat detailnya.";

                $this->notifService->sendPersonal('tukang', (int) $targetApplicant['tukang_id'], $title, $message);
            }
        }

        log_admin_activity('create', 'Construction', 'Tambah Target ' . $id_project);
        return redirect()->to(base_url('admin/construction/detail/' . $id_project . '#target'))->with('success', $msg);
    }

    public function update_schedule()
    {
        if (!can('construction_target')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah jadwal.');
        }

        if (!$this->validate('constructionScheduleUpdate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post = $this->request->getPost();
        $this->svc->updateSchedule((int) $post['construction_id'], $post);
        log_admin_activity('update', 'Construction', 'Update Jadwal ' . $post['construction_id']);
        return redirect()->to(base_url('admin/construction/detail/' . $post['construction_id'] . '#target'))->with('success', 'Jadwal proyek berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // INVOICE
    // -------------------------------------------------------------------------
    public function create_invoice()
    {
        if (!can('construction_pembayaran')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk membuat invoice.');
        }

        if (!$this->validate('constructionInvoiceCreate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $constructionId = $this->request->getPost('construction_id');
            $this->svc->createInvoice($this->request->getPost());

            // Kirim Notifikasi ke Client
            $details = $this->svc->findConstructionWithDetails((int) $constructionId);
            $project = $details['construction'] ?? null;

            if ($project && !empty($project['user_id'])) {
                $title = "Tagihan Baru";
                $message = "Tagihan baru untuk proyek Anda telah diterbitkan. Silakan cek menu pembayaran pada detail proyek.";

                $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);
            }
            log_admin_activity('create', 'Construction', 'Tambah Invoice ' . $constructionId);
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#payment'))->with('success', 'Tagihan dibuat!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_invoice($id, $constructionId)
    {
        if (!can('construction_pembayaran')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus invoice.');
        }
        $this->svc->deleteInvoice((int) $id);
        log_admin_activity('delete', 'Construction', 'Delete Invoice ' . $id);
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

        if (!$this->validate('constructionSurveyUpload')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $constructionId = $this->request->getPost('id');
        $this->svc->uploadSurvey((int) $constructionId, $this->request->getPost(), $this->request->getFile('survey_file'));

        // Kirim Notifikasi ke Client
        $details = $this->svc->findConstructionWithDetails((int) $constructionId);
        $project = $details['construction'] ?? null;

        if ($project && !empty($project['user_id'])) {
            $title = "Laporan Survey Baru";
            $message = "Laporan survey untuk proyek Anda telah diunggah oleh tim kami. Silakan cek detail proyek.";

            $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);
        }

        log_admin_activity('create', 'Construction', 'Tambah Survey ' . $constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#survey'))->with('success', 'Survey ditambahkan!');
    }

    public function deleteSurvey($id, $constructionId)
    {
        if (!can('construction_survey')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus survey.');
        }
        $this->svc->deleteSurvey((int) $id);
        log_admin_activity('delete', 'Construction', 'Delete Survey ' . $id);
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

        if (!$this->validate('constructionDesignUpload')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $constructionId = $this->request->getPost('id');
        try {
            $this->svc->uploadDesign((int) $constructionId, $this->request->getPost(), $this->request->getFile('design_2d'));

            // Kirim Notifikasi ke Client
            $details = $this->svc->findConstructionWithDetails((int) $constructionId);
            $project = $details['construction'] ?? null;

            if ($project && !empty($project['user_id'])) {
                $title = "Hasil Desain Baru";
                $message = "Hasil desain untuk proyek Anda telah diunggah. Silakan cek detail proyek untuk melihat desain terbaru.";

                $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);
            }

            log_admin_activity('create', 'Construction', 'Tambah Desain ' . $constructionId);
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#desain'))->with('success', 'Desain ditambahkan!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteDesign($id, $constructionId)
    {
        if (!can('construction_desain')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus desain.');
        }
        $this->svc->deleteDesign((int) $id);
        log_admin_activity('delete', 'Construction', 'Delete Desain ' . $id);
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

        if (!$this->validate('constructionProgressAdd')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $constructionId = $this->request->getPost('construction_id');
        $this->svc->addProgress($this->request->getPost(), $this->request->getFile('photo'));

        // Kirim Notifikasi ke Tukang
        $details = $this->svc->findConstructionWithDetails((int) $constructionId);
        $project = $details['construction'] ?? null;

        if ($project && !empty($project['tukang_id'])) {
            $title = "Laporan Progres Baru";
            $message = "Ada laporan progres baru untuk proyek ini. Silakan cek detail proyek untuk melihat foto dan detail pengerjaan.";

            $this->notifService->sendPersonal('tukang', (int) $project['tukang_id'], $title, $message);
        }

        log_admin_activity('create', 'Construction', 'Tambah Progres ' . $constructionId);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))->with('success', 'Progress ditambahkan!');
    }

    public function deleteProgress($id, $constructionId)
    {
        if (!can('construction_progress')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus progres.');
        }
        $this->svc->deleteProgress((int) $id);
        log_admin_activity('delete', 'Construction', 'Delete Progres ' . $id);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))->with('success', 'Progress dihapus.');
    }

    public function update_progress_status($id, $status)
    {
        if (!can('construction_progress')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah status progres.');
        }

        try {
            $constructionId = $this->svc->updateProgressStatus((int) $id, $status);

            // Kirim Notifikasi ke Tukang
            $details = $this->svc->findConstructionWithDetails((int) $constructionId);
            $project = $details['construction'] ?? null;

            if ($project && !empty($project['tukang_id'])) {
                $title = "Status Progres Diperbarui";
                $message = "Status laporan progres proyek telah diperbarui menjadi: " . strtoupper($status) . ".";

                $this->notifService->sendPersonal('tukang', (int) $project['tukang_id'], $title, $message);
            }

            log_admin_activity('update_status', 'Construction', 'Update Status Progres ' . $constructionId);
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#progress'))->with('success', 'Status laporan progress berhasil diperbarui!');
        } catch (\Throwable $e) {
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

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $applicant = $this->svc->updateApplicantStatus((int) $id, $status);

        // Kirim Notifikasi ke Tukang
        if ($applicant && !empty($applicant['tukang_id'])) {
            $title = "Update Status Lamaran";
            $message = "Status lamaran Anda untuk proyek konstruksi telah diperbarui menjadi: " . strtoupper($status);

            $this->notifService->sendPersonal('tukang', (int) $applicant['tukang_id'], $title, $message);
        }
        log_admin_activity('update_status', 'Construction', 'Update Status Pelamar ' . $id);
        return redirect()->back()->with('success', 'Status pelamar berhasil diperbarui!');
    }

    public function update_job_info()
    {
        if (!can('construction_lowongan')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah lowongan.');
        }

        $post = $this->request->getPost();
        $this->svc->updateJobInfo($post);

        // Kirim Notifikasi ke Seluruh Tukang (Broadcast)
        $title = "Update Info Pekerjaan Konstruksi";
        $message = "ada proyek konstruksi baru yang tersedia! Silakan cek aplikasi untuk informasi terbaru.";
        $this->notifService->sendBulk('tukang', $title, $message);

        log_admin_activity('update', 'Construction', 'Update Info Pekerjaan ' . $post['id']);
        return redirect()->to(base_url('admin/construction/detail/' . $post['id'] . '#info-pekerjaan'))->with('success', 'Info Pekerjaan & Lokasi disinkronkan dan notifikasi dikirim ke seluruh tukang!');
    }

    // -------------------------------------------------------------------------
    // ABSENSI
    // -------------------------------------------------------------------------
    public function delete_attendance($id, $constructionId)
    {
        if (!can('construction_absensi')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus absensi.');
        }
        $this->svc->deleteAttendance((int) $id);
        log_admin_activity('delete', 'Construction', 'Delete Absensi ' . $id);
        return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#absensi'))->with('success', 'Data absensi dihapus.');
    }

    public function update_material_submission_status($id)
    {
        if (!can('construction')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah status pengajuan.');
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

            $constructionId = (int) $submission['construction_id'];

            $this->svc->updateMaterialSubmissionStatus((int) $id, $status, $comment);

            // Kirim Notifikasi ke Tukang jika ada tukang yang terdaftar
            $db = \Config\Database::connect();
            $jobApplication = $db->table('job_applications')
                ->where('project_id', $constructionId)
                ->where('project_type', 'construction')
                ->where('status', 'Siap Kerja')
                ->get()->getRowArray();

            if ($jobApplication && !empty($jobApplication['tukang_id'])) {
                $title = "Status Pengajuan Bahan/Alat";
                $message = "Pengajuan {$submission['type']} Anda untuk proyek #{$constructionId} telah di-{$status} oleh admin.";
                $this->notifService->sendPersonal('tukang', (int) $jobApplication['tukang_id'], $title, $message);
            }

            log_admin_activity('update_status', 'Construction', 'Update Status Pengajuan Bahan/Alat ' . $id . ' menjadi ' . $status);

            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#material'))->with('success', 'Status pengajuan berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function add_material_submission()
    {
        if (!can('construction')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk membuat pengajuan.');
        }

        $post = $this->request->getPost();
        $constructionId = (int) $post['construction_id'];
        $photoFile = $this->request->getFile('photo');

        try {
            $this->svc->saveMaterialSubmission($post, $photoFile);
            log_admin_activity('create', 'Construction', 'Tambah Pengajuan Bahan/Alat Proyek ' . $constructionId);
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#material'))->with('success', 'Pengajuan bahan/alat berhasil dibuat.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update_material_submission($id)
    {
        if (!can('construction')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk mengubah pengajuan.');
        }

        $post = $this->request->getPost();
        $post['id'] = $id;
        $constructionId = (int) $post['construction_id'];
        $photoFile = $this->request->getFile('photo');

        try {
            $this->svc->saveMaterialSubmission($post, $photoFile);
            log_admin_activity('update', 'Construction', 'Ubah Pengajuan Bahan/Alat ID ' . $id);
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#material'))->with('success', 'Pengajuan bahan/alat berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_material_submission($id)
    {
        if (!can('construction')) {
            return redirect()->to('/admin/construction')->with('error', 'Anda tidak memiliki akses untuk menghapus pengajuan.');
        }

        try {
            $submission = $this->svc->getMaterialSubmission((int) $id);
            if (!$submission) {
                return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan.');
            }
            $constructionId = (int) $submission['construction_id'];

            $this->svc->deleteMaterialSubmission((int) $id);
            log_admin_activity('delete', 'Construction', 'Hapus Pengajuan Bahan/Alat ID ' . $id);
            return redirect()->to(base_url('admin/construction/detail/' . $constructionId . '#material'))->with('success', 'Pengajuan bahan/alat berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
