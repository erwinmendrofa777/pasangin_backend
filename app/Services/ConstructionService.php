<?php

namespace App\Services;

use App\Models\ConstructionModel;
use Config\Database;
use RuntimeException;

/**
 * ConstructionService
 *
 * Menampung semua logika bisnis Proyek Konstruksi.
 * Controller hanya menerima request, cek permission, dan return response.
 */
class ConstructionService
{
    protected ConstructionModel $constructionModel;

    private const PATH_SURVEY   = 'uploads/construction/survey/';
    private const PATH_DESIGN   = 'uploads/construction/designs/';
    private const PATH_PROGRESS = 'uploads/construction/progress/';

    public function __construct()
    {
        $this->constructionModel = new ConstructionModel();
    }

    // =========================================================================
    // READ
    // =========================================================================

    public function getAllProjectsWithStats(): array
    {
        $db       = Database::connect();
        $projects = $db->table('construction_requests')->orderBy('created_at', 'DESC')->get()->getResultArray();

        return [
            'projects' => $projects,
            'stats'    => [
                'total'        => count($projects),
                'pending'      => count(array_filter($projects, fn($p) => $p['status'] === 'PENDING')),
                'construction' => count(array_filter($projects, fn($p) => in_array($p['status'], ['CONSTRUCTION', 'SURVEY', 'DESIGNING', 'RAB']))),
                'completed'    => count(array_filter($projects, fn($p) => $p['status'] === 'COMPLETED')),
            ],
        ];
    }

