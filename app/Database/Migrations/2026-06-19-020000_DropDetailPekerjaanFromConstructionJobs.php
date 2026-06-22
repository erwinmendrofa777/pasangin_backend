<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropDetailPekerjaanFromConstructionJobs extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('detail_pekerjaan', 'construction_jobs')) {
            $this->forge->dropColumn('construction_jobs', 'detail_pekerjaan');
        }
    }

    public function down()
    {
        $this->forge->addColumn('construction_jobs', [
            'detail_pekerjaan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'construction_target_id'
            ]
        ]);
    }
}
