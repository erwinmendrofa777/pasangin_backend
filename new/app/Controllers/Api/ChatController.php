<?php

// Lokasi: app/Controllers/Api/ChatController.php
// VERSI LENGKAP YANG SUDAH BERISI SEMUA FUNGSI (TERMASUK UNTUK LIST CHAT)

namespace App\Controllers\Api;

// Pastikan Anda meng-import model yang benar
use App\Models\ConversationModel;
use App\Models\MessageModel;
use CodeIgniter\RESTful\ResourceController;

class ChatController extends ResourceController
{
    // =========================================================================
    // FUNGSI 1: MENGAMBIL SEMUA PERCAKAPAN UNTUK SEORANG USER (UNTUK HALAMAN LIST CHAT)
    // Dipanggil oleh GET /api/chat/all/[user_id]
    // =========================================================================
    public function getAllConversationsForUser($userId = null)
    {
        if ($userId === null) {
            return $this->fail('User ID dibutuhkan.', 400);
        }

        try {
            $conversationModel = new ConversationModel();
            
            // Cari semua percakapan milik client dengan ID tersebut, urutkan dari yang terbaru
            $conversations = $conversationModel->where('client_id', $userId)
                                              ->orderBy('updated_at', 'DESC')
                                              ->findAll();

            // Ubah tipe data numerik menjadi string agar konsisten dengan output lain
            // Ini PENTING untuk mencegah error parsing di Flutter
            foreach ($conversations as &$convo) {
                $convo['id'] = (string)$convo['id'];
                $convo['client_id'] = (string)$convo['client_id'];
                $convo['unread_by_admin_count'] = (string)$convo['unread_by_admin_count'];
            }

            $response = [
                'status' => true,
                'message' => 'Daftar percakapan berhasil diambil.',
                'conversations' => $conversations
            ];

            return $this->respond($response, 200);

        } catch (\Exception $e) {
            log_message('error', '[getAllConversationsForUser] ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan di server saat mengambil daftar percakapan.');
        }
    }

    // =========================================================================
    // FUNGSI 2: MENCARI SATU PERCAKAPAN SPESIFIK (TETAP DIPERLUKAN JIKA AKAN ADA CHAT DARI HALAMAN LAIN)
    // Dipanggil oleh GET /api/chat/conversations/[user_id]/[user_type]
    // =========================================================================
    public function getConversationByUser($userId = null, $userType = null)
    {
        if ($userId === null || $userType === null) {
            return $this->fail('User ID dan User Type dibutuhkan.', 400);
        }

        try {
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->where('client_id', $userId)
                                              ->where('client_type', $userType)
                                              ->first();

            if ($conversation) {
                // Konversi tipe data ke string secara manual agar Flutter tidak error
                $conversation['id'] = (string)$conversation['id'];
                $conversation['client_id'] = (string)$conversation['client_id'];
                $conversation['unread_by_admin_count'] = (string)$conversation['unread_by_admin_count'];
                
                $response = [
                    'status'        => true,
                    'message'       => 'Percakapan ditemukan.',
                    'conversation'  => $conversation
                ];
                return $this->respond($response, 200);
            } else {
                $response = [
                    'status'        => false,
                    'message'       => 'Belum ada percakapan untuk pengguna ini.',
                    'conversation'  => null
                ];
                return $this->respond($response, 200);
            }
        } catch (\Exception $e) {
            log_message('error', '[getConversationByUser] ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan di server saat memeriksa percakapan.');
        }
    }

    // =========================================================================
    // FUNGSI 3: MENGAMBIL PESAN DALAM SEBUAH RUANG CHAT
    // Dipanggil oleh GET /api/chat/messages/[conversation_id]
    // =========================================================================
    public function getMessages($conversationId = null)
    {
        if ($conversationId === null) {
            return $this->fail('Conversation ID dibutuhkan.', 400);
        }

        try {
            $messageModel = new MessageModel();
            $messages = $messageModel->where('conversation_id', $conversationId)
                                     ->orderBy('created_at', 'ASC')
                                     ->findAll();

            // Ubah tipe data numerik menjadi string
            foreach ($messages as &$msg) {
                $msg['id'] = (string)$msg['id'];
                $msg['conversation_id'] = (string)$msg['conversation_id'];
                $msg['sender_id'] = (string)$msg['sender_id'];
            }

            $response = [
                'status' => true,
                'data' => [
                    'messages' => $messages
                ]
            ];
            return $this->respond($response, 200);

        } catch (\Exception $e) {
            log_message('error', '[getMessages] ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan di server saat mengambil pesan.');
        }
    }

    // =========================================================================
    // FUNGSI 4: MENGIRIM PESAN
    // Dipanggil oleh POST /api/chat/send
    // =========================================================================
    public function sendMessage()
    {
        $json = $this->request->getJSON();
        $conversationId   = $json->conversation_id ?? null;
        $senderId         = $json->sender_id ?? null;
        $senderType       = $json->sender_type ?? null;
        $body             = $json->body ?? null;

        if (empty($senderId) || empty($senderType) || empty($body)) {
            return $this->fail('sender_id, sender_type, dan body harus diisi.', 400);
        }

        try {
            $conversationModel = new ConversationModel();
            $messageModel = new MessageModel();
            
            // Jika ID percakapan tidak ada, artinya ini pesan pertama, buat percakapan baru.
            if ($conversationId === null) {
                $newConversationData = [
                    'client_id'   => $senderId,
                    'client_type' => $senderType,
                ];
                $conversationId = $conversationModel->insert($newConversationData, true);
                if (!$conversationId) {
                    return $this->fail('Gagal membuat entri percakapan baru.', 500);
                }
            }

            // Simpan pesan
            $newMessageData = [
                'conversation_id' => $conversationId,
                'sender_id'       => $senderId,
                'sender_type'     => $senderType,
                'body'            => $body,
            ];
            $messageId = $messageModel->insert($newMessageData, true);
            if (!$messageId) {
                return $this->fail('Gagal menyimpan pesan.', 500);
            }

            // Ambil kembali pesan yang baru disimpan
            $sentMessage = $messageModel->find($messageId);
            // Konversi tipe data untuk dikirim balik
            $sentMessage['id'] = (string)$sentMessage['id'];
            $sentMessage['conversation_id'] = (string)$sentMessage['conversation_id'];
            $sentMessage['sender_id'] = (string)$sentMessage['sender_id'];

            // Update tabel percakapan dengan preview pesan terakhir
            $conversationModel->update($conversationId, [
                'last_message_preview' => substr($body, 0, 50),
                'last_message_at'      => date('Y-m-d H:i:s')
            ]);

            $response = [
                'status'  => true,
                'message' => 'Pesan berhasil dikirim.',
                'data'    => $sentMessage
            ];
            return $this->respondCreated($response);

        } catch (\Exception $e) {
            log_message('error', '[sendMessage] ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan di server saat mengirim pesan.');
        }
    }
}
