<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTransactionIdToOrdersTable extends Migration
{
    public function up()
    {
        // Samakan collation orders table dengan transactions table
        $this->db->query('ALTER TABLE `orders` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        
        // Drop UNIQUE constraint pada transaction_id jika ada
        $this->db->query('ALTER TABLE `orders` DROP KEY IF EXISTS `transaction_id`');
        
        // Add transaction_id field to orders table if not exists
        $fields = [
            'transaction_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'charset'    => 'utf8mb4',
                'collation'  => 'utf8mb4_unicode_ci',
            ],
        ];

        if (!$this->db->fieldExists('transaction_id', 'orders')) {
            $this->forge->addColumn('orders', $fields);
        }
        
        // Add foreign key constraint
        // transaction_id bukan UNIQUE di orders, bisa multiple orders dengan transaction_id yang sama
        $this->forge->addForeignKey('transaction_id', 'transactions', 'transaction_id', '', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('orders', 'orders_transaction_id_foreign');
        $this->forge->dropColumn('orders', 'transaction_id');
    }
}
