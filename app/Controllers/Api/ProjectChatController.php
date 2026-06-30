<?php

namespace App\Controllers\Api;



use App\Modules\Chat\Models\ProjectConversationModel;
use App\Modules\Chat\Models\ProjectMessageModel;
use App\Modules\Users\Models\UserModel;
use App\Modules\Tukang\Models\TukangModel;
use CodeIgniter\RESTful\ResourceController;

class ProjectChatController extends ResourceController
{
    protected $format = 'json';

    private function getAuthData()
    {
        return $this->request->user ?? null;
    }

    public function getAllProjectConversationsForUser($userId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth || $auth->uid != $userId) {
            return $this->respond(['status' => false, 'message' => 'Token tidak valid.'], 401);
        }

        try {
            $projectConversationModel = new ProjectConversationModel();

            $conversations = $projectConversationModel->select('
                    project_conversations.*,
                    COALESCE(project_conversations.last_message_at, project_conversations.created_at) as sort_time,
                    "Admin Pasangin" as opponent_name,
                    "assets/img/avatar/avatar-5.png" as opponent_avatar,
                    "admin" as opponent_type,
                    project_conversations.unread_by_client_count as unread_count
                ')
                ->where('project_conversations.client_id', $userId)
                ->orderBy('sort_time', 'DESC')
                ->findAll();

            foreach ($conversations as &$conv) {
                $conv['id'] = (int) $conv['id'];
                $conv['project_id'] = (int) $conv['project_id'];
                $conv['client_id'] = (int) $conv['client_id'];
                $conv['admin_id'] = $conv['admin_id'] !== null ? (int) $conv['admin_id'] : null;
                $conv['unread_count'] = (int) $conv['unread_count'];
                $conv['unread_by_admin_count'] = (int) $conv['unread_by_admin_count'];
                $conv['unread_by_client_count'] = (int) $conv['unread_by_client_count'];
                $conv['opponent_avatar'] = base_url($conv['opponent_avatar']);
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

    public function createOrGetProjectConversation()
    {
        $auth = $this->getAuthData();
        if (!$auth) {
            return $this->failUnauthorized();
        }

        if (($auth->role ?? 'client') !== 'client') {
            return $this->respond(['status' => false, 'message' => 'Hanya klien yang dapat mengakses chat proyek.'], 403);
        }

        $userId = $auth->uid;
        $json = $this->request->getJSON();
        $projectId = $json->project_id ?? null;
        $projectType = $json->project_type ?? null;
        $title = $json->title ?? null;

        if (!$projectId || !$projectType) {
            return $this->fail('project_id dan project_type wajib diisi.');
        }

        if (!in_array($projectType, ['design', 'construction', 'renovation'])) {
            return $this->fail('project_type tidak valid.');
        }

        try {
            $projectAddress = '';
            if ($projectType === 'design') {
                $designModel = new \App\Modules\Design\Models\DesignRequestModel();
                $project = $designModel->where('id', $projectId)->where('user_id', $userId)->first();
                if (!$project) {
                    return $this->failNotFound('Proyek Desain tidak ditemukan atau bukan milik Anda.');
                }
                $projectAddress = $project['location_address'] ?? '';
            } elseif ($projectType === 'construction') {
                $constructionModel = new \App\Modules\Construction\Models\ConstructionModel();
                $project = $constructionModel->where('id', $projectId)->where('user_id', $userId)->first();
                if (!$project) {
                    return $this->failNotFound('Proyek Konstruksi tidak ditemukan atau bukan milik Anda.');
                }
                $projectAddress = $project['address'] ?? '';
            } elseif ($projectType === 'renovation') {
                $renovationModel = new \App\Modules\Renovation\Models\RenovationModel();
                $project = $renovationModel->where('id', $projectId)->where('user_id', $userId)->first();
                if (!$project) {
                    return $this->failNotFound('Proyek Renovasi tidak ditemukan atau bukan milik Anda.');
                }
                $projectAddress = $project['address'] ?? '';
            }

            if (empty($title)) {
                $typeName = '';
                if ($projectType === 'design') $typeName = 'Desain';
                elseif ($projectType === 'construction') $typeName = 'Konstruksi';
                elseif ($projectType === 'renovation') $typeName = 'Renovasi';

                $title = "Diskusi Proyek {$typeName}" . ($projectAddress ? ' - ' . $projectAddress : '');
            }

            $projectConversationModel = new ProjectConversationModel();
            $existing = $projectConversationModel->where('project_id', $projectId)
                ->where('project_type', $projectType)
                ->where('client_id', $userId)
                ->first();

            if ($existing) {
                $existing['id'] = (int) $existing['id'];
                $existing['project_id'] = (int) $existing['project_id'];
                $existing['client_id'] = (int) $existing['client_id'];
                $existing['admin_id'] = $existing['admin_id'] !== null ? (int) $existing['admin_id'] : null;

                return $this->respond([
                    'status' => true,
                    'message' => 'Obrolan proyek ditemukan.',
                    'data' => $existing
                ]);
            }

            $id = $projectConversationModel->insert([
                'project_id'   => $projectId,
                'project_type' => $projectType,
                'client_id'    => $userId,
                'title'        => $title,
                'status'       => 'open',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);

            $newConvo = $projectConversationModel->find($id);
            $newConvo['id'] = (int) $newConvo['id'];
            $newConvo['project_id'] = (int) $newConvo['project_id'];
            $newConvo['client_id'] = (int) $newConvo['client_id'];
            $newConvo['admin_id'] = $newConvo['admin_id'] !== null ? (int) $newConvo['admin_id'] : null;

            return $this->respondCreated([
                'status' => true,
                'message' => 'Obrolan proyek berhasil dibuat.',
                'data' => $newConvo
            ]);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function getProjectMessages($conversationId = null)
    {
        $auth = $this->getAuthData();
        if (!$auth) {
            return $this->failUnauthorized();
        }

        try {
            $projectMessageModel = new ProjectMessageModel();
            $projectConversationModel = new ProjectConversationModel();
            $conversation = $projectConversationModel->find($conversationId);
            if (!$conversation) {
                return $this->failNotFound('Obrolan tidak ditemukan.');
            }

            if ($conversation['client_id'] != $auth->uid) {
                return $this->failUnauthorized('Anda tidak memiliki akses ke obrolan ini.');
            }

            $projectMessageModel->where('project_conversation_id', $conversationId)
                ->where('sender_type', 'admin')
                ->where('is_read_by_client', 0)
                ->set(['is_read_by_client' => 1])
                ->update();

            $projectConversationModel->update($conversationId, ['unread_by_client_count' => 0]);

            $afterId = $this->request->getGet('after_id');

            $query = $projectMessageModel->where('project_conversation_id', $conversationId);
            if ($afterId) {
                $query->where('id >', (int)$afterId);
            }

            $messages = $query->orderBy('created_at', 'ASC')
                ->findAll();

            foreach ($messages as &$msg) {
                $msg['id'] = (int) $msg['id'];
                $msg['project_conversation_id'] = (int) $msg['project_conversation_id'];
                $msg['sender_id'] = (int) $msg['sender_id'];
                $msg['is_read_by_admin'] = (int) $msg['is_read_by_admin'];
                $msg['is_read_by_client'] = (int) $msg['is_read_by_client'];
                if (isset($msg['file_size']) && $msg['file_size'] !== null) {
                    $msg['file_size'] = (int) $msg['file_size'];
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
                'data' => ['project_conversation_id' => intval($conversationId), 'messages' => $messages]
            ], 200);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal mengambil pesan proyek: ' . $e->getMessage());
        }
    }

    public function sendProjectMessage()
    {
        $auth = $this->getAuthData();
        if (!$auth) {
            return $this->failUnauthorized();
        }

        $contentType = $this->request->getHeaderLine('Content-Type');
        $json = null;
        if (str_contains($contentType, 'application/json')) {
            $json = $this->request->getJSON();
        }

        $conversationId = $json ? ($json->project_conversation_id ?? $json->conversation_id ?? null) : ($this->request->getPost('project_conversation_id') ?? $this->request->getPost('conversation_id'));
        $body = $json ? ($json->body ?? $json->message ?? '') : ($this->request->getPost('body') ?? $this->request->getPost('message') ?? '');
        $attachmentType = $json ? ($json->attachment_type ?? null) : $this->request->getPost('attachment_type');
        $latitude = $json ? ($json->latitude ?? null) : $this->request->getPost('latitude');
        $longitude = $json ? ($json->longitude ?? null) : $this->request->getPost('longitude');

        if (!$conversationId) {
            return $this->fail('Data tidak lengkap: project_conversation_id wajib diisi.');
        }

        try {
            $projectConversationModel = new ProjectConversationModel();
            $conversation = $projectConversationModel->find($conversationId);
            if (!$conversation) {
                return $this->failNotFound('Obrolan proyek tidak ditemukan.');
            }

            if (($conversation['status'] ?? 'open') === 'closed') {
                return $this->respond([
                    'status' => false,
                    'message' => 'Obrolan proyek ini telah ditutup.'
                ], 400);
            }

            if ($conversation['client_id'] != $auth->uid) {
                return $this->failUnauthorized('Anda tidak memiliki akses ke obrolan ini.');
            }

            $projectMessageModel = new ProjectMessageModel();
            $fileUrl = null;
            $fileName = null;
            $fileSize = null;
            $messageType = 'text';

            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/chat';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $fileName = $file->getClientName();
                $fileSize = $file->getSize();
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $fileUrl = 'uploads/chat/' . $newName;

                $messageType = $attachmentType ?? 'file';
                if (!in_array($messageType, ['image', 'video', 'file', 'audio'])) {
                    $messageType = 'file';
                }
            } elseif ($latitude !== null && $longitude !== null && $latitude !== '' && $longitude !== '') {
                $messageType = 'location';
            }

            if ($body === null) {
                $body = '';
            }

            $messageId = $projectMessageModel->insert([
                'project_conversation_id' => $conversationId,
                'sender_id'               => $auth->uid,
                'sender_type'             => 'client',
                'body'                    => $body,
                'file_url'                => $fileUrl,
                'file_name'               => $fileName,
                'file_size'               => $fileSize,
                'message_type'            => $messageType,
                'latitude'                => ($messageType === 'location') ? $latitude : null,
                'longitude'               => ($messageType === 'location') ? $longitude : null,
                'is_read_by_admin'        => 0,
                'is_read_by_client'       => 1,
                'created_at'              => date('Y-m-d H:i:s'),
            ]);

            $previewText = $body;
            if (empty(trim($previewText))) {
                if ($messageType === 'image') $previewText = '📷 Gambar';
                elseif ($messageType === 'video') $previewText = '🎥 Video';
                elseif ($messageType === 'file') $previewText = '📁 Berkas';
                elseif ($messageType === 'audio') $previewText = '🎵 Audio';
                elseif ($messageType === 'location') $previewText = '📍 Lokasi';
            } else {
                if ($messageType === 'image') $previewText = '📷 ' . $body;
                elseif ($messageType === 'video') $previewText = '🎥 ' . $body;
                elseif ($messageType === 'file') $previewText = '📁 ' . $body;
                elseif ($messageType === 'audio') $previewText = '🎵 ' . $body;
                elseif ($messageType === 'location') $previewText = '📍 ' . $body;
            }

            $newUnreadAdmin = intval($conversation['unread_by_admin_count'] ?? 0) + 1;
            $projectConversationModel->update($conversationId, [
                'last_message_preview'     => substr($previewText, 0, 100),
                'last_message_at'          => date('Y-m-d H:i:s'),
                'last_message_sender_id'   => $auth->uid,
                'last_message_sender_type' => 'client',
                'unread_by_admin_count'    => $newUnreadAdmin,
                'updated_at'               => date('Y-m-d H:i:s')
            ]);

            try {
                $userModel = new UserModel();
                $user = $userModel->find($auth->uid);
                $clientName = $user ? $user['full_name'] : 'Pelanggan';

                $title = "Pesan Proyek dari {$clientName}";
                $bodyNotif = (strlen($body) > 80) ? substr($body, 0, 77) . '...' : $body;
                if ($messageType === 'image') $bodyNotif = '📷 Mengirim gambar';
                elseif ($messageType === 'video') $bodyNotif = '🎥 Mengirim video';
                elseif ($messageType === 'file') $bodyNotif = '📁 Mengirim berkas';
                elseif ($messageType === 'audio') $bodyNotif = '🎵 Mengirim audio';
                elseif ($messageType === 'location') $bodyNotif = '📍 Berbagi lokasi';

                $extra = [
                    'type' => 'project_chat',
                    'project_conversation_id' => (string) $conversationId,
                    'project_id' => (string) $conversation['project_id'],
                    'project_type' => $conversation['project_type'],
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                ];

                $notificationService = new \App\Modules\Notifications\Services\NotificationService();
                if (!empty($conversation['admin_id'])) {
                    $notificationService->notifyAdmin($conversation['admin_id'], $title, $bodyNotif, $extra, null, 'chat-project-admin');
                } else {
                    $notificationService->sendToPermission('chat_view', $title, $bodyNotif, null, $extra, 'chat-project-admin');
                }
            } catch (\Exception $ex) {
                log_message('error', '[FCM PROJECT CHAT CLIENT API ERROR] ' . $ex->getMessage());
            }

            $insertedMessage = $projectMessageModel->find($messageId);
            $insertedMessage['id'] = (int) $insertedMessage['id'];
            $insertedMessage['project_conversation_id'] = (int) $insertedMessage['project_conversation_id'];
            $insertedMessage['sender_id'] = (int) $insertedMessage['sender_id'];
            $insertedMessage['is_read_by_admin'] = (int) $insertedMessage['is_read_by_admin'];
            $insertedMessage['is_read_by_client'] = (int) $insertedMessage['is_read_by_client'];
            if ($insertedMessage['file_size'] !== null) {
                $insertedMessage['file_size'] = (int) $insertedMessage['file_size'];
            }
            if ($insertedMessage['latitude'] !== null) {
                $insertedMessage['latitude'] = (double) $insertedMessage['latitude'];
            }
            if ($insertedMessage['longitude'] !== null) {
                $insertedMessage['longitude'] = (double) $insertedMessage['longitude'];
            }
            if (!empty($insertedMessage['file_url'])) {
                if (!str_starts_with($insertedMessage['file_url'], 'http://') && !str_starts_with($insertedMessage['file_url'], 'https://')) {
                    $insertedMessage['file_url'] = base_url($insertedMessage['file_url']);
                }
            }

            return $this->respondCreated(['status' => true, 'data' => $insertedMessage]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal kirim pesan proyek: ' . $e->getMessage());
        }
    }
}
