<?php

namespace App\Modules\Notifications\Services;

use App\Modules\Notifications\Repositories\NotificationRepository;
use App\Modules\Users\Repositories\UserRepository;
use App\Modules\Tukang\Repositories\TukangRepository;
use App\Modules\Supplier\Repositories\SupplierRepository;
use App\Modules\Notifications\Repositories\FcmTokenRepository;
use App\Modules\Admin\Repositories\UserAdminRepository;

use App\Modules\Notifications\Repositories\Contracts\NotificationRepositoryInterface;
use App\Modules\Users\Repositories\Contracts\UserRepositoryInterface;
use App\Modules\Tukang\Repositories\Contracts\TukangRepositoryInterface;
use App\Modules\Supplier\Repositories\Contracts\SupplierRepositoryInterface;

use App\Modules\Notifications\Repositories\Contracts\FcmTokenRepositoryInterface;
use App\Modules\Admin\Repositories\Contracts\UserAdminRepositoryInterface;

/**
 * NotificationService
 *
 * Menangani pengiriman notifikasi push (FCM v1) dan riwayat notifikasi.
 * Menggunakan kreait/firebase-php via notification_helper.php.
 */
class NotificationService
{
    protected NotificationRepositoryInterface $notificationRepository;
    protected UserRepositoryInterface $userRepository;
    protected TukangRepositoryInterface $tukangRepository;
    protected SupplierRepositoryInterface $supplierRepository;
    protected UserAdminRepositoryInterface $userAdminRepository;

    protected FcmTokenRepositoryInterface $fcmTokenRepository;

    public function __construct()
    {
        $this->notificationRepository = new NotificationRepository();
        $this->userRepository = new UserRepository();
        $this->tukangRepository = new TukangRepository();
        $this->supplierRepository = new SupplierRepository();
        $this->userAdminRepository = new UserAdminRepository();
        $this->fcmTokenRepository = new FcmTokenRepository();

        helper(['notification', 'url']);
    }

    /**
     * Ambil riwayat notifikasi beserta statistiknya.
     */
    public function getHistoryWithStats(): array
    {
        $allNotif = $this->notificationRepository->findAllOrderedByCreatedAtDesc();

        return [
            'notifications' => $allNotif,
            'stats' => [
                'total' => count($allNotif),
                'client' => $this->notificationRepository->countByTargetType('client'),
                'tukang' => $this->notificationRepository->countByTargetType('tukang'),
                'supplier' => $this->notificationRepository->countByTargetType('supplier'),
                'admin' => $this->notificationRepository->countByTargetType('admin'),
            ]
        ];
    }

    /**
     * Proses simpan ke DB dan kirim broadcast ke FCM.
     *
     * Menggunakan sendFCMToMultiple() untuk multicast efisien —
     * satu request ke Firebase untuk semua token, bukan N request.
     */
    public function sendBulk(string $target, string $title, string $message, $imageFile = null): array
    {
        $imageUrl = null;

        // Handle Image Upload
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            if ($imageFile->move('uploads/notifications/', $newName)) {
                $imageUrl = base_url('uploads/notifications/' . $newName);
            }
        }

        // 1. Simpan ke database history dan ambil ID yang baru saja dibuat
        $notifId = $this->notificationRepository->insert([
            'target_type' => $target,
            'title' => $title,
            'message' => $message,
            'image_url' => $imageUrl,
        ]);

        // 2. Ambil semua token dari tabel user_fcm_tokens berdasarkan target (user_type)
        $tokensData = $this->fcmTokenRepository->getTokensByType($target);

        if (empty($tokensData)) {
            log_message('info', "[FCM] Tidak ada user dengan FCM token untuk target: {$target}");
            return ['success' => 0, 'failure' => 0];
        }

        // 3. Kumpulkan semua token, lalu kirim SATU kali via multicast dengan menyertakan ID notifikasi
        $tokens = array_column($tokensData, 'fcm_token');

