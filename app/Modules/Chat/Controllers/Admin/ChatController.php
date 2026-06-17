<?php

namespace App\Modules\Chat\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Chat\Models\MessageModel;
use App\Modules\Chat\Models\ConversationModel;
use Exception;

class ChatController extends BaseController
{
    private function _getAllowedCategories()
    {
        if (can('super_admin_override')) {
            return ['technical', 'accounting', 'general'];
        }

        $allowed = [];
        if (can('chat_view_technical')) {
            $allowed[] = 'technical';
        }
        if (can('chat_view_accounting')) {
            $allowed[] = 'accounting';
        }
        if (can('chat_view_general') || can('chat_view')) {
            $allowed[] = 'general';
        }
        return $allowed;
    }

    private function _hasAccessToConversation($conversation)
    {
        if (!$conversation) {
            return false;
        }
        $category = $conversation['category'] ?? 'general';
        $allowed = $this->_getAllowedCategories();
        return in_array($category, $allowed);
    }

    public function index()
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat chat.');
        }

        $conversationModel = new ConversationModel();
        $allowedCategories = $this->_getAllowedCategories();

        $data['allowedCategories'] = $allowedCategories;

        if (empty($allowedCategories)) {
            $data['conversations'] = [];
        } else {
            // Update query: Gunakan IFNULL / COALESCE untuk mengambil nama dari tabel users atau tukang
            $data['conversations'] = $conversationModel->select('
                    conversations.*, 
                    IF(conversations.supplier_id IS NOT NULL, 
                       CONCAT(IF(conversations.client_type = "tukang", tukang.name, users.full_name), " ↔ ", suppliers.name),
                       IF(conversations.client_type = "tukang", tukang.name, users.full_name)
                    ) as client_name,
                    IF(conversations.client_type = "tukang", tukang.profile_photo, users.avatar) as client_avatar,
                    suppliers.name as supplier_name,
                    COALESCE(conversations.last_message_at, conversations.created_at) as sort_time
                ', false)
                ->join('users', 'users.id = conversations.client_id AND conversations.client_type = "client"', 'left')
                ->join('tukang', 'tukang.id = conversations.client_id AND conversations.client_type = "tukang"', 'left')
                ->join('suppliers', 'suppliers.id = conversations.supplier_id', 'left')
                ->whereIn('conversations.category', $allowedCategories)
                ->orderBy('sort_time', 'DESC')
                ->findAll();
        }

        return view('App\Modules\Chat\Views\index', $data);
    }

    public function getMessages($conversationId = null)
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        $this->response->setHeader('Content-Type', 'application/json');
        try {
            $messageModel = new MessageModel();
            $conversationModel = new ConversationModel();

            $conversation = $conversationModel->find($conversationId);
            if (!$conversation) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Obrolan tidak ditemukan.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(404);
            }

            if (!$this->_hasAccessToConversation($conversation)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke kategori chat ini.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(403);
            }

            // Mark messages as read by admin
            $messageModel->where('conversation_id', $conversationId)
                         ->where('sender_type !=', 'admin')
                         ->where('is_read_by_admin', 0)
                         ->set(['is_read_by_admin' => 1])
                         ->update();

            // Reset unread by admin count
            $conversationModel->update($conversationId, ['unread_by_admin_count' => 0]);

            $db = \Config\Database::connect();
            $messages = $db->table('messages m')
                ->select('
                    m.*,
                    CASE 
                        WHEN m.sender_type = "admin" THEN "Admin Pasangin"
                        WHEN m.sender_type = "supplier" THEN s.name
                        WHEN m.sender_type = "tukang" THEN t.name
                        ELSE u.full_name
                    END as sender_name,
                    CASE 
                        WHEN m.sender_type = "admin" THEN "assets/img/avatar/avatar-5.png"
                        WHEN m.sender_type = "supplier" THEN s.logo_url
                        WHEN m.sender_type = "tukang" THEN t.profile_photo
                        ELSE u.avatar
                    END as sender_avatar
                ', false)
                ->join('users u', 'u.id = m.sender_id AND m.sender_type = "client"', 'left')
                ->join('tukang t', 't.id = m.sender_id AND m.sender_type = "tukang"', 'left')
                ->join('suppliers s', 's.id = m.sender_id AND m.sender_type = "supplier"', 'left')
                ->where('m.conversation_id', $conversationId)
                ->orderBy('m.created_at', 'ASC')
                ->get()
                ->getResultArray();
            return $this->response->setJSON(['status' => true, 'data' => $messages]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => false, 
                'message' => $e->getMessage(),
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    public function getConversations()
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk melihat chat.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        try {
            $conversationModel = new ConversationModel();
            $allowedCategories = $this->_getAllowedCategories();

            if (empty($allowedCategories)) {
                $conversations = [];
            } else {
                $conversations = $conversationModel->select('
                        conversations.*, 
                        IF(conversations.supplier_id IS NOT NULL, 
                           CONCAT(IF(conversations.client_type = "tukang", tukang.name, users.full_name), " ↔ ", suppliers.name),
                           IF(conversations.client_type = "tukang", tukang.name, users.full_name)
                        ) as client_name,
                        IF(conversations.client_type = "tukang", tukang.profile_photo, users.avatar) as client_avatar,
                        suppliers.name as supplier_name,
                        COALESCE(conversations.last_message_at, conversations.created_at) as sort_time
                    ', false)
                    ->join('users', 'users.id = conversations.client_id AND conversations.client_type = "client"', 'left')
                    ->join('tukang', 'tukang.id = conversations.client_id AND conversations.client_type = "tukang"', 'left')
                    ->join('suppliers', 'suppliers.id = conversations.supplier_id', 'left')
                    ->whereIn('conversations.category', $allowedCategories)
                    ->orderBy('sort_time', 'DESC')
                    ->findAll();
            }

            return $this->response->setJSON([
                'status' => true,
                'data' => $conversations,
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage(),
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    public function sendMessage()
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk mengirim chat.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        $this->response->setHeader('Content-Type', 'application/json');
        $conversationId = $this->request->getPost('conversation_id');
        $messageText = $this->request->getPost('message') ?? '';

        $adminId = session()->get('user_id') ?? session()->get('id');
        if (empty($adminId)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Sesi habis',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(401);
        }

        $messageModel = new MessageModel();
        $conversationModel = new ConversationModel();

        // Check if conversation exists and admin has access
        $conversation = $conversationModel->find($conversationId);
        if (!$conversation) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Obrolan tidak ditemukan.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(404);
        }

        // Block admin from sending messages in supplier chat (read-only monitoring)
        if (!empty($conversation['supplier_id'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak dapat mengirim pesan pada obrolan supplier.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        if (!$this->_hasAccessToConversation($conversation)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke kategori chat ini.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        $fileUrl = null;
        $messageType = 'text';

        // Check if there is an uploaded file from admin
        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/chat';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Determine message type based on file properties before moving the file
            $mime = $file->getMimeType();
            if (str_starts_with($mime, 'image/')) {
                $messageType = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $messageType = 'video';
            } else {
                $messageType = 'file';
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $fileUrl = 'uploads/chat/' . $newName;
        }

        // Prevent database NOT NULL error for body column
        if ($messageText === null) {
            $messageText = '';
        }

        $messageData = [
            'conversation_id'   => $conversationId,
            'sender_id'         => $adminId,
            'body'              => $messageText,
            'sender_type'       => 'admin',
            'file_url'          => $fileUrl,
            'message_type'      => $messageType,
            'is_read_by_admin'  => 1, // Sent by admin, read by admin
            'is_read_by_client' => 0, // Sent to client, client hasn't read it yet
            'created_at'        => date('Y-m-d H:i:s'),
        ];

        if ($messageModel->insert($messageData)) {
            $insertedId = $messageModel->getInsertID();
            $messageData['id'] = $insertedId;

            // Smart sidebar preview text
            $previewText = $messageText;
            if (empty(trim($previewText))) {
                if ($messageType === 'image') $previewText = '📷 Gambar';
                elseif ($messageType === 'video') $previewText = '🎥 Video';
                elseif ($messageType === 'file') $previewText = '📁 Berkas';
            } else {
                if ($messageType === 'image') $previewText = '📷 ' . $messageText;
                elseif ($messageType === 'video') $previewText = '🎥 ' . $messageText;
                elseif ($messageType === 'file') $previewText = '📁 ' . $messageText;
            }

            try {
                $conversationModel->update($conversationId, [
                    'last_message_preview' => substr($previewText, 0, 100),
                    'last_message_at'      => date('Y-m-d H:i:s'),
                    'updated_at'           => date('Y-m-d H:i:s')
                ]);
            } catch (Exception $e) {
                log_message('error', '[UPDATE CONVO ERROR] ' . $e->getMessage());
            }

            // Trigger FCM to Client/Tukang
            $this->_sendNativeNotificationWithSound($conversationId, $messageText, $messageType);
            log_admin_activity('create', 'Chat', 'Tambah Data Chat');

            return $this->response->setJSON([
                'status' => true,
                'data' => $messageData,
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
        return $this->response->setJSON([
            'status' => false,
            'csrf_name' => csrf_token(),
            'csrf_hash' => csrf_hash()
        ]);
    }

    private function _sendNativeNotificationWithSound($conversationId, $messageText, $messageType = 'text')
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return;
        }
        try {
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->find($conversationId);
            if (!$conversation)
                return;

            $notificationService = new \App\Modules\Notifications\Services\NotificationService();
            $title = 'Pesan Baru dari Admin';
            
            // Generate clean notification body based on type
            if ($messageType === 'image') {
                $body = '📷 Mengirim gambar' . ($messageText ? ': ' . $messageText : '');
            } elseif ($messageType === 'video') {
                $body = '🎥 Mengirim video' . ($messageText ? ': ' . $messageText : '');
            } elseif ($messageType === 'file') {
                $body = '📁 Mengirim berkas' . ($messageText ? ': ' . $messageText : '');
            } else {
                $body = (strlen($messageText) > 80) ? substr($messageText, 0, 77) . '...' : $messageText;
            }

            $extra = [
                'type'            => 'chat',
                'conversation_id' => (string) $conversationId,
                'click_action'    => 'FLUTTER_NOTIFICATION_CLICK'
            ];

            if ($conversation['client_type'] === 'tukang') {
                $notificationService->notifyTukang($conversation['client_id'], $title, $body, $extra, null, 'chat-customer-service');
            } else {
                $notificationService->notifyClient($conversation['client_id'], $title, $body, $extra, null, 'chat-customer-service');
            }

        } catch (Exception $e) {
            log_message('error', '[FCM CHAT ERROR] ' . $e->getMessage());
        }
    }

    public function updateStatus($conversationId = null)
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        try {
            $status = $this->request->getPost('status');
            if (!in_array($status, ['open', 'closed'])) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Status tidak valid.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(400);
            }

            $conversationModel = new ConversationModel();

            // Check if conversation exists and admin has access
            $conversation = $conversationModel->find($conversationId);
            if (!$conversation) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Obrolan tidak ditemukan.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(404);
            }

            // Block admin from changing status of supplier chat (read-only monitoring)
            if (!empty($conversation['supplier_id'])) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Anda tidak dapat mengubah status pada obrolan supplier.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(403);
            }

            if (!$this->_hasAccessToConversation($conversation)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke kategori chat ini.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(403);
            }

            $conversationModel->update($conversationId, ['status' => $status]);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Status obrolan berhasil diperbarui.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage(),
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }
}
