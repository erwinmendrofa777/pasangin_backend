<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameCategoryIdToSupplierCategoryId extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // 1. Coba hapus foreign key lama jika ada
        try {
            $db->query("ALTER TABLE `products` DROP FOREIGN KEY `products_category_id_foreign`");
        } catch (\Exception $e) {
            try {
                $db->query("ALTER TABLE `products` DROP FOREIGN KEY `fk_products_categories`");
            } catch (\Exception $e2) {}
        }

        // 2. Ubah/ganti nama kolom dari category_id menjadi supplier_category_id
        $this->forge->modifyColumn('products', [
            'category_id' => [
                'name'       => 'supplier_category_id',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ]
        ]);

        // 3. Tambahkan foreign key constraint baru yang merujuk ke tabel supplier_categories
        $db->query("ALTER TABLE `products` ADD CONSTRAINT `fk_products_supplier_category` FOREIGN KEY (`supplier_category_id`) REFERENCES `supplier_categories` (`id`) ON DELETE SET NULL");
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        try {
            $db->query("ALTER TABLE `products` DROP FOREIGN KEY `fk_products_supplier_category`");
        } catch (\Exception $e) {}

        $this->forge->modifyColumn('products', [
            'supplier_category_id' => [
                'name'       => 'category_id',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ]
        ]);

        try {
            $db->query("ALTER TABLE `products` ADD CONSTRAINT `fk_products_categories` FOREIGN KEY (`category_id`) REFERENCES `supplier_categories` (`id`) ON DELETE SET NULL");
        } catch (\Exception $e) {}
    }
}
