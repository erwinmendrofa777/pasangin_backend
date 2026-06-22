<?php

namespace App\Modules\Design\Services;

use App\Modules\Design\Repositories\DesignRequestRepository;
use App\Modules\Design\Repositories\ProjectSurveysRepository;
use App\Modules\Design\Repositories\ProjectDesignsRepository;
use App\Modules\Design\Repositories\ProjectInvoicesRepository;
use App\Modules\Design\Repositories\DesignTargetsRepository;

use App\Modules\Design\Repositories\Contracts\DesignRequestRepositoryInterface;
use App\Modules\Design\Repositories\Contracts\ProjectSurveysRepositoryInterface;
use App\Modules\Design\Repositories\Contracts\ProjectDesignsRepositoryInterface;
use App\Modules\Design\Repositories\Contracts\ProjectInvoicesRepositoryInterface;
use App\Modules\Design\Repositories\Contracts\DesignTargetsRepositoryInterface;
use App\Modules\Admin\Repositories\Contracts\UserAdminRepositoryInterface;
use App\Modules\Admin\Repositories\UserAdminRepository;

use RuntimeException;

/**
 * DesignRequestService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Permohonan Desain.
 * Controller hanya bertanggung jawab menerima request dan mengembalikan response.
 */
class DesignRequestService
{
    protected DesignRequestRepositoryInterface $requestRepository;
    protected ProjectSurveysRepositoryInterface $surveyRepository;
    protected ProjectDesignsRepositoryInterface $projectDesignRepository;
    protected ProjectInvoicesRepositoryInterface $invoiceRepository;
    protected DesignTargetsRepositoryInterface $targetRepository;
    protected UserAdminRepositoryInterface $userAdminRepository;

    // Path upload
    private const PATH_SURVEY = 'uploads/survey/';
    private const PATH_DESIGN = 'uploads/design_results/';

