<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminBalanceTables extends Migration
{
    public function up()
    {
        // 1. Create admin_balance table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'balance' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('admin_balance');

        // Seed initial row for admin_balance
        $db = \Config\Database::connect();
        $db->table('admin_balance')->insert([
            'id'         => 1,
            'balance'    => 0.00,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // 2. Create admin_transactions table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['income', 'expense'],
            ],
            'source' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'reference_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('admin_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('admin_transactions', true);
        $this->forge->dropTable('admin_balance', true);
    }
}
