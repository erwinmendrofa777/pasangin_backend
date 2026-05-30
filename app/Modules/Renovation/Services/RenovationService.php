<?php

namespace App\Modules\Renovation\Services;

use App\Modules\Renovation\Repositories\RenovationRepository;
use App\Modules\Renovation\Repositories\RenovationProgressRepository;
use App\Modules\Renovation\Repositories\RenovationRabsRepository;
use App\Modules\Construction\Repositories\RabMaterialOptionRepository;
use App\Modules\Renovation\Repositories\RenovationDesignRepository;
use App\Modules\Renovation\Repositories\RenovationSurveyRepository;
use App\Modules\Renovation\Repositories\RenovationInvoicesRepository;
use App\Modules\Renovation\Repositories\RenovationJobsRepository;
use App\Modules\Construction\Repositories\JobApplicationsRepository;
use App\Modules\Renovation\Repositories\RenovationTargetRepository;
use App\Modules\Renovation\Repositories\RenovationRabMaterialsRepository;
use App\Modules\Products\Repositories\ProductRepository;
use App\Modules\Renovation\Repositories\RenovationAttendanceRepository;
use App\Modules\Renovation\Repositories\RenovationMaterialSubmissionRepository;
use App\Modules\Admin\Repositories\UserAdminRepository;

use App\Modules\Renovation\Repositories\Contracts\RenovationRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationProgressRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationRabsRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\RabMaterialOptionRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationDesignRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationSurveyRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationInvoicesRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationJobsRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\JobApplicationsRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationTargetRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationRabMaterialsRepositoryInterface;
use App\Modules\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationAttendanceRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationMaterialSubmissionRepositoryInterface;

use RuntimeException;

/**
 * RenovationService
 *
 * Menampung semua logika bisnis Proyek Renovasi.
 * Pola hampir identik dengan ConstructionService namun untuk tabel renovation_*.
 * Sekarang menggunakan Repository Pattern untuk memisahkan logika data dari bisnis.
 */
class RenovationService
{
    protected RenovationRepositoryInterface $renovationRepository;
    protected RenovationProgressRepositoryInterface $progressRepository;
    protected RenovationRabsRepositoryInterface $rabRepository;
    protected RabMaterialOptionRepositoryInterface $rabMaterialOptionRepository;
    protected RenovationDesignRepositoryInterface $designRepository;
    protected RenovationSurveyRepositoryInterface $surveyRepository;
    protected RenovationInvoicesRepositoryInterface $invoiceRepository;
    protected RenovationJobsRepositoryInterface $jobRepository;
    protected JobApplicationsRepositoryInterface $jobApplicationRepository;
    protected RenovationTargetRepositoryInterface $targetRepository;
    protected RenovationRabMaterialsRepositoryInterface $rabMaterialRepository;
    protected ProductRepositoryInterface $productRepository;
    protected RenovationAttendanceRepositoryInterface $attendanceRepository;
    protected RenovationMaterialSubmissionRepositoryInterface $materialSubmissionRepository;
    protected UserAdminRepository $userAdminRepository;


    private const PATH_SURVEY = 'uploads/survey/';
    private const PATH_DESIGN = 'uploads/designs/';
    private const PATH_PROGRESS = 'uploads/progress/';

    public function __construct()
    {
        $this->renovationRepository = new RenovationRepository();
        $this->progressRepository = new RenovationProgressRepository();
        $this->rabRepository = new RenovationRabsRepository();
        $this->rabMaterialOptionRepository = new RabMaterialOptionRepository();
        $this->designRepository = new RenovationDesignRepository();
        $this->surveyRepository = new RenovationSurveyRepository();
        $this->invoiceRepository = new RenovationInvoicesRepository();
        $this->jobRepository = new RenovationJobsRepository();
        $this->jobApplicationRepository = new JobApplicationsRepository();
        $this->targetRepository = new RenovationTargetRepository();
        $this->rabMaterialRepository = new RenovationRabMaterialsRepository();
        $this->productRepository = new ProductRepository();
        $this->attendanceRepository = new RenovationAttendanceRepository();
        $this->materialSubmissionRepository = new RenovationMaterialSubmissionRepository();
        $this->userAdminRepository = new UserAdminRepository();
    }

    // =========================================================================
    // READ
    // =========================================================================

