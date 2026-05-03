<?php

namespace App\Services;

use App\Models\RenovationModel;
use Config\Database;
use RuntimeException;

/**
 * RenovationService
 *
 * Menampung semua logika bisnis Proyek Renovasi.
 * Pola hampir identik dengan ConstructionService namun untuk tabel renovation_*.
 */
class RenovationService
{
    protected RenovationModel $renovationModel;

    private const PATH_SURVEY   = 'uploads/survey/';
    private const PATH_DESIGN   = 'uploads/designs/';
    private const PATH_PROGRESS = 'uploads/progress/';

    public function __construct()
    {
        $this->renovationModel = new RenovationModel();
    }

    // =========================================================================
    // READ
    // =========================================================================

    public function getAllRequests(): array
    {
        return Database::connect()
            ->table('renovation_requests')
            ->select('renovation_requests.*, users.full_name AS client_name, users.phone_number')
            ->join('users', 'users.id = renovation_requests.user_id', 'left')
            ->orderBy('renovation_requests.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function findRenovationWithDetails(int $id): array
    {
        $db = Database::connect();

        $renovation = $db->table('renovation_requests')
            ->select('renovation_requests.*, users.full_name, users.email, users.phone_number')
            ->join('users', 'users.id = renovation_requests.user_id', 'left')
            ->where('renovation_requests.id', $id)
            ->get()->getRowArray();

        if (empty($renovation)) {
            throw new RuntimeException('Data tidak ditemukan.');
        }

        $progressListRaw = $db->table('renovation_progress rp')
            ->select('rp.id, rp.bobot, rp.description as keterangan, rp.status, rp.photo_url as photo, rp.created_at, rr.group_name, rr.sub_group_name, rr.activity_name')
            ->join('renovation_targets rt', 'rt.id = rp.id_renovation_targets', 'left')
            ->join('renovation_rabs rr', 'rr.id = rt.id_renovation_rabs', 'left')
            ->where('rp.renovation_id', $id)
            ->orderBy('rp.created_at', 'DESC')
            ->get()->getResultArray();

        $progressList = [];
        $no = 1;
        foreach ($progressListRaw as $p) {
            $subgroup  = !empty($p['sub_group_name']) ? ' - ' . $p['sub_group_name'] : '';
            $pekerjaan = ($p['group_name'] ?? '') . $subgroup . ' - ' . ($p['activity_name'] ?? '-');
            $progressList[] = [
                'id'         => $p['id'],
                'no'         => $no++,
                'target_id'  => $p['id_renovation_targets'] ?? 0,
                'target_key' => trim(trim($pekerjaan, ' -')),
                'pekerjaan'  => trim(trim($pekerjaan, ' -')),
                'bobot'      => $p['bobot'] . '%',
                'keterangan' => $p['keterangan'] ?? '-',
                'status'     => strtoupper($p['status'] ?? 'PENDING'),
                'photo'      => $p['photo'],
                'created_at' => date('d/m/Y H:i', strtotime($p['created_at'])),
            ];
        }

        $rabList = $db->table('renovation_rabs')
            ->where('renovation_id', $id)->orderBy('roman_number', 'ASC')->orderBy('id', 'ASC')
            ->get()->getResultArray();

        foreach ($rabList as &$item) {
            $item['materials'] = $db->table('rab_material_options')
                ->select('rab_material_options.*, products.name as material_name, products.price')
                ->join('products', 'products.id = rab_material_options.product_id')
                ->where('rab_id', $item['id'])->get()->getResultArray();
        }

        return [
            'renovation'    => $renovation,
            'progress_list' => $progressList,
            'design_list'   => $db->table('renovation_designs')->where('request_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray(),
            'survey_list'   => $db->table('renovation_surveys')->where('request_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray(),
            'invoice_list'  => $db->table('renovation_invoices')->where('renovation_id', $id)->orderBy('created_at', 'ASC')->get()->getResultArray(),
            'job_info'      => $db->table('renovation_jobs')->where('renovation_id', $id)->get()->getRowArray(),
            'applicants'    => $db->table('job_applications')
                ->select('job_applications.*, tukang.name as tukang_name')
                ->join('tukang', 'tukang.id = job_applications.tukang_id', 'left')
                ->where('project_id', $id)->where('project_type', 'renovation')
                ->orderBy('created_at', 'DESC')->get()->getResultArray(),
            'target_list'   => $db->table('renovation_targets')->where('renovation_id', $id)->get()->getResultArray(),
            'rab_list'      => $rabList,
            'all_products'  => $db->table('products')->where('status', 'aktif')->get()->getResultArray(),
            'list_tagihan'  => $db->table('renovation_rabs')
                ->select('id, roman_number, group_name, sub_group_name, activity_name, volume, unit, current_unit_price, total_price')
                ->where('renovation_id', $id)->orderBy('roman_number', 'ASC')->orderBy('id', 'ASC')
                ->get()->getResultArray(),
            'rab'           => array_map(fn($r) => ['id' => $r['id'], 'group_name' => $r['group_name'], 'sub_group_name' => $r['sub_group_name'] ?? '', 'activity_name' => $r['activity_name'], 'total_price' => $r['total_price']], $rabList),
        ];
    }

    public function getTargetView(int $id): array
    {
        $db = Database::connect();
        return [
            'renovation'  => $db->table('renovation_requests')->select('id, start_date, week')->where('id', $id)->get()->getRowArray(),
            'rab'         => $db->table('renovation_rabs')->select('id, group_name, sub_group_name, activity_name, total_price')->where('renovation_id', $id)->get()->getResultArray(),
            'target_list' => $db->table('renovation_targets')->where('renovation_id', $id)->get()->getResultArray(),
            'applicants'  => $db->table('job_applications')
                ->select('job_applications.*, tukang.name as tukang_name')
                ->join('tukang', 'tukang.id = job_applications.tukang_id', 'left')
                ->where('project_id', $id)->where('project_type', 'renovation')->where('status', 'Approved')
                ->orderBy('created_at', 'DESC')->get()->getResultArray(),
        ];
    }

    // =========================================================================
    // STATUS & JADWAL
    // =========================================================================

    public function updateStatus(int $id, string $status): void
    {
        $this->renovationModel->update($id, ['status' => $status]);
    }

    public function updateSchedule(int $id, array $data): void
    {
        $week = (int)($data['week'] ?? 0);
        Database::connect()->table('renovation_requests')->where('id', $id)->update([
            'start_date' => $data['start_date'] ?: null,
            'week'       => $week > 0 ? $week : null,
        ]);
    }

    // =========================================================================
    // RAB
    // =========================================================================

    public function saveRabRow(array $data): array
    {
        $db    = Database::connect();
        $id    = $data['id'] ?? null;
        $vol   = (float)($data['volume'] ?? 0);
        $price = (float)($data['price'] ?? 0);

        $row = [
            'renovation_id'      => $data['renovation_id'],
            'roman_number'       => $data['roman_number'] ?: 'I',
            'group_name'         => $data['group_name'] ?: 'PEKERJAAN',
            'sub_group_name'     => $data['section_group'],
            'section_group'      => $data['section_group'],
            'section_name'       => $data['section_group'],
            'activity_name'      => $data['task_name'],
            'volume'             => $vol,
            'unit'               => $data['unit'],
            'current_unit_price' => $price,
            'total_price'        => $vol * $price,
        ];

        if (!$id || $id == '0') {
            $db->table('renovation_rabs')->insert($row);
            $id = $db->insertID();
        } else {
            $check = $db->table('renovation_rabs')->where('id', $id)->get()->getRowArray();
            if ($check && $check['is_locked'] == 1) {
                throw new RuntimeException('Baris sudah dikunci!');
            }
            $db->table('renovation_rabs')->where('id', $id)->update($row);
        }

        return ['id' => $id];
    }

    public function lockRab(int $renovationId): void
    {
        $db = Database::connect();
        $db->table('renovation_rabs')->where('renovation_id', $renovationId)->update(['is_locked' => 1]);
        $this->renovationModel->update($renovationId, ['status' => 'Construction']);
    }

    public function unlockRab(int $renovationId): void
    {
        Database::connect()->table('renovation_rabs')->where('renovation_id', $renovationId)->update(['is_locked' => 0]);
    }

    public function deleteRabRow(int $id): void
    {
        $db    = Database::connect();
        $check = $db->table('renovation_rabs')->where('id', $id)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            throw new RuntimeException('Data terkunci!');
        }
        $db->table('renovation_rabs')->where('id', $id)->delete();
        $db->table('renovation_rab_materials')->where('rab_id', $id)->delete();
    }

    public function getRabMaterials(int $rabId): array
    {
        return Database::connect()->table('renovation_rab_materials')
            ->select('renovation_rab_materials.*, products.name as material_name, products.price')
            ->join('products', 'products.id = renovation_rab_materials.product_id')
            ->where('rab_id', $rabId)->get()->getResultArray();
    }

    public function addRabMaterial(int $rabId, int $productId): void
    {
        $db    = Database::connect();
        $check = $db->table('renovation_rabs')->where('id', $rabId)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            throw new RuntimeException('RAB Terkunci!');
        }
        $db->table('renovation_rab_materials')->insert(['rab_id' => $rabId, 'product_id' => $productId]);
    }

    public function deleteRabMaterial(int $id): void
    {
        Database::connect()->table('renovation_rab_materials')->where('id', $id)->delete();
    }

    public function getRabApiData(int $renovationId): array
    {
        $db  = Database::connect();
        $raw = $db->table('renovation_rabs')
            ->where('renovation_id', $renovationId)->orderBy('roman_number', 'ASC')->orderBy('id', 'ASC')
            ->get()->getResultArray();

        foreach ($raw as &$item) {
            $item['item_name']     = $item['activity_name'];
            $item['current_price'] = $item['current_unit_price'];
            $item['materials']     = $db->table('renovation_rab_materials')
                ->select('products.name as material_name, products.price, products.description')
                ->join('products', 'products.id = renovation_rab_materials.product_id')
                ->where('rab_id', $item['id'])->get()->getResultArray();
        }

        return $raw;
    }

    // =========================================================================
    // TARGET
    // =========================================================================

    public function addTarget(array $data): void
    {
        Database::connect()->table('renovation_targets')->insert([
            'renovation_id' => $data['renovation_id'],
            'target_name'   => $data['target_name'],
            'start_date'    => $data['start_date'],
            'end_date'      => $data['end_date'],
            'description'   => $data['description'],
            'status'        => 'Pending',
        ]);
    }

    public function updateTargetStatus(int $id, string $status): void
    {
        Database::connect()->table('renovation_targets')->where('id', $id)->update(['status' => $status]);
    }

    public function createOrUpdateTarget(int $renovationId, array $data): string
    {
        $db    = Database::connect();
        $rabId = $data['rab_id'] ?? null;

        $row = [
            'id_job_applications' => $data['id_job_applications'],
            'start_week'          => $data['start_week'],
            'end_week'            => $data['end_week'],
            'bobot'               => $data['bobot'],
        ];

        $existing = $db->table('renovation_targets')
            ->where('renovation_id', $renovationId)
            ->where('id_renovation_rabs', $rabId)
            ->get()->getRowArray();

        if ($existing) {
            $db->table('renovation_targets')->where('id', $existing['id'])->update($row);
            return 'Target diperbarui!';
        }

        $row['renovation_id']      = $renovationId;
        $row['id_renovation_rabs'] = $rabId;
        $db->table('renovation_targets')->insert($row);
        return 'Target ditambahkan!';
    }

    // =========================================================================
    // INVOICE
    // =========================================================================

    public function createInvoice(array $data): void
    {
        $db           = Database::connect();
        $renovationId = (int)$data['renovation_id'];
        $description  = trim($data['description']);

        $project = $db->table('renovation_requests')->where('id', $renovationId)->get()->getRowArray();
        if (!$project || !isset($project['user_id'])) {
            throw new RuntimeException('Proyek tidak ditemukan.');
        }

        $existing = $db->table('renovation_invoices')
            ->where('renovation_id', $renovationId)
            ->where('LOWER(description)', strtolower($description))
            ->countAllResults();

        if ($existing > 0) {
            throw new RuntimeException('Tagihan untuk pekerjaan "' . $description . '" sudah pernah dibuat.');
        }

        $db->table('renovation_invoices')->insert([
            'renovation_id' => $renovationId,
            'user_id'       => $project['user_id'],
            'description'   => $description,
            'amount'        => (int)$data['amount'],
            'due_date'      => $data['due_date'] ?: null,
            'status'        => 'UNPAID',
        ]);
    }

    public function deleteInvoice(int $id): void
    {
        Database::connect()->table('renovation_invoices')->where('id', $id)->delete();
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

        Database::connect()->table('renovation_surveys')->insert([
            'request_id'  => $requestId,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'file_url'    => $fileName,
        ]);
    }

    public function deleteSurvey(int $id): void
    {
        Database::connect()->table('renovation_surveys')->where('id', $id)->delete();
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

        Database::connect()->table('renovation_designs')->insert([
            'request_id' => $requestId,
            'title'      => $data['title'],
            'file_url'   => $fileName,
        ]);

        return true;
    }

    public function deleteDesign(int $id): void
    {
        Database::connect()->table('renovation_designs')->where('id', $id)->delete();
    }

    // =========================================================================
    // PROGRESS
    // =========================================================================

    public function addProgress(int $renovationId, array $data, $photo): void
    {
        $row = [
            'renovation_id' => $renovationId,
            'week_number'   => $data['week_number'],
            'percentage'    => $data['percentage'],
            'description'   => $data['description'],
        ];

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName       = $photo->getRandomName();
            $photo->move(FCPATH . self::PATH_PROGRESS, $photoName);
            $row['photo_url'] = $photoName;
        }

        Database::connect()->table('renovation_progress')->insert($row);
    }

    /**
     * Update status progress. Jika APPROVED, hitung apakah target/proyek selesai.
     *
     * @return int renovation_id untuk redirect
     * @throws RuntimeException
     */
    public function updateProgressStatus(int $id, string $status): int
    {
        $db       = Database::connect();
        $progress = $db->table('renovation_progress')->where('id', $id)->get()->getRowArray();

        if (!$progress) {
            throw new RuntimeException('Progress tidak ditemukan!');
        }

        $renovationId = (int)$progress['renovation_id'];
        $db->table('renovation_progress')->where('id', $id)->update(['status' => strtoupper($status)]);

        if (strtoupper($status) === 'APPROVED') {
            $targetId = $progress['id_renovation_targets'];
            $target   = $db->table('renovation_targets')->where('id', $targetId)->get()->getRowArray();

            if ($target) {
                $totalProgress = $db->table('renovation_progress')
                    ->selectSum('bobot')->where('id_renovation_targets', $targetId)->where('status', 'APPROVED')
                    ->get()->getRowArray();

                if (round((float)($totalProgress['bobot'] ?? 0), 2) >= round((float)$target['bobot'], 2)) {
                    $db->table('renovation_targets')->where('id', $targetId)->update(['status' => 'Achieved']);
                }

                $allApproved = $db->table('renovation_progress')
                    ->selectSum('bobot')->where('renovation_id', $renovationId)->where('status', 'APPROVED')
                    ->get()->getRowArray();

                if (round((float)($allApproved['bobot'] ?? 0), 2) >= 100.00) {
                    $db->table('renovation_requests')->where('id', $renovationId)->update(['status' => 'COMPLETED']);
                }
            }
        }

        return $renovationId;
    }

    // =========================================================================
    // PELAMAR & JOB INFO
    // =========================================================================

    public function updateApplicantStatus(int $id, string $status): void
    {
        Database::connect()->table('job_applications')->where('id', $id)->update([
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function updateJobInfo(array $data): void
    {
        $db           = Database::connect();
        $renovationId = (int)$data['id'];
        $request      = $db->table('renovation_requests')->where('id', $renovationId)->get()->getRowArray();

        $row = [
            'renovation_id'    => $renovationId,
            'detail_pekerjaan' => $data['detail_pekerjaan'],
            'detail_lokasi'    => $data['detail_lokasi'],
            'tempat_tinggal'   => $data['tempat_tinggal'],
            'tanggal_mulai'    => $data['tanggal_mulai'],
            'tanggal_akhir'    => $data['tanggal_akhir'],
            'upah_per_hari'    => $data['upah_per_hari'],
            'latitude'         => $request['latitude'] ?? '0',
            'longitude'        => $request['longitude'] ?? '0',
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        $builder = $db->table('renovation_jobs');
        if ($builder->where('renovation_id', $renovationId)->get()->getRow()) {
            $builder->where('renovation_id', $renovationId)->update($row);
        } else {
            $row['created_at'] = date('Y-m-d H:i:s');
            $builder->insert($row);
        }
    }
}
