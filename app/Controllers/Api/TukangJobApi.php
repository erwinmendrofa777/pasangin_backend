<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class TukangJobApi extends ResourceController
{
    protected $format = 'json';
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * 1. Mengambil lowongan pembangunan
     */
    public function getConstructionJobs()
    {
        try {
            $jobs = $this->db->table('construction_jobs')
                ->select('construction_jobs.*, construction_requests.latitude, construction_requests.longitude, construction_requests.address as client_address')
                ->join('construction_requests', 'construction_requests.id = construction_jobs.construction_id')
                ->orderBy('construction_jobs.created_at', 'DESC')
                ->get()
                ->getResultArray();

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
        if ($tukangId === null) return $this->fail('ID Tukang tidak ditemukan.', 400);

        try {
            // PERBAIKAN: Ambil status dari tabel tukang kawan
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
        if ($tukangId === null) return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $data = $this->db->query("
                SELECT ja.*, IF(ja.project_type = 'construction', cj.detail_pekerjaan, rj.detail_pekerjaan) as project_name
                FROM job_applications ja
                LEFT JOIN construction_jobs cj ON ja.project_id = cj.construction_id AND ja.project_type = 'construction'
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
    public function getMyTargets($tukangId = null)
    {
        if ($tukangId === null) return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $sql = "
                    (
                        SELECT 
                            ct.target_name,
                            cr.id as construction_id,
                            null as renovation_id,
                            ct.target_date,
                            ct.description,
                            cr.status,
                            ja.project_type
                        FROM construction_targets ct
                        JOIN job_applications ja ON ja.id = ct.id_job_applications
                        JOIN construction_requests cr ON cr.id = ct.construction_id
                        WHERE ja.tukang_id = ?
                    )

                    UNION ALL

                    (
                        SELECT 
                            rt.target_name,
                            null as construction_id,
                            rr.id as renovation_id,
                            rt.target_date,
                            rt.description,
                            rr.status,
                            ja.project_type
                        FROM renovation_targets rt
                        JOIN job_applications ja ON ja.id = rt.id_job_applications
                        JOIN renovation_requests rr ON rr.id = rt.renovation_id
                        WHERE ja.tukang_id = ?
                    )

                    ORDER BY target_date ASC
                    ";
            $data = $this->db->query($sql, [$tukangId, $tukangId])->getResultArray();

            return $this->respond(['status' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 6. Proyek Aktif
     */
    public function getActiveProjects($tukangId = null)
    {
        if ($tukangId === null) return $this->fail('ID Tukang dibutuhkan', 400);

        try {
            $data = $this->db->query("
                SELECT ja.project_id, cj.detail_pekerjaan as project_name, cr.address as client_address,
                (SELECT percentage FROM construction_progress WHERE construction_id = ja.project_id ORDER BY id DESC LIMIT 1) as last_percentage
                FROM job_applications ja
                JOIN construction_jobs cj ON ja.project_id = cj.construction_id
                JOIN construction_requests cr ON ja.project_id = cr.id
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
                'week_number'     => $this->request->getPost('week_number') ?? 1,
                'percentage'      => $this->request->getPost('percentage') ?? 0,
                'description'     => $this->request->getPost('description'),
                'photo_url'       => $newName,
                'created_at'      => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated(['status' => true, 'message' => 'Laporan progress berhasil dikirim kawan!']);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 8. GET Progress
     */
    public function getConstructionProgress($constructionId = null)
    {
        if ($constructionId === null) return $this->fail('ID Proyek dibutuhkan', 400);

        try {
            // GET construction_request basic data
            $construction_request = $this->db->table('construction_requests')
                                             ->where('id', $constructionId)
                                             ->get()
                                             ->getRowArray();

            if (!$construction_request) {
                return $this->failNotFound('Proyek tidak ditemukan');
            }

            // Calculate current_project_week: (hari ini - tanggal mulai) / 7 + 1
            $startDate = $construction_request['start_date'];
            $current_project_week = 1;
            if (!empty($startDate)) {
                $start = new \DateTime($startDate);
                $today = new \DateTime();
                if ($today >= $start) {
                    $diffDays = $today->diff($start)->days;
                    $current_project_week = floor($diffDays / 7) + 1;
                } else {
                    $current_project_week = 0;
                }
            }

            // GET progress data and group by id_construction_targets
            $progressData = $this->db->table('construction_progress')
                 ->where('construction_id', $constructionId)
                 ->orderBy('created_at', 'DESC')
                 ->get()
                 ->getResultArray();

            $progressByTarget = [];
            foreach ($progressData as $p) {
                $tId = $p['id_construction_targets'];
                $progressByTarget[$tId][] = $p;
            }

            // GET construction targets
            $targetsRaw = $this->db->table('construction_targets t')
                ->select('t.id, r.activity_name as target_name, t.start_week as startweek, t.end_week as endweek, t.bobot as weight, t.status')
                ->join('construction_rabs r', 'r.id = t.id_construction_rabs', 'left')
                ->where('t.construction_id', $constructionId)
                ->get()
                ->getResultArray();

            $formattedTargets = [];
            foreach ($targetsRaw as $t) {
                $targetId = $t['id'];
                $is_late = $current_project_week > $t['endweek'];
                
                $approved_weight = 0;
                $pending_weight = 0;
                
                $last_report_status = null;
                $last_report_date = null;

                $report_count = 0;
                $approved_count = 0;
                $rejected_count = 0;
                $pending_count = 0;

                if (isset($progressByTarget[$targetId])) {
                    $pList = $progressByTarget[$targetId];
                    $report_count = count($pList);
                    
                    // Because progressData is ordered by DESC created_at, the first item is the latest report.
                    $last_report_status = strtoupper($pList[0]['status'] ?? 'PENDING');
                    $last_report_date = $pList[0]['created_at'] ?? null;

                    foreach ($pList as $p) {
                        $pStatus = strtolower($p['status'] ?? 'pending');
                        if ($pStatus === 'approved') {
                            $approved_weight += (float)$p['bobot'];
                            $approved_count++;
                        } elseif ($pStatus === 'pending') {
                            $pending_weight += (float)$p['bobot'];
                            $pending_count++;
                        } elseif ($pStatus === 'rejected') {
                            $rejected_count++;
                        }
                    }
                }

                $formattedTargets[] = [
                    'id' => $targetId,
                    'target_name' => $t['target_name'],
                    'startweek' => (int)$t['startweek'],
                    'endweek' => (int)$t['endweek'],
                    'weight' => (float)$t['weight'],
                    'status' => $t['status'],
                    'is_late' => $is_late,
                    'approved_weight' => $approved_weight,
                    'pending_weight' => $pending_weight,
                    'last_report_status' => $last_report_status,
                    'last_report_date' => $last_report_date,
                    'report_count' => $report_count,
                    'approved_count' => $approved_count,
                    'rejected_count' => $rejected_count,
                    'pending_count' => $pending_count
                ];
            }

            return $this->respond([
                'status'  => 'success',
                'message' => 'Berhasil mengambil data target proyek',
                'data'    => array_merge($construction_request, [
                    'current_project_week' => $current_project_week,
                    'targets'              => $formattedTargets
                ])
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    

}