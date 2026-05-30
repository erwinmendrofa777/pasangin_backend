<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

/**
 * sendFCMNotification()
 *
 * Mengirim push notification via Firebase Cloud Messaging (FCM v1 HTTP API)
 * menggunakan library kreait/firebase-php dan Service Account JSON.
 *
 * @param string $token   FCM registration token perangkat tujuan
 * @param string $title   Judul notifikasi
 * @param string $body    Isi/pesan notifikasi
 * @param array  $data    Data tambahan (key-value) yang dikirim ke app
 * @return bool           true jika berhasil, false jika gagal
 */
function sendFCMNotification(string $token, string $title, string $body, array $data = [], ?string $imageUrl = null, ?string $topic = null): bool
{
    // Validasi token tidak boleh kosong
    if (empty(trim($token))) {
        log_message('warning', '[FCM] Token kosong, notifikasi dibatalkan.');
        return false;
    }

    try {
        if ($topic !== null) {
            $data['topic'] = $topic;
        }

        // Path ke Service Account JSON (relatif dari ROOTPATH)
        $serviceAccountPath = WRITEPATH . 'pasangin-c8050-firebase-adminsdk-fbsvc-547edad397.json';

        if (!file_exists($serviceAccountPath)) {
            log_message('error', '[FCM] File JSON tidak ditemukan di: ' . $serviceAccountPath);
            return false;
        }

        // Inisialisasi Firebase Factory dengan Service Account
        $factory  = (new Factory())->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();

        // Bangun pesan FCM
        $notification = Notification::create($title, $body, $imageUrl);
        
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)
            ->withData($data); // Data tambahan (opsional, bisa kosong)

        // Kirim!
        $messaging->send($message);

        log_message('info', '[FCM] Notifikasi berhasil dikirim ke token: ' . substr($token, 0, 20) . '...');
        return true;

    } catch (\Kreait\Firebase\Exception\Messaging\InvalidArgument $e) {
        log_message('error', '[FCM] Token tidak valid: ' . $e->getMessage());
        return false;
    } catch (\Kreait\Firebase\Exception\MessagingException $e) {
        log_message('error', '[FCM] Gagal kirim notifikasi: ' . $e->getMessage());
        return false;
    } catch (\Throwable $e) {
        log_message('error', '[FCM] Error tidak terduga: ' . $e->getMessage());
        return false;
    }
}

/**
 * sendFCMToMultiple()
 *
 * Mengirim notifikasi ke banyak token sekaligus (multicast).
 * Efisien untuk notifikasi broadcast (misal: kirim ke semua tukang).
 *
 * @param array  $tokens  Array FCM token perangkat tujuan
 * @param string $title   Judul notifikasi
 * @param string $body    Isi/pesan notifikasi
 * @param array  $data    Data tambahan
 * @return array          ['success' => int, 'failure' => int]
 */
function sendFCMToMultiple(array $tokens, string $title, string $body, array $data = [], ?string $imageUrl = null, ?string $topic = null): array
{
    $result = ['success' => 0, 'failure' => 0];

    // Filter token kosong
    $tokens = array_filter($tokens, fn($t) => !empty(trim($t)));

    if (empty($tokens)) {
        log_message('warning', '[FCM] Tidak ada token valid untuk multicast.');
        return $result;
    }

    try {
        if ($topic !== null) {
            $data['topic'] = $topic;
        }
        
        $serviceAccountPath = WRITEPATH . 'pasangin-c8050-firebase-adminsdk-fbsvc-547edad397.json';

        if (!file_exists($serviceAccountPath)) {
            log_message('error', '[FCM] File JSON tidak ditemukan di: ' . $serviceAccountPath);
            return $result;
        }

        $factory   = (new Factory())->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();

        // Multicast di v7 sangat efisien dan tidak lagi menggunakan URL /batch yang error.
        // Sangat aman untuk mengirim ke ribuan token sekaligus.
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body, $imageUrl))
            ->withData($data);

        $report = $messaging->sendMulticast($message, array_values($tokens));

        $result['success'] = $report->successes()->count();
        $result['failure'] = $report->failures()->count();

        if ($result['failure'] > 0) {
            foreach ($report->failures()->getItems() as $failure) {
                log_message('warning', '[FCM] Pengiriman gagal untuk token: ' . $failure->target()->value());
            }
        }

        log_message('info', "[FCM] Pengiriman massal selesai. Sukses: {$result['success']}, Gagal: {$result['failure']}");

    } catch (\Throwable $e) {
        log_message('error', '[FCM] Error multicast: ' . $e->getMessage());
        throw $e; // Lempar ke controller agar ditangkap try-catch di sana
    }

    return $result;
}