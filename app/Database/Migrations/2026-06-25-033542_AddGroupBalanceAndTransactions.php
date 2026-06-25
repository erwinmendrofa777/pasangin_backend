<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGroupBalanceAndTransactions extends Migration
{
    public function up()
    {
        // 1. Tambahkan kolom balance ke tabel tukang_group
        $this->forge->addColumn('tukang_group', [
            'balance' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'after'      => 'referral_code',
                'null'       => false,
            ]
        ]);

        // 2. Buat tabel group_transactions
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'group_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['inflow', 'outflow'],
                'null'       => false,
            ],
            'source_project_type' => [
                'type'       => 'ENUM',
                'constraint' => ['construction', 'renovation'],
                'null'       => true,
            ],
            'source_invoice_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('group_id', 'tukang_group', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('group_transactions');

        // 3. Tambahkan kolom group_transaction_id ke tabel tukang_transactions (wallet pribadi)
        $this->forge->addColumn('tukang_transactions', [
            'group_transaction_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'tukang_id',
            ]
        ]);
        // Menambahkan foreign key constraint manual menggunakan query pembantu
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `tukang_transactions` ADD CONSTRAINT `fk_tukang_transactions_group` FOREIGN KEY (`group_transaction_id`) REFERENCES `group_transactions` (`id`) ON DELETE SET NULL");
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        // Hapus foreign key constraint di tukang_transactions
        try {
            $db->query("ALTER TABLE `tukang_transactions` DROP FOREIGN KEY `fk_tukang_transactions_group`");
        } catch (\Exception $e) {}

        // Drop kolom group_transaction_id di tabel tukang_transactions
        $this->forge->dropColumn('tukang_transactions', 'group_transaction_id');

        // Drop tabel group_transactions
        $this->forge->dropTable('group_transactions', true);

        // Drop kolom balance di tabel tukang_group
        $this->forge->dropColumn('tukang_group', 'balance');
    }
}
