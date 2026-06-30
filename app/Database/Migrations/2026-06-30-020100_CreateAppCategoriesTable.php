<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppCategoriesTable extends Migration
{
    public function up()
    {
        // 1. Buat tabel app_categories (tanpa kolom description)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->createTable('app_categories');

        // 2. Tambah kolom app_category_id ke tabel products
        $this->forge->addColumn('products', [
            'app_category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'supplier_id',
            ]
        ]);

        // Tambah foreign key constraint
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `products` ADD CONSTRAINT `fk_products_app_category` FOREIGN KEY (`app_category_id`) REFERENCES `app_categories` (`id`) ON DELETE SET NULL");
    }

    public function down()
    {
        $db = \Config\Database::connect();
        try {
            $db->query("ALTER TABLE `products` DROP FOREIGN KEY `fk_products_app_category`");
        } catch (\Exception $e) {}

        $this->forge->dropColumn('products', 'app_category_id');
        $this->forge->dropTable('app_categories', true);
    }
}
