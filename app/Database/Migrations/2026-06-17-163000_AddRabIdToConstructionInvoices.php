<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRabIdToConstructionInvoices extends Migration
{
    public function up()
    {
        // 1. Hapus tagihan yang lama sesuai instruksi user
        $this->db->query("DELETE FROM construction_invoices");

        // 2. Tambah kolom rab_id
        if (!$this->db->fieldExists('rab_id', 'construction_invoices')) {
            $fields = [
                'rab_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'after'      => 'construction_id'
                ]
            ];
            $this->forge->addColumn('construction_invoices', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('rab_id', 'construction_invoices')) {
            $this->forge->dropColumn('construction_invoices', 'rab_id');
        }
    }
}
