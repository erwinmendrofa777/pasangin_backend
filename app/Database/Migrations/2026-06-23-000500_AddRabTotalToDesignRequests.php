<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRabTotalToDesignRequests extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('design_requests') && !$this->db->fieldExists('rab_total', 'design_requests')) {
            $this->forge->addColumn('design_requests', [
                'rab_total' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => true,
                    'default'    => 0.00,
                    'after'      => 'total_payment',
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->tableExists('design_requests') && $this->db->fieldExists('rab_total', 'design_requests')) {
            $this->forge->dropColumn('design_requests', 'rab_total');
        }
    }
}
