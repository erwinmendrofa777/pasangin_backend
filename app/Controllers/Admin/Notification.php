<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\NotificationService;

class Notification extends BaseController
{
    protected $notifService;

    public function __construct()
    {
        $this->notifService = new NotificationService();
    }

    /**
     * 1. HALAMAN DAFTAR RIWAYAT NOTIFIKASI
     */
    public function index()
    {
        if (!can('notification')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        $result = $this->notifService->getHistoryWithStats();

        return view('admin/notification/index', array_merge($result, [
            'title' => 'Riwayat Notifikasi'
        ]));
    }

    /**
     * 2. HALAMAN FORM KIRIM (CREATE)
     */
    public function create()
    {
        if (!can('notification_create')) {
            return redirect()->to('/admin/notification')->with('error', 'Anda tidak memiliki akses untuk membuat notifikasi.');
        }

        return view('admin/notification/create', [
            'title' => 'Kirim Notifikasi Baru'
        ]);
    }

    /**
     * 3. PROSES SIMPAN & KIRIM
     */
    public function send()
    {
        if (!can('notification_create')) {
            return redirect()->to('/admin/notification')->with('error', 'Anda tidak memiliki akses untuk membuat notifikasi.');
        }

        if (!$this->validateData($this->request->getPost(), 'notificationSend')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $target  = $this->request->getPost('target');
        $title   = $this->request->getPost('title');
        $message = $this->request->getPost('message');

        $this->notifService->sendBulk($target, $title, $message);

        return redirect()->to(base_url('admin/notification'))->with('success', 'Notifikasi berhasil dikirim!');
    }
}
