<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeliveryDetailsAndCleanStatuses extends Migration
{
    public function up()
    {
        // 1. Modifikasi tipe ENUM pada kolom status di tabel orders
        // Kita gunakan SQL langsung karena CodeIgniter Forge tidak memiliki cara elegan untuk mengubah ENUM
        $this->db->query("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('UNPAID', 'PAID', 'PROCESSED', 'LOADING', 'SHIPPED', 'ARRIVED', 'COMPLETED', 'CANCELLED') NOT NULL DEFAULT 'UNPAID'");

        // 2. Tambahkan kolom rincian pengiriman mandor & client setelah kolom status
        $fields = [
            'delivery_photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'status'
            ],
            'delivery_notes' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'delivery_photo'
            ],
            'mandor_confirmed_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'delivery_notes'
            ],
            'client_confirmed_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'mandor_confirmed_at'
            ],
        ];

        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        // 1. Kembalikan tipe ENUM kolom status
        $this->db->query("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('PENDING', 'UNPAID', 'PAID', 'SETTLEMENT', 'SHIPPED', 'COMPLETED', 'CANCELLED') NOT NULL DEFAULT 'PENDING'");

        // 2. Hapus kolom-kolom pengiriman
        $this->forge->dropColumn('orders', 'delivery_photo');
        $this->forge->dropColumn('orders', 'delivery_notes');
        $this->forge->dropColumn('orders', 'mandor_confirmed_at');
        $this->forge->dropColumn('orders', 'client_confirmed_at');
    }
}
