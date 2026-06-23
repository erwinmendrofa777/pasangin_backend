<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeConstructionIdNullableInRabs extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('rabs') && $this->db->fieldExists('construction_id', 'rabs')) {
            $fields = [
                'construction_id' => [
                    'name'       => 'construction_id',
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'default'    => null,
                ],
            ];
            $this->forge->modifyColumn('rabs', $fields);
        }
    }

    public function down(): void
    {
        if ($this->db->tableExists('rabs') && $this->db->fieldExists('construction_id', 'rabs')) {
            $fields = [
                'construction_id' => [
                    'name'       => 'construction_id',
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => false,
                ],
            ];
            $this->forge->modifyColumn('rabs', $fields);
        }
    }
}
