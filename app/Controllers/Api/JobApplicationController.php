<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Modules\Tukang\Models\TukangModel;

class JobApplicationController extends ResourceController
{
    protected $format = 'json';

    public function submit()
    {
        // Mendapatkan data JSON yang dikirim dari Flutter
        $data = $this->request->getJSON(true);

        $tukangId       = $data['tukang_id'] ?? null;
        $projectType    = $data['project_type'] ?? null;
        $jobId          = $data['construction_job_id'] ?? $data['job_id'] ?? null;

        $db = \Config\Database::connect();

        // =====================================================================
        // VALIDASI KUALIFIKASI SKILL (khusus lowongan konstruksi)
        // =====================================================================
        if ($projectType === 'construction' && $jobId && $tukangId) {
            // Ambil daftar skill yang dibutuhkan lowongan
            $requiredSkills = $db->table('construction_job_skills')
                ->select('tukang_skill_id')
                ->where('construction_job_id', $jobId)
                ->get()->getResultArray();

            // Jika lowongan punya persyaratan skill, wajib cocok minimal 1
            if (!empty($requiredSkills)) {
                $requiredSkillIds = array_column($requiredSkills, 'tukang_skill_id');

                // Cek apakah tukang memiliki minimal 1 skill yang cocok
                $matchCount = $db->table('tukang_skill_map')
                    ->where('tukang_id', $tukangId)
                    ->whereIn('tukang_skill_id', $requiredSkillIds)
                    ->countAllResults();

                if ($matchCount === 0) {
                    // Ambil nama-nama skill yang dibutuhkan untuk pesan error informatif
                    $skillNames = $db->table('construction_job_skills cjs')
                        ->select('ts.skill_name')
                        ->join('tukang_skill ts', 'ts.id = cjs.tukang_skill_id')
                        ->where('cjs.construction_job_id', $jobId)
                        ->get()->getResultArray();

                    $requiredNames = implode(', ', array_column($skillNames, 'skill_name'));

                    return $this->failForbidden(
                        "Anda tidak memenuhi kualifikasi skill untuk lowongan ini. " .
                        "Dibutuhkan minimal salah satu dari: {$requiredNames}."
                    );
                }
            }
        }

        // =====================================================================
        // SIMPAN LAMARAN PEKERJAAN
        // =====================================================================
        try {
            $db->table('job_applications')->insert([
                'tukang_id'           => $tukangId,
                'project_id'          => $data['project_id'] ?? null,
                'project_type'        => $projectType,
                'construction_job_id' => $jobId,
                'tukang_name'         => $data['name'],
                'email'               => $data['email'],
                'phone'               => $data['phone'],
                'dob'                 => $data['dob'],
                'address'             => $data['address'],
                'status'              => 'Berkas Diproses',
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s')
            ]);

            // Kirim notifikasi ke Admin berdasarkan tipe proyek
            $notifService = new \App\Modules\Notifications\Services\NotificationService();
            $permission       = ($projectType === 'construction') ? 'construction_lowongan' : 'renovation_lowongan';
            $projectTypeLabel = ($projectType === 'construction') ? 'Konstruksi' : 'Renovasi';

            $notifService->sendToPermission(
                $permission,
                'Pelamar Proyek Baru',
                "Tukang bernama {$data['name']} telah melamar pada proyek {$projectTypeLabel}. Silakan cek detail pelamarnya."
            );

            return $this->respondCreated([
                'status'  => true,
                'message' => 'Permohonan kesiapan kerja berhasil dikirim!'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}