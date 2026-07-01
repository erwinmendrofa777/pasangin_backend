<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupplierReferralAndLinkSales extends Migration
{
    public function up()
    {
        // 1. Tambah kolom sales_id ke tabel suppliers
        $this->forge->addColumn('suppliers', [
            'sales_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ]
        ]);

        // Tambah foreign key constraint ke user_admin
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `suppliers` ADD CONSTRAINT `fk_suppliers_sales_id` FOREIGN KEY (`sales_id`) REFERENCES `user_admin` (`id`) ON DELETE SET NULL");

        // 2. Buat tabel supplier_referral_codes
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'is_used' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('supplier_referral_codes');

        // Tambah foreign key constraint ke suppliers
        $db->query("ALTER TABLE `supplier_referral_codes` ADD CONSTRAINT `fk_referral_codes_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE");
    }

    public function down()
    {
        $db = \Config\Database::connect();
        try {
            $db->query("ALTER TABLE `suppliers` DROP FOREIGN KEY `fk_suppliers_sales_id`");
        } catch (\Exception $e) {}

        $this->forge->dropColumn('suppliers', 'sales_id');
        $this->forge->dropTable('supplier_referral_codes', true);
    }
}
