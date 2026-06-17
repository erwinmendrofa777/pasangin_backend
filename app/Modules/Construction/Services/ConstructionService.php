<?php

namespace App\Modules\Construction\Services;

use App\Modules\Construction\Repositories\ConstructionRepository;
use App\Modules\Products\Repositories\ProductRepository;
use App\Modules\Construction\Repositories\ConstructionInvoicesRepository;
use App\Modules\Construction\Repositories\ConstructionJobsRepository;
use App\Modules\Construction\Repositories\JobApplicationsRepository;
use App\Modules\Construction\Repositories\ConstructionTargetsRepository;
use App\Modules\Construction\Repositories\ConstructionProgressRepository;
use App\Modules\Construction\Repositories\ConstructionRabsRepository;
use App\Modules\Construction\Repositories\ConstructionAddendumRepository;
use App\Modules\Construction\Repositories\ConstructionDesignRepository;
use App\Modules\Construction\Repositories\ConstructionSurveyRepository;
use App\Modules\Construction\Repositories\RabMaterialOptionRepository;
use App\Modules\Construction\Repositories\ConstructionAddendumMaterialRepository;
use App\Modules\Construction\Repositories\ConstructionRabMaterialsRepository;
use App\Modules\Construction\Repositories\ConstructionAttendanceRepository;
use App\Modules\Admin\Repositories\UserAdminRepository;
use App\Modules\Construction\Repositories\ConstructionMaterialSubmissionRepository;
use App\Modules\Satuan\Models\SatuanModel;

use App\Modules\Construction\Repositories\Contracts\ConstructionRepositoryInterface;
use App\Modules\Products\Repositories\Contracts\ProductRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionInvoicesRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionJobsRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\JobApplicationsRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionTargetsRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionProgressRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionRabsRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionAddendumRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionDesignRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionSurveyRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\RabMaterialOptionRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionAddendumMaterialRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionRabMaterialsRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionAttendanceRepositoryInterface;
use App\Modules\Admin\Repositories\Contracts\UserAdminRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionMaterialSubmissionRepositoryInterface;

use RuntimeException;

/**
 * ConstructionService
 *
 * Menampung semua logika bisnis Proyek Konstruksi.
 * Sekarang menggunakan Repository Pattern untuk akses data.
 */
class ConstructionService
{
    protected ConstructionRepositoryInterface $constructionRepository;
    protected ProductRepositoryInterface $productRepository;
    protected ConstructionInvoicesRepositoryInterface $invoiceRepository;
    protected ConstructionJobsRepositoryInterface $jobRepository;
    protected JobApplicationsRepositoryInterface $applicationRepository;
    protected ConstructionTargetsRepositoryInterface $targetRepository;
    protected ConstructionProgressRepositoryInterface $progressRepository;
    protected ConstructionRabsRepositoryInterface $rabRepository;
    protected ConstructionAddendumRepositoryInterface $addendumRepository;
    protected ConstructionDesignRepositoryInterface $designRepository;
    protected ConstructionSurveyRepositoryInterface $surveyRepository;
    protected RabMaterialOptionRepositoryInterface $rabMaterialOptionRepository;
    protected ConstructionAddendumMaterialRepositoryInterface $addendumMaterialRepository;
    protected ConstructionRabMaterialsRepositoryInterface $rabMaterialsRepository;
    protected ConstructionAttendanceRepositoryInterface $attendanceRepository;
    protected UserAdminRepositoryInterface $userAdminRepository;
    protected ConstructionMaterialSubmissionRepositoryInterface $materialSubmissionRepository;

    private const PATH_SURVEY = 'uploads/construction/survey/';
    private const PATH_DESIGN = 'uploads/construction/designs/';
    private const PATH_PROGRESS = 'uploads/construction/progress/';

