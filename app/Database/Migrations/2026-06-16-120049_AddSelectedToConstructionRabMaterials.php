<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSelectedToConstructionRabMaterials extends Migration
{
    public function up()
    {
        $fields = [
            'selected' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'product_id'
            ]
        ];
        $this->forge->addColumn('construction_rab_materials', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('construction_rab_materials', 'selected');
    }
}
