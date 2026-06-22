<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDesignTargetIdToProjectInvoices extends Migration
{
    public function up(): void
    {
        // Tambah kolom design_target_id (nullable, karena tagihan manual tidak punya target)
        $this->forge->addColumn('project_invoices', [
            'design_target_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'design_request_id',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('project_invoices', 'design_target_id');
    }
}