    public function findConstructionWithDetails(int $id): array
    {
        $construction = $this->constructionModel
            ->select('construction_requests.*, users.full_name, users.email, users.phone_number')
            ->join('users', 'users.id = construction_requests.user_id', 'left')
            ->find($id);

        if (!$construction) {
            throw new RuntimeException('Data tidak ditemukan.');
        }

        $db = Database::connect();

        $progressListRaw = $db->table('construction_progress cp')
            ->select('cp.id, cp.id_construction_targets, cp.bobot, cp.description as keterangan, cp.status, cp.photo_url as photo, cp.created_at, ct.id_construction_rabs, ct.id_construction_addendum, cr.group_name as rab_group_name, cr.sub_group_name as rab_sub_group_name, cr.activity_name as rab_activity_name, ca.group_name as addendum_group_name, ca.sub_group_name as addendum_sub_group_name, ca.activity_name as addendum_activity_name')
            ->join('construction_targets ct', 'ct.id = cp.id_construction_targets', 'left')
            ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum', 'left')
            ->join('construction_rabs cr', 'cr.id = ct.id_construction_rabs', 'left')
            ->where('cp.construction_id', $id)
            ->orderBy('cp.created_at', 'DESC')
            ->get()->getResultArray();

        $progressList = [];
        $no = 1;
        foreach ($progressListRaw as $p) {
            if ($p['rab_activity_name']) {
                $subgroup = !empty($p['rab_sub_group_name']) ? ' - ' . $p['rab_sub_group_name'] : '';
                $pekerjaan = ($p['rab_group_name'] ?? '') . $subgroup . ' - ' . ($p['rab_activity_name'] ?? '-');
            } else {
                $subgroup = !empty($p['addendum_sub_group_name']) ? ' - ' . $p['addendum_sub_group_name'] : '';
                $pekerjaan = ($p['addendum_group_name'] ?? '') . $subgroup . ' - ' . ($p['addendum_activity_name'] ?? '-');
            }
            $progressList[] = [
                'id'         => $p['id'],
                'no'         => $no++,
                'target_id'  => $p['id_construction_targets'] ?? 0,
                'target_key' => trim(trim($pekerjaan, ' -')),
                'pekerjaan'  => trim(trim($pekerjaan, ' -')),
                'bobot'      => $p['bobot'] . '%',
                'keterangan' => $p['keterangan'] ?? '-',
                'status'     => strtoupper($p['status'] ?? 'PENDING'),
                'photo'      => $p['photo'],
                'created_at' => date('d/m/Y H:i', strtotime($p['created_at'])),
            ];
        }

        $rabList = $db->table('construction_rabs')
            ->where('construction_id', $id)->orderBy('roman_number', 'ASC')->orderBy('id', 'ASC')
            ->get()->getResultArray();

        foreach ($rabList as &$item) {
            $item['materials'] = $db->table('rab_material_options')
                ->select('rab_material_options.*, products.name as material_name, products.price')
                ->join('products', 'products.id = rab_material_options.product_id')
                ->where('rab_id', $item['id'])->get()->getResultArray();
        }

        $addendumList = $db->table('construction_addendum')
            ->where('construction_id', $id)->orderBy('roman_number', 'ASC')->orderBy('id', 'ASC')
            ->get()->getResultArray();

        foreach ($addendumList as &$item) {
            $item['materials'] = $db->table('construction_addendum_materials')
                ->select('construction_addendum_materials.*, products.name as material_name, products.price')
                ->join('products', 'products.id = construction_addendum_materials.product_id')
                ->where('addendum_id', $item['id'])->get()->getResultArray();
        }

        return [
            'construction'  => $construction,
            'progress_list' => $progressList,
            'design_list'   => $db->table('construction_designs')->where('construction_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray(),
            'survey_list'   => $db->table('construction_surveys')->where('construction_id', $id)->orderBy('created_at', 'DESC')->get()->getResultArray(),
            'invoice_list'  => $db->table('construction_invoices')->where('construction_id', $id)->orderBy('created_at', 'ASC')->get()->getResultArray(),
            'job_info'      => $db->table('construction_jobs')->where('construction_id', $id)->get()->getRowArray(),
            'applicants'    => $db->table('job_applications')->where('project_id', $id)->where('project_type', 'construction')->orderBy('created_at', 'DESC')->get()->getResultArray(),
            'target_list'   => $db->table('construction_targets')->where('construction_id', $id)->get()->getResultArray(),
            'rab_list'      => $rabList,
            'addendum_list' => $addendumList,
            'all_products'  => $db->table('products')->where('status', 'aktif')->get()->getResultArray(),
            'list_tagihan'  => $db->table('construction_rabs')->select('id, roman_number, group_name, sub_group_name, activity_name, volume, unit, current_unit_price, total_price')->where('construction_id', $id)->orderBy('roman_number', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray(),
            'rab'           => array_map(fn($r) => ['id' => $r['id'], 'group_name' => $r['group_name'], 'sub_group_name' => $r['sub_group_name'] ?? '', 'activity_name' => $r['activity_name'], 'total_price' => $r['total_price']], $rabList),
            'addendum'      => array_map(fn($a) => ['id' => $a['id'], 'group_name' => $a['group_name'], 'sub_group_name' => $a['sub_group_name'] ?? '', 'activity_name' => $a['activity_name'], 'total_price' => $a['total_price']], $addendumList),
        ];
    }

    public function getTargetView(int $id): array
    {
        $db = Database::connect();
        return [
            'construction' => $db->table('construction_requests')->select('id, start_date, week, workday')->where('id', $id)->get()->getRowArray(),
            'rab'          => $db->table('construction_rabs')->select('id, group_name, sub_group_name, activity_name, total_price')->where('construction_id', $id)->get()->getResultArray(),
            'addendum'     => $db->table('construction_addendum')->select('id, group_name, sub_group_name, activity_name, total_price')->where('construction_id', $id)->get()->getResultArray(),
            'target_list'  => $db->table('construction_targets')->where('construction_id', $id)->get()->getResultArray(),
            'applicants'   => $db->table('job_applications')
                ->select('job_applications.*, tukang.nama as tukang_name')
                ->join('tukang', 'tukang.id = job_applications.tukang_id', 'left')
                ->where('job_applications.project_id', $id)
                ->where('job_applications.project_type', 'construction')
                ->where('job_applications.status', 'Approved')
                ->orderBy('job_applications.created_at', 'DESC')
                ->get()->getResultArray(),
        ];
    }

    // =========================================================================
    // STATUS
    // =========================================================================

    public function updateStatus(int $id, string $status): void
    {
        $this->constructionModel->update($id, ['status' => $status]);
    }

    public function updateSchedule(int $id, array $data): void
    {
        Database::connect()->table('construction_requests')->where('id', $id)->update([
            'start_date' => $data['start_date'] ?: null,
            'week'       => (int)$data['week'] > 0 ? (int)$data['week'] : null,
            'workday'    => (int)$data['workday'] > 0 ? (int)$data['workday'] : null,
        ]);
    }

    // =========================================================================
    // RAB
    // =========================================================================

    public function saveRabRow(array $data): array
    {
        $db   = Database::connect();
        $id   = $data['id'] ?? null;
        $vol  = (float)($data['volume'] ?? 0);
        $price = (float)($data['price'] ?? 0);

        $row = [
            'construction_id'    => $data['construction_id'],
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
            $db->table('construction_rabs')->insert($row);
            $id = $db->insertID();
        } else {
            $check = $db->table('construction_rabs')->where('id', $id)->get()->getRowArray();
            if ($check && $check['is_locked'] == 1) {
                throw new RuntimeException('Baris sudah dikunci!');
            }
            $db->table('construction_rabs')->where('id', $id)->update($row);
        }

        return ['id' => $id];
    }

    public function lockRab(int $constructionId): void
    {
        $db = Database::connect();
        $db->table('construction_rabs')->where('construction_id', $constructionId)->update(['is_locked' => 1]);
        $this->constructionModel->update($constructionId, ['status' => 'Construction']);
    }

    public function unlockRab(int $constructionId): void
    {
        Database::connect()->table('construction_rabs')->where('construction_id', $constructionId)->update(['is_locked' => 0]);
    }

    public function deleteRabRow(int $id): void
    {
        $db    = Database::connect();
        $check = $db->table('construction_rabs')->where('id', $id)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            throw new RuntimeException('Data terkunci!');
        }
        $db->table('construction_rabs')->where('id', $id)->delete();
        $db->table('construction_rab_materials')->where('rab_id', $id)->delete();
    }

    public function getRabMaterials(int $rabId): array
    {
        return Database::connect()->table('construction_rab_materials')
            ->select('construction_rab_materials.*, products.name as material_name, products.price')
            ->join('products', 'products.id = construction_rab_materials.product_id')
            ->where('rab_id', $rabId)->get()->getResultArray();
    }

    public function addRabMaterial(int $rabId, int $productId): void
    {
        $db    = Database::connect();
        $check = $db->table('construction_rabs')->where('id', $rabId)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            throw new RuntimeException('RAB Terkunci!');
        }
        $db->table('construction_rab_materials')->insert(['rab_id' => $rabId, 'product_id' => $productId]);
    }

