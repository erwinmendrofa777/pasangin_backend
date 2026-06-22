<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConstructionJobSkillsTable extends Migration
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
            'construction_job_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tukang_skill_id' => [
                'type'       => 'INT',
                'constraint' => 11,
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
        $this->forge->addForeignKey('construction_job_id', 'construction_jobs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tukang_skill_id', 'tukang_skill', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('construction_job_skills');
    }

    public function down()
    {
        $this->forge->dropTable('construction_job_skills');
    }
}
