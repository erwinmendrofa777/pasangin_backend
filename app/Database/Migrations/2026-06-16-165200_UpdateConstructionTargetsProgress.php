<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateConstructionTargetsProgress extends Migration
{
    public function up()
    {
        // 1. Drop column 'bobot' from 'construction_targets'
        if ($this->db->fieldExists('bobot', 'construction_targets')) {
            $this->forge->dropColumn('construction_targets', 'bobot');
        }

        // 2. Drop column 'bobot' from 'construction_progress' and add 'volume'
        if ($this->db->fieldExists('bobot', 'construction_progress')) {
            $this->forge->dropColumn('construction_progress', 'bobot');
        }
        
        if (!$this->db->fieldExists('volume', 'construction_progress')) {
            $fields = [
                'volume' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'default'    => 0.00,
                    'after'      => 'week_number'
                ]
            ];
            $this->forge->addColumn('construction_progress', $fields);
        }
    }

    public function down()
    {
        // Add 'bobot' back to 'construction_targets'
        if (!$this->db->fieldExists('bobot', 'construction_targets')) {
            $this->forge->addColumn('construction_targets', [
                'bobot' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,2',
                    'default'    => 0.00,
                    'after'      => 'end_week'
                ]
            ]);
        }

        // Add 'bobot' back to 'construction_progress' and drop 'volume'
        if ($this->db->fieldExists('volume', 'construction_progress')) {
            $this->forge->dropColumn('construction_progress', 'volume');
        }

        if (!$this->db->fieldExists('bobot', 'construction_progress')) {
            $this->forge->addColumn('construction_progress', [
                'bobot' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '5,2',
                    'default'    => 0.00,
                    'after'      => 'week_number'
                ]
            ]);
        }
    }
}