    public function __construct()
    {
        $this->constructionRepository = new ConstructionRepository();
        $this->productRepository = new ProductRepository();
        $this->invoiceRepository = new ConstructionInvoicesRepository();
        $this->jobRepository = new ConstructionJobsRepository();
        $this->applicationRepository = new JobApplicationsRepository();
        $this->targetRepository = new ConstructionTargetsRepository();
        $this->progressRepository = new ConstructionProgressRepository();
        $this->rabRepository = new ConstructionRabsRepository();
        $this->addendumRepository = new ConstructionAddendumRepository();
        $this->designRepository = new ConstructionDesignRepository();
        $this->surveyRepository = new ConstructionSurveyRepository();
        $this->rabMaterialOptionRepository = new RabMaterialOptionRepository();
        $this->addendumMaterialRepository = new ConstructionAddendumMaterialRepository();
        $this->rabMaterialsRepository = new ConstructionRabMaterialsRepository();
        $this->attendanceRepository = new ConstructionAttendanceRepository();
        $this->userAdminRepository = new UserAdminRepository();
        $this->materialSubmissionRepository = new ConstructionMaterialSubmissionRepository();
    }

    // =========================================================================
    // READ
    // =========================================================================

    public function getAllProjectsWithStats(?int $userId = null, ?string $role = null): array
    {
        $projects = $this->constructionRepository->findAllOrderedByCreatedAtDesc();

        $stats = [
            'total' => count($projects),
            'pending' => count(array_filter($projects, fn($p) => $p['status'] === 'PENDING')),
            'construction' => count(array_filter($projects, fn($p) => in_array($p['status'], ['CONSTRUCTION', 'SURVEY', 'DESIGNING', 'RAB']))),
            'completed' => count(array_filter($projects, fn($p) => $p['status'] === 'COMPLETED')),
        ];

        // Custom stats for desainer
        if (in_array(strtolower($role ?? ''), ['kepala divisi desain', 'drafter', 'arsitek']) && $userId) {
            $designModel = new \App\Modules\Construction\Models\ConstructionDesignModel();
            $myDesigns = $designModel->where('user_admin_id', $userId)->findAll();
            
            $myProjectIds = array_unique(array_column($myDesigns, 'construction_id'));
            
            $queueDesigning = 0;
            $queueSurvey = 0;
            
            foreach ($projects as $p) {
                if ($p['status'] === 'DESIGNING') $queueDesigning++;
                if ($p['status'] === 'SURVEY') $queueSurvey++;
            }
            
            $myActiveConstruction = 0;
            $myCompletedConstruction = 0;
            foreach ($projects as $p) {
                if (in_array($p['id'], $myProjectIds)) {
                    if ($p['status'] === 'CONSTRUCTION') $myActiveConstruction++;
                    if ($p['status'] === 'COMPLETED') $myCompletedConstruction++;
                }
            }
            
            $stats['designer'] = [
                'queue_designing' => $queueDesigning,
                'queue_survey' => $queueSurvey,
                'my_designs_total' => count($myDesigns),
                'my_projects_total' => count($myProjectIds),
                'impact_construction' => $myActiveConstruction,
                'impact_completed' => $myCompletedConstruction,
            ];
        }

        return [
            'projects' => $projects,
            'stats' => $stats,
        ];
    }

