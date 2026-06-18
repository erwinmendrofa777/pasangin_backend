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

        $db = \Config\Database::connect();
        try {
            $db->table('job_applications')->insert([
                'tukang_id' => $data['tukang_id'] ?? null,    // ID Tukang yang melamar
                'project_id' => $data['project_id'] ?? null,   // ID Proyek yang dilamar
                'project_type' => $data['project_type'] ?? null, // 'construction' atau 'renovation'
                'construction_job_id' => $data['construction_job_id'] ?? $data['job_id'] ?? null, // Link ke lowongan target
                'tukang_name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'dob' => $data['dob'],
                'address' => $data['address'],
                'specialization' => $data['specialization'],
                // Set status awal sesuai permintaan  
                'status' => 'Berkas Diproses',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Kirim notifikasi ke Admin berdasarkan tipe proyek
            $notifService = new \App\Modules\Notifications\Services\NotificationService();
            $permission = ($data['project_type'] === 'construction') ? 'construction_lowongan' : 'renovation_lowongan';
            $projectTypeLabel = ($data['project_type'] === 'construction') ? 'Konstruksi' : 'Renovasi';

            $notifService->sendToPermission(
                $permission,
                'Pelamar Proyek Baru',
                "Tukang bernama {$data['name']} telah melamar pada proyek {$projectTypeLabel}. Silakan cek detail pelamarnya."
            );

            return $this->respondCreated([
                'status' => true,
                'message' => 'Permohonan kesiapan kerja berhasil dikirim  !'
            ]);
        } catch (\Exception $e) {
            // Memberikan pesan eror jika gagal simpan
            return $this->failServerError('Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}