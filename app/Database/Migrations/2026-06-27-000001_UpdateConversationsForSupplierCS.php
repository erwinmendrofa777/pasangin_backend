<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateConversationsForSupplierCS extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('conversations', [
            'client_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('conversations', [
            'client_type' => [
                'type'       => 'ENUM',
                'constraint' => ['client', 'tukang'],
                'null'       => false,
            ]
        ]);
    }
}
