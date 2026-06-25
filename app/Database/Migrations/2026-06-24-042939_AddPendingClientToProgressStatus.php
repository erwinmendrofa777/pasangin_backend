<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPendingClientToProgressStatus extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `construction_progress` MODIFY COLUMN `status` ENUM('PENDING', 'PENDING_CLIENT', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'PENDING'");
        $this->db->query("ALTER TABLE `renovation_progress` MODIFY COLUMN `status` ENUM('PENDING', 'PENDING_CLIENT', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'PENDING'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `construction_progress` MODIFY COLUMN `status` ENUM('PENDING', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'PENDING'");
        $this->db->query("ALTER TABLE `renovation_progress` MODIFY COLUMN `status` ENUM('PENDING', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'PENDING'");
    }
}
