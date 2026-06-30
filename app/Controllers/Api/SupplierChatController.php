<?php

namespace App\Controllers\Api;



use App\Modules\Chat\Models\SupplierConversationModel;
use App\Modules\Chat\Models\SupplierMessageModel;
use App\Modules\Users\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class SupplierChatController extends ResourceController
{
    protected $format = 'json';

    private function getAuthData()
    {
        return $this->request->user ?? null;
    }

    public function getSupplierConversations($userId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth || $auth->uid != $userId) {
            return $this->respond(['status' => false, 'message' => 'Token tidak valid.'], 401);
        }

        try {
            $supplierConvoModel = new SupplierConversationModel();
            
            if (($auth->role ?? 'client') === 'supplier') {
                $conversations = $supplierConvoModel->select('
                        supplier_conversations.*,
                        COALESCE(supplier_conversations.last_message_at, supplier_conversations.created_at) as sort_time,
                        users.full_name as opponent_name,
                        users.avatar as opponent_avatar,
                        "client" as opponent_type,
                        supplier_conversations.unread_by_supplier_count as unread_count
                    ')
                    ->join('users', 'users.id = supplier_conversations.client_id', 'left')
                    ->where('supplier_conversations.supplier_id', $userId)
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
                $conversations = $supplierConvoModel->select('
                        supplier_conversations.*,
                        COALESCE(supplier_conversations.last_message_at, supplier_conversations.created_at) as sort_time,
                        suppliers.name as supplier_name,
                        suppliers.logo_url as supplier_logo,
                        supplier_conversations.unread_by_client_count as unread_count
                    ')
                    ->join('suppliers', 'suppliers.id = supplier_conversations.supplier_id', 'left')
                    ->where('supplier_conversations.client_id', $userId)
                    ->orderBy('sort_time', 'DESC')
                    ->findAll();

                foreach ($conversations as &$conv) {
                    $conv['opponent_name'] = $conv['supplier_name'] ?? 'Supplier';
                    $conv['opponent_avatar'] = !empty($conv['supplier_logo'])
                        ? (str_starts_with($conv['supplier_logo'], 'http') ? $conv['supplier_logo'] : base_url('uploads/supplier/' . $conv['supplier_logo']))
                        : base_url('uploads/supplier/default.png');
                    $conv['opponent_type'] = 'supplier';
                    unset($conv['supplier_name']);
                    unset($conv['supplier_logo']);
                }
            }

            foreach ($conversations as &$conv) {
                $conv['id'] = (int) $conv['id'];
                $conv['client_id'] = (int) $conv['client_id'];
                $conv['supplier_id'] = (int) $conv['supplier_id'];
                $conv['unread_count'] = (int) $conv['unread_count'];
                $conv['unread_by_supplier_count'] = (int) $conv['unread_by_supplier_count'];
                $conv['unread_by_client_count'] = (int) $conv['unread_by_client_count'];
                
                $conv['client_type'] = 'client';
                $conv['admin_id'] = null;
                $conv['category'] = 'general';
            }

            return $this->respond([
                'status' => true,
                'conversations' => $conversations ?? []
            ], 200);
        } catch (\Exception $e) {
            return $this->respond(['status' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    public function createSupplierConversation()
    {
        $auth = $this->getAuthData();
        if (!$auth) return $this->failUnauthorized();

        $userId = $auth->uid;
        $json = $this->request->getJSON();
        $supplierId = $json->supplier_id ?? null;

        try {
            $supplierConvoModel = new SupplierConversationModel();

            if (($auth->role ?? 'client') === 'supplier') {
                $clientId = $json->client_id ?? null;
                if (!$clientId) {
                    return $this->fail('client_id wajib diisi oleh supplier.');
                }

                $existing = $supplierConvoModel->where('client_id', $clientId)
                    ->where('supplier_id', $userId)
                    ->first();

                if ($existing) {
                    $existing['id'] = (int) $existing['id'];
                    $existing['client_id'] = (int) $existing['client_id'];
                    $existing['supplier_id'] = (int) $existing['supplier_id'];
                    return $this->respond(['status' => true, 'message' => 'Obrolan ditemukan.', 'data' => $existing]);
                }

                $id = $supplierConvoModel->insert([
                    'client_id' => $clientId,
                    'supplier_id' => $userId,
                    'status' => 'open',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $newConvo = $supplierConvoModel->find($id);
                $newConvo['id'] = (int) $newConvo['id'];
                $newConvo['client_id'] = (int) $newConvo['client_id'];
                $newConvo['supplier_id'] = (int) $newConvo['supplier_id'];
                return $this->respondCreated(['status' => true, 'message' => 'Obrolan berhasil dibuat.', 'data' => $newConvo]);
            }

            if (!$supplierId) {
                return $this->fail('supplier_id wajib diisi oleh client.');
            }

            $existing = $supplierConvoModel->where('client_id', $userId)
                ->where('supplier_id', $supplierId)
                ->first();

            if ($existing) {
                $existing['id'] = (int) $existing['id'];
                $existing['client_id'] = (int) $existing['client_id'];
                $existing['supplier_id'] = (int) $existing['supplier_id'];
                return $this->respond(['status' => true, 'message' => 'Obrolan ditemukan.', 'data' => $existing]);
            }

            $id = $supplierConvoModel->insert([
                'client_id' => $userId,
                'supplier_id' => $supplierId,
                'status' => 'open',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $newConvo = $supplierConvoModel->find($id);
            $newConvo['id'] = (int) $newConvo['id'];
            $newConvo['client_id'] = (int) $newConvo['client_id'];
            $newConvo['supplier_id'] = (int) $newConvo['supplier_id'];
            return $this->respondCreated(['status' => true, 'message' => 'Obrolan berhasil dibuat.', 'data' => $newConvo]);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function getSupplierMessages($conversationId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth) return $this->failUnauthorized();

        try {
            $supplierConvoModel = new SupplierConversationModel();
            $conversation = $supplierConvoModel->find($conversationId);
            if (!$conversation) {
                return $this->failNotFound('Obrolan supplier tidak ditemukan.');
            }

            $supplierMsgModel = new SupplierMessageModel();

            if (($auth->role ?? 'client') === 'supplier') {
                $supplierMsgModel->where('supplier_conversation_id', $conversationId)
                    ->where('sender_type', 'client')
                    ->where('is_read_by_supplier', 0)
                    ->set(['is_read_by_supplier' => 1])
                    ->update();

                $supplierConvoModel->update($conversationId, ['unread_by_supplier_count' => 0]);
            } else {
                $supplierMsgModel->where('supplier_conversation_id', $conversationId)
                    ->where('sender_type', 'supplier')
                    ->where('is_read_by_client', 0)
                    ->set(['is_read_by_client' => 1])
                    ->update();

                $supplierConvoModel->update($conversationId, ['unread_by_client_count' => 0]);
            }

            $messages = $supplierMsgModel->where('supplier_conversation_id', $conversationId)
                ->orderBy('created_at', 'ASC')
                ->findAll();

            foreach ($messages as &$msg) {
                $msg['id'] = (int) $msg['id'];
                $msg['conversation_id'] = (int) $msg['supplier_conversation_id'];
                $msg['sender_id'] = (int) $msg['sender_id'];
                $msg['is_read_by_admin'] = 1;
                $msg['is_read_by_client'] = (int) $msg['is_read_by_client'];
                $msg['is_read_by_supplier'] = (int) $msg['is_read_by_supplier'];
                if (isset($msg['latitude']) && $msg['latitude'] !== null) {
                    $msg['latitude'] = (double) $msg['latitude'];
                }
                if (isset($msg['longitude']) && $msg['longitude'] !== null) {
                    $msg['longitude'] = (double) $msg['longitude'];
                }
                if (isset($msg['file_url']) && !empty($msg['file_url'])) {
                    $msg['file_url'] = base_url($msg['file_url']);
                }
            }

            return $this->respond([
                'status' => true,
                'data' => ['conversation_id' => intval($conversationId), 'messages' => $messages]
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError('Gagal mengambil pesan supplier: ' . $e->getMessage());
        }
    }

    public function sendSupplierMessage()
    {
        $auth = $this->getAuthData();
        if (!$auth) return $this->failUnauthorized();

        $json = null;
        $contentType = $this->request->getHeaderLine('Content-Type');
        if (str_contains($contentType, 'application/json')) {
            $json = $this->request->getJSON();
        }

        $conversationId = $json ? ($json->conversation_id ?? null) : $this->request->getPost('conversation_id');
        $body = $json ? ($json->body ?? $json->message ?? '') : ($this->request->getPost('body') ?? $this->request->getPost('message') ?? '');
        $attachmentType = $json ? ($json->attachment_type ?? null) : $this->request->getPost('attachment_type');
        $latitude = $json ? ($json->latitude ?? null) : $this->request->getPost('latitude');
        $longitude = $json ? ($json->longitude ?? null) : $this->request->getPost('longitude');

        if (!$conversationId) {
            return $this->fail('Data tidak lengkap: conversation_id wajib diisi.');
        }

        try {
            $supplierConvoModel = new SupplierConversationModel();
            $conversation = $supplierConvoModel->find($conversationId);
            if ($conversation && ($conversation['status'] ?? 'open') === 'closed') {
                return $this->respond(['status' => false, 'message' => 'Obrolan ini telah ditutup.'], 400);
            }

            $supplierMsgModel = new SupplierMessageModel();
            $fileUrl = null;
            $messageType = 'text';

            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/chat';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
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

            if ($body === null) $body = '';

            $isReadByClient = 0;
            $isReadBySupplier = 0;

            if (($auth->role ?? 'client') === 'supplier') {
                $isReadBySupplier = 1;
                $isReadByClient = 0;
            } else {
                $isReadByClient = 1;
                $isReadBySupplier = 0;
            }

            $messageId = $supplierMsgModel->insert([
                'supplier_conversation_id' => $conversationId,
                'sender_id' => $auth->uid,
                'sender_type' => $auth->role ?? 'client',
                'body' => $body,
                'file_url' => $fileUrl,
                'message_type' => $messageType,
                'latitude' => ($messageType === 'location') ? $latitude : null,
                'longitude' => ($messageType === 'location') ? $longitude : null,
                'is_read_by_client' => $isReadByClient,
                'is_read_by_supplier' => $isReadBySupplier,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $previewText = $body;
            if (empty(trim($previewText))) {
                $previewText = ($messageType === 'image') ? '📷 Gambar' : (($messageType === 'video') ? '🎥 Video' : (($messageType === 'file') ? '📁 Berkas' : '📍 Lokasi'));
            } else {
                $previewText = (($messageType === 'image') ? '📷 ' : (($messageType === 'video') ? '🎥 ' : (($messageType === 'file') ? '📁 ' : (($messageType === 'location') ? '📍 ' : '')))) . $body;
            }

            $updateData = [
                'last_message_preview' => substr($previewText, 0, 100),
                'last_message_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if (($auth->role ?? 'client') === 'supplier') {
                $newUnreadCount = ($conversation ? intval($conversation['unread_by_client_count'] ?? 0) : 0) + 1;
                $updateData['unread_by_client_count'] = $newUnreadCount;
            } else {
                $newUnreadCount = ($conversation ? intval($conversation['unread_by_supplier_count'] ?? 0) : 0) + 1;
                $updateData['unread_by_supplier_count'] = $newUnreadCount;
            }

            $supplierConvoModel->update($conversationId, $updateData);

            try {
                $notificationService = new \App\Modules\Notifications\Services\NotificationService();
                $notificationBody = (strlen($body) > 80) ? substr($body, 0, 77) . '...' : $body;
                $extra = [
                    'type' => 'chat',
                    'conversation_id' => (string) $conversationId,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                ];

                if (($auth->role ?? 'client') === 'supplier') {
                    $supplierModel = new \App\Modules\Supplier\Models\SupplierModel();
                    $supplier = $supplierModel->find($auth->uid);
                    $supplierName = $supplier ? $supplier['name'] : 'Supplier';
                    $title = 'Pesan Baru dari ' . $supplierName;

                    $notificationService->notifyClient($conversation['client_id'], $title, $notificationBody, $extra, null, 'chat-supplier-client');
                } else {
                    $userModel = new UserModel();
                    $user = $userModel->find($conversation['client_id']);
                    $clientName = $user ? $user['full_name'] : 'Pelanggan';
                    $title = 'Pesan Baru dari ' . $clientName;

                    $notificationService->notifySupplier($conversation['supplier_id'], $title, $notificationBody, $extra, null, 'chat-supplier-client');
                }
            } catch (\Exception $ex) {
                log_message('error', '[FCM SUPPLIER CHAT API ERROR] ' . $ex->getMessage());
            }

            $insertedMessage = $supplierMsgModel->find($messageId);
            if ($insertedMessage) {
                $insertedMessage['id'] = (int) $insertedMessage['id'];
                $insertedMessage['conversation_id'] = (int) $insertedMessage['supplier_conversation_id'];
                $insertedMessage['sender_id'] = (int) $insertedMessage['sender_id'];
                $insertedMessage['is_read_by_admin'] = 1;
                $insertedMessage['is_read_by_client'] = (int) $insertedMessage['is_read_by_client'];
                $insertedMessage['is_read_by_supplier'] = (int) $insertedMessage['is_read_by_supplier'];
                if (isset($insertedMessage['file_url']) && !empty($insertedMessage['file_url'])) {
                    $insertedMessage['file_url'] = base_url($insertedMessage['file_url']);
                }
            }

            return $this->respondCreated(['status' => true, 'data' => $insertedMessage]);

        } catch (\Exception $e) {
            return $this->failServerError('Gagal mengirim pesan supplier: ' . $e->getMessage());
        }
    }
}
