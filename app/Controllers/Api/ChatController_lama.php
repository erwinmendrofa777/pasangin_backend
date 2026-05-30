<?php
// FILE: app/Controllers/Api/ChatController.php
// =========================================================================
// === VERSI FINAL DENGAN UPGRADE PENGIRIMAN NOTIFIKASI SECARA MANUAL      ===// =========================================================================

namespace App\Controllers\Api;

// =========================================================================
// <<<--- INI ADALAH BARIS KUNCI YANG HARUS ADA ---
// Pastikan baris ini ada persis seperti ini di bawah namespace.
// =========================================================================
require_once APPPATH . 'ThirdParty/vendor_manual/autoloader.php';

use App\Modules\Chat\Models\ConversationModel;
use App\Modules\Chat\Models\MessageModel;
use App\Modules\Users\Models\UserModel; // <-- Pastikan ini ada
use CodeIgniter\RESTful\ResourceController;

// Import Kelas-kelas dari Library Notifikasi yang sudah kita siapkan
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification;

class ChatController extends ResourceController
{
    // ... (Fungsi getAllConversationsForUser dan getMessages tidak perlu diubah) ...
    public function getAllConversationsForUser($userId = null)
    {
        if ($userId === null) {
            return $this->fail('User ID dibutuhkan.', 400);
        }
        try {
            $conversationModel = new ConversationModel();
            $conversations = $conversationModel->where('client_id', $userId)->orderBy('updated_at', 'DESC')->findAll();
            foreach ($conversations as &$convo) {
                $convo['id'] = (string)$convo['id'];
                $convo['client_id'] = (string)$convo['client_id'];
                $convo['unread_by_admin_count'] = (string)$convo['unread_by_admin_count'];
            }
            return $this->respond(['status' => true, 'message' => 'Daftar percakapan berhasil diambil.', 'conversations' => $conversations], 200);
        } catch (\Exception $e) {
            log_message('error', '[getAllConversationsForUser] ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan di server saat mengambil daftar percakapan.');
        }
    }

    public function getMessages($conversationId = null)
    {
        if ($conversationId === null) {
            return $this->fail('Conversation ID dibutuhkan.', 400);
        }
        try {
            $messageModel = new MessageModel();
            $messages = $messageModel->where('conversation_id', $conversationId)->orderBy('created_at', 'ASC')->findAll();
            foreach ($messages as &$msg) {
                $msg['id'] = (string)$msg['id'];
                $msg['conversation_id'] = (string)$msg['conversation_id'];
                $msg['sender_id'] = (string)$msg['sender_id'];
            }
            return $this->respond(['status' => true, 'data' => ['messages' => $messages]], 200);
        } catch (\Exception $e) {
            log_message('error', '[getMessages] ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan di server saat mengambil pesan.');
        }
    }

    // =========================================================================
    // <<<--- FUNGSI sendMessage() YANG SUDAH DI-UPGRADE ---
    // =========================================================================
    public function sendMessage()
    {
        $json = $this->request->getJSON();
        $conversationId   = $json->conversation_id ?? null;
        $senderId         = $json->sender_id ?? null;
        $senderType       = $json->sender_type ?? null;
        $body             = $json->body ?? null;

        try {
            $conversationModel = new ConversationModel();
            $messageModel = new MessageModel();
            
            // Logika untuk membuat percakapan baru jika belum ada
            if ($conversationId === null && $senderType === 'client') {
                $newConversationData = ['client_id' => $senderId];
                $conversationId = $conversationModel->insert($newConversationData, true);
            }

            // Simpan pesan
            $newMessageData = [
                'conversation_id' => $conversationId,
                'sender_id'       => $senderId,
                'sender_type'     => $senderType,
                'body'            => $body,
            ];
            $messageId = $messageModel->insert($newMessageData, true);

            // Update preview pesan di tabel percakapan
            $conversationModel->update($conversationId, [
                'last_message_preview' => substr($body, 0, 50),
                'last_message_at'      => date('Y-m-d H:i:s')
            ]);
            
            // =========================================================================
            // <<<--- LOGIKA NOTIFIKASI DIMULAI DI SINI ---
            // =========================================================================
            if ($senderType === 'admin') {
                log_message('info', "[NOTIF_TRIGGER] Pengirim adalah admin. Memulai proses notifikasi...");
                $conversation = $conversationModel->find($conversationId);
                $targetUserId = $conversation['client_id'] ?? null;
                if ($targetUserId) {
                    $userModel = new UserModel();
                    $recipientUser = $userModel->find($targetUserId);
                    $recipientFcmToken = $recipientUser['fcm_token'] ?? null;
                    if (!empty($recipientFcmToken)) {
                        $this->sendFcmNotification(
                            $recipientFcmToken,
                            'Balasan Baru dari Admin',
                            $body,
                            [ 'type' => 'chat', 'conversation_id' => (string)$conversationId ]
                        );
                    } else {
                        log_message('warning', "[FCM] Gagal notif: User ID " . $targetUserId . " tidak memiliki FCM Token.");
                    }
                }
            }
            // --- AKHIR LOGIKA NOTIFIKASI ---

            $sentMessage = $messageModel->find($messageId);
            $sentMessage['id'] = (string)$sentMessage['id'];
            $sentMessage['conversation_id'] = (string)$sentMessage['conversation_id'];
            $sentMessage['sender_id'] = (string)$sentMessage['sender_id'];
            $response = ['status' => true, 'message' => 'Pesan berhasil dikirim.', 'data' => $sentMessage];
            return $this->respondCreated($response);

        } catch (\Exception $e) {
            log_message('error', '[sendMessage] ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan di server saat mengirim pesan.');
        }
    }

    // =========================================================================
    // <<<--- FUNGSI HELPER UNTUK MENGIRIM NOTIFIKASI ---
    // =========================================================================
    private function sendFcmNotification($deviceToken, $title, $body, $dataPayload = [])
    {
        $serverKeyFile = WRITEPATH . 'firebase_credentials.json';
        if (!file_exists($serverKeyFile)) {
            log_message('error', '[FCM] KRITIS: File kredensial firebase_credentials.json tidak ditemukan!');
            return;
        }
        log_message('info', "[FCM] Mencoba mengirim notifikasi ke token: " . substr($deviceToken, 0, 15) . "...");
        try {
            $client = new Client();
            $client->setApiKey($serverKeyFile);
            $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());
            $message = new Message();
            $message->setRecipient(new Device($deviceToken));
            $message->setNotification(new Notification($title, $body));
            $message->setData($dataPayload);
            $message->setAndroidConfig([
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'pasangin_chat_channel', // PENTING!
                    'sound'      => 'default'
                ],
            ]);
            $response = $client->send($message);
            log_message('info', '[FCM] Respons Sukses dari Firebase: ' . $response->getBody()->getContents());
        } catch (\Exception $e) {
            log_message('error', '[FCM] Exception saat mengirim ke Firebase: ' . $e->getMessage());
        }
    }
}