    public function __construct()
    {
        $this->requestRepository = new DesignRequestRepository();
        $this->surveyRepository = new ProjectSurveysRepository();
        $this->projectDesignRepository = new ProjectDesignsRepository();
        $this->invoiceRepository = new ProjectInvoicesRepository();
        $this->targetRepository = new DesignTargetsRepository();
        $this->userAdminRepository = new UserAdminRepository();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua permohonan desain, terbaru lebih dulu.
     */
    public function getAllRequests(): array
    {
        return $this->requestRepository->findAllOrderedByCreatedAtDesc();
    }

    /**
     * Ambil detail satu proyek beserta semua data pendukungnya.
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @return array ['request', 'surveys', 'design_results', 'invoices', 'targets']
     * @throws RuntimeException
     */
    public function findRequestWithDetails(int $id): array
    {
        $request = $this->requestRepository->findById($id);

        if (!$request) {
            throw new RuntimeException('Data tidak ditemukan.');
        }

        return [
            'request' => $request,
            'surveys' => $this->surveyRepository->findByDesignRequestId($id),
            'design_results' => $this->projectDesignRepository->findWithTaskByDesignRequestId($id),
            'invoices' => $this->invoiceRepository->findByDesignRequestId($id),
            'targets' => $this->targetRepository->findByDesignRequestId($id),
            'admin_users' => $this->userAdminRepository->findAllOrderedByIdDesc(),
        ];
    }

    /**
     * Alias for findRequestWithDetails() — digunakan di beberapa bagian controller.
     *
     * @throws RuntimeException
     */
    public function findDetailOrFail(int $id): array
    {
        return $this->findRequestWithDetails($id);
    }

    /**
     * Mengambil statistik pekerjaan (survei, target, desain).
     * Jika $adminId diberikan, filter khusus untuk desainer tersebut.
     */
    public function getDesignerWorkStats(?int $adminId = null): array
    {
        $surveyModel = new \App\Modules\Design\Models\ProjectSurveysModel();
        $targetModel = new \App\Modules\Design\Models\DesignTargetsModel();
        $designModel = new \App\Modules\Design\Models\ProjectDesignsModel();

        $surveysCount = $adminId !== null 
            ? $surveyModel->where('user_admin_id', $adminId)->countAllResults() 
            : $surveyModel->countAllResults();

        // Targets Breakdowns
        $targetPending = clone $targetModel;
        $targetPending->where('status', 'PENDING');
        if ($adminId !== null) $targetPending->where('user_admin_id', $adminId);
        $targetPendingCount = $targetPending->countAllResults();

        $targetProgress = clone $targetModel;
        $targetProgress->where('status', 'ON PROGRESS');
        if ($adminId !== null) $targetProgress->where('user_admin_id', $adminId);
        $targetProgressCount = $targetProgress->countAllResults();

        $targetDone = clone $targetModel;
        $targetDone->where('status', 'DONE');
        if ($adminId !== null) $targetDone->where('user_admin_id', $adminId);
        $targetDoneCount = $targetDone->countAllResults();

        // Designs Breakdowns
        $designPending = clone $designModel;
        $designPending->where('status', 'PENDING');
        if ($adminId !== null) $designPending->where('user_admin_id', $adminId);
        $designPendingCount = $designPending->countAllResults();

        $designApproved = clone $designModel;
        $designApproved->where('status', 'APPROVED');
        if ($adminId !== null) $designApproved->where('user_admin_id', $adminId);
        $designApprovedCount = $designApproved->countAllResults();

        // Revisi didapatkan bukan berdasarkan status REJECTED, 
        // melainkan berdasarkan jumlah tugas (design_targets_id) yang sudah ada file desainnya
        // tetapi tidak memiliki satupun file yang berstatus APPROVED.
        $db = \Config\Database::connect();
        $revisiBuilder = $db->table('project_designs')
            ->select('design_targets_id')
            ->groupBy('design_targets_id')
            ->having("SUM(CASE WHEN status = 'APPROVED' THEN 1 ELSE 0 END) = 0");
            
        if ($adminId !== null) {
            $revisiBuilder->where('user_admin_id', $adminId);
        }
        $designRejectedCount = $revisiBuilder->get()->getNumRows();

        $designsTotal = clone $designModel;
        if ($adminId !== null) $designsTotal->where('user_admin_id', $adminId);
        $totalDesignsCount = $designsTotal->countAllResults();

        return [
            'surveys' => [
                'total' => $surveysCount,
            ],
            'targets' => [
                'total' => $targetPendingCount + $targetProgressCount + $targetDoneCount,
                'pending' => $targetPendingCount,
                'progress' => $targetProgressCount,
                'done' => $targetDoneCount,
            ],
            'designs' => [
                'total' => $totalDesignsCount,
                'pending' => $designPendingCount,
                'approved' => $designApprovedCount,
                'rejected' => $designRejectedCount,
            ]
        ];
    }

    /**
     * Ambil daftar tugas (design_targets) yang ditugaskan ke desainer tertentu.
     * 
     * @param int|null $adminId ID desainer (opsional)
     * @return array Daftar tugas beserta detail request (concept)
     */
    public function getDesignerTasks(?int $adminId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('design_targets');
        
        $builder->select("
            design_targets.*, 
            dr.design_concept, 
            dr.full_name as client_name,
            dr.start_date as request_start_date,
            ua.full_name as designer_name,
            COUNT(pd.id) as total_designs,
            SUM(CASE WHEN pd.status = 'APPROVED' THEN 1 ELSE 0 END) as approved_designs,
            SUM(CASE WHEN pd.status = 'PENDING' THEN 1 ELSE 0 END) as pending_designs,
            SUM(CASE WHEN pd.status = 'REJECTED' THEN 1 ELSE 0 END) as rejected_designs
        ");
        $builder->join('design_requests dr', 'dr.id = design_targets.design_request_id', 'left');
        $builder->join('project_designs pd', 'pd.design_targets_id = design_targets.id', 'left');
        $builder->join('user_admin ua', 'ua.id = design_targets.user_admin_id', 'left');
            
        if ($adminId !== null) {
            $builder->where('design_targets.user_admin_id', $adminId);
        }
        
        $builder->groupBy('design_targets.id');
        
        // Urutkan berdasarkan yang belum selesai, baru yang progres, lalu selesai
        $builder->orderBy("FIELD(design_targets.status, 'ON PROGRESS', 'PENDING', 'DONE')");
        $builder->orderBy("design_targets.created_at", "DESC");
        
        $tasks = $builder->get()->getResultArray();
        
        // Filter: Hanya tampilkan tugas yang sedang berjalan (Belum Dikerjakan, Sedang Diproses, Tinjauan, Perlu Revisi)
        // Sembunyikan tugas yang sudah benar-benar selesai (DISETUJUI) agar daftar lebih fokus
        $activeTasks = array_filter($tasks, function($task) {
            if ($task['total_designs'] > 0) {
                // Jika sudah ada desain yang disetujui, berarti tugas selesai -> sembunyikan
                if ($task['approved_designs'] > 0) return false;
            } else {
                // Jika tidak ada desain tapi status targetnya DONE -> sembunyikan
                if ($task['status'] === 'DONE') return false;
            }
            return true;
        });
        
        return array_values($activeTasks);
    }

    // =========================================================================
    // UPDATE DESIGN REQUEST
    // =========================================================================

    /**
     * Update jadwal/progress proyek (start_date, target_date, progress_percent, status).
     *
     * @throws RuntimeException
     */
    public function updateProgress(int $id, array $data): void
    {
        if (!$this->requestRepository->findById($id)) {
            throw new RuntimeException('Data proyek tidak ditemukan.');
        }

        $this->requestRepository->update($id, [
            'start_date'       => $data['start_date'],
            'target_date'      => $data['target_date'],
            'progress_percent' => $data['progress_percent'],
            'status'           => $data['status'],
            'max_revision'     => isset($data['max_revision']) ? (int) $data['max_revision'] : null,
        ]);
    }

    /**
     * Update status permohonan desain.
     */
    public function updateStatus(int $id, string $status): void
    {
        $this->requestRepository->update($id, ['status' => $status]);
    }

    /**
     * Hapus permohonan desain beserta seluruh data pendukungnya (cascade manual).
     *
     * Logika bisnis: hapus semua data anak sebelum menghapus induk.
     */
    public function deleteRequest(int $id): void
    {
        $this->surveyRepository->deleteByDesignRequestId($id);
        $this->projectDesignRepository->deleteByDesignRequestId($id);
        $this->invoiceRepository->deleteByDesignRequestId($id);
        $this->targetRepository->deleteByDesignRequestId($id);
        $this->requestRepository->delete($id);
    }

    // =========================================================================
    // SURVEY
    // =========================================================================

    /**
     * Tambah laporan survey + upload file.
     */
    public function addSurvey(int $designRequestId, array $postData, $file): void
    {
        $fileName = $this->uploadFile($file, self::PATH_SURVEY);

        $this->surveyRepository->insert([
            'design_request_id' => $designRequestId,
            'user_admin_id'     => $postData['user_admin_id'] ?? null,
            'title'             => $postData['title'],
            'note'              => $postData['note'] ?? null,
            'file'              => $fileName,
        ]);
    }

    /**
     * Hapus laporan survey beserta file fisiknya.
     *
     * @return int design_request_id untuk redirect
     * @throws RuntimeException
     */
    public function deleteSurvey(int $id): int
    {
        $survey = $this->surveyRepository->findById($id);

        if (!$survey) {
            throw new RuntimeException('Data survey tidak ditemukan.');
        }

        $this->deleteFile($survey['file'] ?? null, self::PATH_SURVEY);
        $this->surveyRepository->delete($id);

        return (int) $survey['design_request_id'];
    }

    // =========================================================================
    // HASIL DESAIN
    // =========================================================================

    /**
     * Upload hasil desain + hitung nomor revisi otomatis.
     *
     * Logika bisnis: nomor revisi = MAX(revision_number) untuk target ini + 1.
     *
     * @return int Nomor revisi yang baru dibuat
     */
    public function addDesignResult(int $designRequestId, array $postData, $files): int
    {
        $targetId = (int) $postData['design_targets_id'];

        $maxRev = $this->projectDesignRepository->getMaxRevisionNumber($targetId);
        $nextRev = $maxRev + 1;

        $hasInserted = false;

        // 1. Process 3D Object Name if filled
        $objectName = $postData['3d_object_name'] ?? '';
        if (!empty(trim($objectName))) {
            $displayName = $postData['design_name'];
            $this->projectDesignRepository->insert([
                'design_request_id' => $designRequestId,
                'design_targets_id' => $targetId,
                'user_admin_id' => $postData['user_admin_id'] ?? null,
                'revision_number' => $nextRev,
                'design_name' => $displayName,
                'file' => trim($objectName),
                'status' => 'PENDING',
                'revision_note' => null,
                'design_type' => '3d',
            ]);
            $hasInserted = true;
        }

        // 2. Process Files if uploaded
        if (!empty($files)) {
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $fileName = $this->uploadFile($file, self::PATH_DESIGN);
                    
                    $originalName = $file->getClientName();
                    $displayName = $postData['design_name'];
                    // If multiple files, or if both files and a 3D string are uploaded, append original name
                    if (count($files) > 1 || !empty(trim($objectName))) {
                        $displayName .= ' - ' . pathinfo($originalName, PATHINFO_FILENAME);
                    }

                    // Auto-detect the type from the file extension
                    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                    $detectedType = 'general';
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        $detectedType = 'image';
                    } elseif ($ext === 'pdf') {
                        $detectedType = 'pdf';
                    } elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv'])) {
                        $detectedType = 'video';
                    } elseif (in_array($ext, ['obj', 'fbx', 'glb', 'gltf', 'dwg', 'rvt'])) {
                        $detectedType = '3d';
                    }

                    $this->projectDesignRepository->insert([
                        'design_request_id' => $designRequestId,
                        'design_targets_id' => $targetId,
                        'user_admin_id' => $postData['user_admin_id'] ?? null,
                        'revision_number' => $nextRev,
                        'design_name' => $displayName,
                        'file' => $fileName,
                        'status' => 'PENDING',
                        'revision_note' => null,
                        'design_type' => $detectedType,
                    ]);
                    $hasInserted = true;
                }
            }
        }

        if (!$hasInserted) {
            throw new \RuntimeException('Tidak ada data desain yang berhasil disimpan.');
        }

        return $nextRev;
    }

    /**
     * Hapus satu file hasil desain beserta record-nya.
     *
     * @return int design_request_id untuk redirect
     * @throws RuntimeException
     */
    public function deleteDesignResult(int $id): int
    {
        $design = $this->projectDesignRepository->findById($id);

        if (!$design) {
            throw new RuntimeException('Data desain tidak ditemukan.');
        }

        $this->deleteFile($design['file'] ?? null, self::PATH_DESIGN);
        $this->projectDesignRepository->delete($id);

        return (int) $design['design_request_id'];
    }

    /**
     * Approve revisi desain & tolak semua revisi PENDING lain dalam target yang sama.
     *
     * Logika bisnis: satu target hanya boleh punya satu revisi APPROVED.
     *
     * @return array ['design_request_id', 'revision_number']
     * @throws RuntimeException
     */
    public function approveDesign(int $id): array
    {
        $design = $this->projectDesignRepository->findById($id);

        if (!$design) {
            throw new RuntimeException('Data desain tidak ditemukan.');
        }

        $targetId = (int) $design['design_targets_id'];
        $revNum = (int) $design['revision_number'];

        // Set ALL designs in this target and revision to APPROVED
        $db = \Config\Database::connect();
        $db->table('project_designs')
            ->where('design_targets_id', $targetId)
            ->where('revision_number', $revNum)
            ->update([
                'status' => 'APPROVED',
                'revision_note' => 'Disetujui oleh admin',
            ]);

        // Tolak semua revisi PENDING lain dalam target yang sama (yang nomor revisinya berbeda)
        $db->table('project_designs')
            ->where('design_targets_id', $targetId)
            ->where('revision_number !=', $revNum)
            ->where('status', 'PENDING')
            ->update([
                'status' => 'REJECTED',
                'revision_note' => 'Revisi lain telah disetujui',
            ]);

        // Otomatis ubah status target menjadi DONE
        if (!empty($targetId)) {
            $this->targetRepository->update($targetId, [
                'status' => 'DONE'
            ]);

            // Periksa jika seluruh target sudah selesai untuk mengubah status permohonan menjadi COMPLETED
            $this->checkAndUpdateDesignRequestStatus((int) $design['design_request_id']);
        }

        return [
            'design_request_id' => (int) $design['design_request_id'],
            'revision_number' => $revNum,
        ];
    }

    /**
     * Reject satu revisi desain.
     *
     * @return array ['design_request_id', 'revision_number']
     * @throws RuntimeException
     */
    public function rejectDesign(int $id, string $note = ''): array
    {
        $design = $this->projectDesignRepository->findById($id);

        if (!$design) {
            throw new RuntimeException('Data desain tidak ditemukan.');
        }

        $targetId = (int) $design['design_targets_id'];
        $revNum = (int) $design['revision_number'];

        // Set ALL designs in this target and revision to REJECTED
        $db = \Config\Database::connect();
        $db->table('project_designs')
            ->where('design_targets_id', $targetId)
            ->where('revision_number', $revNum)
            ->update([
                'status' => 'REJECTED',
                'revision_note' => !empty($note) ? $note : 'Ditolak oleh admin',
            ]);

        return [
            'design_request_id' => (int) $design['design_request_id'],
            'revision_number' => $revNum,
        ];
    }

    // =========================================================================
    // TARGET DESAIN
    // =========================================================================

    /**
     * Tambah target/task baru ke dalam proyek desain.
     * Setelah target dibuat, otomatis membuat tagihan (invoice) terkait.
     */
    public function createTarget(int $designRequestId, array $postData): void
    {
        $db = \Config\Database::connect();

        $this->targetRepository->insert([
            'design_request_id' => $designRequestId,
            'user_admin_id'     => $postData['user_admin_id'] ?? null,
            'task_name'         => $postData['task_name'],
            'start_week'        => $postData['start_week'] ?: 1,
            'end_week'          => $postData['end_week'] ?: 1,
            'keterangan'        => null,
            'status'            => 'PENDING',
        ]);

        // Ambil ID target yang baru saja di-insert
        $newTargetId = $db->insertID();

        // Otomatis buat tagihan terkait target ini (nominal & jatuh tempo diisi kemudian oleh admin)
        if ($newTargetId) {
            $this->invoiceRepository->insert([
                'design_request_id' => $designRequestId,
                'design_target_id'  => $newTargetId,
                'description'       => $postData['task_name'],
                'amount'            => null,
                'due_date'          => null,
                'status'            => 'UNPAID',
            ]);
        }
    }

    /**
     * Hapus satu target dari proyek desain.
     * Invoice yang terhubung ke target ini juga akan dihapus otomatis.
     *
     * @throws RuntimeException
     */
    public function deleteTarget(int $targetId, int $designRequestId): void
    {
        $target = $this->targetRepository->findByIdAndDesignRequestId($targetId, $designRequestId);

        if (!$target) {
            throw new RuntimeException('Target tidak ditemukan.');
        }

        // Hapus invoice yang terhubung ke target ini (jika ada)
        $this->invoiceRepository->deleteByDesignTargetId($targetId);

        $this->targetRepository->delete($targetId);

        // Periksa jika seluruh target yang tersisa sudah selesai untuk mengubah status permohonan menjadi COMPLETED
        $this->checkAndUpdateDesignRequestStatus($designRequestId);
    }

    /**
     * Update keterangan dan status satu target desain.
     *
     * @return int design_request_id untuk redirect
     * @throws RuntimeException
     */
    public function updateTargetProgress(int $targetId, array $postData): int
    {
        $target = $this->targetRepository->findById($targetId);

        if (!$target) {
            throw new RuntimeException('Target tidak ditemukan.');
        }

        $this->targetRepository->update($targetId, [
            'keterangan' => $postData['keterangan'] ?? null,
            'status' => $postData['status'] ?: 'PENDING',
        ]);

        // Periksa jika seluruh target sudah selesai untuk mengubah status permohonan menjadi COMPLETED
        $this->checkAndUpdateDesignRequestStatus((int) $target['design_request_id']);

        return (int) $target['design_request_id'];
    }

    // =========================================================================
    // INVOICE
    // =========================================================================

    /**
     * Buat tagihan manual (tanpa target) untuk proyek desain.
     */
    public function addInvoice(int $designRequestId, array $postData): void
    {
        $this->invoiceRepository->insert([
            'design_request_id' => $designRequestId,
            'design_target_id'  => null, // tagihan manual tidak terhubung ke target
            'description'       => $postData['description'],
            'amount'            => $postData['amount'],
            'due_date'          => $postData['due_date'],
            'status'            => 'UNPAID',
        ]);
    }

    /**
     * Update nominal dan jatuh tempo tagihan target.
     *
     * @throws RuntimeException
     */
    public function updateInvoice(int $invoiceId, array $postData): int
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if (!$invoice) {
            throw new RuntimeException('Tagihan tidak ditemukan.');
        }

        $this->invoiceRepository->update($invoiceId, [
            'amount'   => $postData['amount'],
            'due_date' => $postData['due_date'],
        ]);

        return (int) $invoice['design_request_id'];
    }

    /**
     * Hapus tagihan.
     *
     * @return int design_request_id untuk redirect
     * @throws RuntimeException
     */
    public function deleteInvoice(int $id): int
    {
        $invoice = $this->invoiceRepository->findById($id);

        if (!$invoice) {
            throw new RuntimeException('Data tagihan tidak ditemukan.');
        }

        $this->invoiceRepository->delete($id);

        return (int) $invoice['design_request_id'];
    }

    /**
     * Ambil seluruh target desain untuk Kanban dari proyek yang aktif (belum COMPLETED/CANCELLED).
     */
    public function getDesignerTasksForKanban(?int $adminId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('design_targets');
        
        $builder->select("
            design_targets.*, 
            dr.design_concept, 
            dr.full_name as client_name,
            dr.start_date as request_start_date,
            dr.target_date as request_target_date,
            dr.status as request_status,
            ua.full_name as designer_name,
            COUNT(pd.id) as total_designs,
            SUM(CASE WHEN pd.status = 'APPROVED' THEN 1 ELSE 0 END) as approved_designs,
            SUM(CASE WHEN pd.status = 'PENDING' THEN 1 ELSE 0 END) as pending_designs,
            SUM(CASE WHEN pd.status = 'REJECTED' THEN 1 ELSE 0 END) as rejected_designs
        ");
        $builder->join('design_requests dr', 'dr.id = design_targets.design_request_id', 'left');
        $builder->join('project_designs pd', 'pd.design_targets_id = design_targets.id', 'left');
        $builder->join('user_admin ua', 'ua.id = design_targets.user_admin_id', 'left');
            
        if ($adminId !== null) {
            $builder->where('design_targets.user_admin_id', $adminId);
        }
        
        $builder->whereNotIn('dr.status', ['COMPLETED', 'CANCELLED']);
        
        $builder->groupBy('design_targets.id');
        $builder->orderBy("design_targets.created_at", "DESC");
        
        return $builder->get()->getResultArray();
    }

    /**
     * Update status target saja.
     */
    public function updateTargetStatus(int $targetId, string $status): void
    {
        $target = $this->targetRepository->findById($targetId);
        if (!$target) {
            throw new RuntimeException('Target tidak ditemukan.');
        }
        $this->targetRepository->update($targetId, [
            'status' => $status
        ]);

        // Periksa jika seluruh target sudah selesai untuk mengubah status permohonan menjadi COMPLETED
        $this->checkAndUpdateDesignRequestStatus((int) $target['design_request_id']);
    }

    /**
     * Update desainer pelaksana target saja.
     */
    public function updateTargetDesigner(int $targetId, ?int $designerId): void
    {
        $target = $this->targetRepository->findById($targetId);
        if (!$target) {
            throw new RuntimeException('Target tidak ditemukan.');
        }
        $this->targetRepository->update($targetId, [
            'user_admin_id' => $designerId ?: null
        ]);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Upload file ke direktori yang ditentukan.
     *
     * @throws RuntimeException
     */
    private function uploadFile($file, string $path): string
    {
        if (!$file || !$file->isValid()) {
            throw new RuntimeException('File tidak valid.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . $path, $newName);

        return $newName;
    }

    /**
     * Hapus file dari filesystem secara aman.
     */
    private function deleteFile(?string $filename, string $path): void
    {
        if (empty($filename)) {
            return;
        }

        $filePath = FCPATH . $path . $filename;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Memeriksa apakah seluruh target pengerjaan desain berstatus DONE.
     * Jika ya, maka status permohonan desain (design_requests) diubah menjadi COMPLETED.
     */
    public function checkAndUpdateDesignRequestStatus(int $designRequestId): void
    {
        $targets = $this->targetRepository->findByDesignRequestId($designRequestId);
        
        $allDone = true;
        if (empty($targets)) {
            $allDone = false;
        } else {
            foreach ($targets as $t) {
                if (($t['status'] ?? '') !== 'DONE') {
                    $allDone = false;
                    break;
                }
            }
        }

        if ($allDone) {
            $this->requestRepository->update($designRequestId, [
                'status' => 'COMPLETED'
            ]);
        }
    }
}
