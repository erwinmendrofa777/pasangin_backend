<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAhspBahanIdToConstructionRabMaterials extends Migration
{
    public function up()
    {
        $fields = [
            'ahsp_bahan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'rab_id'
            ]
        ];
        $this->forge->addColumn('construction_rab_materials', $fields);

        // Add foreign key constraint
        $this->db->query("ALTER TABLE construction_rab_materials ADD CONSTRAINT fk_construction_rab_materials_ahsp_bahan FOREIGN KEY (ahsp_bahan_id) REFERENCES ahsp_bahan(id) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        try {
            $this->db->query("ALTER TABLE construction_rab_materials DROP FOREIGN KEY fk_construction_rab_materials_ahsp_bahan");
        } catch (\Exception $e) {}
        $this->forge->dropColumn('construction_rab_materials', 'ahsp_bahan_id');
    }
}
