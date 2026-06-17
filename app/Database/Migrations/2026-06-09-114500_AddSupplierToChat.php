<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierToChat extends Migration
{
    public function up()
    {
        // 1. Tambah kolom ke tabel conversations
        $this->forge->addColumn('conversations', [
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'admin_id'
            ],
            'unread_by_supplier_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
                'after'      => 'unread_by_admin_count'
            ],
            'unread_by_client_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
                'after'      => 'unread_by_supplier_count'
            ]
        ]);

        // 2. Tambah kolom ke tabel messages
        $this->forge->addColumn('messages', [
            'is_read_by_supplier' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'is_read_by_client'
            ]
        ]);
    }

    public function down()
    {
        // 1. Drop kolom dari tabel conversations
        $this->forge->dropColumn('conversations', [
            'supplier_id',
            'unread_by_supplier_count',
            'unread_by_client_count'
        ]);

        // 2. Drop kolom dari tabel messages
        $this->forge->dropColumn('messages', [
            'is_read_by_supplier'
        ]);
    }
}
