<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class TukangJobApi extends ResourceController
{
    protected $format = 'json';
    protected $db;
    protected $notifService;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notifService = new \App\Modules\Notifications\Services\NotificationService();
    }

    /**
     * 1. Mengambil lowongan pembangunan
     */
    public function getConstructionJobs()
    {
        try {
            $jobs = $this->db->table('construction_jobs')
                ->select('
                    construction_jobs.*, 
                    construction_requests.latitude, 
                    construction_requests.longitude, 
                    construction_requests.address as client_address,
                    COALESCE(ahsp.uraian, ca.activity_name) as detail_pekerjaan
                ')
                ->join('construction_requests', 'construction_requests.id = construction_jobs.construction_id')
                ->join('construction_targets ct', 'ct.id = construction_jobs.construction_target_id', 'left')
                ->join('rabs cr', 'cr.id = ct.id_construction_rabs', 'left')
                ->join('ahsp', 'ahsp.id = cr.ahsp_id', 'left')
                ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum', 'left')
                ->where('construction_jobs.is_open', 1)
                ->orderBy('construction_jobs.created_at', 'DESC')
                ->get()
                ->getResultArray();

            if (!empty($jobs)) {
                $jobIds = array_column($jobs, 'id');
                $jobSkills = $this->db->table('construction_job_skills cjs')
                    ->select('cjs.construction_job_id, ts.id as skill_id, ts.skill_name')
                    ->join('tukang_skill ts', 'ts.id = cjs.tukang_skill_id')
                    ->whereIn('cjs.construction_job_id', $jobIds)
                    ->get()->getResultArray();

                $skillsByJob = [];
                foreach ($jobSkills as $js) {
                    $skillsByJob[$js['construction_job_id']][] = [
                        'id' => (int) $js['skill_id'],
                        'skill_name' => $js['skill_name']
                    ];
                }

                foreach ($jobs as &$job) {
                    $job['skills'] = $skillsByJob[$job['id']] ?? [];
                }
            }

            return $this->respond(['status' => true, 'data' => $jobs], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 2. Mengambil lowongan renovasi
     */
    public function getRenovationJobs()
    {
        try {
            $jobs = $this->db->table('renovation_jobs')
                ->select('renovation_jobs.*, renovation_requests.latitude, renovation_requests.longitude, renovation_requests.address as client_address')
                ->join('renovation_requests', 'renovation_requests.id = renovation_jobs.renovation_id')
                ->orderBy('renovation_jobs.created_at', 'DESC')
                ->get()
                ->getResultArray();

            return $this->respond(['status' => true, 'data' => $jobs], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 3. Mengambil status pendaftaran akun (DARI TABEL TUKANG)
     * GET: api/tukang/application-status/{tukang_id}
     */
    public function getApplicationStatus($tukangId = null)
    {
        if ($tukangId === null)
            return $this->fail('ID Tukang tidak ditemukan.', 400);

        try {
            // PERBAIKAN: Ambil status dari tabel tukang  
            $status = $this->db->table('tukang')
                ->select('status')
                ->where('id', $tukangId)
                ->get()
                ->getRowArray();

            return $this->respond(['status' => true, 'data' => $status], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 4. Riwayat Lamaran
     */
    public function getMyApplications($tukangId = null)
    {
        if ($tukangId === null)
            return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $data = $this->db->query("
                SELECT ja.*, 
                       IF(ja.project_type = 'construction', COALESCE(ahsp.uraian, ca.activity_name), rj.detail_pekerjaan) as project_name
                FROM job_applications ja
                LEFT JOIN construction_jobs cj ON (cj.id = ja.construction_job_id OR (ja.construction_job_id IS NULL AND cj.construction_id = ja.project_id))
                LEFT JOIN construction_targets ct ON ct.id = cj.construction_target_id
                LEFT JOIN rabs cr ON cr.id = ct.id_construction_rabs
                LEFT JOIN ahsp ON ahsp.id = cr.ahsp_id
                LEFT JOIN construction_addendum ca ON ca.id = ct.id_construction_addendum
                LEFT JOIN renovation_jobs rj ON ja.project_id = rj.renovation_id AND ja.project_type = 'renovation'
                WHERE ja.tukang_id = ? ORDER BY ja.id DESC
            ", [$tukangId])->getResultArray();

            return $this->respond(['status' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 5. Target Proyek
     */
    /**
     * 5a. Target Proyek Konstruksi
     * GET: api/tukang/my-targets/construction/{tukangId}
     */
    public function getMyConstructionTargets($tukangId = null)
    {
        if ($tukangId === null)
            return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $sql = "
                SELECT 
                    COALESCE(crab.group_name, ca.group_name) as group_name,
                    COALESCE(crab.sub_group_name, ca.sub_group_name) as sub_group_name,
                    COALESCE(ahsp.uraian, ca.activity_name) as activity_name,
                    creq.id as construction_id,
                    creq.workday as hari_kerja,
                    ct.id as target_id,
                    ct.start_week,
                    ct.end_week,
                    COALESCE(crab.volume, ca.volume) as volume,
                    ct.status as target_status,
                    crab.unit as satuan_volume,
                    creq.status as construction_status,
                    'construction' as project_type,
                    creq.start_date,
                    (SELECT COUNT(id) FROM construction_progress WHERE id_construction_targets = ct.id) as report_count,
                    (SELECT status FROM construction_progress WHERE id_construction_targets = ct.id ORDER BY created_at DESC LIMIT 1) as last_report_status,
                    (SELECT COUNT(id) FROM construction_progress WHERE id_construction_targets = ct.id AND status = 'APPROVED') as approved_count,
                    (SELECT COUNT(id) FROM construction_progress WHERE id_construction_targets = ct.id AND status = 'REJECTED') as rejected_count,
                    (SELECT COUNT(id) FROM construction_progress WHERE id_construction_targets = ct.id AND status = 'PENDING') as pending_count,
                    (SELECT SUM(volume) FROM construction_progress WHERE id_construction_targets = ct.id AND status = 'APPROVED') as approved_weight,
                    (SELECT SUM(volume) FROM construction_progress WHERE id_construction_targets = ct.id AND status = 'PENDING') as pending_weight
                FROM construction_targets ct
                JOIN job_applications ja ON ja.id = ct.id_job_applications
                LEFT JOIN rabs crab ON crab.id = ct.id_construction_rabs
                LEFT JOIN ahsp ON ahsp.id = crab.ahsp_id
                LEFT JOIN construction_addendum ca ON ca.id = ct.id_construction_addendum
                JOIN construction_requests creq ON creq.id = ct.construction_id
                WHERE ja.tukang_id IN (
                    SELECT DISTINCT member_id FROM (
                        SELECT ? as member_id
                        UNION
                        SELECT tg.tukang_id FROM tukang_group tg JOIN tukang_group_members tgm ON tgm.tukang_group_id = tg.id WHERE tgm.tukang_id = ? AND tgm.status = 'approved'
                        UNION
                        SELECT tgm.tukang_id FROM tukang_group_members tgm JOIN tukang_group tg ON tg.id = tgm.tukang_group_id WHERE tg.tukang_id = ? AND tgm.status = 'approved'
                        UNION
                        SELECT tgm2.tukang_id FROM tukang_group_members tgm1 JOIN tukang_group_members tgm2 ON tgm2.tukang_group_id = tgm1.tukang_group_id WHERE tgm1.tukang_id = ? AND tgm1.status = 'approved' AND tgm2.status = 'approved'
                    ) as group_members
                )
                ORDER BY ct.start_week ASC
            ";

            $data = $this->db->query($sql, [$tukangId, $tukangId, $tukangId, $tukangId])->getResultArray();

            return $this->respond([
                'status' => true,
                'data' => $this->_formatTargetRows($data)
            ], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 5b. Target Proyek Renovasi
     * GET: api/tukang/my-targets/renovation/{tukangId}
     */
    public function getMyRenovationTargets($tukangId = null)
    {
        if ($tukangId === null)
            return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $sql = "
                SELECT 
                    rrab.group_name,
                    rrab.sub_group_name,
                    rrab.activity_name,
                    rreq.id as renovation_id,
                    rreq.workday as hari_kerja,
                    rt.id as target_id,
                    rt.start_week,
                    rt.end_week,
                    rt.bobot,
                    rt.status as target_status,
                    rreq.status as renovation_status,
                    'renovation' as project_type,
                    rreq.start_date,
                    (SELECT COUNT(id) FROM renovation_progress WHERE id_renovation_targets = rt.id) as report_count,
                    (SELECT status FROM renovation_progress WHERE id_renovation_targets = rt.id ORDER BY created_at DESC LIMIT 1) as last_report_status,
                    (SELECT COUNT(id) FROM renovation_progress WHERE id_renovation_targets = rt.id AND LOWER(status) = 'approved') as approved_count,
                    (SELECT COUNT(id) FROM renovation_progress WHERE id_renovation_targets = rt.id AND LOWER(status) = 'rejected') as rejected_count,
                    (SELECT COUNT(id) FROM renovation_progress WHERE id_renovation_targets = rt.id AND LOWER(status) = 'pending') as pending_count,
                    (SELECT SUM(bobot) FROM renovation_progress WHERE id_renovation_targets = rt.id AND LOWER(status) = 'approved') as approved_weight,
                    (SELECT SUM(bobot) FROM renovation_progress WHERE id_renovation_targets = rt.id AND LOWER(status) = 'pending') as pending_weight
                FROM renovation_targets rt
                JOIN job_applications ja ON ja.id = rt.id_job_applications
                JOIN renovation_rabs rrab ON rrab.id = rt.id_renovation_rabs
                JOIN renovation_requests rreq ON rreq.id = rt.renovation_id
                WHERE ja.tukang_id IN (
                    SELECT DISTINCT member_id FROM (
                        SELECT ? as member_id
                        UNION
                        SELECT tg.tukang_id FROM tukang_group tg JOIN tukang_group_members tgm ON tgm.tukang_group_id = tg.id WHERE tgm.tukang_id = ? AND tgm.status = 'approved'
                        UNION
                        SELECT tgm.tukang_id FROM tukang_group_members tgm JOIN tukang_group tg ON tg.id = tgm.tukang_group_id WHERE tg.tukang_id = ? AND tgm.status = 'approved'
                        UNION
                        SELECT tgm2.tukang_id FROM tukang_group_members tgm1 JOIN tukang_group_members tgm2 ON tgm2.tukang_group_id = tgm1.tukang_group_id WHERE tgm1.tukang_id = ? AND tgm1.status = 'approved' AND tgm2.status = 'approved'
                    ) as group_members
                )
                ORDER BY rt.start_week ASC
            ";

            $data = $this->db->query($sql, [$tukangId, $tukangId, $tukangId, $tukangId])->getResultArray();

            return $this->respond([
                'status' => true,
                'data' => $this->_formatTargetRows($data)
            ], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Helper: format & kalkulasi field pada setiap baris target
     */
    private function _formatTargetRows(array $rows): array
    {
        $today = new \DateTime();
        foreach ($rows as &$row) {
            $row['current_project_week'] = 0;
            if (!empty($row['start_date'])) {
                $start = new \DateTime($row['start_date']);
                if ($today >= $start) {
                    $diffDays = $today->diff($start)->days;
                    $row['current_project_week'] = (int) floor($diffDays / 7) + 1;
                }
            }
            unset($row['start_date']);

            $row['report_count'] = (int) ($row['report_count'] ?? 0);
            $row['approved_count'] = (int) ($row['approved_count'] ?? 0);
            $row['rejected_count'] = (int) ($row['rejected_count'] ?? 0);
            $row['pending_count'] = (int) ($row['pending_count'] ?? 0);
            $row['approved_weight'] = (float) ($row['approved_weight'] ?? 0);
            $row['pending_weight'] = (float) ($row['pending_weight'] ?? 0);

            if (!empty($row['last_report_status'])) {
                $row['last_report_status'] = strtoupper($row['last_report_status']);
            }
        }
        return $rows;
    }

    /**
     * Helper: mendapatkan semua ID tukang dalam satu group (anggota + mandor)
     */
    private function _getGroupTukangIds($tukangId): array
    {
        $sql = "
            SELECT DISTINCT member_id FROM (
                SELECT ? as member_id
                UNION
                SELECT tg.tukang_id FROM tukang_group tg JOIN tukang_group_members tgm ON tgm.tukang_group_id = tg.id WHERE tgm.tukang_id = ? AND tgm.status = 'approved'
                UNION
                SELECT tgm.tukang_id FROM tukang_group_members tgm JOIN tukang_group tg ON tg.id = tgm.tukang_group_id WHERE tg.tukang_id = ? AND tgm.status = 'approved'
                UNION
                SELECT tgm2.tukang_id FROM tukang_group_members tgm1 JOIN tukang_group_members tgm2 ON tgm2.tukang_group_id = tgm1.tukang_group_id WHERE tgm1.tukang_id = ? AND tgm1.status = 'approved' AND tgm2.status = 'approved'
            ) as group_members
        ";
        $rows = $this->db->query($sql, [$tukangId, $tukangId, $tukangId, $tukangId])->getResultArray();
        return array_column($rows, 'member_id');
    }

    /**
     * 6. Proyek Aktif
     */
    public function getActiveProjects($tukangId = null)
    {
        if ($tukangId === null)
            return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $data = $this->db->query("
                SELECT ja.project_id, 
                       COALESCE(ahsp.uraian, ca.activity_name) as project_name, 
                       req.address as client_address,
                       (SELECT percentage FROM construction_progress WHERE construction_id = ja.project_id ORDER BY id DESC LIMIT 1) as last_percentage
                FROM job_applications ja
                LEFT JOIN construction_jobs cj ON (cj.id = ja.construction_job_id OR (ja.construction_job_id IS NULL AND cj.construction_id = ja.project_id))
                LEFT JOIN construction_targets ct ON ct.id = cj.construction_target_id
                LEFT JOIN rabs rab ON rab.id = ct.id_construction_rabs
                LEFT JOIN ahsp ON ahsp.id = rab.ahsp_id
                LEFT JOIN construction_addendum ca ON ca.id = ct.id_construction_addendum
                JOIN construction_requests req ON ja.project_id = req.id
                WHERE ja.tukang_id = ? AND ja.status = 'Siap Kerja' AND ja.project_type = 'construction'
            ", [$tukangId])->getResultArray();

            return $this->respond(['status' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 7. Submit Progress
     */
    public function submitProgress()
    {
        try {
            $file = $this->request->getFile('photo');
            $constructionId = $this->request->getPost('construction_id');

            $newName = '';
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/construction/progress', $newName);
            }

            $this->db->table('construction_progress')->insert([
                'construction_id' => $constructionId,
                'week_number' => $this->request->getPost('week_number') ?? 1,
                'percentage' => $this->request->getPost('percentage') ?? 0,
                'description' => $this->request->getPost('description'),
                'photo_url' => $newName,
            ]);

            // Kirim notifikasi  
            $project = $this->db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();
            if ($project && !empty($project['user_id'])) {
                $title = "Update Progres Konstruksi";
                $message = "Tukang mengirim progres untuk " . ($project['project_name'] ?? "Proyek #{$constructionId}") . ".";

                $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);

                // Admin juga butuh notif  
                $this->notifService->sendToPermission('construction_progress', $title, $message);
            }

            return $this->respondCreated(['status' => true, 'message' => 'Laporan progress berhasil dikirim  !']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 8. GET Progress
     */
    public function getConstructionProgress($tukangId = null)
    {
        if ($tukangId === null)
            return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $constructionId = $this->request->getVar('construction_id');
            $groupTukangIds = $this->_getGroupTukangIds($tukangId);

            // 1. Cari proyek aktif untuk tukang ini
            $jaBuilder = $this->db->table('job_applications ja')
                ->select('
                    ja.project_id as construction_id, 
                    ja.tukang_id, 
                    COALESCE(ahsp.uraian, ca.activity_name) as project_name, 
                    req.id, 
                    req.address, 
                    req.start_date as project_start_date
                ')
                ->join('construction_jobs cj', 'cj.id = ja.construction_job_id', 'left')
                ->join('construction_targets ct', 'ct.id = cj.construction_target_id', 'left')
                ->join('rabs rab', 'rab.id = ct.id_construction_rabs', 'left')
                ->join('ahsp', 'ahsp.id = rab.ahsp_id', 'left')
                ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum', 'left')
                ->join('construction_requests req', 'req.id = ja.project_id', 'left')
                ->whereIn('ja.tukang_id', $groupTukangIds)
                ->where('ja.project_type', 'construction')
                ->orderBy('ja.id', 'DESC');

            if ($constructionId) {
                $jaBuilder->where('ja.project_id', $constructionId);
            } else {
                $jaBuilder->where('ja.status', 'Siap Kerja');
            }

            $project = $jaBuilder->get()->getRowArray();

            if (!$project) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Belum ada proyek aktif',
                    'data' => null
                ], 200);
            }

            $currentConstructionId = $project['construction_id'];

            // 2. Kalkulasi minggu proyek berjalan
            $current_project_week = 1;
            if (!empty($project['project_start_date'])) {
                $start = new \DateTime($project['project_start_date']);
                $today = new \DateTime();
                if ($today >= $start) {
                    $diffDays = $today->diff($start)->days;
                    $current_project_week = floor($diffDays / 7) + 1;
                } else {
                    $current_project_week = 0;
                }
            }

            // 3. Ambil data progress (difilter berdasarkan tukang_id)
            $progressData = $this->db->table('construction_progress p')
                ->select('p.*')
                ->join('construction_targets t', 't.id = p.id_construction_targets')
                ->join('job_applications ja', 'ja.id = t.id_job_applications')
                ->whereIn('ja.tukang_id', $groupTukangIds)
                ->where('t.construction_id', $currentConstructionId)
                ->orderBy('p.created_at', 'DESC')
                ->get()
                ->getResultArray();

            $progressByTarget = [];
            foreach ($progressData as $p) {
                $tId = $p['id_construction_targets'];
                $progressByTarget[$tId][] = $p;
            }

            // 4. Ambil data target (difilter berdasarkan tukang_id)
            $targetsRaw = $this->db->table('construction_targets t')
                ->select('t.id, COALESCE(ahsp.uraian, ca.activity_name) as target_name, t.start_week as startweek, t.end_week as endweek, COALESCE(r.volume, ca.volume) as volume, COALESCE(r.unit, ca.unit) as unit, t.status')
                ->join('rabs r', 'r.id = t.id_construction_rabs', 'left')
                ->join('ahsp', 'ahsp.id = r.ahsp_id', 'left')
                ->join('construction_addendum ca', 'ca.id = t.id_construction_addendum', 'left')
                ->join('job_applications ja', 'ja.id = t.id_job_applications')
                ->whereIn('ja.tukang_id', $groupTukangIds)
                ->where('t.construction_id', $currentConstructionId)
                ->get()
                ->getResultArray();

            $formattedTargets = [];
            foreach ($targetsRaw as $t) {
                $targetId = $t['id'];
                $is_late = $current_project_week > $t['endweek'];

                $approved_volume = 0;
                $pending_volume = 0;
                $pending_client_volume = 0;

                $last_report_status = null;
                $last_report_date = null;

                $report_count = 0;
                $approved_count = 0;
                $rejected_count = 0;
                $pending_count = 0;
                $pending_client_count = 0;

                if (isset($progressByTarget[$targetId])) {
                    $pList = $progressByTarget[$targetId];
                    $report_count = count($pList);

                    // Laporan terakhir adalah item pertama karena query diurutkan DESC
                    $last_report_status = strtoupper($pList[0]['status'] ?? 'PENDING');
                    $last_report_date = $pList[0]['created_at'] ?? null;

                    foreach ($pList as $p) {
                        $pStatus = strtolower($p['status'] ?? 'pending');
                        if ($pStatus === 'approved') {
                            $approved_volume += (float) $p['volume'];
                            $approved_count++;
                        } elseif ($pStatus === 'pending') {
                            $pending_volume += (float) $p['volume'];
                            $pending_count++;
                        } elseif ($pStatus === 'pending_client') {
                            $pending_client_volume += (float) $p['volume'];
                            $pending_client_count++;
                        } elseif ($pStatus === 'rejected') {
                            $rejected_count++;
                        }
                    }
                }

                $statusFormatted = strtoupper($t['status'] ?? 'NOT_STARTED');

                $formattedTargets[] = [
                    'id' => $targetId,
                    'target_name' => $t['target_name'],
                    'startweek' => (int) $t['startweek'],
                    'endweek' => (int) $t['endweek'],
                    'volume' => (float) $t['volume'],
                    'unit' => $t['unit'],
                    'description' => $t['target_name'], // Placeholder deskripsi disamakan dengan target_name
                    'status' => $statusFormatted,
                    'is_late' => $is_late,
                    'approved_volume' => $approved_volume,
                    'pending_volume' => $pending_volume,
                    'pending_client_volume' => $pending_client_volume,
                    'last_report_status' => $last_report_status,
                    'last_report_date' => $last_report_date,
                    'report_count' => $report_count,
                    'approved_count' => $approved_count,
                    'rejected_count' => $rejected_count,
                    'pending_count' => $pending_count,
                    'pending_client_count' => $pending_client_count
                ];
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Berhasil mengambil data target proyek',
                'data' => [
                    'project_id' => (int) $currentConstructionId,
                    'project_name' => 'Proyek ' . $project['id'],
                    'project_address' => $project['address'],
                    'project_start_date' => $project['project_start_date'],
                    'current_project_date' => date('Y-m-d'),
                    'current_project_week' => $current_project_week,
                    'targets' => $formattedTargets
                ]
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 9. POST Progress
     */
    public function createConstructionProgress()
    {
        try {
            $file = $this->request->getFile('photo');
            $newName = '';
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/construction/progress', $newName);
            }

            $constructionId = $this->request->getPost('construction_id');
            $weekNumber = $this->request->getPost('week_number');

            // Hitung week_number secara otomatis berdasarkan start_date proyek jika tidak dikirim dari POST
            if (empty($weekNumber) && !empty($constructionId)) {
                $project = $this->db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();
                if ($project && !empty($project['start_date'])) {
                    $start = new \DateTime($project['start_date']);
                    $today = new \DateTime();
                    if ($today >= $start) {
                        $diffDays = $today->diff($start)->days;
                        $weekNumber = floor($diffDays / 7) + 1;
                    } else {
                        $weekNumber = 1;
                    }
                } else {
                    $weekNumber = 1;
                }
            }

            $this->db->table('construction_progress')->insert([
                'id_construction_targets' => $this->request->getPost('id_construction_targets'),
                'construction_id' => $constructionId,
                'week_number' => $weekNumber ? (int) $weekNumber : null,
                'volume' => $this->request->getPost('volume') ?? $this->request->getPost('bobot') ?? 0,
                'description' => $this->request->getPost('description'),
                'status' => 'pending',
                'photo_url' => $newName,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated(['status' => true, 'message' => 'Laporan progress berhasil dikirim']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function getRenovationProgress($tukangId = null)
    {
        if ($tukangId === null)
            return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $renovationId = $this->request->getVar('renovation_id');
            $groupTukangIds = $this->_getGroupTukangIds($tukangId);

            // 1. Cari proyek aktif untuk tukang ini
            $jaBuilder = $this->db->table('job_applications ja')
                ->select('ja.project_id as renovation_id, ja.tukang_id, rj.detail_pekerjaan as project_name, rr.id, rr.address, rr.start_date as project_start_date')
                ->join('renovation_jobs rj', 'rj.renovation_id = ja.project_id', 'left')
                ->join('renovation_requests rr', 'rr.id = ja.project_id', 'left')
                ->whereIn('ja.tukang_id', $groupTukangIds)
                ->where('ja.project_type', 'renovation')
                ->orderBy('ja.id', 'DESC');

            if ($renovationId) {
                $jaBuilder->where('ja.project_id', $renovationId);
            } else {
                $jaBuilder->where('ja.status', 'Siap Kerja');
            }

            $project = $jaBuilder->get()->getRowArray();

            if (!$project) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Belum ada proyek aktif',
                    'data' => null
                ], 200);
            }

            $currentRenovationId = $project['renovation_id'];

            // 2. Kalkulasi minggu proyek berjalan
            $current_project_week = 1;
            if (!empty($project['project_start_date'])) {
                $start = new \DateTime($project['project_start_date']);
                $today = new \DateTime();
                if ($today >= $start) {
                    $diffDays = $today->diff($start)->days;
                    $current_project_week = floor($diffDays / 7) + 1;
                } else {
                    $current_project_week = 0;
                }
            }

            // 3. Ambil data progress (difilter berdasarkan tukang_id)
            $progressData = $this->db->table('renovation_progress p')
                ->select('p.*')
                ->join('renovation_targets t', 't.id = p.id_renovation_targets')
                ->join('job_applications ja', 'ja.id = t.id_job_applications')
                ->whereIn('ja.tukang_id', $groupTukangIds)
                ->where('t.renovation_id', $currentRenovationId)
                ->orderBy('p.created_at', 'DESC')
                ->get()
                ->getResultArray();

            $progressByTarget = [];
            foreach ($progressData as $p) {
                $tId = $p['id_renovation_targets'];
                $progressByTarget[$tId][] = $p;
            }

            // 4. Ambil data target (difilter berdasarkan tukang_id)
            $targetsRaw = $this->db->table('renovation_targets t')
                ->select('t.id, r.activity_name as target_name, t.start_week as startweek, t.end_week as endweek, t.bobot as weight, t.status')
                ->join('renovation_rabs r', 'r.id = t.id_renovation_rabs', 'left')
                ->join('job_applications ja', 'ja.id = t.id_job_applications')
                ->whereIn('ja.tukang_id', $groupTukangIds)
                ->where('t.renovation_id', $currentRenovationId)
                ->get()
                ->getResultArray();

            $formattedTargets = [];
            foreach ($targetsRaw as $t) {
                $targetId = $t['id'];
                $is_late = $current_project_week > $t['endweek'];

                $approved_weight = 0;
                $pending_weight = 0;
                $pending_client_weight = 0;

                $last_report_status = null;
                $last_report_date = null;

                $report_count = 0;
                $approved_count = 0;
                $rejected_count = 0;
                $pending_count = 0;
                $pending_client_count = 0;

                if (isset($progressByTarget[$targetId])) {
                    $pList = $progressByTarget[$targetId];
                    $report_count = count($pList);

                    // Laporan terakhir adalah item pertama karena query diurutkan DESC
                    $last_report_status = strtoupper($pList[0]['status'] ?? 'PENDING');
                    $last_report_date = $pList[0]['created_at'] ?? null;

                    foreach ($pList as $p) {
                        $pStatus = strtolower($p['status'] ?? 'pending');
                        if ($pStatus === 'approved') {
                            $approved_weight += (float) $p['bobot'];
                            $approved_count++;
                        } elseif ($pStatus === 'pending') {
                            $pending_weight += (float) $p['bobot'];
                            $pending_count++;
                        } elseif ($pStatus === 'pending_client') {
                            $pending_client_weight += (float) $p['bobot'];
                            $pending_client_count++;
                        } elseif ($pStatus === 'rejected') {
                            $rejected_count++;
                        }
                    }
                }

                $statusFormatted = strtoupper($t['status'] ?? 'NOT_STARTED');

                $formattedTargets[] = [
                    'id' => $targetId,
                    'target_name' => $t['target_name'],
                    'startweek' => (int) $t['startweek'],
                    'endweek' => (int) $t['endweek'],
                    'weight' => (float) $t['weight'],
                    'description' => $t['target_name'], // Placeholder deskripsi disamakan dengan target_name
                    'status' => $statusFormatted,
                    'is_late' => $is_late,
                    'approved_weight' => $approved_weight,
                    'pending_weight' => $pending_weight,
                    'pending_client_weight' => $pending_client_weight,
                    'last_report_status' => $last_report_status,
                    'last_report_date' => $last_report_date,
                    'report_count' => $report_count,
                    'approved_count' => $approved_count,
                    'rejected_count' => $rejected_count,
                    'pending_count' => $pending_count,
                    'pending_client_count' => $pending_client_count
                ];
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Berhasil mengambil data target proyek',
                'data' => [
                    'project_id' => (int) $currentRenovationId,
                    'project_name' => 'Proyek ' . $project['id'],
                    'project_address' => $project['address'],
                    'project_start_date' => $project['project_start_date'],
                    'current_project_date' => date('Y-m-d'),
                    'current_project_week' => $current_project_week,
                    'targets' => $formattedTargets
                ]
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function createRenovationProgress()
    {
        try {
            $file = $this->request->getFile('photo');
            $newName = '';
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/renovation/progress', $newName);
            }

            $renovationId = $this->request->getPost('renovation_id');
            $weekNumber = $this->request->getPost('week_number');

            // Hitung week_number secara otomatis berdasarkan start_date proyek jika tidak dikirim dari POST
            if (empty($weekNumber) && !empty($renovationId)) {
                $project = $this->db->table('renovation_requests')->where('id', $renovationId)->get()->getRowArray();
                if ($project && !empty($project['start_date'])) {
                    $start = new \DateTime($project['start_date']);
                    $today = new \DateTime();
                    if ($today >= $start) {
                        $diffDays = $today->diff($start)->days;
                        $weekNumber = floor($diffDays / 7) + 1;
                    } else {
                        $weekNumber = 1;
                    }
                } else {
                    $weekNumber = 1;
                }
            }

            $this->db->table('renovation_progress')->insert([
                'id_renovation_targets' => $this->request->getPost('id_renovation_targets'),
                'renovation_id' => $renovationId,
                'week_number' => $weekNumber ? (int) $weekNumber : null,
                'bobot' => $this->request->getPost('bobot') ?? 0,
                'description' => $this->request->getPost('description'),
                'status' => 'pending',
                'photo_url' => $newName,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Kirim notifikasi  
            $project = $this->db->table('renovation_requests')->where('id', $renovationId)->get()->getRowArray();
            if ($project && !empty($project['user_id'])) {
                $title = "Update Progres Renovasi";
                $message = "Laporan progres untuk proyek " . ($project['renovation_type'] ?? "Renovasi #{$renovationId}") . " telah dikirim oleh Tukang.";

                $this->notifService->sendPersonal('client', (int) $project['user_id'], $title, $message);

                // Admin juga butuh notif  
                $this->notifService->sendToPermission('renovation_progress', $title, $message);
            }

            return $this->respondCreated(['status' => true, 'message' => 'Laporan progress berhasil dikirim']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 10. GET Project List for Attendance
     */
    public function getProjectListForAttendance($tukangId = null)
    {
        if ($tukangId === null)
            return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $today = date('Y-m-d');

            $builder = $this->db->table('job_applications ja')
                ->select([
                    'cr.id',
                    'cr.address as alamat',
                    'cr.latitude',
                    'cr.longitude',
                    'cr.land_area as radius_meter'
                ])
                ->select("'construction' as tipe_proyek", false)
                ->select("(SELECT COALESCE(SUM(total_price), 0) FROM rabs WHERE construction_id = cr.id) as total_rab_price", false)
                ->select("(SELECT COALESCE(SUM(total_price), 0) FROM construction_addendum WHERE construction_id = cr.id) as total_addendum_price", false)
                ->select("(SELECT COALESCE(SUM(cp.volume * r.current_unit_price), 0) FROM construction_progress cp JOIN construction_targets ct ON ct.id = cp.id_construction_targets JOIN rabs r ON r.id = ct.id_construction_rabs WHERE ct.construction_id = cr.id AND LOWER(cp.status) = 'approved') as realisasi_rab_price", false)
                ->select("(SELECT COALESCE(SUM(cp.volume * a.current_unit_price), 0) FROM construction_progress cp JOIN construction_targets ct ON ct.id = cp.id_construction_targets JOIN construction_addendum a ON a.id = ct.id_construction_addendum WHERE ct.construction_id = cr.id AND LOWER(cp.status) = 'approved') as realisasi_addendum_price", false)
                ->select("(SELECT COUNT(id) FROM construction_attendance WHERE id_construction = cr.id AND DATE(waktu) = '{$today}' AND type = 'masuk') as absen_masuk_count", false)
                ->select("(SELECT COUNT(id) FROM construction_attendance WHERE id_construction = cr.id AND DATE(waktu) = '{$today}' AND type = 'keluar') as absen_keluar_count", false)
                ->join('construction_requests cr', 'cr.id = ja.project_id')
                ->where('ja.tukang_id', $tukangId)
                ->where('ja.status', 'Siap Kerja')
                ->where('ja.project_type', 'construction');

            $data = $builder->get()->getResultArray();

            $formattedData = [];
            foreach ($data as $row) {
                $realisasiRabPrice = (float) $row['realisasi_rab_price'];
                $realisasiAddPrice = (float) $row['realisasi_addendum_price'];

                $totalRealisasiPrice = $realisasiRabPrice + $realisasiAddPrice;
                $totalBudget = (float) $row['total_rab_price'] + (float) $row['total_addendum_price'];

                $progressPersen = $totalBudget > 0 ? ($totalRealisasiPrice / $totalBudget) * 100 : 0;

                $masukCount = (int) $row['absen_masuk_count'];
                $keluarCount = (int) $row['absen_keluar_count'];

                if ($masukCount == 0 && $keluarCount == 0) {
                    $statusAbsen = 'belumAbsen';
                } elseif ($masukCount > 0 && $keluarCount == 0) {
                    $statusAbsen = 'sudahAbsenMasuk';
                } elseif ($masukCount > 0 && $keluarCount > 0) {
                    $statusAbsen = 'sudahAbsenKeluar';
                } else {
                    $statusAbsen = 'belumAbsen';
                }

                $formattedData[] = [
                    'id' => (string) $row['id'],
                    'nama' => 'Proyek #' . $row['id'],
                    'alamat' => $row['alamat'],
                    'progress_persen' => round($progressPersen, 2),
                    'status_absen' => $statusAbsen,
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'radius_meter' => (float) $row['radius_meter'] ?: 150.0,
                    'tipe_proyek' => $row['tipe_proyek']
                ];
            }

            return $this->respond([
                'status' => true,
                'message' => 'Berhasil mengambil data proyek',
                'data' => $formattedData
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function getRenovationListForAttendance($tukangId = null)
    {
        if ($tukangId === null) {
            return $this->fail('ID Tukang dibutuhkan', 400);
        }


        try {
            $today = date('Y-m-d');

            $builder = $this->db->table('job_applications ja')
                ->select([
                    'rr.id',
                    'rr.address as alamat',
                    'rr.latitude',
                    'rr.longitude'
                ])
                ->select("150 as radius_meter", false)
                ->select("'renovation' as tipe_proyek", false)
                ->select("(SELECT COALESCE(SUM(rp.bobot), 0) FROM renovation_progress rp JOIN renovation_targets rt ON rt.id = rp.id_renovation_targets WHERE rt.renovation_id = rr.id AND LOWER(rp.status) = 'approved') as progress_persen", false)
                ->select("(SELECT COUNT(id) FROM renovation_attendance WHERE id_renovation = rr.id AND DATE(waktu) = '{$today}' AND type = 'masuk') as absen_masuk_count", false)
                ->select("(SELECT COUNT(id) FROM renovation_attendance WHERE id_renovation = rr.id AND DATE(waktu) = '{$today}' AND type = 'keluar') as absen_keluar_count", false)
                ->join('renovation_requests rr', 'rr.id = ja.project_id')
                ->where('ja.tukang_id', $tukangId)
                ->where('ja.status', 'Siap Kerja')
                ->where('ja.project_type', 'renovation');

            $data = $builder->get()->getResultArray();

            $formattedData = [];
            foreach ($data as $row) {
                $progressPersen = (float) $row['progress_persen'];
                $masukCount = (int) $row['absen_masuk_count'];
                $keluarCount = (int) $row['absen_keluar_count'];

                // Tentukan status absen berdasarkan jumlah absen hari ini
                if ($masukCount == 0 && $keluarCount == 0) {
                    $statusAbsen = 'belumAbsen';
                } elseif ($masukCount > 0 && $keluarCount == 0) {
                    $statusAbsen = 'sudahAbsenMasuk';
                } elseif ($masukCount > 0 && $keluarCount > 0) {
                    $statusAbsen = 'sudahAbsenKeluar';
                } else {
                    $statusAbsen = 'belumAbsen';
                }

                $formattedData[] = [
                    'id' => (string) $row['id'],
                    'nama' => 'Renovasi #' . $row['id'],
                    'alamat' => $row['alamat'],
                    'progress_persen' => round($progressPersen, 2),
                    'status_absen' => $statusAbsen,
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'radius_meter' => (float) $row['radius_meter'] ?: 200.0,
                    'tipe_proyek' => $row['tipe_proyek']
                ];
            }

            return $this->respond([
                'status' => true,
                'message' => 'Berhasil mengambil data proyek renovasi',
                'data' => $formattedData
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}