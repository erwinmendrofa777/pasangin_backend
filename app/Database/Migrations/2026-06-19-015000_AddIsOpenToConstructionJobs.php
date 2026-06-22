<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsOpenToConstructionJobs extends Migration
{
    public function up()
    {
        /** @var \CodeIgniter\Database\BaseConnection $conn */
        $conn = $this->forge->getConnection();
        if (!$conn->fieldExists('is_open', 'construction_jobs')) {
            $this->forge->addColumn('construction_jobs', [
                'is_open' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                    'null'       => false,
                    'after'      => 'upah'
                ]
            ]);
        }
    }

    public function down()
    {
        /** @var \CodeIgniter\Database\BaseConnection $conn */
        $conn = $this->forge->getConnection();
        if ($conn->fieldExists('is_open', 'construction_jobs')) {
            $this->forge->dropColumn('construction_jobs', 'is_open');
        }
    }
}
