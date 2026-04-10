<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\NotificationService;

class Notification extends BaseController{
    protected $db;
    protected $notifService;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->notifService = new NotificationService();
    }

    // 1. HALAMAN DAFTAR RIWAYAT NOTIFIKASI
    public function index()
    {
        $data = [
            'title' => 'Riwayat Notifikasi',
            'notifications' => $this->db->table('notifications')
                                        ->orderBy('created_at', 'DESC')
                                        ->get()->getResultArray()
        ];
        return view('admin/notification/index', $data);
    }

    // 2. HALAMAN FORM KIRIM (CREATE)
    public function create()
    {
        return view('admin/notification/create', ['title' => 'Kirim Notifikasi Baru']);
    }

    // 3. PROSES SIMPAN & KIRIM
    // app/Controllers/Admin/Notification.php

public function send()
{
    $target  = $this->request->getPost('target');
    $title   = $this->request->getPost('title');
    $message = $this->request->getPost('message');

    // 1. Simpan ke database
    $this->db->table('notifications')->insert([
        'target_type' => $target,
        'title'       => $title,
        'message'     => $message,
        'created_at'  => date('Y-m-d H:i:s')
    ]);

    // 2. Tentukan tabel target
    $table = ($target == 'client') ? 'users' : (($target == 'tukang') ? 'tukang' : 'suppliers');
    
    // 3. Ambil ID dan Token (PENTING: Harus ambil ID juga)
    $users = $this->db->table($table)
                     ->select('id, fcm_token')
                     ->where('fcm_token IS NOT NULL')
                     ->get()->getResultArray();

    // 4. Loop kirim satu per satu
    foreach ($users as $user) {
        if ($target == 'client') {
            $this->notifService->notifyClient($user['id'], $title, $message);
        } elseif ($target == 'tukang') {
            $this->notifService->notifyTukang($user['id'], $title, $message);
        } elseif ($target == 'supplier') {
            $this->notifService->notifySupplier($user['id'], $title, $message);
        }
    }

    return redirect()->to(base_url('admin/notification'))->with('success', 'Notifikasi berhasil dikirim!');
}
}