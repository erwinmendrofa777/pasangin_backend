<?php

function sendFCMNotification($token, $title, $body, $data = [])
{
    // KAWAN: Ganti dengan 'Server Key' dari Firebase Console kamu
    // Lokasi: Project Settings -> Cloud Messaging -> Cloud Messaging API (Legacy)
    $serverKey = 'AIzaSy...ISI_DENGAN_SERVER_KEY_KAMU_KAWAN...';

    $url = "https://fcm.googleapis.com/fcm/send";

    $notification = [
        'title' => $title,
        'body'  => $body,
        'sound' => 'default',
        'badge' => '1'
    ];

    $fields = [
        'to'           => $token,
        'notification' => $notification,
        'data'         => $data, // Opsional: Untuk kirim data tambahan ke App
        'priority'     => 'high'
    ];

    $headers = [
        'Authorization: key=' . $serverKey,
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}