    public function deleteRabMaterial(int $id): void
    {
        Database::connect()->table('construction_rab_materials')->where('id', $id)->delete();
    }

    public function getRabApiData(int $constructionId): array
    {
        $db  = Database::connect();
        $raw = $db->table('construction_rabs')
            ->where('construction_id', $constructionId)->orderBy('roman_number', 'ASC')->orderBy('id', 'ASC')
            ->get()->getResultArray();

        foreach ($raw as &$item) {
            $item['item_name']     = $item['activity_name'];
            $item['current_price'] = $item['current_unit_price'];
            $item['materials']     = $db->table('construction_rab_materials')
                ->select('products.name as material_name, products.price, products.description')
                ->join('products', 'products.id = construction_rab_materials.product_id')
                ->where('rab_id', $item['id'])->get()->getResultArray();
        }

        return $raw;
    }

    // =========================================================================
    // ADDENDUM
    // =========================================================================

    public function saveAddendumRow(array $data): array
    {
        $db    = Database::connect();
        $id    = $data['id'] ?? null;
        $vol   = (float)($data['volume'] ?? 0);
        $price = (float)($data['price'] ?? 0);

        $row = [
            'construction_id'    => $data['construction_id'],
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
            $db->table('construction_addendum')->insert($row);
            $id = $db->insertID();
        } else {
            $check = $db->table('construction_addendum')->where('id', $id)->get()->getRowArray();
            if ($check && $check['is_locked'] == 1) {
                throw new RuntimeException('Baris sudah dikunci!');
            }
            $db->table('construction_addendum')->where('id', $id)->update($row);
        }

        return ['id' => $id];
    }

    public function lockAddendum(int $constructionId): void
    {
        Database::connect()->table('construction_addendum')->where('construction_id', $constructionId)->update(['is_locked' => 1]);
    }

