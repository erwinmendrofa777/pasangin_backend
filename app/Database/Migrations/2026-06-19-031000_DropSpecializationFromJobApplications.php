<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropSpecializationFromJobApplications extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('specialization', 'job_applications')) {
            $this->forge->dropColumn('job_applications', 'specialization');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('specialization', 'job_applications')) {
            $this->forge->addColumn('job_applications', [
                'specialization' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'address'
                ]
            ]);
        }
    }
}