    public function getAllProjectsWithStats(?int $userId = null, ?string $role = null): array
    {
        $projects = $this->renovationRepository->findAllWithClient();

        $stats = [
            'total' => count($projects),
            'pending' => count(array_filter($projects, fn($p) => $p['status'] === 'PENDING')),
            'renovation' => count(array_filter($projects, fn($p) => in_array($p['status'], ['RENOVATION', 'SURVEY', 'DESIGNING', 'RAB']))),
            'completed' => count(array_filter($projects, fn($p) => $p['status'] === 'COMPLETED')),
        ];

        // Custom stats for desainer
        if (in_array(strtolower($role ?? ''), ['kepala divisi desain', 'drafter', 'arsitek']) && $userId) {
            $designModel = new \App\Modules\Renovation\Models\RenovationDesignModel();
            $myDesigns = $designModel->where('user_admin_id', $userId)->findAll();
            
            $myProjectIds = array_unique(array_column($myDesigns, 'request_id'));
            
            $queueDesigning = 0;
            $queueSurvey = 0;
            
            foreach ($projects as $p) {
                if ($p['status'] === 'DESIGNING') $queueDesigning++;
                if ($p['status'] === 'SURVEY') $queueSurvey++;
            }
            
            $myActiveRenovation = 0;
            $myCompletedRenovation = 0;
            foreach ($projects as $p) {
                if (in_array($p['id'], $myProjectIds)) {
                    if ($p['status'] === 'RENOVATION') $myActiveRenovation++;
                    if ($p['status'] === 'COMPLETED') $myCompletedRenovation++;
                }
            }
            
            $stats['designer'] = [
                'queue_designing' => $queueDesigning,
                'queue_survey' => $queueSurvey,
                'my_designs_total' => count($myDesigns),
                'my_projects_total' => count($myProjectIds),
                'impact_renovation' => $myActiveRenovation,
                'impact_completed' => $myCompletedRenovation,
            ];
        }

        return [
            'requests' => $projects,
            'stats' => $stats,
        ];
    }

    public function findRenovationWithDetailsByProgressId(int $progressId): array
    {
        $progress = $this->progressRepository->findById($progressId);
        if (!$progress) {
            throw new RuntimeException('Laporan progress tidak ditemukan.');
        }

        $tukangId = null;
        $targetId = $progress['id_renovation_targets'] ?? null;

        if ($targetId) {
            $target = $this->targetRepository->findById((int) $targetId);
            if ($target && !empty($target['id_job_applications'])) {
                $application = $this->jobApplicationRepository->findById((int) $target['id_job_applications']);
                $tukangId = $application ? ($application['tukang_id'] ?? null) : null;
            }
        }

        return [
            'progress' => array_merge($progress, ['tukang_id' => $tukangId])
        ];
    }

    public function findRenovationWithDetails(int $id): array
    {
        $renovation = $this->renovationRepository->findWithClientById($id);

        if (!$renovation) {
            throw new RuntimeException('Data tidak ditemukan.');
        }

        $progressListRaw = $this->progressRepository->findDetailsByRenovationId($id);

        $progressList = [];
        $no = 1;
        foreach ($progressListRaw as $p) {
            $subgroup = !empty($p['sub_group_name']) ? ' - ' . $p['sub_group_name'] : '';
            $pekerjaan = ($p['group_name'] ?? '') . $subgroup . ' - ' . ($p['activity_name'] ?? '-');
            $progressList[] = [
                'id' => $p['id'],
                'no' => $no++,
                'target_id' => $p['id_renovation_targets'] ?? 0,
                'target_key' => trim(trim($pekerjaan, ' -')),
                'pekerjaan' => trim(trim($pekerjaan, ' -')),
                'bobot' => $p['bobot'] . '%',
                'keterangan' => $p['description'] ?? '-',
                'status' => strtoupper($p['status'] ?? 'PENDING'),
                'photo' => $p['photo_url'],
                'created_at' => date('d/m/Y H:i', strtotime($p['created_at'])),
            ];
        }

        $rabList = $this->rabRepository->findByRenovationId($id);

        foreach ($rabList as &$item) {
            $item['materials'] = $this->rabMaterialRepository->findByRabId($item['id']);
        }

        return [
            'renovation'         => $renovation,
            'progress_list'      => $progressList,
            'design_list'        => $this->designRepository->findByRequestId($id),
            'survey_list'        => $this->surveyRepository->findByRequestId($id),
            'invoice_list'       => $this->invoiceRepository->findByRenovationId($id),
            'job_info'           => $this->jobRepository->findByRenovationId($id),
            'applicants'         => $this->jobApplicationRepository->findByProjectIdAndType($id, 'renovation'),
            'target_list'        => $this->targetRepository->findByRenovationId($id),
            'rab_list'           => $rabList,
            'all_products'       => $this->productRepository->findAllWithSupplier(),
            'list_tagihan'       => $this->rabRepository->findByRenovationId($id),
            'rab'                => array_map(fn($r) => ['id' => $r['id'], 'group_name' => $r['group_name'], 'sub_group_name' => $r['sub_group_name'] ?? '', 'activity_name' => $r['activity_name'], 'total_price' => $r['total_price']], $rabList),
            'attendance_list'    => $this->attendanceRepository->findByRenovationId($id),
            'admin_users'        => $this->userAdminRepository->findAllOrderedByIdDesc(),
            'material_submissions' => $this->materialSubmissionRepository->findByRenovationId($id),
        ];
    }

