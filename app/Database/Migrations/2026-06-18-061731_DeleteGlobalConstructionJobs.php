<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DeleteGlobalConstructionJobs extends Migration
{
    public function up()
    {
        // 1. Hapus data lowongan lama yang tidak memiliki construction_target_id
        $this->db->table('construction_jobs')
            ->groupStart()
                ->where('construction_target_id', null)
                ->orWhere('construction_target_id', 0)
                ->orWhere('construction_target_id', '')
            ->groupEnd()
            ->delete();

        // 2. Pastikan kolom construction_target_id ada di tabel construction_jobs
        /** @var \CodeIgniter\Database\BaseConnection $conn */
        $conn = $this->forge->getConnection();
        if (!$conn->fieldExists('construction_target_id', 'construction_jobs')) {
            $this->forge->addColumn('construction_jobs', [
                'construction_target_id' => [
                    'type'       => 'INT',
                    'null'       => true,
                    'after'      => 'construction_id'
                ]
            ]);
        }
    }

    public function down()
    {
        // Down migration is no-op to protect new data structure
    }
}
