<?php

namespace App\Modules\Chat\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Chat\Models\MessageModel;
use App\Modules\Chat\Models\ConversationModel;
use App\Modules\Users\Models\UserModel;
use App\Modules\Tukang\Models\TukangModel;
use App\Modules\Supplier\Models\SupplierModel;
use Exception;

class CSChatController extends BaseController
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
        if (can('chat_view_general')) {
            $allowed[] = 'general';
        }
        if (can('chat_view')) {
            $allowed[] = 'general';
        }
        return array_unique($allowed);
    }

    private function _hasAccessToConversation($conversation): bool
    {
        if (can('super_admin_override')) {
            return true;
        }
        $category = $conversation['category'] ?? 'general';
        $allowed = $this->_getAllowedCategories();
        return in_array($category, $allowed);
    }

    private function _loadChatData(string $type = 'cs'): array
    {
        $conversationModel = new ConversationModel();
        $allowedCategories = $this->_getAllowedCategories();

        $data['allowedCategories'] = $allowedCategories;
        $data['pageType'] = $type;

        if (empty($allowedCategories)) {
            $data['conversations'] = [];
            $data['projectConversations'] = [];
            return $data;
        }

        $baseSelectCS = '
            conversations.*,
            CASE 
                WHEN conversations.client_type = "tukang" THEN tukang.name
                WHEN conversations.client_type = "supplier" THEN suppliers.name
                ELSE users.full_name
            END as client_name,
            CASE 
                WHEN conversations.client_type = "tukang" THEN tukang.profile_photo
                WHEN conversations.client_type = "supplier" THEN suppliers.logo_url
                ELSE users.avatar
            END as client_avatar,
            COALESCE(conversations.last_message_at, conversations.created_at) as sort_time
        ';

        $data['conversations'] = $conversationModel->select($baseSelectCS, false)
            ->join('users', 'users.id = conversations.client_id AND conversations.client_type = "client"', 'left')
            ->join('tukang', 'tukang.id = conversations.client_id AND conversations.client_type = "tukang"', 'left')
            ->join('suppliers', 'suppliers.id = conversations.client_id AND conversations.client_type = "supplier"', 'left')
            ->whereIn('conversations.category', $allowedCategories)
            ->orderBy('sort_time', 'DESC')
            ->findAll();
        $data['projectConversations'] = [];

        return $data;
    }

    public function index()
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat chat.');
        }

        $data = $this->_loadChatData('cs');
        return view('App\Modules\Chat\Views\cs', $data);
    }

    public function cs()
    {
        return $this->index();
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
                        CASE 
                            WHEN conversations.client_type = "tukang" THEN tukang.name
                            WHEN conversations.client_type = "supplier" THEN suppliers.name
                            ELSE users.full_name
                        END as client_name,
                        CASE 
                            WHEN conversations.client_type = "tukang" THEN tukang.profile_photo
                            WHEN conversations.client_type = "supplier" THEN suppliers.logo_url
                            ELSE users.avatar
                        END as client_avatar,
                        COALESCE(conversations.last_message_at, conversations.created_at) as sort_time
                    ', false)
                    ->join('users', 'users.id = conversations.client_id AND conversations.client_type = "client"', 'left')
                    ->join('tukang', 'tukang.id = conversations.client_id AND conversations.client_type = "tukang"', 'left')
                    ->join('suppliers', 'suppliers.id = conversations.client_id AND conversations.client_type = "supplier"', 'left')
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

    public function getMessages($conversationId = null)
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk melihat detail chat.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        try {
            $conversationModel = new ConversationModel();
            $messageModel      = new MessageModel();

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

            // Mark admin messages read status
            $messageModel->where('conversation_id', $conversationId)
                ->where('sender_type !=', 'admin')
                ->where('is_read_by_admin', 0)
                ->set(['is_read_by_admin' => 1])
                ->update();

            $conversationModel->update($conversationId, ['unread_by_admin_count' => 0]);

            $messages = $messageModel->where('conversation_id', $conversationId)
                ->orderBy('created_at', 'ASC')
                ->findAll();

            foreach ($messages as &$msg) {
                $msg['id'] = (int) $msg['id'];
                $msg['conversation_id'] = (int) $msg['conversation_id'];
                $msg['sender_id'] = (int) $msg['sender_id'];
                $msg['is_read_by_client'] = (int) $msg['is_read_by_client'];
                $msg['is_read_by_admin'] = (int) $msg['is_read_by_admin'];
                $msg['is_read_by_supplier'] = 0;
                if (isset($msg['file_url']) && !empty($msg['file_url'])) {
                    if (!str_starts_with($msg['file_url'], 'http://') && !str_starts_with($msg['file_url'], 'https://')) {
                        $msg['file_url'] = base_url($msg['file_url']);
                    }
                }
            }

            return $this->response->setJSON([
                'status' => true,
                'data' => $messages,
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

        try {
            $conversationId = $this->request->getPost('conversation_id');
            $messageText    = $this->request->getPost('message');
            $adminId        = session()->get('id');

            if (empty($conversationId)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'ID Obrolan wajib diisi.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(400);
            }

            $conversationModel = new ConversationModel();
            $messageModel      = new MessageModel();

            $conversation = $conversationModel->find($conversationId);
            if (!$conversation) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Obrolan tidak ditemukan.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(404);
            }

            if (($conversation['status'] ?? 'open') === 'closed') {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Obrolan telah ditutup.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(400);
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

            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/chat';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

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
                'is_read_by_admin'  => 1,
                'is_read_by_client' => 0,
                'created_at'        => date('Y-m-d H:i:s'),
            ];

            if ($messageModel->insert($messageData)) {
                $insertedId = $messageModel->getInsertID();
                $messageData['id'] = $insertedId;

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
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage(),
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    private function _sendNativeNotificationWithSound($conversationId, $messageText, $messageType = 'text')
    {
        try {
            $conversationModel = new ConversationModel();
            $conversation = $conversationModel->find($conversationId);
            if (!$conversation) return;

            $notificationService = new \App\Modules\Notifications\Services\NotificationService();
            $title = 'Pesan Baru dari Admin';
            
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
            } elseif ($conversation['client_type'] === 'supplier') {
                $notificationService->notifySupplier($conversation['client_id'], $title, $body, $extra, null, 'chat-customer-service');
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