    public function getTargetView(int $id): array
    {
        return [
            'renovation' => $this->renovationRepository->findById($id),
            'rab' => $this->rabRepository->findByRenovationId($id),
            'target_list' => $this->targetRepository->findByRenovationId($id),
            'applicants' => $this->jobApplicationRepository->findApprovedByProjectIdAndType($id, 'renovation'),
        ];
    }

    // =========================================================================
    // STATUS & JADWAL
    // =========================================================================

    public function updateStatus(int $id, string $status): void
    {
        $this->renovationRepository->update($id, ['status' => $status]);
    }

    public function updateSchedule(int $id, array $data): void
    {
        $this->renovationRepository->update($id, [
            'start_date' => $data['start_date'] ?: null,
            'week' => (int) $data['week'] > 0 ? (int) $data['week'] : null,
            'workday' => (int) $data['workday'] > 0 ? (int) $data['workday'] : null,
        ]);
    }

    // =========================================================================
    // RAB
    // =========================================================================

    public function saveRabRow(array $data): array
    {
        $id = $data['id'] ?? null;
        $vol = (float) ($data['volume'] ?? 0);
        $price = (float) ($data['price'] ?? 0);

        $row = [
            'renovation_id' => $data['renovation_id'],
            'roman_number' => $data['roman_number'] ?: 'I',
            'group_name' => $data['group_name'] ?: 'PEKERJAAN',
            'sub_group_name' => $data['section_group'],
            'section_group' => $data['section_group'],
            'section_name' => $data['section_group'],
            'activity_name' => $data['task_name'],
            'volume' => $vol,
            'unit' => $data['unit'],
            'current_unit_price' => $price,
            'total_price' => $vol * $price,
        ];

        if (!$id || $id == '0') {
            $this->rabRepository->insert($row);
            // Ambil ID terakhir (asumsi menggunakan getInsertID di dalam repository wrapper jika ada, 
            // atau repository mengembalikan ID)
            // Di sini kita akan memodifikasi repository agar insert mengembalikan bool tapi kita butuh ID.
            // Karena CI4 Model insert() mengembalikan ID atau false, saya akan memodifikasi repository-nya.
        } else {
            $check = $this->rabRepository->findById($id);
            if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
                throw new RuntimeException('Baris sudah dikunci!');
            }
            $this->rabRepository->update($id, $row);
        }

