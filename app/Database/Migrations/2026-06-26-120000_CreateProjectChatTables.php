<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectChatTables extends Migration
{
    public function up()
    {
        // 1. Buat tabel project_conversations
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'project_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'project_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'client_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'admin_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['open', 'closed'],
                'default'    => 'open',
                'null'       => false,
            ],
            'last_message_preview' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'last_message_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'last_message_sender_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'last_message_sender_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'unread_by_admin_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
            ],
            'unread_by_client_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
                'null'       => false,
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
        $this->forge->addKey(['project_type', 'project_id']);
        $this->forge->addKey('client_id');
        $this->forge->addKey('admin_id');
        $this->forge->createTable('project_conversations');

        // 2. Buat tabel project_messages
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'project_conversation_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'sender_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'sender_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'file_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_size' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'message_type' => [
                'type'       => 'ENUM',
                'constraint' => ['text', 'image', 'video', 'file', 'location', 'audio'],
                'default'    => 'text',
                'null'       => false,
            ],
            'latitude' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
            'longitude' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
            'is_read_by_admin' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'is_read_by_client' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
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
        $this->forge->addKey('project_conversation_id');
        $this->forge->addKey('created_at');
        $this->forge->createTable('project_messages');

        // Tambahkan foreign keys secara manual dengan query pembantu
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `project_conversations` ADD CONSTRAINT `fk_project_conversations_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE");
        $db->query("ALTER TABLE `project_conversations` ADD CONSTRAINT `fk_project_conversations_admin` FOREIGN KEY (`admin_id`) REFERENCES `user_admin` (`id`) ON DELETE SET NULL");
        $db->query("ALTER TABLE `project_messages` ADD CONSTRAINT `fk_project_messages_conversation` FOREIGN KEY (`project_conversation_id`) REFERENCES `project_conversations` (`id`) ON DELETE CASCADE");
    }

    public function down()
    {
        $db = \Config\Database::connect();

        // Hapus foreign keys
        try {
            $db->query("ALTER TABLE `project_messages` DROP FOREIGN KEY `fk_project_messages_conversation`");
        } catch (\Exception $e) {}
        try {
            $db->query("ALTER TABLE `project_conversations` DROP FOREIGN KEY `fk_project_conversations_client`");
        } catch (\Exception $e) {}
        try {
            $db->query("ALTER TABLE `project_conversations` DROP FOREIGN KEY `fk_project_conversations_admin`");
        } catch (\Exception $e) {}

        // Drop tabel
        $this->forge->dropTable('project_messages', true);
        $this->forge->dropTable('project_conversations', true);
    }
}
