<?php

namespace App\Modules\Chat\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Chat\Models\SupplierConversationModel;
use App\Modules\Chat\Models\SupplierMessageModel;
use Exception;

class SupplierChatController extends BaseController
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

    private function _loadChatData(string $type = 'monitoring'): array
    {
        $allowedCategories = $this->_getAllowedCategories();

        $data['allowedCategories'] = $allowedCategories;
        $data['pageType'] = $type;

        if (empty($allowedCategories)) {
            $data['conversations'] = [];
            $data['projectConversations'] = [];
            return $data;
        }

        $supplierConvoModel = new SupplierConversationModel();
        $baseSelectSupplier = '
            supplier_conversations.*,
            "client" as client_type,
            "general" as category,
            "Obrolan" as title,
            CONCAT(users.full_name, " ↔ ", suppliers.name) as client_name,
            users.avatar as client_avatar,
            suppliers.name as supplier_name,
            supplier_conversations.unread_by_supplier_count as unread_by_admin_count,
            COALESCE(supplier_conversations.last_message_at, supplier_conversations.created_at) as sort_time
        ';
        $data['conversations'] = $supplierConvoModel->select($baseSelectSupplier, false)
            ->join('users', 'users.id = supplier_conversations.client_id', 'left')
            ->join('suppliers', 'suppliers.id = supplier_conversations.supplier_id', 'left')
            ->orderBy('sort_time', 'DESC')
            ->findAll();
        $data['projectConversations'] = [];

        return $data;
    }

    public function monitoring()
    {
        if (!canAny(['chat_view', 'chat_view_technical', 'chat_view_accounting', 'chat_view_general'])) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat chat.');
        }

        $data = $this->_loadChatData('monitoring');
        return view('App\Modules\Chat\Views\monitoring', $data);
    }

    public function getSupplierConversations()
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
            $supplierConvoModel = new SupplierConversationModel();
            $conversations = $supplierConvoModel->select('
                    supplier_conversations.*,
                    "client" as client_type,
                    "general" as category,
                    "Obrolan" as title,
                    CONCAT(users.full_name, " ↔ ", suppliers.name) as client_name,
                    users.avatar as client_avatar,
                    suppliers.name as supplier_name,
                    supplier_conversations.unread_by_supplier_count as unread_by_admin_count,
                    COALESCE(supplier_conversations.last_message_at, supplier_conversations.created_at) as sort_time
                ', false)
                ->join('users', 'users.id = supplier_conversations.client_id', 'left')
                ->join('suppliers', 'suppliers.id = supplier_conversations.supplier_id', 'left')
                ->orderBy('sort_time', 'DESC')
                ->findAll();

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

    public function getSupplierMessages($conversationId = null)
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
            $supplierMsgModel = new SupplierMessageModel();
            $messages = $supplierMsgModel->where('supplier_conversation_id', $conversationId)
                ->orderBy('created_at', 'ASC')
                ->findAll();

            foreach ($messages as &$msg) {
                $msg['id'] = (int) $msg['id'];
                $msg['sender_id'] = (int) $msg['sender_id'];
                $msg['is_read_by_client'] = (int) $msg['is_read_by_client'];
                $msg['is_read_by_supplier'] = (int) $msg['is_read_by_supplier'];
                $msg['is_read_by_admin'] = 1;
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
}
