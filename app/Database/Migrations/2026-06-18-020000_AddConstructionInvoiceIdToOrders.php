<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddConstructionInvoiceIdToOrders extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('construction_invoice_id', 'orders')) {
            $fields = [
                'construction_invoice_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'after'      => 'user_id'
                ]
            ];
            $this->forge->addColumn('orders', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('construction_invoice_id', 'orders')) {
            $this->forge->dropColumn('orders', 'construction_invoice_id');
        }
    }
}
