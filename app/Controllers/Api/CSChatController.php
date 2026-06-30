<?php

namespace App\Controllers\Api;



use App\Modules\Chat\Models\ConversationModel;
use App\Modules\Chat\Models\MessageModel;
use App\Modules\Users\Models\UserModel;
use App\Modules\Tukang\Models\TukangModel;
use CodeIgniter\RESTful\ResourceController;

class CSChatController extends ResourceController
{
    protected $format = 'json';

    private function getAuthData()
    {
        return $this->request->user ?? null;
    }

    public function getCSConversations($userId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth || $auth->uid != $userId) {
            return $this->respond(['status' => false, 'message' => 'Token tidak valid.'], 401);
        }

        try {
            $conversationModel = new ConversationModel();
            $conversations = $conversationModel->select('
                    conversations.*,
                    COALESCE(conversations.last_message_at, conversations.created_at) as sort_time,
                    conversations.unread_by_client_count as unread_count
                ')
                ->where('conversations.client_id', $userId)
                ->where('conversations.client_type', $auth->role ?? 'client')
                ->orderBy('sort_time', 'DESC')
                ->findAll();

            foreach ($conversations as &$conv) {
                $conv['opponent_name'] = 'Admin Pasangin';
                $conv['opponent_avatar'] = base_url('assets/img/avatar/avatar-5.png');
                $conv['opponent_type'] = 'admin';
                $conv['supplier_id'] = null;

                $conv['id'] = (int) $conv['id'];
                $conv['client_id'] = (int) $conv['client_id'];
                $conv['admin_id'] = $conv['admin_id'] !== null ? (int) $conv['admin_id'] : null;
                $conv['unread_count'] = (int) $conv['unread_count'];
                $conv['unread_by_admin_count'] = (int) $conv['unread_by_admin_count'];
                $conv['unread_by_client_count'] = (int) $conv['unread_by_client_count'];
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

    public function createCSConversation()
    {
        $auth = $this->getAuthData();
        if (!$auth) return $this->failUnauthorized();

        $userId = $auth->uid;
        $role = $auth->role ?? 'client';

        $json = $this->request->getJSON();
        $title = $json->title ?? 'Bantuan Pasangin';
        $category = $json->category ?? 'general';

        if (!in_array($category, ['technical', 'accounting', 'general'])) {
            $category = 'general';
        }

        try {
            $conversationModel = new ConversationModel();
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
            $newConvo['id'] = (int) $newConvo['id'];
            $newConvo['client_id'] = (int) $newConvo['client_id'];

            return $this->respondCreated([
                'status' => true,
                'message' => 'Obrolan CS baru berhasil dibuat.',
                'data' => $newConvo
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function getCSMessages($conversationId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth) return $this->failUnauthorized();

        try {
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->find($conversationId);
            if (!$conversation) {
                return $this->failNotFound('Obrolan tidak ditemukan.');
            }

            $messageModel = new MessageModel();
            $messageModel->where('conversation_id', $conversationId)
                ->where('sender_type', 'admin')
                ->where('is_read_by_client', 0)
                ->set(['is_read_by_client' => 1])
                ->update();

            $conversationModel->update($conversationId, ['unread_by_client_count' => 0]);

            $messages = $messageModel->where('conversation_id', $conversationId)
                ->orderBy('created_at', 'ASC')
                ->findAll();

            foreach ($messages as &$msg) {
                $msg['id'] = (int) $msg['id'];
                $msg['conversation_id'] = (int) $msg['conversation_id'];
                $msg['sender_id'] = (int) $msg['sender_id'];
                $msg['is_read_by_admin'] = (int) $msg['is_read_by_admin'];
                $msg['is_read_by_client'] = (int) $msg['is_read_by_client'];
                $msg['is_read_by_supplier'] = 0;
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

    public function sendCSMessage()
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
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->find($conversationId);
            if ($conversation && ($conversation['status'] ?? 'open') === 'closed') {
                return $this->respond(['status' => false, 'message' => 'Obrolan ini telah ditutup.'], 400);
            }

            $messageModel = new MessageModel();
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

            $messageId = $messageModel->insert([
                'conversation_id' => $conversationId,
                'sender_id' => $auth->uid,
                'sender_type' => $auth->role ?? 'client',
                'body' => $body,
                'file_url' => $fileUrl,
                'message_type' => $messageType,
                'latitude' => ($messageType === 'location') ? $latitude : null,
                'longitude' => ($messageType === 'location') ? $longitude : null,
                'is_read_by_admin' => 0,
                'is_read_by_client' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $previewText = $body;
            if (empty(trim($previewText))) {
                $previewText = ($messageType === 'image') ? '📷 Gambar' : (($messageType === 'video') ? '🎥 Video' : (($messageType === 'file') ? '📁 Berkas' : '📍 Lokasi'));
            } else {
                $previewText = (($messageType === 'image') ? '📷 ' : (($messageType === 'video') ? '🎥 ' : (($messageType === 'file') ? '📁 ' : (($messageType === 'location') ? '📍 ' : '')))) . $body;
            }

            $newUnreadCount = ($conversation ? intval($conversation['unread_by_admin_count'] ?? 0) : 0) + 1;
            $conversationModel->update($conversationId, [
                'last_message_preview' => substr($previewText, 0, 100),
                'last_message_at' => date('Y-m-d H:i:s'),
                'unread_by_admin_count' => $newUnreadCount,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            try {
                $notificationService = new \App\Modules\Notifications\Services\NotificationService();
                $clientName = 'Pelanggan';
                if ($conversation['client_type'] === 'tukang') {
                    $tukangModel = new TukangModel();
                    $tukang = $tukangModel->find($conversation['client_id']);
                    if ($tukang) $clientName = $tukang['name'];
                } elseif ($conversation['client_type'] === 'supplier') {
                    $supplierModel = new \App\Modules\Supplier\Models\SupplierModel();
                    $supplier = $supplierModel->find($conversation['client_id']);
                    if ($supplier) $clientName = $supplier['name'];
                } else {
                    $userModel = new UserModel();
                    $user = $userModel->find($conversation['client_id']);
                    if ($user) $clientName = $user['full_name'];
                }

                $title = 'Pesan Baru dari ' . $clientName;
                $notificationBody = (strlen($body) > 80) ? substr($body, 0, 77) . '...' : $body;
                $extra = [
                    'type' => 'chat',
                    'conversation_id' => (string) $conversationId,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                ];

                if (!empty($conversation['admin_id'])) {
                    $notificationService->notifyAdmin($conversation['admin_id'], $title, $notificationBody, $extra, null, 'chat-customer-service');
                } else {
                    $cat = $conversation['category'] ?? 'general';
                    $perm = ($cat === 'technical') ? 'chat_view_technical' : (($cat === 'accounting') ? 'chat_view_accounting' : 'chat_view_general');
                    $notificationService->sendToPermission($perm, $title, $notificationBody, null, $extra, 'chat-customer-service');
                    if ($perm !== 'chat_view_general') {
                        $notificationService->sendToPermission('chat_view', $title, $notificationBody, null, $extra, 'chat-customer-service');
                    }
                }
            } catch (\Exception $ex) {
                log_message('error', '[FCM CS CHAT API ERROR] ' . $ex->getMessage());
            }

            $insertedMessage = $messageModel->find($messageId);
            if ($insertedMessage) {
                $insertedMessage['id'] = (int) $insertedMessage['id'];
                $insertedMessage['conversation_id'] = (int) $insertedMessage['conversation_id'];
                $insertedMessage['sender_id'] = (int) $insertedMessage['sender_id'];
                $insertedMessage['is_read_by_admin'] = (int) $insertedMessage['is_read_by_admin'];
                $insertedMessage['is_read_by_client'] = (int) $insertedMessage['is_read_by_client'];
                $insertedMessage['is_read_by_supplier'] = 0;
                if (isset($insertedMessage['file_url']) && !empty($insertedMessage['file_url'])) {
                    $insertedMessage['file_url'] = base_url($insertedMessage['file_url']);
                }
            }

            return $this->respondCreated(['status' => true, 'data' => $insertedMessage]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal kirim CS chat: ' . $e->getMessage());
        }
    }
}
