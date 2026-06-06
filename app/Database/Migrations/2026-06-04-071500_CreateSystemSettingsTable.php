<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSystemSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'setting_group' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'general',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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
        $this->forge->createTable('system_settings');

        // Seeding initial settings
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        
        $db->table('system_settings')->insertBatch([
            [
                'setting_key'   => 'tax_rate',
                'setting_value' => '11',
                'setting_group' => 'order',
                'description'   => 'Tarif Pajak (PPN) dalam persen (%)',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'setting_key'   => 'app_fee_type',
                'setting_value' => 'flat',
                'setting_group' => 'order',
                'description'   => 'Tipe Biaya Aplikasi (flat / percentage)',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'setting_key'   => 'app_fee_value',
                'setting_value' => '2000',
                'setting_group' => 'order',
                'description'   => 'Nilai Biaya Aplikasi',
                'created_at'    => $now,
                'updated_at'    => $now,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('system_settings', true);
    }
}
