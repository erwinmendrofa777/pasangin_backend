<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TukangModel;

class JobApplicationController extends ResourceController
{
    protected $format = 'json';

    public function submit()
    {
        // Mendapatkan data JSON yang dikirim dari Flutter
        $data = $this->request->getJSON(true);
        
        $db = \Config\Database::connect();
        try {
            // Memasukkan data ke tabel job_applications secara lengkap kawan
            $db->table('job_applications')->insert([
                'tukang_id'      => $data['tukang_id'] ?? null,    // ID Tukang yang melamar
                'project_id'     => $data['project_id'] ?? null,   // ID Proyek yang dilamar
                'project_type'   => $data['project_type'] ?? null, // 'construction' atau 'renovation'
                'tukang_name'    => $data['name'],
                'email'          => $data['email'],
                'phone'          => $data['phone'],
                'dob'            => $data['dob'],
                'address'        => $data['address'],
                'specialization' => $data['specialization'],
                // Set status awal sesuai permintaan kawan
                'status'         => 'Berkas Diproses', 
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated([
                'status'  => true,
                'message' => 'Permohonan kesiapan kerja berhasil dikirim kawan!'
            ]);
        } catch (\Exception $e) {
            // Memberikan pesan eror jika gagal simpan
            return $this->failServerError('Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}