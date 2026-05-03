<?php

namespace App\Services;

use App\Models\DesignRequestModel;
use App\Models\DesignTargetsModel;
use App\Models\ProjectDesignsModel;
use App\Models\ProjectInvoicesModel;
use App\Models\ProjectSurveysModel;
use RuntimeException;

/**
 * DesignRequestService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Permohonan Desain.
 * Controller hanya bertanggung jawab menerima request dan mengembalikan response.
 */
class DesignRequestService
{
    protected DesignRequestModel  $requestModel;
    protected ProjectSurveysModel $surveyModel;
    protected ProjectDesignsModel $projectDesignModel;
    protected ProjectInvoicesModel $invoiceModel;
    protected DesignTargetsModel  $targetModel;

    // Path upload
    private const PATH_SURVEY = 'uploads/survey/';
    private const PATH_DESIGN = 'uploads/design_results/';

    public function __construct()
    {
        $this->requestModel       = new DesignRequestModel();
        $this->surveyModel        = new ProjectSurveysModel();
        $this->projectDesignModel = new ProjectDesignsModel();
        $this->invoiceModel       = new ProjectInvoicesModel();
        $this->targetModel        = new DesignTargetsModel();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua permohonan desain, terbaru lebih dulu.
     */
    public function getAllRequests(): array
    {
        return $this->requestModel
            ->orderBy('created_at', 'DESC')
            ->findAll();
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
        $request = $this->requestModel->find($id);

        if (!$request) {
            throw new RuntimeException('Data tidak ditemukan.');
        }

        return [
            'request'        => $request,

            'surveys'        => $this->surveyModel
                ->where('design_request_id', $id)
                ->orderBy('created_at', 'DESC')
                ->findAll(),

            // JOIN dengan design_targets untuk mendapatkan task_name
            'design_results' => $this->projectDesignModel
                ->select('project_designs.*, dt.task_name')
                ->join('design_targets dt', 'dt.id = project_designs.design_targets_id', 'left')
                ->where('project_designs.design_request_id', $id)
                ->orderBy('project_designs.created_at', 'DESC')
                ->findAll(),

            'invoices'       => $this->invoiceModel
                ->where('design_request_id', $id)
                ->orderBy('id', 'ASC')
                ->findAll(),

            'targets'        => $this->targetModel
                ->where('design_request_id', $id)
                ->orderBy('id', 'ASC')
                ->findAll(),
        ];
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
        if (!$this->requestModel->find($id)) {
            throw new RuntimeException('Data proyek tidak ditemukan.');
        }

        $this->requestModel->update($id, [
            'start_date'       => $data['start_date'],
            'target_date'      => $data['target_date'],
            'progress_percent' => $data['progress_percent'],
            'status'           => $data['status'],
        ]);
    }

    /**
     * Update status permohonan desain.
     */
    public function updateStatus(int $id, string $status): void
    {
        $this->requestModel->update($id, ['status' => $status]);
    }

    /**
     * Hapus permohonan desain beserta seluruh data pendukungnya (cascade manual).
     *
     * Logika bisnis: hapus semua data anak sebelum menghapus induk.
     */
    public function deleteRequest(int $id): void
    {
        $this->surveyModel->where('design_request_id', $id)->delete();
        $this->projectDesignModel->where('design_request_id', $id)->delete();
        $this->invoiceModel->where('design_request_id', $id)->delete();
        $this->targetModel->where('design_request_id', $id)->delete();
        $this->requestModel->delete($id);
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

        $this->surveyModel->insert([
            'design_request_id' => $designRequestId,
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
        $survey = $this->surveyModel->find($id);

        if (!$survey) {
            throw new RuntimeException('Data survey tidak ditemukan.');
        }

        $this->deleteFile($survey['file'] ?? null, self::PATH_SURVEY);
        $this->surveyModel->delete($id);

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
    public function addDesignResult(int $designRequestId, array $postData, $file): int
    {
        $fileName = $this->uploadFile($file, self::PATH_DESIGN);
        $targetId = (int) $postData['design_targets_id'];

        $maxRev  = $this->projectDesignModel
            ->selectMax('revision_number')
            ->where('design_targets_id', $targetId)
            ->first();

        $nextRev = ($maxRev['revision_number'] ?? 0) + 1;

        $this->projectDesignModel->insert([
            'design_request_id' => $designRequestId,
            'design_targets_id' => $targetId,
            'revision_number'   => $nextRev,
            'design_name'       => $postData['design_name'],
            'file'              => $fileName,
            'status'            => 'PENDING',
            'revision_note'     => null,
        ]);

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
        $design = $this->projectDesignModel->find($id);

        if (!$design) {
            throw new RuntimeException('Data desain tidak ditemukan.');
        }

        $this->deleteFile($design['file'] ?? null, self::PATH_DESIGN);
        $this->projectDesignModel->delete($id);

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
        $design = $this->projectDesignModel->find($id);

        if (!$design) {
            throw new RuntimeException('Data desain tidak ditemukan.');
        }

        // Set revisi ini APPROVED
        $this->projectDesignModel->update($id, [
            'status'        => 'APPROVED',
            'revision_note' => 'Disetujui oleh admin',
        ]);

        // Tolak semua revisi PENDING lain dalam target yang sama (bulk update)
        $this->projectDesignModel
            ->where('design_targets_id', $design['design_targets_id'])
            ->where('id !=', $id)
            ->where('status', 'PENDING')
            ->update(null, ['status' => 'REJECTED', 'revision_note' => 'Revisi lain telah disetujui']);

        return [
            'design_request_id' => (int) $design['design_request_id'],
            'revision_number'   => (int) $design['revision_number'],
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
        $design = $this->projectDesignModel->find($id);

        if (!$design) {
            throw new RuntimeException('Data desain tidak ditemukan.');
        }

        $this->projectDesignModel->update($id, [
            'status'        => 'REJECTED',
            'revision_note' => !empty($note) ? $note : 'Ditolak oleh admin',
        ]);

        return [
            'design_request_id' => (int) $design['design_request_id'],
            'revision_number'   => (int) $design['revision_number'],
        ];
    }

    // =========================================================================
    // TARGET DESAIN
    // =========================================================================

    /**
     * Tambah target/task baru ke dalam proyek desain.
     */
    public function createTarget(int $designRequestId, array $postData): void
    {
        $this->targetModel->insert([
            'design_request_id' => $designRequestId,
            'task_name'         => $postData['task_name'],
            'start_week'        => $postData['start_week'] ?: 1,
            'end_week'          => $postData['end_week'] ?: 1,
            'keterangan'        => null,
            'status'            => 'PENDING',
        ]);
    }

    /**
     * Hapus satu target dari proyek desain.
     *
     * @throws RuntimeException
     */
    public function deleteTarget(int $targetId, int $designRequestId): void
    {
        $target = $this->targetModel
            ->where('id', $targetId)
            ->where('design_request_id', $designRequestId)
            ->first();

        if (!$target) {
            throw new RuntimeException('Target tidak ditemukan.');
        }

        $this->targetModel
            ->where('id', $targetId)
            ->where('design_request_id', $designRequestId)
            ->delete();
    }

    /**
     * Update keterangan dan status satu target desain.
     *
     * @return int design_request_id untuk redirect
     * @throws RuntimeException
     */
    public function updateTargetProgress(int $targetId, array $postData): int
    {
        $target = $this->targetModel->find($targetId);

        if (!$target) {
            throw new RuntimeException('Target tidak ditemukan.');
        }

        $this->targetModel->update($targetId, [
            'keterangan' => $postData['keterangan'] ?? null,
            'status'     => $postData['status'] ?: 'PENDING',
        ]);

        return (int) $target['design_request_id'];
    }

    // =========================================================================
    // INVOICE
    // =========================================================================

    /**
     * Buat tagihan baru untuk proyek desain.
     */
    public function addInvoice(int $designRequestId, array $postData): void
    {
        $this->invoiceModel->insert([
            'design_request_id' => $designRequestId,
            'description'       => $postData['description'],
            'amount'            => $postData['amount'],
            'due_date'          => $postData['due_date'],
            'status'            => 'UNPAID',
        ]);
    }

    /**
     * Hapus tagihan.
     *
     * @return int design_request_id untuk redirect
     * @throws RuntimeException
     */
    public function deleteInvoice(int $id): int
    {
        $invoice = $this->invoiceModel->find($id);

        if (!$invoice) {
            throw new RuntimeException('Data tagihan tidak ditemukan.');
        }

        $this->invoiceModel->delete($id);

        return (int) $invoice['design_request_id'];
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
}
