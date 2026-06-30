<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnreadByClientCountToConversations extends Migration
{
    public function up()
    {
        $this->forge->addColumn('conversations', [
            'unread_by_client_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
                'after'      => 'unread_by_admin_count'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('conversations', 'unread_by_client_count');
    }
}