        return sendFCMToMultiple($tokens, $title, $message, ['notification_id' => (string) $notifId], $imageUrl);
    }

    /**
     * Kirim notifikasi ke semua user yang memiliki role tertentu.
     */
    public function sendToRole(string $role, string $title, string $message, string $userType = 'admin', $imageFile = null): array
    {
        $imageUrl = null;

        // Handle Image Upload
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            if ($imageFile->move('uploads/notifications/', $newName)) {
                $imageUrl = base_url('uploads/notifications/' . $newName);
            }
        }

        // 1. Simpan ke database
        $notifId = $this->notificationRepository->insert([
            'target_type' => 'role:' . $role . '(' . $userType . ')',
            'title' => $title,
            'message' => $message,
            'image_url' => $imageUrl,
        ]);

        // 2. Ambil token berdasarkan role dan tipe user
        $tokensData = $this->fcmTokenRepository->getTokensByRole($role, $userType);

        if (empty($tokensData)) {
            return ['success' => 0, 'failure' => 0];
        }

        $tokens = array_column($tokensData, 'fcm_token');

        return sendFCMToMultiple($tokens, $title, $message, ['notification_id' => (string) $notifId], $imageUrl);
    }

    /**
     * Kirim notifikasi ke semua Admin yang memiliki permission tertentu.
     * Sangat berguna agar notifikasi lebih tepat sasaran (misal: hanya bagian Banner yang dapat notif banner).
     */
    public function sendToPermission(string $permission, string $title, string $message, $imageFile = null, array $extra = [], ?string $topic = null): array
    {
        $imageUrl = null;

        // Handle Image Upload
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            if ($imageFile->move('uploads/notifications/', $newName)) {
                $imageUrl = base_url('uploads/notifications/' . $newName);
            }
        }

        // 1. Simpan ke database dengan target_type 'admin' agar terbaca oleh filter navbar admin
        $notifId = $this->notificationRepository->insert([
            'target_type' => 'admin',
            'title' => $title,
            'message' => $message,
            'image_url' => $imageUrl,
        ]);

        // 2. Ambil token berdasarkan permission
        $tokensData = $this->fcmTokenRepository->getTokensByPermission($permission);

        if (empty($tokensData)) {
            return ['success' => 0, 'failure' => 0];
        }

        $tokens = array_column($tokensData, 'fcm_token');

        $mergedExtra = array_merge(['notification_id' => (string) $notifId], $extra);

        return sendFCMToMultiple($tokens, $title, $message, $mergedExtra, $imageUrl, $topic);
    }

    /**
     * Proses simpan ke DB dan kirim notifikasi ke satu user spesifik.
     */
    public function sendPersonal(string $targetType, int $targetId, string $title, string $message, $imageFile = null): array
    {
        $imageUrl = null;

        // Handle Image Upload
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            if ($imageFile->move('uploads/notifications/', $newName)) {
                $imageUrl = base_url('uploads/notifications/' . $newName);
            }
        }

        // 1. Simpan ke database history dan ambil ID-nya
        $notifId = $this->notificationRepository->insert([
            'target_type' => $targetType,
            'target_id' => $targetId,
            'title' => $title,
            'message' => $message,
            'image_url' => $imageUrl,
        ]);

        // 2. Siapkan extra data berisi ID notifikasi untuk dikirim ke HP
        $extra = ['notification_id' => (string) $notifId];
        $success = false;

        if ($targetType === 'client') {
            $success = $this->notifyClient($targetId, $title, $message, $extra, $imageUrl);
        } elseif ($targetType === 'tukang') {
            $success = $this->notifyTukang($targetId, $title, $message, $extra, $imageUrl);
        } elseif ($targetType === 'supplier') {
            $success = $this->notifySupplier($targetId, $title, $message, $extra, $imageUrl);
        } elseif ($targetType === 'admin') {
            $success = $this->notifyAdmin($targetId, $title, $message, $extra, $imageUrl);
        }

        return [
            'success' => $success ? 1 : 0,
            'failure' => $success ? 0 : 1,
            'notification_id' => $notifId
        ];
    }

    /**
     * Kirim notifikasi ke semua perangkat milik satu Tukang berdasarkan ID.
     */
    public function notifyTukang(int $tukangId, string $title, string $body, array $extra = [], ?string $imageUrl = null, ?string $topic = null): bool
    {
        $tokensData = $this->fcmTokenRepository->getTokens($tukangId, 'tukang');
        if (!empty($tokensData)) {
            $tokens = array_column($tokensData, 'fcm_token');
            $res = sendFCMToMultiple($tokens, $title, $body, $extra, $imageUrl, $topic);
            return $res['success'] > 0;
        }
        return false;
    }

    /**
     * Kirim notifikasi ke semua perangkat milik satu Klien (Users) berdasarkan ID.
     */
    public function notifyClient(int $userId, string $title, string $body, array $extra = [], ?string $imageUrl = null, ?string $topic = null): bool
    {
        $tokensData = $this->fcmTokenRepository->getTokens($userId, 'client');
        if (!empty($tokensData)) {
            $tokens = array_column($tokensData, 'fcm_token');
            $res = sendFCMToMultiple($tokens, $title, $body, $extra, $imageUrl, $topic);
            return $res['success'] > 0;
        }
        return false;
    }

    /**
     * Kirim notifikasi ke semua perangkat milik satu Supplier berdasarkan ID.
     */
    public function notifySupplier(int $supplierId, string $title, string $body, array $extra = [], ?string $imageUrl = null, ?string $topic = null): bool
    {
        $tokensData = $this->fcmTokenRepository->getTokens($supplierId, 'supplier');
        if (!empty($tokensData)) {
            $tokens = array_column($tokensData, 'fcm_token');
            $res = sendFCMToMultiple($tokens, $title, $body, $extra, $imageUrl, $topic);
            return $res['success'] > 0;
        }
        return false;
    }

    /**
     * Kirim notifikasi ke semua perangkat milik satu Admin berdasarkan ID.
     */
    public function notifyAdmin(int $adminId, string $title, string $body, array $extra = [], ?string $imageUrl = null, ?string $topic = null): bool
    {
        $tokensData = $this->fcmTokenRepository->getTokens($adminId, 'admin');
        if (!empty($tokensData)) {
            $tokens = array_column($tokensData, 'fcm_token');
            $res = sendFCMToMultiple($tokens, $title, $body, $extra, $imageUrl, $topic);
            return $res['success'] > 0;
        }
        return false;
    }

    /**
     * Cari target (user, tukang, supplier) berdasarkan nama/phone untuk dropdown Select2.
     */
    public function searchTargets(string $role, string $term): array
    {
        if ($role === 'client') {
            return $this->userRepository->searchForDropdown($term);
        } elseif ($role === 'tukang') {
            return $this->tukangRepository->searchForDropdown($term);
        } elseif ($role === 'supplier') {
            return $this->supplierRepository->searchForDropdown($term);
        } elseif ($role === 'admin') {
            return $this->userAdminRepository->searchForDropdown($term);
        }

        return [];
    }

    /**
     * Kirim push notification ke Mandor ketika status pesanan menjadi SHIPPED
     */
    public function sendShippedNotificationToMandor(int $orderId): void
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil data order
        $order = $db->table('orders')->where('id', $orderId)->get()->getRow();
        if (!$order || empty($order->construction_invoice_id)) {
            return;
        }
        
        // 2. Ambil construction_id dari construction_invoices
        $invoice = $db->table('construction_invoices')
            ->where('id', $order->construction_invoice_id)
            ->get()
            ->getRow();
        if (!$invoice || empty($invoice->construction_id)) {
            return;
        }
        
        $projectId = $invoice->construction_id;
        
        // 3. Cari mandor/tukang yang bertugas di proyek tersebut
        $assignedTukangs = $db->table('job_applications')
            ->where('project_id', $projectId)
            ->where('project_type', 'construction')
            ->where('status', 'Siap Kerja')
            ->get()
            ->getResultArray();
            
        foreach ($assignedTukangs as $app) {
            $tukangId = $app['tukang_id'];
            
            // Simpan riwayat notifikasi ke database
            $notifId = $this->notificationRepository->insert([
                'target_type' => 'tukang',
                'target_id' => $tukangId,
                'title' => 'Material Sedang Dikirim',
                'message' => "Material untuk pesanan {$order->order_id} sedang dalam perjalanan ke lokasi proyek.",
                'image_url' => null,
            ]);
            
            // Kirim push notification via FCM
            $extra = [
                'notification_id' => (string) $notifId,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'topic' => 'order',
                'order_id' => (string) $orderId,
            ];
            
            $this->notifyTukang((int) $tukangId, 'Material Sedang Dikirim', "Material untuk pesanan {$order->order_id} sedang dalam perjalanan ke lokasi proyek.", $extra, null, 'order');
        }
    }
}