<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameConstructionRabTables extends Migration
{
    public function up(): void
    {
        // 1. Rename tabel construction_rabs menjadi rabs jika ada
        if ($this->db->tableExists('construction_rabs')) {
            $this->forge->renameTable('construction_rabs', 'rabs');
        }

        // 2. Rename tabel construction_rab_materials menjadi rab_materials jika ada
        if ($this->db->tableExists('construction_rab_materials')) {
            $this->forge->renameTable('construction_rab_materials', 'rab_materials');
        }

        // 3. Tambah kolom design_request_id ke tabel rabs jika belum ada
        if ($this->db->tableExists('rabs') && !$this->db->fieldExists('design_request_id', 'rabs')) {
            $this->forge->addColumn('rabs', [
                'design_request_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'construction_id',
                ],
            ]);
        }
    }

    public function down(): void
    {
        // Kembalikan nama kolom dan tabel jika rollback (down) dipanggil
        if ($this->db->tableExists('rabs') && $this->db->fieldExists('design_request_id', 'rabs')) {
            $this->forge->dropColumn('rabs', 'design_request_id');
        }

        if ($this->db->tableExists('rabs')) {
            $this->forge->renameTable('rabs', 'construction_rabs');
        }

        if ($this->db->tableExists('rab_materials')) {
            $this->forge->renameTable('rab_materials', 'construction_rab_materials');
        }
    }
}