    public function unlockAddendum(int $constructionId): void
    {
        Database::connect()->table('construction_addendum')->where('construction_id', $constructionId)->update(['is_locked' => 0]);
    }

    public function deleteAddendumRow(int $id): void
    {
        $db    = Database::connect();
        $check = $db->table('construction_addendum')->where('id', $id)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            throw new RuntimeException('Data terkunci!');
        }
        $db->table('construction_addendum')->where('id', $id)->delete();
        $db->table('construction_addendum_materials')->where('addendum_id', $id)->delete();
    }

    public function getAddendumMaterials(int $addendumId): array
    {
        return Database::connect()->table('construction_addendum_materials')
            ->select('construction_addendum_materials.*, products.name as material_name, products.price')
            ->join('products', 'products.id = construction_addendum_materials.product_id')
            ->where('addendum_id', $addendumId)->get()->getResultArray();
    }

    public function addAddendumMaterial(int $addendumId, int $productId): void
    {
        $db    = Database::connect();
        $check = $db->table('construction_addendum')->where('id', $addendumId)->get()->getRowArray();
        if ($check && $check['is_locked'] == 1) {
            throw new RuntimeException('Addendum Terkunci!');
        }
        $db->table('construction_addendum_materials')->insert(['addendum_id' => $addendumId, 'product_id' => $productId]);
    }

    public function deleteAddendumMaterial(int $id): void
    {
        Database::connect()->table('construction_addendum_materials')->where('id', $id)->delete();
    }

    // =========================================================================
    // TARGET
    // =========================================================================

    public function addTarget(array $data): void
    {
        Database::connect()->table('construction_targets')->insert([
            'construction_id' => $data['construction_id'],
            'target_name'     => $data['target_name'],
            'start_date'      => $data['start_date'],
            'end_date'        => $data['end_date'],
            'description'     => $data['description'],
            'status'          => 'Pending',
        ]);
    }

    public function updateTargetStatus(int $id, string $status): void
    {
        Database::connect()->table('construction_targets')->where('id', $id)->update(['status' => $status]);
    }

    public function deleteTarget(int $id): void
    {
        Database::connect()->table('construction_targets')->where('id', $id)->delete();
    }

    /**
     * Upsert target berdasarkan RAB/Addendum (insert jika belum ada, update jika sudah).
     */
    public function createOrUpdateTarget(int $projectId, array $data): string
    {
        $db          = Database::connect();
        $rabId       = $data['rab_id'] ?? null;
        $addendumId  = $data['addendum_id'] ?? null;

        $row = [
            'id_job_applications' => $data['id_job_applications'],
            'start_week'          => $data['start_week'],
            'end_week'            => $data['end_week'],
            'bobot'               => $data['bobot'],
        ];

        $builder = $db->table('construction_targets')->where('construction_id', $projectId);
        if ($addendumId) {
            $builder->where('id_construction_addendum', $addendumId);
        } else {
            $builder->where('id_construction_rabs', $rabId);
        }
        $existing = $builder->get()->getRowArray();

        if ($existing) {
            $db->table('construction_targets')->where('id', $existing['id'])->update($row);
            return 'Target diperbarui!';
        }

        $row['construction_id'] = $projectId;
        if ($addendumId) {
            $row['id_construction_addendum'] = $addendumId;
        } else {
            $row['id_construction_rabs'] = $rabId;
        }
        $db->table('construction_targets')->insert($row);
        return 'Target ditambahkan!';
    }

    // =========================================================================
    // INVOICE
    // =========================================================================

    public function createInvoice(array $data): void
    {
        $db             = Database::connect();
        $constructionId = (int)$data['construction_id'];
        $description    = trim($data['description']);

        $project = $db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();
        if (!$project || !isset($project['user_id'])) {
            throw new RuntimeException('Proyek tidak ditemukan.');
        }

        $existing = $db->table('construction_invoices')
            ->where('construction_id', $constructionId)
            ->where('LOWER(description)', strtolower($description))
            ->countAllResults();

        if ($existing > 0) {
            throw new RuntimeException('Tagihan untuk pekerjaan "' . $description . '" sudah pernah dibuat.');
        }

        $db->table('construction_invoices')->insert([
            'construction_id' => $constructionId,
            'user_id'         => $project['user_id'],
            'description'     => $description,
            'amount'          => (int)$data['amount'],
            'due_date'        => $data['due_date'] ?: null,
            'status'          => 'UNPAID',
        ]);
    }

    public function deleteInvoice(int $id): void
    {
        Database::connect()->table('construction_invoices')->where('id', $id)->delete();
    }

    // =========================================================================
    // SURVEY
    // =========================================================================

    public function uploadSurvey(int $constructionId, array $data, $file): void
    {
        $fileName = '';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(FCPATH . self::PATH_SURVEY, $fileName);
        }
        Database::connect()->table('construction_surveys')->insert([
            'construction_id' => $constructionId,
            'survey_title'    => $data['survey_title'],
            'survey_notes'    => $data['survey_notes'] ?? null,
            'survey_file'     => $fileName,
        ]);
    }

    public function deleteSurvey(int $id): void
    {
        Database::connect()->table('construction_surveys')->where('id', $id)->delete();
    }

    // =========================================================================
    // DESIGN
    // =========================================================================

    public function uploadDesign(int $constructionId, string $title, $file): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('Gagal upload.');
        }
        $newName = $file->getRandomName();
        $file->move(FCPATH . self::PATH_DESIGN, $newName);
        Database::connect()->table('construction_designs')->insert([
            'construction_id' => $constructionId,
            'title'           => $title,
            'file'            => $newName,
        ]);
    }

    public function deleteDesign(int $id): void
    {
        Database::connect()->table('construction_designs')->where('id', $id)->delete();
    }

    // =========================================================================
    // PROGRESS
    // =========================================================================

    public function addProgress(array $data, $file): void
    {
        $row = [
            'construction_id' => $data['construction_id'],
            'week_number'     => $data['week_number'],
            'percentage'      => $data['percentage'],
            'description'     => $data['description'],
        ];
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . self::PATH_PROGRESS, $newName);
            $row['photo_url'] = $newName;
        }
        Database::connect()->table('construction_progress')->insert($row);
    }

    public function deleteProgress(int $id): void
    {
        Database::connect()->table('construction_progress')->where('id', $id)->delete();
    }

    /**
     * Update status progress. Jika APPROVED, hitung apakah target/proyek selesai.
     *
     * @throws RuntimeException
     */
    public function updateProgressStatus(int $id, string $status): int
    {
        $db       = Database::connect();
        $progress = $db->table('construction_progress')->where('id', $id)->get()->getRowArray();
        if (!$progress) {
            throw new RuntimeException('Progress tidak ditemukan!');
        }

        $constructionId = (int)$progress['construction_id'];
        $db->table('construction_progress')->where('id', $id)->update(['status' => strtoupper($status)]);

        if (strtoupper($status) === 'APPROVED') {
            $targetId = $progress['id_construction_targets'];
            $target   = $db->table('construction_targets')->where('id', $targetId)->get()->getRowArray();

            if ($target) {
                $totalProgress = $db->table('construction_progress')
                    ->selectSum('bobot')->where('id_construction_targets', $targetId)->where('status', 'APPROVED')
                    ->get()->getRowArray();
                $totalAcc = (float)($totalProgress['bobot'] ?? 0);

                if (round($totalAcc, 2) >= round((float)$target['bobot'], 2)) {
                    $db->table('construction_targets')->where('id', $targetId)->update(['status' => 'Achieved']);
                }

                $allApproved = $db->table('construction_progress')
                    ->selectSum('bobot')->where('construction_id', $constructionId)->where('status', 'APPROVED')
                    ->get()->getRowArray();

                if (round((float)($allApproved['bobot'] ?? 0), 2) >= 100.00) {
                    $db->table('construction_requests')->where('id', $constructionId)->update(['status' => 'COMPLETED']);
                }
            }
        }

        return $constructionId;
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
        $db             = Database::connect();
        $constructionId = (int)$data['id'];
        $request        = $db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();

        $row = [
            'construction_id'  => $constructionId,
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

        $builder = $db->table('construction_jobs');
        if ($builder->where('construction_id', $constructionId)->get()->getRow()) {
            $builder->where('construction_id', $constructionId)->update($row);
        } else {
            $row['created_at'] = date('Y-m-d H:i:s');
            $builder->insert($row);
        }
    }
}
