<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameCategoriesToSupplierCategories extends Migration
{
    public function up()
    {
        // Ganti nama tabel categories menjadi supplier_categories
        $this->forge->renameTable('categories', 'supplier_categories');
    }

    public function down()
    {
        $this->forge->renameTable('supplier_categories', 'categories');
    }
}