        return ['id' => $id];
    }

    public function lockRab(int $renovationId): void
    {
        $this->rabRepository->lockByRenovationId($renovationId);
        $this->renovationRepository->update($renovationId, ['status' => 'RENOVATION']);
    }

    public function unlockRab(int $renovationId): void
    {
        $this->rabRepository->unlockByRenovationId($renovationId);
    }

    public function deleteRabRow(int $id): void
    {
        $check = $this->rabRepository->findById($id);
        if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
            throw new RuntimeException('Data terkunci!');
        }
        $this->rabRepository->delete($id);
        $this->rabMaterialRepository->deleteByRabId($id);
    }

    public function getRabMaterials(int $rabId): array
    {
        return $this->rabMaterialRepository->findByRabId($rabId);
    }

    public function addRabMaterial(int $rabId, int $productId): void
    {
        $check = $this->rabRepository->findById($rabId);
        if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
            throw new RuntimeException('RAB Terkunci!');
        }

        $this->rabMaterialRepository->insert([
            'rab_id' => $rabId,
            'product_id' => $productId,
        ]);
    }

    public function deleteRabMaterial(int $id): void
    {
        $this->rabMaterialRepository->delete($id);
    }

    public function getRabApiData(int $renovationId): array
    {
        $raw = $this->rabRepository->findByRenovationId($renovationId);

        foreach ($raw as &$item) {
            $item['item_name'] = $item['activity_name'];
            $item['current_price'] = $item['current_unit_price'];
            $item['materials'] = $this->rabMaterialRepository->findByRabId($item['id']);
        }

        return $raw;
    }

    // =========================================================================
    // TARGET
    // =========================================================================

    public function addTarget(array $data): void
    {
        $this->targetRepository->insert([
            'renovation_id' => $data['renovation_id'],
            'target_name' => $data['target_name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => $data['description'],
            'status' => 'Pending',
        ]);
    }

    public function updateTargetStatus(int $id, string $status): void
    {
        $this->targetRepository->update($id, ['status' => $status]);
    }

    public function createOrUpdateTarget(int $renovationId, array $data): string
    {
        $rabId = $data['rab_id'] ?? null;

        $row = [
            'id_job_applications' => $data['id_job_applications'] ?? null,
            'start_week' => $data['start_week'],
            'end_week' => $data['end_week'],
            'bobot' => $data['bobot'],
        ];

        $existing = $this->targetRepository->findByRenovationAndRab($renovationId, (int) $rabId);

        if ($existing) {
            $this->targetRepository->update($existing['id'], $row);
            return 'Target diperbarui!';
        }

        $row['renovation_id'] = $renovationId;
        $row['id_renovation_rabs'] = $rabId;
        $this->targetRepository->insert($row);
        return 'Target ditambahkan!';
    }

    // =========================================================================
    // INVOICE
    // =========================================================================

    public function createInvoice(array $data): void
    {
        $renovationId = (int) $data['renovation_id'];
        $description = trim($data['description']);

        $project = $this->renovationRepository->findById($renovationId);
        if (!$project || !isset($project['user_id'])) {
            throw new RuntimeException('Proyek tidak ditemukan.');
        }

        $existing = $this->invoiceRepository->countByDescription($renovationId, $description);

        if ($existing > 0) {
            throw new RuntimeException('Tagihan untuk pekerjaan "' . $description . '" sudah pernah dibuat.');
        }

        $this->invoiceRepository->insert([
            'renovation_id' => $renovationId,
            'user_id' => $project['user_id'],
            'description' => $description,
            'amount' => (int) $data['amount'],
            'due_date' => $data['due_date'] ?: null,
            'status' => 'UNPAID',
        ]);
    }

    public function deleteInvoice(int $id): void
    {
        $this->invoiceRepository->delete($id);
    }

    // =========================================================================
    // SURVEY
    // =========================================================================

    public function addSurvey(int $requestId, array $data, $file): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return;
        }
        $fileName = $file->getRandomName();
        $file->move(FCPATH . self::PATH_SURVEY, $fileName);

        $this->surveyRepository->insert([
            'request_id' => $requestId,
            'user_admin_id' => $data['user_admin_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_url' => $fileName,
        ]);
    }

    public function deleteSurvey(int $id): void
    {
        $this->surveyRepository->delete($id);
    }

    // =========================================================================
    // DESIGN
    // =========================================================================

    public function addDesign(int $requestId, array $data, $file): bool
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return false;
        }
        $fileName = $file->getRandomName();
        $file->move(FCPATH . self::PATH_DESIGN, $fileName);

        $this->designRepository->insert([
            'request_id' => $requestId,
            'user_admin_id' => $data['user_admin_id'] ?? null,
            'title' => $data['title'],
            'file_url' => $fileName,
        ]);

        return true;
    }

    public function deleteDesign(int $id): void
    {
        $this->designRepository->delete($id);
    }

    // =========================================================================
    // PROGRESS
    // =========================================================================

    public function addProgress(int $renovationId, array $data, $photo): void
    {
        $row = [
            'renovation_id' => $renovationId,
            'week_number' => $data['week_number'],
            'percentage' => $data['percentage'],
            'description' => $data['description'],
        ];

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move(FCPATH . self::PATH_PROGRESS, $photoName);
            $row['photo_url'] = $photoName;
        }

        $this->progressRepository->insert($row);
    }

    /**
     * Update status progress. Jika APPROVED, hitung apakah target/proyek selesai.
     *
     * @return int renovation_id untuk redirect
     * @throws RuntimeException
     */
    public function updateProgressStatus(int $id, string $status): int
    {
        $progress = $this->progressRepository->findById($id);

        if (!$progress) {
            throw new RuntimeException('Progress tidak ditemukan!');
        }

        $renovationId = (int) $progress['renovation_id'];
        $this->progressRepository->update($id, ['status' => strtoupper($status)]);

        if (strtoupper($status) === 'APPROVED') {
            $targetId = $progress['id_renovation_targets'];
            $target = $this->targetRepository->findById((int) $targetId);

            if ($target) {
                $totalProgress = $this->progressRepository->sumBobotByTargetId((int) $targetId);

                if (round((float) $totalProgress, 2) >= round((float) ($target['bobot'] ?? 0), 2)) {
                    $this->targetRepository->update((int) $targetId, ['status' => 'Achieved']);
                }

                $allApproved = $this->progressRepository->sumBobotByRenovationId($renovationId);

                if (round((float) $allApproved, 2) >= 100.00) {
                    $this->renovationRepository->update($renovationId, ['status' => 'COMPLETED']);
                }
            }
        }

        return $renovationId;
    }

    // =========================================================================
    // PELAMAR & JOB INFO
    // =========================================================================

    public function updateApplicantStatus(int $id, string $status): ?array
    {
        $this->jobApplicationRepository->update($id, [
            'status' => $status
        ]);
        return $this->jobApplicationRepository->findById($id);
    }

    public function updateJobInfo(array $data): void
    {
        $renovationId = (int) $data['id'];
        $request = $this->renovationRepository->findById($renovationId);

        $row = [
            'renovation_id' => $renovationId,
            'detail_pekerjaan' => $data['detail_pekerjaan'],
            'detail_lokasi' => $data['detail_lokasi'],
            'tempat_tinggal' => $data['tempat_tinggal'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_akhir' => $data['tanggal_akhir'],
            'upah_per_hari' => $data['upah_per_hari'],
            'latitude' => $request['latitude'] ?? '0',
            'longitude' => $request['longitude'] ?? '0',
        ];

        $existingJob = $this->jobRepository->findByRenovationId($renovationId);
        if ($existingJob) {
            $this->jobRepository->update((int) $existingJob['id'], $row);
        } else {
            $this->jobRepository->insert($row);
        }
    }

    public function deleteAttendance(int $id): void
    {
        $this->attendanceRepository->delete($id);
    }

    // =========================================================================
    // MATERIAL SUBMISSION
    // =========================================================================

    public function getMaterialSubmission(int $id): ?array
    {
        return $this->materialSubmissionRepository->findById($id);
    }

    public function getMaterialSubmissionsByRenovation(int $renovationId): array
    {
        return $this->materialSubmissionRepository->findByRenovationId($renovationId);
    }

    public function updateMaterialSubmissionStatus(int $id, string $status, ?string $comment = null): void
    {
        $submission = $this->materialSubmissionRepository->findById($id);
        if (!$submission) {
            throw new RuntimeException('Pengajuan tidak ditemukan.');
        }

        $updateData = ['status' => strtolower($status)];
        if ($comment !== null) {
            $updateData['comment'] = $comment;
        }

        $this->materialSubmissionRepository->update($id, $updateData);
    }

    public function saveMaterialSubmission(array $data, $photoFile = null): void
    {
        $id = $data['id'] ?? null;
        $items = array_filter(array_map('trim', explode("\n", $data['description'] ?? '')));

        $row = [
            'renovation_id' => (int) $data['renovation_id'],
            'type'          => $data['type'],
            'description'   => json_encode(array_values($items)),
            'status'        => $data['status'] ?? 'pending',
        ];

        if (isset($data['job_applications_id'])) {
            $row['job_applications_id'] = !empty($data['job_applications_id']) ? (int) $data['job_applications_id'] : null;
        }
        if (isset($data['title'])) {
            $row['title'] = !empty($data['title']) ? $data['title'] : null;
        }
        if (isset($data['comment'])) {
            $row['comment'] = !empty($data['comment']) ? $data['comment'] : null;
        }

        // Cari data lama untuk upload/update foto
        $existing = null;
        if ($id && $id != '0' && $id != 0) {
            $existing = $this->materialSubmissionRepository->findById((int) $id);
        }

        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $uploadPath = 'uploads/renovation/material_submissions/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            if ($existing && !empty($existing['photo']) && file_exists($uploadPath . $existing['photo'])) {
                unlink($uploadPath . $existing['photo']);
            }
            $photoName = $photoFile->getRandomName();
            $photoFile->move($uploadPath, $photoName);
            $row['photo'] = $photoName;
        }

        if (!$id || $id == '0' || $id == 0) {
            $this->materialSubmissionRepository->save($row);
        } else {
            $this->materialSubmissionRepository->update((int) $id, $row);
        }
    }

    public function deleteMaterialSubmission(int $id): void
    {
        $existing = $this->materialSubmissionRepository->findById($id);
        if ($existing && !empty($existing['photo'])) {
            $uploadPath = 'uploads/renovation/material_submissions/';
            if (file_exists($uploadPath . $existing['photo'])) {
                unlink($uploadPath . $existing['photo']);
            }
        }
        $this->materialSubmissionRepository->delete($id);
    }
}
