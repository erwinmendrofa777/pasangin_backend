<?php

namespace App\Controllers\Api;

require_once APPPATH . 'ThirdParty/php-jwt/src/JWT.php';
require_once APPPATH . 'ThirdParty/php-jwt/src/Key.php';

use App\Models\ConversationModel;
use App\Models\MessageModel;
use App\Models\UserModel;
use App\Models\TukangModel;
use CodeIgniter\RESTful\ResourceController;

class ChatController extends ResourceController
{
    protected $format = 'json';
    private $jwtKey = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma';

    private function getAuthData()
    {
        try {
            $authHeader = $this->request->getHeaderLine('Authorization');
            if (empty($authHeader)) return null;
            $token = explode(' ', $authHeader)[1];
            return \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($this->jwtKey, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAllConversationsForUser($userId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth || $auth->uid != $userId) {
            return $this->respond(['status' => false, 'message' => 'Token tidak valid kawan.'], 401);
        }

        try {
            $conversationModel = new ConversationModel();
            $conversations = $conversationModel->where('client_id', $userId)
                                                ->where('client_type', $auth->role ?? 'client')
                                                ->orderBy('updated_at', 'DESC')
                                                ->findAll();

            return $this->respond([
                'status' => true,
                'conversations' => $conversations ?? []
            ], 200);

        } catch (\Exception $e) {
            return $this->respond([
                'status' => false,
                'message' => 'Kesalahan Database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FUNGSI UNTUK MEMBUAT CHAT ROOM BARU DENGAN JUDUL
     */
    public function createOrGetConversation()
    {
        $auth = $this->getAuthData();
        if (!$auth) return $this->failUnauthorized();

        $userId = $auth->uid;
        $role   = $auth->role ?? 'client';
        
        // Ambil data JSON dari Flutter kawan
        $json   = $this->request->getJSON();
        $title  = $json->title ?? 'Bantuan Pasangin'; 

        try {
            $conversationModel = new ConversationModel();

            // KUNCI PERBAIKAN:
            // Kita tidak perlu lagi mengecek 'existing' agar setiap kawan buat judul baru,
            // dia akan membuatkan ID Chat Room yang baru (seperti tiket baru).
            $id = $conversationModel->insert([
                'client_id'   => $userId,
                'client_type' => $role, 
                'title'       => $title, // Simpan judul uniknya di sini
                'status'      => 'open',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            return $this->respondCreated([
                'status' => true, 
                'message' => 'Obrolan baru berhasil dibuat kawan.',
                'data' => $conversationModel->find($id)
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function getMessages($conversationId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth) return $this->failUnauthorized();

        try {
            $messageModel = new MessageModel();
            $messages = $messageModel->where('conversation_id', $conversationId)
                                     ->orderBy('created_at', 'ASC')
                                     ->findAll();
            return $this->respond([
                'status' => true,
                'data' => ['conversation_id' => intval($conversationId), 'messages' => $messages]
            ], 200);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal mengambil pesan: ' . $e->getMessage());
        }
    }

    public function sendMessage()
    {
        $auth = $this->getAuthData();
        if (!$auth) return $this->failUnauthorized();

        $json = $this->request->getJSON();
        $conversationId = $json->conversation_id ?? null;
        $body = $json->body ?? $json->message;

        if (!$conversationId || !$body) return $this->fail('Data tidak lengkap.');
        
        try {
            $messageModel = new MessageModel();
            $messageId = $messageModel->insert([
                'conversation_id' => $conversationId,
                'sender_id'       => $auth->uid,
                'sender_type'     => $auth->role ?? 'client',
                'body'            => $body,
                'message_type'    => 'text',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            (new ConversationModel())->update($conversationId, [
                'last_message_preview' => substr($body, 0, 100),
                'last_message_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated(['status' => true, 'data' => $messageModel->find($messageId)]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal kirim: ' . $e->getMessage());
        }
    }
}
