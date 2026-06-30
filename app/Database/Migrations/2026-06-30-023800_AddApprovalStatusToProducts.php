<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApprovalStatusToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'approval_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
                'after'      => 'status',
            ]
        ]);

        // Coba migrasikan data lama agar produk yang sudah ada diubah statusnya menjadi approved
        $db = \Config\Database::connect();
        $db->query("UPDATE `products` SET `approval_status` = 'approved' WHERE `app_category_id` IS NOT NULL");
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'approval_status');
    }
}
