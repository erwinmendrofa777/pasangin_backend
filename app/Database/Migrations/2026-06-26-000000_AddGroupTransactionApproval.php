<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGroupTransactionApproval extends Migration
{
    public function up()
    {
        // 1. Tambahkan kolom status dan distributions_data ke tabel group_transactions
        $this->forge->addColumn('group_transactions', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'approved',
                'after'      => 'type',
                'null'       => false,
            ],
            'distributions_data' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'description',
            ]
        ]);

        // 2. Buat tabel group_transaction_approvals
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'group_transaction_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tukang_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'vote' => [
                'type'       => 'ENUM',
                'constraint' => ['approved', 'rejected'],
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['group_transaction_id', 'tukang_id']);
        
        $this->forge->createTable('group_transaction_approvals');

        // Menambahkan foreign key constraint manual menggunakan query pembantu
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `group_transaction_approvals` ADD CONSTRAINT `fk_approvals_group_transaction` FOREIGN KEY (`group_transaction_id`) REFERENCES `group_transactions` (`id`) ON DELETE CASCADE");
        $db->query("ALTER TABLE `group_transaction_approvals` ADD CONSTRAINT `fk_approvals_tukang` FOREIGN KEY (`tukang_id`) REFERENCES `tukang` (`id`) ON DELETE CASCADE");
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        // Hapus foreign key constraints
        try {
            $db->query("ALTER TABLE `group_transaction_approvals` DROP FOREIGN KEY `fk_approvals_group_transaction`");
        } catch (\Exception $e) {}
        try {
            $db->query("ALTER TABLE `group_transaction_approvals` DROP FOREIGN KEY `fk_approvals_tukang`");
        } catch (\Exception $e) {}

        // Drop tabel group_transaction_approvals
        $this->forge->dropTable('group_transaction_approvals', true);

        // Drop kolom status dan distributions_data dari group_transactions
        $this->forge->dropColumn('group_transactions', ['status', 'distributions_data']);
    }
}
