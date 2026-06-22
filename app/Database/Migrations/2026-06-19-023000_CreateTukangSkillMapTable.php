<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTukangSkillMapTable extends Migration
{
    public function up()
    {
        // 1. Buat tabel junction tukang_skill_map
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tukang_id' => [
                'type'       => 'INT',
                'constraint' => 11,
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
        $this->forge->addForeignKey('tukang_id', 'tukang', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tukang_skill_id', 'tukang_skill', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tukang_skill_map');

        // 2. Migrasi data lama dari kolom specialization
        $db = \Config\Database::connect();
        
        // Pastikan tabel master tukang_skill ada sebelum memulai pemindahan data
        if ($db->tableExists('tukang') && $db->tableExists('tukang_skill')) {
            $tukangs = $db->table('tukang')->select('id, specialization')->get()->getResultArray();

            foreach ($tukangs as $t) {
                $specialization = $t['specialization'] ?? '';
                if (empty(trim($specialization))) {
                    continue;
                }

                // Pecah teks dengan koma, garis tegak, atau garis miring
                $names = preg_split('/[,|\/]/', $specialization);
                foreach ($names as $name) {
                    $name = trim($name);
                    if ($name === '') {
                        continue;
                    }

                    // Cari apakah nama skill sudah terdaftar di master tukang_skill
                    $skill = $db->table('tukang_skill')->where('skill_name', $name)->get()->getRowArray();
                    if ($skill) {
                        $skillId = (int) $skill['id'];
                    } else {
                        // Masukkan ke master jika belum ada
                        $db->table('tukang_skill')->insert([
                            'skill_name' => $name,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        $skillId = (int) $db->insertID();
                    }

                    // Periksa duplikasi relasi sebelum disimpan
                    $existingRelation = $db->table('tukang_skill_map')
                        ->where('tukang_id', $t['id'])
                        ->where('tukang_skill_id', $skillId)
                        ->get()
                        ->getRowArray();

                    if (!$existingRelation) {
                        $db->table('tukang_skill_map')->insert([
                            'tukang_id'       => $t['id'],
                            'tukang_skill_id' => $skillId,
                            'created_at'      => date('Y-m-d H:i:s'),
                            'updated_at'      => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
        }

        // 3. Hapus kolom specialization dari tabel tukang secara permanen
        $this->forge->dropColumn('tukang', 'specialization');
    }

    public function down()
    {
        // Kembalikan kolom specialization jika di-rollback
        $this->forge->addColumn('tukang', [
            'specialization' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'selfie_photo'
            ]
        ]);

        // Rekonstruksi data specialization dari tabel junction
        $db = \Config\Database::connect();
        if ($db->tableExists('tukang_skill_map')) {
            $relations = $db->table('tukang_skill_map m')
                ->select('m.tukang_id, s.skill_name')
                ->join('tukang_skill s', 's.id = m.tukang_skill_id')
                ->get()
                ->getResultArray();

            $grouped = [];
            foreach ($relations as $r) {
                $grouped[$r['tukang_id']][] = $r['skill_name'];
            }

            foreach ($grouped as $tukangId => $skills) {
                $specializationStr = implode(', ', $skills);
                $db->table('tukang')->where('id', $tukangId)->update([
                    'specialization' => $specializationStr
                ]);
            }
        }

        // Drop tabel junction
        $this->forge->dropTable('tukang_skill_map');
    }
}