    public function findConstructionWithDetails(int $id): array
    {
        $construction = $this->constructionRepository->findByIdWithUser($id);

        if (!$construction) {
            throw new RuntimeException('Data tidak ditemukan.');
        }

        $progressList = $this->progressRepository->findDetailsByConstructionId($id);

        // Map data progress untuk UI
        $progressMapped = array_map(function ($p) {
            $isAddendum = !empty($p['id_construction_addendum']);
            $itemName = $isAddendum ? ($p['addendum_activity_name'] ?? '') : ($p['rab_activity_name'] ?? '');
            $groupName = $isAddendum ? ($p['addendum_group_name'] ?? '') : ($p['rab_group_name'] ?? '');
            $targetKey = trim(($groupName ? $groupName . ' – ' : '') . $itemName) ?: 'Tanpa Target';

            return [
                'id' => $p['id'],
                'target_id' => $p['id_construction_targets'],
                'volume' => $p['volume'],
                'keterangan' => $p['keterangan'],
                'status' => $p['status'],
                'photo' => $p['photo'],
                'created_at' => $p['created_at'],
                'item_name' => $itemName,
                'group_name' => $groupName,
                'target_key' => $targetKey,
            ];
        }, $progressList);

        return [
            'construction' => $construction,
            'progress' => $progressMapped,
            'progress_list' => $progressMapped,
            'invoice_list' => $this->invoiceRepository->findByConstructionId($id),
            'job_info' => $this->jobRepository->findByConstructionId($id),
            'design_list' => $this->designRepository->findByConstructionId($id),
            'survey_list' => $this->surveyRepository->findByConstructionId($id),
            'rab_list' => $this->rabRepository->findByConstructionId($id),
            'rab' => $this->rabRepository->findByConstructionId($id),
            'list_tagihan' => $this->rabRepository->findByConstructionId($id),
            'addendum_list' => $this->addendumRepository->findByConstructionId($id),
            'addendum' => $this->addendumRepository->findByConstructionId($id),
            'all_products' => $this->productRepository->findAllWithSupplier(),
            'ahsp_list' => (function() {
                $ahspRepo = new \App\Modules\AHSP\Repositories\AHSPRepository();
                $list = $ahspRepo->findAllOrderedByIdDesc();
                return array_map(fn($item) => $ahspRepo->findWithChildren($item['id']), $list);
            })(),
            'applicants' => $this->applicationRepository->findByProjectIdAndType($id, 'CONSTRUCTION'),
            'target_list' => $this->targetRepository->findByConstructionId($id),
            'attendance_list' => $this->attendanceRepository->findByConstructionId($id),
            'admin_users' => $this->userAdminRepository->findAllOrderedByIdDesc(),
            'material_submissions' => $this->materialSubmissionRepository->findByConstructionId($id),
            'satuan_options' => (new SatuanModel())->orderBy('nama_satuan', 'ASC')->findAll(),
        ];
    }

    public function getRABDetails(int $id): array
    {
        $targets = $this->targetRepository->findByConstructionId($id);
        $result = [];

        foreach ($targets as $t) {
            $item = null;
            $materials = [];

            if ($t['id_construction_rabs']) {
                $item = $this->rabRepository->findById($t['id_construction_rabs']);
                $materials = $this->rabMaterialsRepository->findByRabId($t['id_construction_rabs']);
            } elseif ($t['id_construction_addendum']) {
                $item = $this->addendumRepository->findById($t['id_construction_addendum']);
                $materials = $this->addendumMaterialRepository->findByAddendumId($t['id_construction_addendum']);
            }

            if ($item) {
                $result[] = [
                    'target' => $t,
                    'item' => $item,
                    'materials' => $materials,
                ];
            }
        }

        return $result;
    }

    public function getMaterialOptions(): array
    {
        return $this->rabMaterialOptionRepository->findAll();
    }

    // =========================================================================
    // WRITE / PROCESS
    // =========================================================================

    public function updateStatus(int $id, string $status): void
    {
        $this->constructionRepository->update($id, ['status' => strtoupper($status)]);
    }

