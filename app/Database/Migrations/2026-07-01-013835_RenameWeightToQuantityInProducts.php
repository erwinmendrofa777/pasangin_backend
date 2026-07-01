<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameWeightToQuantityInProducts extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('products', [
            'weight' => [
                'name'       => 'quantity',
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('products', [
            'quantity' => [
                'name'       => 'weight',
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'default'    => 0,
            ]
        ]);
    }
}

