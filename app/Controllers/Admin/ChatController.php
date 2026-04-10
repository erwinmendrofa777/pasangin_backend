<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MessageModel;
use App\Models\ConversationModel;
use App\Models\UserModel;
use App\Models\TukangModel; // Tambahkan Model Tukang
use Exception;

class ChatController extends BaseController
{
    public function index()
    {
        $conversationModel = new ConversationModel();
        
        // Update query: Gunakan IFNULL / COALESCE untuk mengambil nama dari tabel users atau tukang
        $data['conversations'] = $conversationModel->select('
                conversations.*, 
                IF(conversations.client_type = "tukang", tukang.name, users.full_name) as client_name
            ')
            ->join('users', 'users.id = conversations.client_id AND conversations.client_type = "client"', 'left')
            ->join('tukang', 'tukang.id = conversations.client_id AND conversations.client_type = "tukang"', 'left')
            ->orderBy('conversations.updated_at', 'DESC')
            ->findAll();

        return view('admin/chat/index', $data);
    }

    public function getMessages($conversationId = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        try {
            $messageModel = new MessageModel();
            $messages = $messageModel->where('conversation_id', $conversationId)->orderBy('created_at', 'ASC')->findAll();
            return $this->response->setJSON(['status' => true, 'data' => $messages]);
        } catch (Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function sendMessage()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $conversationId = $this->request->getPost('conversation_id');
        $messageText    = $this->request->getPost('message');
        
        $adminId = session()->get('user_id') ?? session()->get('id');
        if (empty($adminId)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Sesi habis'])->setStatusCode(401);
        }

        $messageModel = new MessageModel();
        $conversationModel = new ConversationModel();

        if ($messageModel->insert([
            'conversation_id' => $conversationId,
            'sender_id'       => $adminId,
            'body'            => $messageText,
            'sender_type'     => 'admin',
        ])) {
            
            try {
                $conversationModel->db->table('conversations')
                    ->where('id', $conversationId)
                    ->update(['updated_at' => date('Y-m-d H:i:s')]);
            } catch (Exception $e) {}
            
            // PANGGIL NOTIFIKASI YANG SUDAH SUPPORT TUKANG
            $this->_sendNativeNotificationWithSound($conversationId, $messageText);
            
            return $this->response->setJSON(['status' => true]);
        }
        return $this->response->setJSON(['status' => false]);
    }

    private function _sendNativeNotificationWithSound($conversationId, $messageText)
    {
        try {
            $conversation = (new ConversationModel())->find($conversationId);
            if (!$conversation) return;

            // LOGIKA BARU: Cek client_type untuk ambil token FCM
            $fcmToken = '';
            if ($conversation['client_type'] === 'tukang') {
                $tukang = (new TukangModel())->find($conversation['client_id']);
                $fcmToken = $tukang['fcm_token'] ?? '';
            } else {
                $client = (new UserModel())->find($conversation['client_id']);
                $fcmToken = $client['fcm_token'] ?? '';
            }

            if (empty($fcmToken)) return;

            $keyPath = WRITEPATH . 'firebase_key.json';
            if (!file_exists($keyPath)) return;

            $key = json_decode(file_get_contents($keyPath), true);
            $projectId = $key['project_id'];

            // 1. GENERATE ACCESS TOKEN (OAUTH2 JWT)
            $now = time();
            $header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])));
            $payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode([
                'iss' => $key['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600
            ])));
            openssl_sign($header . "." . $payload, $signature, $key['private_key'], OPENSSL_ALGO_SHA256);
            $jwt = $header . "." . $payload . "." . str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer', 'assertion' => $jwt]));
            $authRes = json_decode(curl_exec($ch), true);
            $accessToken = $authRes['access_token'] ?? null;
            curl_close($ch);

            if (!$accessToken) return;

            // 2. KIRIM KE FCM
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
            $data = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => 'Pesan Baru dari Admin',
                        'body'  => (strlen($messageText) > 80) ? substr($messageText, 0, 77) . '...' : $messageText
                    ],
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'pasangin_chat_channel',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                        ]
                    ],
                    'data' => [
                        'type' => 'chat',
                        'conversation_id' => (string)$conversationId
                    ]
                ]
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken, 'Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);
            curl_close($ch);

        } catch (Exception $e) {
            log_message('error', '[FCM SOUND ERROR] ' . $e->getMessage());
        }
    }
}