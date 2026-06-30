<?php

namespace App\Modules\Chat\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Chat\Models\ProjectConversationModel;
use App\Modules\Chat\Models\ProjectMessageModel;
use App\Modules\Users\Models\UserModel;
use Exception;

class ProjectChatController extends BaseController
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

    private function _loadChatData(string $type = 'project'): array
    {
        $allowedCategories = $this->_getAllowedCategories();

        $data['allowedCategories'] = $allowedCategories;
        $data['pageType'] = $type;

        if (empty($allowedCategories)) {
            $data['conversations'] = [];
            $data['projectConversations'] = [];
            return $data;
        }

        $data['conversations'] = [];
        $projectConversationModel = new ProjectConversationModel();
        $data['projectConversations'] = $projectConversationModel->getConversationsWithDetails();

        return $data;
    }

    public function project()
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat chat.');
        }

        $data = $this->_loadChatData('project');
        return view('App\Modules\Chat\Views\project', $data);
    }

    public function getProjectConversations()
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
            $projectConversationModel = new ProjectConversationModel();
            $conversations = $projectConversationModel->getConversationsWithDetails();

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

    public function getProjectConversationInfo($conversationId = null)
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
            $projectConversationModel = new ProjectConversationModel();
            $conversation = $projectConversationModel->select('
                    project_conversations.*,
                    users.full_name as client_name,
                    users.avatar as client_avatar,
                    COALESCE(project_conversations.last_message_at, project_conversations.created_at) as sort_time,
                    COALESCE(
                        project_conversations.title,
                        dr.location_address,
                        cr.address,
                        rr.address,
                        CONCAT(project_conversations.project_type, " #", project_conversations.project_id)
                    ) as project_name,
                    COALESCE(dr.location_address, cr.address, rr.address) as project_address,
                    COALESCE(dr.total_payment, cr.total_payment, rr.total_payment) as project_total_payment
                ', false)
                ->join('users', 'users.id = project_conversations.client_id', 'left')
                ->join('design_requests dr', 'dr.id = project_conversations.project_id AND project_conversations.project_type = "design"', 'left')
                ->join('construction_requests cr', 'cr.id = project_conversations.project_id AND project_conversations.project_type = "construction"', 'left')
                ->join('renovation_requests rr', 'rr.id = project_conversations.project_id AND project_conversations.project_type = "renovation"', 'left')
                ->where('project_conversations.id', $conversationId)
                ->first();

            if (!$conversation) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Obrolan tidak ditemukan.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(404);
            }

            return $this->response->setJSON([
                'status' => true,
                'data' => $conversation,
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

    public function getProjectMessages($conversationId = null)
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
            $projectMessageModel = new ProjectMessageModel();
            $projectConversationModel = new ProjectConversationModel();

            $conversation = $projectConversationModel->find($conversationId);
            if (!$conversation) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Obrolan tidak ditemukan.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(404);
            }

            $projectMessageModel->where('project_conversation_id', $conversationId)
                          ->where('sender_type !=', 'admin')
                          ->where('is_read_by_admin', 0)
                          ->set(['is_read_by_admin' => 1])
                          ->update();

            $projectConversationModel->update($conversationId, ['unread_by_admin_count' => 0]);

            $afterId = $this->request->getGet('after_id');

            $db = \Config\Database::connect();
            $query = $db->table('project_messages pm')
                ->select('
                    pm.*,
                    CASE 
                        WHEN pm.sender_type = "admin" THEN "Admin Pasangin"
                        ELSE u.full_name
                    END as sender_name,
                    CASE 
                        WHEN pm.sender_type = "admin" THEN "assets/img/avatar/avatar-5.png"
                        ELSE u.avatar
                    END as sender_avatar
                ', false)
                ->join('users u', 'u.id = pm.sender_id AND pm.sender_type = "client"', 'left')
                ->where('pm.project_conversation_id', $conversationId);

            if ($afterId) {
                $query->where('pm.id >', (int)$afterId);
            }

            $messages = $query->orderBy('pm.created_at', 'ASC')
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

    public function sendProjectMessage()
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

        $projectMessageModel = new ProjectMessageModel();
        $projectConversationModel = new ProjectConversationModel();

        $conversation = $projectConversationModel->find($conversationId);
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
                'message' => 'Obrolan ini telah ditutup.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(400);
        }

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

            $mime = $file->getMimeType();
            $fileName = $file->getClientName();
            $fileSize = $file->getSize();

            if (str_starts_with($mime, 'image/')) {
                $messageType = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $messageType = 'video';
            } elseif (str_starts_with($mime, 'audio/')) {
                $messageType = 'audio';
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
            'project_conversation_id' => $conversationId,
            'sender_id'               => $adminId,
            'body'                    => $messageText,
            'sender_type'             => 'admin',
            'file_url'                => $fileUrl,
            'file_name'               => $fileName,
            'file_size'               => $fileSize,
            'message_type'            => $messageType,
            'is_read_by_admin'        => 1,
            'is_read_by_client'       => 0,
            'created_at'              => date('Y-m-d H:i:s'),
        ];

        if ($projectMessageModel->insert($messageData)) {
            $insertedId = $projectMessageModel->getInsertID();
            $messageData['id'] = $insertedId;

            $previewText = $messageText;
            if (empty(trim($previewText))) {
                if ($messageType === 'image') $previewText = '📷 Gambar';
                elseif ($messageType === 'video') $previewText = '🎥 Video';
                elseif ($messageType === 'file') $previewText = '📁 Berkas';
                elseif ($messageType === 'audio') $previewText = '🎵 Audio';
            } else {
                if ($messageType === 'image') $previewText = '📷 ' . $messageText;
                elseif ($messageType === 'video') $previewText = '🎥 ' . $messageText;
                elseif ($messageType === 'file') $previewText = '📁 ' . $messageText;
                elseif ($messageType === 'audio') $previewText = '🎵 ' . $messageText;
            }

            try {
                $newUnreadClient = intval($conversation['unread_by_client_count'] ?? 0) + 1;
                $projectConversationModel->update($conversationId, [
                    'last_message_preview'     => substr($previewText, 0, 100),
                    'last_message_at'          => date('Y-m-d H:i:s'),
                    'last_message_sender_id'   => $adminId,
                    'last_message_sender_type' => 'admin',
                    'unread_by_client_count'    => $newUnreadClient,
                    'updated_at'               => date('Y-m-d H:i:s')
                ]);
            } catch (Exception $e) {
                log_message('error', '[UPDATE PROJECT CONVO ERROR] ' . $e->getMessage());
            }

            try {
                $notificationService = new \App\Modules\Notifications\Services\NotificationService();
                $title = 'Pesan Proyek dari Admin';
                
                $bodyNotif = $messageText;
                if ($messageType === 'image') $bodyNotif = '📷 Mengirim gambar';
                elseif ($messageType === 'video') $bodyNotif = '🎥 Mengirim video';
                elseif ($messageType === 'file') $bodyNotif = '📁 Mengirim berkas';
                elseif ($messageType === 'audio') $bodyNotif = '🎵 Mengirim audio';
                
                $extra = [
                    'type' => 'project_chat',
                    'project_conversation_id' => (string) $conversationId,
                    'project_id' => (string) $conversation['project_id'],
                    'project_type' => $conversation['project_type'],
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                ];

                $notificationService->notifyClient($conversation['client_id'], $title, $bodyNotif, $extra, null, 'chat-project-client');
            } catch (Exception $e) {
                log_message('error', '[FCM PROJECT CHAT ADMIN ERROR] ' . $e->getMessage());
            }

            log_admin_activity('create', 'ProjectChat', 'Tambah Data Chat Proyek');

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

    public function updateProjectStatus($conversationId = null)
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

            $projectConversationModel = new ProjectConversationModel();
            $conversation = $projectConversationModel->find($conversationId);
            if (!$conversation) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Obrolan tidak ditemukan.',
                    'csrf_name' => csrf_token(),
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(404);
            }

            $projectConversationModel->update($conversationId, ['status' => $status]);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Status obrolan proyek berhasil diperbarui.',
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

    public function getAvailableProjects()
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Anda tidak memiliki akses.',
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        try {
            $projectType = $this->request->getGet('project_type') ?? 'design';
            $db = \Config\Database::connect();

            switch ($projectType) {
                case 'construction':
                    $projects = $db->table('construction_requests cr')
                        ->select('cr.id, cr.user_id, cr.full_name as requester_name, cr.address as project_label, cr.status as project_status, users.full_name as client_name, users.avatar as client_avatar')
                        ->join('users', 'users.id = cr.user_id', 'left')
                        ->orderBy('cr.created_at', 'DESC')
                        ->get()->getResultArray();
                    break;

                case 'renovation':
                    $projects = $db->table('renovation_requests rr')
                        ->select('rr.id, rr.user_id, rr.full_name as requester_name, rr.address as project_label, rr.status as project_status, users.full_name as client_name, users.avatar as client_avatar')
                        ->join('users', 'users.id = rr.user_id', 'left')
                        ->orderBy('rr.created_at', 'DESC')
                        ->get()->getResultArray();
                    break;

                default: // design
                    $projects = $db->table('design_requests dr')
                        ->select('dr.id, dr.user_id, dr.full_name as requester_name, CONCAT(dr.design_concept, " — ", LEFT(dr.location_address, 60)) as project_label, dr.status as project_status, users.full_name as client_name, users.avatar as client_avatar')
                        ->join('users', 'users.id = dr.user_id', 'left')
                        ->orderBy('dr.created_at', 'DESC')
                        ->get()->getResultArray();
                    break;
            }

            return $this->response->setJSON([
                'status' => true,
                'data'   => $projects,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $e->getMessage(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    public function createProjectConversation()
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Anda tidak memiliki akses.',
                'csrf_hash' => csrf_hash()
            ])->setStatusCode(403);
        }

        try {
            $projectType = $this->request->getPost('project_type');
            $projectId   = (int) $this->request->getPost('project_id');
            $adminId     = session()->get('user_id') ?? session()->get('id');

            if (empty($projectType) || empty($projectId)) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Tipe proyek dan ID proyek wajib diisi.',
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(400);
            }

            if (!in_array($projectType, ['design', 'construction', 'renovation'])) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Tipe proyek tidak valid.',
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(400);
            }

            $db = \Config\Database::connect();
            switch ($projectType) {
                case 'construction':
                    $project = $db->table('construction_requests')
                        ->select('id, user_id, full_name, address as label')
                        ->where('id', $projectId)->get()->getRowArray();
                    $projectTitle = $project['label'] ?? 'Proyek Konstruksi';
                    break;
                case 'renovation':
                    $project = $db->table('renovation_requests')
                        ->select('id, user_id, full_name, address as label')
                        ->where('id', $projectId)->get()->getRowArray();
                    $projectTitle = $project['label'] ?? 'Proyek Renovasi';
                    break;
                default: // design
                    $project = $db->table('design_requests')
                        ->select('id, user_id, full_name, design_concept, location_address')
                        ->where('id', $projectId)->get()->getRowArray();
                    $projectTitle = !empty($project) ? ($project['design_concept'] . ' — ' . $project['location_address']) : 'Proyek Desain';
                    break;
            }

            if (empty($project) || empty($project['user_id'])) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Proyek tidak ditemukan.',
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(404);
            }

            $clientId = (int) $project['user_id'];

            $userExists = $db->table('users')->where('id', $clientId)->countAllResults() > 0;
            if (!$userExists) {
                return $this->response->setJSON([
                    'status'    => false,
                    'message'   => 'Akun klien proyek ini tidak ditemukan di sistem. Hubungi administrator untuk memperbaiki data.',
                    'csrf_hash' => csrf_hash()
                ])->setStatusCode(200);
            }

            $projectConversationModel = new ProjectConversationModel();
            $existing = $projectConversationModel
                ->where('project_id', $projectId)
                ->where('project_type', $projectType)
                ->where('client_id', $clientId)
                ->first();

            if ($existing) {
                return $this->response->setJSON([
                    'status'          => true,
                    'conversation_id' => $existing['id'],
                    'is_existing'     => true,
                    'message'         => 'Percakapan untuk proyek ini sudah ada.',
                    'csrf_hash'       => csrf_hash()
                ]);
            }

            $conversationData = [
                'project_id'   => $projectId,
                'project_type' => $projectType,
                'client_id'    => $clientId,
                'admin_id'     => $adminId,
                'title'        => substr($projectTitle, 0, 255),
                'status'       => 'open',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];

            $projectConversationModel->insert($conversationData);
            $newId = $projectConversationModel->getInsertID();

            try {
                $notificationService = new \App\Modules\Notifications\Services\NotificationService();
                $extra = [
                    'type'                    => 'project_chat',
                    'project_conversation_id' => (string) $newId,
                    'project_id'              => (string) $projectId,
                    'project_type'            => $projectType,
                    'click_action'            => 'FLUTTER_NOTIFICATION_CLICK'
                ];
                $notificationService->notifyClient(
                    $clientId,
                    'Chat Proyek Baru dari Admin',
                    'Admin Pasangin telah memulai percakapan untuk proyek Anda.',
                    $extra,
                    null,
                    'chat-project-admin-init'
                );
            } catch (Exception $e) {
                log_message('error', '[FCM CREATE PROJECT CHAT ERROR] ' . $e->getMessage());
            }

            log_admin_activity('create', 'ProjectChat', 'Admin memulai chat proyek #' . $newId);

            return $this->response->setJSON([
                'status'          => true,
                'conversation_id' => $newId,
                'is_existing'     => false,
                'message'         => 'Percakapan proyek berhasil dibuat.',
                'csrf_hash'       => csrf_hash()
            ]);
        } catch (Exception $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $e->getMessage(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }
}
