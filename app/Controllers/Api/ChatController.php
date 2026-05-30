<?php

namespace App\Controllers\Api;

require_once APPPATH . 'ThirdParty/php-jwt/src/JWT.php';
require_once APPPATH . 'ThirdParty/php-jwt/src/Key.php';

use App\Modules\Chat\Models\ConversationModel;
use App\Modules\Chat\Models\MessageModel;
use App\Modules\Users\Models\UserModel;
use App\Modules\Tukang\Models\TukangModel;
use CodeIgniter\RESTful\ResourceController;

class ChatController extends ResourceController
{
    protected $format = 'json';
    private function getAuthData()
    {
        return $this->request->user ?? null;
    }

    public function getAllConversationsForUser($userId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth || $auth->uid != $userId) {
            return $this->respond(['status' => false, 'message' => 'Token tidak valid  .'], 401);
        }

        try {
            $conversationModel = new ConversationModel();
            $conversations = $conversationModel->select('*, COALESCE(last_message_at, created_at) as sort_time')
                                                ->where('client_id', $userId)
                                                ->where('client_type', $auth->role ?? 'client')
                                                ->orderBy('sort_time', 'DESC')
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
        
        // Ambil data JSON dari Flutter  
        $json   = $this->request->getJSON();
        $title  = $json->title ?? 'Bantuan Pasangin'; 
        $category = $json->category ?? 'general';

        if (!in_array($category, ['technical', 'accounting', 'general'])) {
            $category = 'general';
        }

        try {
            $conversationModel = new ConversationModel();

            // KUNCI PERBAIKAN:
            // Kita tidak perlu lagi mengecek 'existing' agar setiap   buat judul baru,
            // dia akan membuatkan ID Chat Room yang baru (seperti tiket baru).
            $id = $conversationModel->insert([
                'client_id'   => $userId,
                'client_type' => $role, 
                'title'       => $title, // Simpan judul uniknya di sini
                'status'      => 'open',
                'category'    => $category,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

            return $this->respondCreated([
                'status' => true, 
                'message' => 'Obrolan baru berhasil dibuat.',
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
            
            // Mark messages from admin as read by client
            $messageModel->where('conversation_id', $conversationId)
                         ->where('sender_type', 'admin')
                         ->where('is_read_by_client', 0)
                         ->set(['is_read_by_client' => 1])
                         ->update();

            $messages = $messageModel->where('conversation_id', $conversationId)
                                     ->orderBy('created_at', 'ASC')
                                     ->findAll();

            // Cast numeric fields for clean Dart JSON parsing compatibility and absolute file URL formatting
            foreach ($messages as &$msg) {
                if (isset($msg['id'])) {
                    $msg['id'] = (int)$msg['id'];
                }
                if (isset($msg['conversation_id'])) {
                    $msg['conversation_id'] = (int)$msg['conversation_id'];
                }
                if (isset($msg['sender_id'])) {
                    $msg['sender_id'] = (int)$msg['sender_id'];
                }
                if (isset($msg['is_read_by_admin'])) {
                    $msg['is_read_by_admin'] = (int)$msg['is_read_by_admin'];
                }
                if (isset($msg['is_read_by_client'])) {
                    $msg['is_read_by_client'] = (int)$msg['is_read_by_client'];
                }
                if (isset($msg['latitude']) && $msg['latitude'] !== null) {
                    $msg['latitude'] = (double)$msg['latitude'];
                }
                if (isset($msg['longitude']) && $msg['longitude'] !== null) {
                    $msg['longitude'] = (double)$msg['longitude'];
                }
                if (isset($msg['file_url']) && !empty($msg['file_url'])) {
                    if (!str_starts_with($msg['file_url'], 'http://') && !str_starts_with($msg['file_url'], 'https://')) {
                        $msg['file_url'] = base_url($msg['file_url']);
                    }
                }
            }

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

        // Safe JSON parsing 
        $json = null;

        $contentType = $this->request->getHeaderLine('Content-Type');

        if (str_contains($contentType, 'application/json')) {
            $json = $this->request->getJSON();
        }

        $conversationId = $json
            ? ($json->conversation_id ?? null)
            : $this->request->getPost('conversation_id');

        $body = $json
            ? ($json->body ?? $json->message ?? '')
            : ($this->request->getPost('body')
            ?? $this->request->getPost('message')
            ?? '');

        $attachmentType = $json
            ? ($json->attachment_type ?? null)
            : $this->request->getPost('attachment_type');

        $latitude = $json
            ? ($json->latitude ?? null)
            : $this->request->getPost('latitude');

        $longitude = $json
            ? ($json->longitude ?? null)
            : $this->request->getPost('longitude');

        if (!$conversationId) {
            return $this->fail('Data tidak lengkap: conversation_id wajib diisi.');
        }
        
        try {
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->find($conversationId);
            if ($conversation && ($conversation['status'] ?? 'open') === 'closed') {
                return $this->respond([
                    'status' => false,
                    'message' => 'Obrolan ini telah ditutup.'
                ], 400);
            }

            $messageModel = new MessageModel();
            
            $fileUrl = null;
            $messageType = 'text';

            // Check if there is an uploaded file
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/chat';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $fileUrl = 'uploads/chat/' . $newName;

                $messageType = $attachmentType ?? 'file';
                if (!in_array($messageType, ['image', 'video', 'file'])) {
                    $messageType = 'file';
                }
            } elseif ($latitude !== null && $longitude !== null && $latitude !== '' && $longitude !== '') {
                $messageType = 'location';
            }

            // Fallback for body type to prevent MySQL NOT NULL errors
            if ($body === null) {
                $body = '';
            }

            $messageId = $messageModel->insert([
                'conversation_id'   => $conversationId,
                'sender_id'         => $auth->uid,
                'sender_type'       => $auth->role ?? 'client',
                'body'              => $body,
                'file_url'          => $fileUrl,
                'message_type'      => $messageType,
                'latitude'          => ($messageType === 'location') ? $latitude : null,
                'longitude'         => ($messageType === 'location') ? $longitude : null,
                'is_read_by_admin'  => 0,
                'is_read_by_client' => 1, // Sent by client, read by client
                'created_at'        => date('Y-m-d H:i:s'),
            ]);

            // Increment unread count for admin side
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->find($conversationId);
            $newUnreadCount = ($conversation ? intval($conversation['unread_by_admin_count'] ?? 0) : 0) + 1;

            // Generate smart sidebar preview text
            $previewText = $body;
            if (empty(trim($previewText))) {
                if ($messageType === 'image') $previewText = '📷 Gambar';
                elseif ($messageType === 'video') $previewText = '🎥 Video';
                elseif ($messageType === 'file') $previewText = '📁 Berkas';
                elseif ($messageType === 'location') $previewText = '📍 Lokasi';
            } else {
                if ($messageType === 'image') $previewText = '📷 ' . $body;
                elseif ($messageType === 'video') $previewText = '🎥 ' . $body;
                elseif ($messageType === 'file') $previewText = '📁 ' . $body;
                elseif ($messageType === 'location') $previewText = '📍 ' . $body;
            }

            $conversationModel->update($conversationId, [
                'last_message_preview'  => substr($previewText, 0, 100),
                'last_message_at'       => date('Y-m-d H:i:s'),
                'unread_by_admin_count' => $newUnreadCount,
                'updated_at'            => date('Y-m-d H:i:s')
            ]);

            // Trigger FCM to Admin
            try {
                $conversation = $conversationModel->find($conversationId);
                if ($conversation) {
                    $clientName = 'Pelanggan';
                    if ($conversation['client_type'] === 'tukang') {
                        $tukangModel = new TukangModel();
                        $tukang = $tukangModel->find($conversation['client_id']);
                        if ($tukang) {
                            $clientName = $tukang['name'];
                        }
                    } else {
                        $userModel = new UserModel();
                        $user = $userModel->find($conversation['client_id']);
                        if ($user) {
                            $clientName = $user['full_name'];
                        }
                    }

                    $notificationService = new \App\Modules\Notifications\Services\NotificationService();
                    $title = 'Pesan Baru dari ' . $clientName;
                    
                    // User friendly notification body depending on message type
                    if ($messageType === 'image') {
                        $notificationBody = '📷 Mengirim gambar' . ($body ? ': ' . $body : '');
                    } elseif ($messageType === 'video') {
                        $notificationBody = '🎥 Mengirim video' . ($body ? ': ' . $body : '');
                    } elseif ($messageType === 'file') {
                        $notificationBody = '📁 Mengirim berkas' . ($body ? ': ' . $body : '');
                    } elseif ($messageType === 'location') {
                        $notificationBody = '📍 Berbagi lokasi';
                    } else {
                        $notificationBody = (strlen($body) > 80) ? substr($body, 0, 77) . '...' : $body;
                    }
                    
                    $extra = [
                        'type'            => 'chat',
                        'conversation_id' => (string) $conversationId,
                        'click_action'    => 'FLUTTER_NOTIFICATION_CLICK'
                    ];

                    if (!empty($conversation['admin_id'])) {
                        $notificationService->notifyAdmin($conversation['admin_id'], $title, $notificationBody, $extra);
                    } else {
                        $cat = $conversation['category'] ?? 'general';
                        if ($cat === 'technical') {
                            $notificationService->sendToPermission('chat_view_technical', $title, $notificationBody, null, $extra);
                        } elseif ($cat === 'accounting') {
                            $notificationService->sendToPermission('chat_view_accounting', $title, $notificationBody, null, $extra);
                        } else {
                            $notificationService->sendToPermission('chat_view_general', $title, $notificationBody, null, $extra);
                            // Sediakan juga fallback untuk admin dengan permission legacy 'chat_view'
                            $notificationService->sendToPermission('chat_view', $title, $notificationBody, null, $extra);
                        }
                    }
                }
            } catch (\Exception $ex) {
                log_message('error', '[FCM CHAT API ERROR] ' . $ex->getMessage());
            }

            // Retrieve and cast the newly inserted message row
            $insertedMessage = $messageModel->find($messageId);
            if ($insertedMessage) {
                if (isset($insertedMessage['id'])) {
                    $insertedMessage['id'] = (int)$insertedMessage['id'];
                }
                if (isset($insertedMessage['conversation_id'])) {
                    $insertedMessage['conversation_id'] = (int)$insertedMessage['conversation_id'];
                }
                if (isset($insertedMessage['sender_id'])) {
                    $insertedMessage['sender_id'] = (int)$insertedMessage['sender_id'];
                }
                if (isset($insertedMessage['is_read_by_admin'])) {
                    $insertedMessage['is_read_by_admin'] = (int)$insertedMessage['is_read_by_admin'];
                }
                if (isset($insertedMessage['is_read_by_client'])) {
                    $insertedMessage['is_read_by_client'] = (int)$insertedMessage['is_read_by_client'];
                }
                if (isset($insertedMessage['latitude']) && $insertedMessage['latitude'] !== null) {
                    $insertedMessage['latitude'] = (double)$insertedMessage['latitude'];
                }
                if (isset($insertedMessage['longitude']) && $insertedMessage['longitude'] !== null) {
                    $insertedMessage['longitude'] = (double)$insertedMessage['longitude'];
                }
                if (isset($insertedMessage['file_url']) && !empty($insertedMessage['file_url'])) {
                    if (!str_starts_with($insertedMessage['file_url'], 'http://') && !str_starts_with($insertedMessage['file_url'], 'https://')) {
                        $insertedMessage['file_url'] = base_url($insertedMessage['file_url']);
                    }
                }
            }

            return $this->respondCreated(['status' => true, 'data' => $insertedMessage]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal kirim: ' . $e->getMessage());
        }
    }
}
