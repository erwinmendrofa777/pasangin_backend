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
            return $this->respond(['status' => false, 'message' => 'Token tidak valid.'], 401);
        }

        try {
            $conversationModel = new ConversationModel();

            if (($auth->role ?? 'client') === 'supplier') {
                // Supplier: get conversations where supplier_id = $userId
                $conversations = $conversationModel->select('
                        conversations.*,
                        COALESCE(conversations.last_message_at, conversations.created_at) as sort_time,
                        users.full_name as opponent_name,
                        users.avatar as opponent_avatar,
                        "client" as opponent_type,
                        conversations.unread_by_supplier_count as unread_count
                    ')
                    ->join('users', 'users.id = conversations.client_id', 'left')
                    ->where('conversations.supplier_id', $userId)
                    ->where('conversations.client_type', 'client')
                    ->orderBy('sort_time', 'DESC')
                    ->findAll();

                foreach ($conversations as &$conv) {
                    if (isset($conv['opponent_avatar']) && !empty($conv['opponent_avatar'])) {
                        if (!str_starts_with($conv['opponent_avatar'], 'http://') && !str_starts_with($conv['opponent_avatar'], 'https://')) {
                            $conv['opponent_avatar'] = base_url('uploads/profile/' . $conv['opponent_avatar']);
                        }
                    } else {
                        $conv['opponent_avatar'] = base_url('uploads/profile/default.jpg');
                    }
                }
            } else {
                // Client / Tukang: get conversations where client_id = $userId and client_type = $auth->role
                $conversations = $conversationModel->select('
                        conversations.*,
                        COALESCE(conversations.last_message_at, conversations.created_at) as sort_time,
                        suppliers.name as supplier_name,
                        suppliers.logo_url as supplier_logo,
                        conversations.unread_by_client_count as unread_count
                    ')
                    ->join('suppliers', 'suppliers.id = conversations.supplier_id', 'left')
                    ->where('conversations.client_id', $userId)
                    ->where('conversations.client_type', $auth->role ?? 'client')
                    ->orderBy('sort_time', 'DESC')
                    ->findAll();

                foreach ($conversations as &$conv) {
                    if ($conv['supplier_id'] !== null) {
                        $conv['opponent_name'] = $conv['supplier_name'] ?? 'Supplier';
                        $conv['opponent_avatar'] = !empty($conv['supplier_logo'])
                            ? (str_starts_with($conv['supplier_logo'], 'http') ? $conv['supplier_logo'] : base_url('uploads/supplier/' . $conv['supplier_logo']))
                            : base_url('uploads/supplier/default.png');
                        $conv['opponent_type'] = 'supplier';
                    } else {
                        $conv['opponent_name'] = 'Admin Pasangin';
                        $conv['opponent_avatar'] = base_url('assets/img/avatar/avatar-5.png');
                        $conv['opponent_type'] = 'admin';
                    }

                    unset($conv['supplier_name']);
                    unset($conv['supplier_logo']);
                }
            }

            // Cast numeric fields for clean Dart JSON parsing compatibility
            foreach ($conversations as &$conv) {
                if (isset($conv['id'])) {
                    $conv['id'] = (int) $conv['id'];
                }
                if (isset($conv['client_id'])) {
                    $conv['client_id'] = (int) $conv['client_id'];
                }
                if (isset($conv['supplier_id'])) {
                    $conv['supplier_id'] = $conv['supplier_id'] !== null ? (int) $conv['supplier_id'] : null;
                }
                if (isset($conv['admin_id'])) {
                    $conv['admin_id'] = $conv['admin_id'] !== null ? (int) $conv['admin_id'] : null;
                }
                if (isset($conv['unread_count'])) {
                    $conv['unread_count'] = (int) $conv['unread_count'];
                }
                if (isset($conv['unread_by_admin_count'])) {
                    $conv['unread_by_admin_count'] = (int) $conv['unread_by_admin_count'];
                }
                if (isset($conv['unread_by_supplier_count'])) {
                    $conv['unread_by_supplier_count'] = (int) $conv['unread_by_supplier_count'];
                }
                if (isset($conv['unread_by_client_count'])) {
                    $conv['unread_by_client_count'] = (int) $conv['unread_by_client_count'];
                }
            }

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
     * FUNGSI UNTUK MEMBUAT CHAT ROOM BARU DENGAN JUDUL ATAU MENGAMBIL CHAT SUPPLIER YANG SUDAH ADA
     */
    public function createOrGetConversation()
    {
        $auth = $this->getAuthData();
        if (!$auth)
            return $this->failUnauthorized();

        $userId = $auth->uid;
        $role = $auth->role ?? 'client';

        // Ambil data JSON dari Flutter  
        $json = $this->request->getJSON();
        $title = $json->title ?? 'Bantuan Pasangin';
        $category = $json->category ?? 'general';
        $supplierId = $json->supplier_id ?? null;

        if (!in_array($category, ['technical', 'accounting', 'general'])) {
            $category = 'general';
        }

        try {
            $conversationModel = new ConversationModel();

            // Handle supplier initiating chat with client
            if (($auth->role ?? 'client') === 'supplier') {
                $clientId = $json->client_id ?? null;
                if (!$clientId) {
                    return $this->fail('client_id wajib diisi oleh supplier untuk memulai obrolan.');
                }

                $existing = $conversationModel->where('client_id', $clientId)
                    ->where('client_type', 'client')
                    ->where('supplier_id', $userId)
                    ->first();
                if ($existing) {
                    if (isset($existing['id'])) {
                        $existing['id'] = (int) $existing['id'];
                    }
                    if (isset($existing['client_id'])) {
                        $existing['client_id'] = (int) $existing['client_id'];
                    }
                    if (isset($existing['supplier_id'])) {
                        $existing['supplier_id'] = (int) $existing['supplier_id'];
                    }
                    return $this->respond([
                        'status' => true,
                        'message' => 'Obrolan dengan client ditemukan.',
                        'data' => $existing
                    ]);
                }

                $id = $conversationModel->insert([
                    'client_id' => $clientId,
                    'client_type' => 'client',
                    'supplier_id' => $userId,
                    'title' => null, // Obrolan supplier-client tidak menggunakan title
                    'status' => 'open',
                    'category' => 'general',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $newConvo = $conversationModel->find($id);
                if (isset($newConvo['id'])) {
                    $newConvo['id'] = (int) $newConvo['id'];
                }
                if (isset($newConvo['client_id'])) {
                    $newConvo['client_id'] = (int) $newConvo['client_id'];
                }
                if (isset($newConvo['supplier_id'])) {
                    $newConvo['supplier_id'] = (int) $newConvo['supplier_id'];
                }

                return $this->respondCreated([
                    'status' => true,
                    'message' => 'Obrolan dengan client berhasil dibuat.',
                    'data' => $newConvo
                ]);
            }

            // Handle client initiating chat with supplier
            if ($supplierId) {
                $existing = $conversationModel->where('client_id', $userId)
                    ->where('client_type', 'client')
                    ->where('supplier_id', $supplierId)
                    ->first();
                if ($existing) {
                    if (isset($existing['id'])) {
                        $existing['id'] = (int) $existing['id'];
                    }
                    if (isset($existing['client_id'])) {
                        $existing['client_id'] = (int) $existing['client_id'];
                    }
                    if (isset($existing['supplier_id'])) {
                        $existing['supplier_id'] = (int) $existing['supplier_id'];
                    }
                    return $this->respond([
                        'status' => true,
                        'message' => 'Obrolan dengan supplier ditemukan.',
                        'data' => $existing
                    ]);
                }

                $id = $conversationModel->insert([
                    'client_id' => $userId,
                    'client_type' => 'client',
                    'supplier_id' => $supplierId,
                    'title' => null, // Obrolan supplier-client tidak menggunakan title
                    'status' => 'open',
                    'category' => 'general',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $newConvo = $conversationModel->find($id);
                if (isset($newConvo['id'])) {
                    $newConvo['id'] = (int) $newConvo['id'];
                }
                if (isset($newConvo['client_id'])) {
                    $newConvo['client_id'] = (int) $newConvo['client_id'];
                }
                if (isset($newConvo['supplier_id'])) {
                    $newConvo['supplier_id'] = (int) $newConvo['supplier_id'];
                }

                return $this->respondCreated([
                    'status' => true,
                    'message' => 'Obrolan dengan supplier berhasil dibuat.',
                    'data' => $newConvo
                ]);
            }

            // Fallback to client-admin ticketing
            $id = $conversationModel->insert([
                'client_id' => $userId,
                'client_type' => $role,
                'title' => $title,
                'status' => 'open',
                'category' => $category,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $newConvo = $conversationModel->find($id);
            if (isset($newConvo['id'])) {
                $newConvo['id'] = (int) $newConvo['id'];
            }
            if (isset($newConvo['client_id'])) {
                $newConvo['client_id'] = (int) $newConvo['client_id'];
            }

            return $this->respondCreated([
                'status' => true,
                'message' => 'Obrolan baru berhasil dibuat.',
                'data' => $newConvo
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function getMessages($conversationId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth)
            return $this->failUnauthorized();

        try {
            $messageModel = new MessageModel();
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->find($conversationId);
            if (!$conversation) {
                return $this->failNotFound('Obrolan tidak ditemukan.');
            }

            if (($auth->role ?? 'client') === 'supplier') {
                // Mark messages from client as read by supplier
                $messageModel->where('conversation_id', $conversationId)
                    ->whereIn('sender_type', ['client', 'tukang'])
                    ->where('is_read_by_supplier', 0)
                    ->set(['is_read_by_supplier' => 1])
                    ->update();

                // Reset unread_by_supplier_count
                $conversationModel->update($conversationId, ['unread_by_supplier_count' => 0]);
            } else {
                // Mark messages from admin/supplier as read by client
                $messageModel->where('conversation_id', $conversationId)
                    ->whereIn('sender_type', ['admin', 'supplier'])
                    ->where('is_read_by_client', 0)
                    ->set(['is_read_by_client' => 1])
                    ->update();

                // Reset unread_by_client_count
                $conversationModel->update($conversationId, ['unread_by_client_count' => 0]);
            }

            $messages = $messageModel->where('conversation_id', $conversationId)
                ->orderBy('created_at', 'ASC')
                ->findAll();

            // Cast numeric fields for clean Dart JSON parsing compatibility and absolute file URL formatting
            foreach ($messages as &$msg) {
                if (isset($msg['id'])) {
                    $msg['id'] = (int) $msg['id'];
                }
                if (isset($msg['conversation_id'])) {
                    $msg['conversation_id'] = (int) $msg['conversation_id'];
                }
                if (isset($msg['sender_id'])) {
                    $msg['sender_id'] = (int) $msg['sender_id'];
                }
                if (isset($msg['is_read_by_admin'])) {
                    $msg['is_read_by_admin'] = (int) $msg['is_read_by_admin'];
                }
                if (isset($msg['is_read_by_client'])) {
                    $msg['is_read_by_client'] = (int) $msg['is_read_by_client'];
                }
                if (isset($msg['is_read_by_supplier'])) {
                    $msg['is_read_by_supplier'] = (int) $msg['is_read_by_supplier'];
                }
                if (isset($msg['latitude']) && $msg['latitude'] !== null) {
                    $msg['latitude'] = (double) $msg['latitude'];
                }
                if (isset($msg['longitude']) && $msg['longitude'] !== null) {
                    $msg['longitude'] = (double) $msg['longitude'];
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
        if (!$auth)
            return $this->failUnauthorized();

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

            // Determine read flags based on sender role
            $isReadByAdmin = 0;
            $isReadByClient = 0;
            $isReadBySupplier = 0;

            if (($auth->role ?? 'client') === 'supplier') {
                $isReadBySupplier = 1;
                $isReadByClient = 0;
                $isReadByAdmin = 1;
            } else {
                $isReadByClient = 1;
                if ($conversation && !empty($conversation['supplier_id'])) {
                    $isReadBySupplier = 0;
                    $isReadByAdmin = 1;
                } else {
                    $isReadByAdmin = 0;
                    $isReadBySupplier = 1;
                }
            }

            $messageId = $messageModel->insert([
                'conversation_id' => $conversationId,
                'sender_id' => $auth->uid,
                'sender_type' => $auth->role ?? 'client',
                'body' => $body,
                'file_url' => $fileUrl,
                'message_type' => $messageType,
                'latitude' => ($messageType === 'location') ? $latitude : null,
                'longitude' => ($messageType === 'location') ? $longitude : null,
                'is_read_by_admin' => $isReadByAdmin,
                'is_read_by_client' => $isReadByClient,
                'is_read_by_supplier' => $isReadBySupplier,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Generate smart sidebar preview text
            $previewText = $body;
            if (empty(trim($previewText))) {
                if ($messageType === 'image')
                    $previewText = '📷 Gambar';
                elseif ($messageType === 'video')
                    $previewText = '🎥 Video';
                elseif ($messageType === 'file')
                    $previewText = '📁 Berkas';
                elseif ($messageType === 'location')
                    $previewText = '📍 Lokasi';
            } else {
                if ($messageType === 'image')
                    $previewText = '📷 ' . $body;
                elseif ($messageType === 'video')
                    $previewText = '🎥 ' . $body;
                elseif ($messageType === 'file')
                    $previewText = '📁 ' . $body;
                elseif ($messageType === 'location')
                    $previewText = '📍 ' . $body;
            }

            // Update unread count based on sender and receiver
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->find($conversationId);
            $updateData = [
                'last_message_preview' => substr($previewText, 0, 100),
                'last_message_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if (($auth->role ?? 'client') === 'supplier') {
                $newUnreadCount = ($conversation ? intval($conversation['unread_by_client_count'] ?? 0) : 0) + 1;
                $updateData['unread_by_client_count'] = $newUnreadCount;
            } else {
                if ($conversation && !empty($conversation['supplier_id'])) {
                    $newUnreadCount = ($conversation ? intval($conversation['unread_by_supplier_count'] ?? 0) : 0) + 1;
                    $updateData['unread_by_supplier_count'] = $newUnreadCount;
                } else {
                    $newUnreadCount = ($conversation ? intval($conversation['unread_by_admin_count'] ?? 0) : 0) + 1;
                    $updateData['unread_by_admin_count'] = $newUnreadCount;
                }
            }

            $conversationModel->update($conversationId, $updateData);

            // Trigger FCM Notification
            try {
                $conversation = $conversationModel->find($conversationId);
                if ($conversation) {
                    $notificationService = new \App\Modules\Notifications\Services\NotificationService();

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
                        'type' => 'chat',
                        'conversation_id' => (string) $conversationId,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                    ];

                    if (($auth->role ?? 'client') === 'supplier') {
                        // Sent by supplier -> notify client (Client-Supplier chat)
                        $supplierModel = new \App\Modules\Supplier\Models\SupplierModel();
                        $supplier = $supplierModel->find($auth->uid);
                        $supplierName = $supplier ? $supplier['name'] : 'Supplier';
                        $title = 'Pesan Baru dari ' . $supplierName;

                        $notificationService->notifyClient($conversation['client_id'], $title, $notificationBody, $extra, null, 'chat-supplier-client');
                    } else {
                        // Sent by client
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

                        $title = 'Pesan Baru dari ' . $clientName;

                        if (!empty($conversation['supplier_id'])) {
                            // Notify Supplier (Client-Supplier chat)
                            $notificationService->notifySupplier($conversation['supplier_id'], $title, $notificationBody, $extra, null, 'chat-supplier-client');
                        } else {
                            // Notify Admin (Customer Service chat)
                            if (!empty($conversation['admin_id'])) {
                                $notificationService->notifyAdmin($conversation['admin_id'], $title, $notificationBody, $extra, null, 'chat-customer-service');
                            } else {
                                $cat = $conversation['category'] ?? 'general';
                                if ($cat === 'technical') {
                                    $notificationService->sendToPermission('chat_view_technical', $title, $notificationBody, null, $extra, 'chat-customer-service');
                                } elseif ($cat === 'accounting') {
                                    $notificationService->sendToPermission('chat_view_accounting', $title, $notificationBody, null, $extra, 'chat-customer-service');
                                } else {
                                    $notificationService->sendToPermission('chat_view_general', $title, $notificationBody, null, $extra, 'chat-customer-service');
                                    $notificationService->sendToPermission('chat_view', $title, $notificationBody, null, $extra, 'chat-customer-service');
                                }
                            }
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
                    $insertedMessage['id'] = (int) $insertedMessage['id'];
                }
                if (isset($insertedMessage['conversation_id'])) {
                    $insertedMessage['conversation_id'] = (int) $insertedMessage['conversation_id'];
                }
                if (isset($insertedMessage['sender_id'])) {
                    $insertedMessage['sender_id'] = (int) $insertedMessage['sender_id'];
                }
                if (isset($insertedMessage['is_read_by_admin'])) {
                    $insertedMessage['is_read_by_admin'] = (int) $insertedMessage['is_read_by_admin'];
                }
                if (isset($insertedMessage['is_read_by_client'])) {
                    $insertedMessage['is_read_by_client'] = (int) $insertedMessage['is_read_by_client'];
                }
                if (isset($insertedMessage['is_read_by_supplier'])) {
                    $insertedMessage['is_read_by_supplier'] = (int) $insertedMessage['is_read_by_supplier'];
                }
                if (isset($insertedMessage['latitude']) && $insertedMessage['latitude'] !== null) {
                    $insertedMessage['latitude'] = (double) $insertedMessage['latitude'];
                }
                if (isset($insertedMessage['longitude']) && $insertedMessage['longitude'] !== null) {
                    $insertedMessage['longitude'] = (double) $insertedMessage['longitude'];
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
