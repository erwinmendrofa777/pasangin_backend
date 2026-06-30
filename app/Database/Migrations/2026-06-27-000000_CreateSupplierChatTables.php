<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupplierChatTables extends Migration
{
    public function up()
    {
        // 1. Buat tabel supplier_conversations
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'client_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
            'unread_by_supplier_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'unread_by_client_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['open', 'closed'],
                'default'    => 'open',
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
        $this->forge->addKey('client_id');
        $this->forge->addKey('supplier_id');
        $this->forge->createTable('supplier_conversations');

        // 2. Buat tabel supplier_messages
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'supplier_conversation_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sender_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sender_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
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
            'message_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'text',
            ],
            'latitude' => [
                'type'       => 'DOUBLE',
                'null'       => true,
            ],
            'longitude' => [
                'type'       => 'DOUBLE',
                'null'       => true,
            ],
            'is_read_by_client' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_read_by_supplier' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
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
        $this->forge->addKey('supplier_conversation_id');
        $this->forge->createTable('supplier_messages');

        // 3. Salin data supplier chat dari tabel lama ke tabel baru
        $db = \Config\Database::connect();

        // Salin percakapan
        $db->query("
            INSERT INTO supplier_conversations (
                id, client_id, supplier_id, last_message_preview, last_message_at, 
                unread_by_supplier_count, unread_by_client_count, status, created_at, updated_at
            )
            SELECT 
                id, client_id, supplier_id, last_message_preview, last_message_at, 
                unread_by_supplier_count, unread_by_client_count, status, created_at, updated_at
            FROM conversations
            WHERE supplier_id IS NOT NULL
        ");

        // Salin pesan
        $db->query("
            INSERT INTO supplier_messages (
                id, supplier_conversation_id, sender_id, sender_type, body, file_url, 
                message_type, latitude, longitude, is_read_by_client, is_read_by_supplier, created_at, updated_at
            )
            SELECT 
                id, conversation_id, sender_id, sender_type, body, file_url, 
                message_type, latitude, longitude, is_read_by_client, is_read_by_supplier, created_at, updated_at
            FROM messages
            WHERE conversation_id IN (SELECT id FROM conversations WHERE supplier_id IS NOT NULL)
        ");

        // Hapus data supplier chat dari tabel lama
        $db->query("DELETE FROM messages WHERE conversation_id IN (SELECT id FROM conversations WHERE supplier_id IS NOT NULL)");
        $db->query("DELETE FROM conversations WHERE supplier_id IS NOT NULL");

        // 4. Hapus kolom-kolom terkait supplier dari tabel lama
        $this->forge->dropColumn('conversations', [
            'supplier_id',
            'unread_by_supplier_count',
            'unread_by_client_count'
        ]);

        $this->forge->dropColumn('messages', [
            'is_read_by_supplier'
        ]);
    }

    public function down()
    {
        // Kembalikan kolom-kolom ke tabel lama
        $this->forge->addColumn('conversations', [
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'admin_id'
            ],
            'unread_by_supplier_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
                'after'      => 'unread_by_admin_count'
            ],
            'unread_by_client_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
                'after'      => 'unread_by_supplier_count'
            ]
        ]);

        $this->forge->addColumn('messages', [
            'is_read_by_supplier' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'is_read_by_client'
            ]
        ]);

        // Kembalikan data dari supplier_conversations ke conversations
        $db = \Config\Database::connect();
        $db->query("
            INSERT INTO conversations (
                id, client_id, supplier_id, last_message_preview, last_message_at, 
                unread_by_supplier_count, unread_by_client_count, status, created_at, updated_at, client_type, category
            )
            SELECT 
                id, client_id, supplier_id, last_message_preview, last_message_at, 
                unread_by_supplier_count, unread_by_client_count, status, created_at, updated_at, 'client', 'general'
            FROM supplier_conversations
        ");

        $db->query("
            INSERT INTO messages (
                id, conversation_id, sender_id, sender_type, body, file_url, 
                message_type, latitude, longitude, is_read_by_client, is_read_by_supplier, created_at, updated_at
            )
            SELECT 
                id, supplier_conversation_id, sender_id, sender_type, body, file_url, 
                message_type, latitude, longitude, is_read_by_client, is_read_by_supplier, created_at, updated_at
            FROM supplier_messages
        ");

        // Hapus tabel baru
        $this->forge->dropTable('supplier_messages', true);
        $this->forge->dropTable('supplier_conversations', true);
    }
}
