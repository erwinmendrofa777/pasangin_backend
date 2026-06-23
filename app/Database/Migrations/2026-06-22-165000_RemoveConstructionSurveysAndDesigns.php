<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveConstructionSurveysAndDesigns extends Migration
{
    public function up(): void
    {
        // 1. Hapus tabel construction_surveys & construction_designs jika ada
        $this->forge->dropTable('construction_surveys', true);
        $this->forge->dropTable('construction_designs', true);

        // 2. Tambah kolom design_request_id ke tabel construction_requests jika belum ada
        if (!$this->db->fieldExists('design_request_id', 'construction_requests')) {
            $this->forge->addColumn('construction_requests', [
                'design_request_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'user_id',
                ],
            ]);
        }
    }

    public function down(): void
    {
        // Tidak perlu implementasi down yang kompleks karena ini menghapus tabel permanen,
        // namun untuk kelengkapan kita bisa biarkan kosong.
    }
}