    public function uploadSurvey(int $constructionId, array $data, array $files = []): void
    {
        $uploadedFiles = [];
        foreach ($files as $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . self::PATH_SURVEY, $newName);
                $uploadedFiles[] = $newName;
            }
        }

        $payload = [
            'construction_id' => $constructionId,
            'user_admin_id' => $data['user_admin_id'] ?? null,
            'survey_title' => $data['survey_title'],
            'survey_notes' => $data['survey_notes'],
            'survey_file' => !empty($uploadedFiles) ? json_encode($uploadedFiles) : null,
        ];

        $this->surveyRepository->save($payload);
        $this->updateStatus($constructionId, 'DESIGNING');
    }

    public function uploadDesign(int $constructionId, array $data, $file = null): void
    {
        $payload = [
            'construction_id' => $constructionId,
            'user_admin_id' => $data['user_admin_id'] ?? null,
            'title' => $data['design_title'],
        ];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . self::PATH_DESIGN, $newName);
            $payload['file'] = $newName;
        }

        $this->designRepository->save($payload);
        $this->updateStatus($constructionId, 'RAB');
    }

    public function saveRabRow(array $data): array
    {
        $id = $data['id'] ?? null;
        $vol = (float) ($data['volume'] ?? 0);
        $price = (float) ($data['price'] ?? 0);

        $row = [
            'construction_id' => $data['construction_id'],
            'roman_number' => $data['roman_number'] ?: 'I',
            'group_name' => $data['group_name'] ?: 'PEKERJAAN',
            'sub_group_name' => $data['section_group'],
            'section_group' => $data['section_group'],
            'section_name' => $data['section_group'],
            'ahsp_id' => $data['ahsp_id'],
            'volume' => $vol,
            'unit' => $data['unit'],
            'current_unit_price' => $price,
            'total_price' => $vol * $price,
        ];

        if (!$id || $id == '0') {
            $id = $this->rabRepository->insert($row);
        } else {
            $check = $this->rabRepository->findById($id);
            if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
                throw new RuntimeException('Baris sudah dikunci!');
            }
            $this->rabRepository->update($id, $row);
        }

        return ['id' => $id];
    }

    public function lockRab(int $id): void
    {
        $this->rabRepository->lockByConstructionId($id);
        $this->constructionRepository->update($id, ['status' => 'CONSTRUCTION']);
    }

    public function unlockRab(int $id): void
    {
        $this->rabRepository->unlockByConstructionId($id);
    }

    public function deleteRabRow(int $id): void
    {
        $check = $this->rabRepository->findById($id);
        if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
            throw new RuntimeException('Data terkunci!');
        }
        $this->rabRepository->delete($id);
        $this->rabMaterialsRepository->deleteByRabId($id);
    }

    public function getRabMaterials(int $rabId): array
    {
        return $this->rabMaterialsRepository->findByRabId($rabId);
    }

    public function addRabMaterial(array $data): void
    {
        $rabId = (int) $data['rab_id'];
        $check = $this->rabRepository->findById($rabId);
        if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
            throw new RuntimeException('RAB Terkunci!');
        }

        $this->rabMaterialsRepository->insert([
            'rab_id' => $rabId,
            'product_id' => $data['product_id'],
        ]);
    }

    public function deleteRabMaterial(int $id): void
    {
        $this->rabMaterialsRepository->delete($id);
    }

    public function saveAddendumRow(array $data): array
    {
        $id = $data['id'] ?? null;
        $vol = (float) ($data['volume'] ?? 0);
        $price = (float) ($data['price'] ?? 0);

        $row = [
            'construction_id' => $data['construction_id'],
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
            $id = $this->addendumRepository->insert($row);
        } else {
            $check = $this->addendumRepository->findById($id);
            if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
                throw new RuntimeException('Baris sudah dikunci!');
            }
            $this->addendumRepository->update($id, $row);
        }

        return ['id' => $id];
    }

    public function lockAddendum(int $id): void
    {
        $this->addendumRepository->lockByConstructionId($id);
    }

    public function unlockAddendum(int $id): void
    {
        $this->addendumRepository->unlockByConstructionId($id);
    }

    public function deleteAddendumRow(int $id): void
    {
        $check = $this->addendumRepository->findById($id);
        if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
            throw new RuntimeException('Data terkunci!');
        }
        $this->addendumRepository->delete($id);
        $this->addendumMaterialRepository->deleteByAddendumId($id);
    }

    public function getAddendumMaterials(int $addendumId): array
    {
        return $this->addendumMaterialRepository->findByAddendumId($addendumId);
    }

    public function addAddendumMaterial(array $data): void
    {
        $addendumId = (int) $data['addendum_id'];
        $check = $this->addendumRepository->findById($addendumId);
        if ($check && (int) ($check['is_locked'] ?? 0) === 1) {
            throw new RuntimeException('Addendum Terkunci!');
        }

        $this->addendumMaterialRepository->insert([
            'addendum_id' => $addendumId,
            'product_id' => $data['product_id'],
        ]);
    }

    public function deleteAddendumMaterial(int $id): void
    {
        $this->addendumMaterialRepository->delete($id);
    }

    public function updateSchedule(int $id, array $data): void
    {
        $this->constructionRepository->update($id, [
            'week' => $data['week'],
            'workday' => $data['workday'],
            'start_date' => $data['start_date'] ?: null,
        ]);
    }

    public function createOrUpdateTarget(int $constructionId, array $data): string
    {
        $rabId = $data['rab_id'] ?: null;
        $addendumId = $data['addendum_id'] ?: null;

        $row = [
            'construction_id' => $constructionId,
            'id_construction_rabs' => $rabId,
            'id_construction_addendum' => $addendumId,
            'id_job_applications' => $data['id_job_applications'] ?: null,
            'start_week' => $data['start_week'],
            'end_week' => $data['end_week'],
        ];

        if ($rabId) {
            $existing = $this->targetRepository->findByConstructionAndRab($constructionId, (int) $rabId);
        } else {
            $existing = $this->targetRepository->findByConstructionAndAddendum($constructionId, (int) $addendumId);
        }

        if ($existing) {
            $this->targetRepository->update($existing['id'], $row);
            return 'Target diperbarui!';
        } else {
            $this->targetRepository->insert($row);
            return 'Target ditambahkan!';
        }
    }

    public function getTargetView(int $id): array
    {
        return [
            'construction' => $this->constructionRepository->findById($id),
            'rab'          => $this->rabRepository->findByConstructionId($id),
            'target_list'  => $this->targetRepository->findByConstructionId($id),
            'applicants'   => $this->applicationRepository->findApprovedByProjectIdAndType($id, 'CONSTRUCTION'),
        ];
    }

    public function addTarget(array $data): void
    {
        $this->targetRepository->insert([
            'construction_id'          => $data['construction_id'],
            'id_job_applications'      => $data['id_job_applications'] ?? null,
            'id_construction_rabs'     => $data['rab_id'] ?? null,
            'id_construction_addendum' => $data['addendum_id'] ?? null,
            'start_week'               => $data['start_week'] ?? 1,
            'end_week'                 => $data['end_week'] ?? 1,
            'status'                   => $data['status'] ?? 'Pending',
        ]);
    }

    public function updateTargetStatus(int $id, string $status): void
    {
        $this->targetRepository->update($id, ['status' => $status]);
    }

    public function deleteTarget(int $id): void
    {
        $this->targetRepository->delete($id);
    }

    public function getRabApiData(int $constructionId): array
    {
        $raw = $this->rabRepository->findByConstructionId($constructionId);

        foreach ($raw as &$item) {
            $item['item_name']     = $item['activity_name'];
            $item['current_price'] = $item['current_unit_price'];
            $item['materials']     = $this->rabMaterialsRepository->findByRabId($item['id']);
        }

        return $raw;
    }

    public function createInvoice(array $data): void
    {
        $constructionId = (int) $data['construction_id'];
        $userId = $data['user_id'] ?? null;

        // Jika user_id tidak dikirim, ambil dari data konstruksi
        if (!$userId) {
            $construction = $this->constructionRepository->findById($constructionId);
            $userId = $construction ? ($construction['user_id'] ?? null) : null;
        }

        if (!$userId) {
            throw new RuntimeException('Gagal membuat tagihan: ID User tidak ditemukan.');
        }

        $this->invoiceRepository->save([
            'construction_id' => $constructionId,
            'invoice_number' => 'INV-CONST-' . time(),
            'user_id' => $userId,
            'amount' => $data['amount'],
            'description' => $data['description'],
            'due_date' => $data['due_date'] ?: null,
            'status' => 'PENDING',
        ]);
    }

    public function deleteInvoice(int $id): void
    {
        $this->invoiceRepository->delete($id);
    }

    public function addProgress(array $data, $file = null): void
    {
        $constructionId = (int) $data['construction_id'];
        $payload = [
            'construction_id' => $constructionId,
            'id_construction_targets' => $data['target_id'],
            'volume' => $data['volume'],
            'description' => $data['description'],
            'status' => 'PENDING',
        ];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . self::PATH_PROGRESS, $newName);
            $payload['photo_url'] = $newName;
        }

        $this->progressRepository->save($payload);
    }

    public function deleteProgress(int $id): void
    {
        $this->progressRepository->delete($id);
    }

    public function updateProgressStatus(int $id, string $status): int
    {
        $progress = $this->progressRepository->findById($id);
        if (!$progress) {
            throw new RuntimeException('Progress tidak ditemukan!');
        }

        $constructionId = (int) $progress['construction_id'];
        $this->progressRepository->update($id, ['status' => strtoupper($status)]);

        if (strtoupper($status) === 'APPROVED') {
            $targetId = (int) $progress['id_construction_targets'];
            $target = $this->targetRepository->findById($targetId);

            if ($target) {
                // Bisa tambahkan logika update status target jika sudah 100%
                // Tapi saat ini kita biarkan dulu sesuai permintaan user.
            }

            // Hitung total anggaran (RAB + Addendum)
            $db = \Config\Database::connect();
            $totalRAB = $db->table('construction_rabs')
                ->where('construction_id', $constructionId)
                ->selectSum('total_price')
                ->get()->getRowArray()['total_price'] ?? 0;
                
            $totalAddendum = $db->table('construction_addendum')
                ->where('construction_id', $constructionId)
                ->selectSum('total_price')
                ->get()->getRowArray()['total_price'] ?? 0;
                
            $totalBudget = $totalRAB + $totalAddendum;

            // Hitung total realisasi harga (volume disetujui * harga satuan)
            $realizationRAB = $db->table('construction_progress cp')
                ->join('construction_targets ct', 'ct.id = cp.id_construction_targets')
                ->join('construction_rabs cr', 'cr.id = ct.id_construction_rabs')
                ->where('cp.construction_id', $constructionId)
                ->where('cp.status', 'APPROVED')
                ->select('SUM(cp.volume * cr.current_unit_price) as realization')
                ->get()->getRowArray()['realization'] ?? 0;

            $realizationAddendum = $db->table('construction_progress cp')
                ->join('construction_targets ct', 'ct.id = cp.id_construction_targets')
                ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum')
                ->where('cp.construction_id', $constructionId)
                ->where('cp.status', 'APPROVED')
                ->select('SUM(cp.volume * ca.current_unit_price) as realization')
                ->get()->getRowArray()['realization'] ?? 0;

            $totalRealization = $realizationRAB + $realizationAddendum;

            if ($totalBudget > 0 && $totalRealization >= $totalBudget) {
                $this->constructionRepository->update($constructionId, ['status' => 'COMPLETED']);
            }
        }

        return $constructionId;
    }

    public function updateApplicantStatus(int $id, string $status): ?array
    {
        $this->applicationRepository->update($id, ['status' => $status]);
        return $this->applicationRepository->findById($id);
    }

    public function updateJobInfo(array $data): void
    {
        $constructionId = (int) $data['id'];
        $request = $this->constructionRepository->findById($constructionId);

        $row = [
            'construction_id' => $constructionId,
            'detail_pekerjaan' => $data['detail_pekerjaan'],
            'detail_lokasi' => $data['detail_lokasi'],
            'tempat_tinggal' => $data['tempat_tinggal'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_akhir' => $data['tanggal_akhir'],
            'upah_per_hari' => $data['upah_per_hari'],
            'latitude' => $request['latitude'] ?? '0',
            'longitude' => $request['longitude'] ?? '0',
        ];

        $existingJob = $this->jobRepository->findByConstructionId($constructionId);
        if ($existingJob) {
            $this->jobRepository->update($existingJob['id'], $row);
        } else {
            $this->jobRepository->insert($row);
        }
    }

    public function deleteSurvey(int $id): void
    {
        $this->surveyRepository->delete($id);
    }

    public function deleteDesign(int $id): void
    {
        $this->designRepository->delete($id);
    }

    public function deleteAttendance(int $id): void
    {
        $this->attendanceRepository->delete($id);
    }

    public function getMaterialSubmission(int $id): ?array
    {
        return $this->materialSubmissionRepository->findById($id);
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
            'construction_id' => (int) $data['construction_id'],
            'type' => $data['type'],
            'description' => json_encode(array_values($items)),
            'status' => $data['status'] ?? 'pending',
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
            $uploadPath = 'uploads/construction/material_submissions/';
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
            $uploadPath = 'uploads/construction/material_submissions/';
            if (file_exists($uploadPath . $existing['photo'])) {
                unlink($uploadPath . $existing['photo']);
            }
        }
        $this->materialSubmissionRepository->delete($id);
    }
}
