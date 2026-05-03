<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'transaction_id'   => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'user_id'          => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'total_amount'     => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'status'           => [
                'type'       => 'ENUM',
                'constraint' => ['PENDING', 'PAID', 'FAILED'],
                'default'    => 'PENDING',
            ],
            'payment_method'   => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'MIDTRANS',
            ],
            'order_count'      => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'created_at'       => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at'       => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', '', 'CASCADE');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
