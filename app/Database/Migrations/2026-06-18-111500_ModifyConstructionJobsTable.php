<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyConstructionJobsTable extends Migration
{
    public function up()
    {
        // 1. Hapus kolom tempat_tinggal jika ada
        if ($this->db->fieldExists('tempat_tinggal', 'construction_jobs')) {
            $this->forge->dropColumn('construction_jobs', 'tempat_tinggal');
        }

        // 2. Ganti nama kolom upah_per_hari ke upah jika ada
        if ($this->db->fieldExists('upah_per_hari', 'construction_jobs') && !$this->db->fieldExists('upah', 'construction_jobs')) {
            $fields = [
                'upah_per_hari' => [
                    'name'       => 'upah',
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => true
                ]
            ];
            $this->forge->modifyColumn('construction_jobs', $fields);
        }
    }

    public function down()
    {
        // 1. Kembalikan kolom tempat_tinggal jika belum ada
        if (!$this->db->fieldExists('tempat_tinggal', 'construction_jobs')) {
            $fields = [
                'tempat_tinggal' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => true,
                    'after'      => 'detail_lokasi'
                ]
            ];
            $this->forge->addColumn('construction_jobs', $fields);
        }

        // 2. Kembalikan kolom upah ke upah_per_hari jika ada
        if ($this->db->fieldExists('upah', 'construction_jobs') && !$this->db->fieldExists('upah_per_hari', 'construction_jobs')) {
            $fields = [
                'upah' => [
                    'name'       => 'upah_per_hari',
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => true
                ]
            ];
            $this->forge->modifyColumn('construction_jobs', $fields);
        }
    }
}
