<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDesignTypeToProjectDesigns extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('design_type', 'project_designs')) {
            $fields = [
                'design_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                    'default'    => 'general',
                    'after'      => 'design_targets_id'
                ]
            ];
            $this->forge->addColumn('project_designs', $fields);

            // Populate data lama berdasarkan ekstensi file
            $db = \Config\Database::connect();
            $designs = $db->table('project_designs')->get()->getResultArray();
            foreach ($designs as $design) {
                $ext = strtolower(pathinfo($design['file'], PATHINFO_EXTENSION));
                $type = 'general';
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $type = 'image';
                } elseif ($ext === 'pdf') {
                    $type = 'pdf';
                } elseif (in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv'])) {
                    $type = 'video';
                } elseif (in_array($ext, ['obj', 'fbx', 'glb', 'gltf', 'dwg', 'rvt'])) {
                    $type = '3d';
                }

                $db->table('project_designs')
                    ->where('id', $design['id'])
                    ->update(['design_type' => $type]);
            }
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('design_type', 'project_designs')) {
            $this->forge->dropColumn('project_designs', 'design_type');
        }
    }
}
