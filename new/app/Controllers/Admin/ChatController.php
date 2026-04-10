<?php
// FILE: app/Controllers/Admin/ChatController.php
// VERSI ANTI-ERROR SESI DENGAN HELPER FUNCTION

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ConversationModel;
use App\Models\MessageModel;
use App\Models\UserModel; // Pindahkan UserModel ke atas

class ChatController extends BaseController
{
    // ... (Fungsi index() dan getMessages() Anda yang sudah benar, tidak saya ubah)
    public function index()
    {
        $data['title'] = 'Pusat Pesan';
        $clientNameColumn = 'full_name'; 
        $conversationModel = new ConversationModel();
        $queryBuilder = $conversationModel
            ->select('conversations.*, users.' . $clientNameColumn . ' as client_name')
            ->join('users', 'users.id = conversations.client_id', 'left')
            ->orderBy('conversations.updated_at', 'DESC');
        try {
            $data['conversations'] = $queryBuilder->findAll();
        } catch (\Exception $e) {
            log_message('error', '[Admin/ChatController/Index] Query JOIN gagal: ' . $e->getMessage());
            // ... (error handling Anda)
        }
        return view('admin/chat/index', $data);
    }

    public function getMessages($conversationId = null)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        if ($conversationId === null) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Conversation ID dibutuhkan.']);
        }
        try {
            $messageModel = new MessageModel();
            $messages = $messageModel->where('conversation_id', $conversationId)->orderBy('created_at', 'ASC')->findAll();
            return $this->response->setJSON(['status' => 'success', 'messages' => $messages]);
        } catch (\Exception $e) {
            log_message('error', '[Admin/getMessages] ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan di server.']);
        }
    }


    // =========================================================================
    // === FUNGSI INI YANG KITA PERBAIKI SECARA TOTAL ==========================
    // =========================================================================
    public function sendMessage()
    {
        // Pastikan ini adalah AJAX request dan user adalah admin yang valid
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Akses ditolak.']);
        }

        $this->response->setHeader('Content-Type', 'application/json');

        $conversationId = $this->request->getPost('conversation_id');
        $body           = $this->request->getPost('body');
        $adminId        = session()->get('user_id');

        // Validasi input
        if (empty($conversationId) || empty($body) || empty($adminId)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        }

        // BAGIAN 1: Simpan pesan ke database (Logika Anda yang sudah ada)
        $messageModel = new MessageModel();
        $messageModel->insert([
            'conversation_id' => $conversationId,
            'sender_id'       => $adminId,
            'sender_type'     => 'admin',
            'body'            => $body,
        ]);

        $conversationModel = new ConversationModel();
        $conversation = $conversationModel->find($conversationId);
        $conversationModel->update($conversationId, [
            'last_message_preview' => substr($body, 0, 50),
            'last_message_at'      => date('Y-m-d H:i:s'),
        ]);

        // BAGIAN 2: Panggil helper untuk mengirim notifikasi
        $clientId = $conversation['client_id'] ?? null;
        if ($clientId) {
            // Panggil fungsi terpisah yang bersih
            $this->sendFirebaseNotification($clientId, $conversationId, "Balasan dari Admin", $body);
        }
        
        // BAGIAN 3: Kirim respons sukses ke AJAX
        return $this->response->setJSON(['status' => 'success', 'message' => 'Pesan berhasil dikirim.']);
    }

    // =========================================================================
    // === INI DIA HELPER FUNCTION YANG BERSIH UNTUK MENGURUS FIREBASE =======
    // =========================================================================
    private function sendFirebaseNotification($clientId, $conversationId, $title, $body)
    {
        try {
            // Load semua yang dibutuhkan di dalam fungsi ini saja
            require_once APPPATH . '../vendor/autoload.php';
            $userModel = new UserModel();
            $client = $userModel->find($clientId);
            $fcmToken = $client['fcm_token'] ?? null;

            if ($fcmToken) {
                $credentialsPath = WRITEPATH . 'firebase_credentials.json';
                if (!file_exists($credentialsPath)) {
                    throw new \Exception('File kredensial Firebase tidak ditemukan.');
                }
                
                $factory = (new \Kreait\Firebase\Factory)->withServiceAccount($credentialsPath);
                $messaging = $factory->createMessaging();

                $notification = \Kreait\Firebase\Messaging\Notification::create($title, $body);
                $data = [
                    'type'            => 'chat',
                    'conversation_id' => (string) $conversationId,
                ];

                $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $fcmToken)
                    ->withNotification($notification)
                    ->withData($data);

                $messaging->send($message);
                log_message('info', 'Notifikasi Firebase terkirim ke client ID: ' . $clientId);
            } else {
                log_message('warning', 'FCM Token kosong untuk client ID: ' . $clientId);
            }
        } catch (\Throwable $e) {
            // Catat error ke log, tapi jangan hentikan eksekusi utama
            log_message('error', '[sendFirebaseNotification] Gagal: ' . $e->getMessage());
        }
    }
}
