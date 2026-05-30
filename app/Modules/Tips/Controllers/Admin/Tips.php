<?php

namespace App\Modules\Tips\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Tips\Services\TipsService;
use App\Modules\Notifications\Services\NotificationService;
use RuntimeException;

class Tips extends BaseController
{
    protected TipsService $svc;
    protected NotificationService $notifService;

    public function __construct()
    {
        $this->svc = new TipsService();
        $this->notifService = new NotificationService();
    }

    public function index()
    {
        if (!can('tips')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        return view('App\Modules\Tips\Views\index', ['title' => 'Kelola Tips & Tricks', 'tips' => $this->svc->getAll()]);
    }

    public function show($id)
    {
        if (!can('tips')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        try {
            $tips = $this->svc->findOrFail((int) $id);
            return view('App\Modules\Tips\Views\detail', ['title' => 'Detail Tips - ' . $tips['title'], 'tips' => $tips]);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/tips')->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        if (!can('tips_create')) {
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses untuk membuat tips.');
        }
        return view('App\Modules\Tips\Views\create', ['title' => 'Tambah Tips']);
    }

    public function store()
    {
        if (!can('tips_create')) {
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses untuk membuat tips.');
        }

        // FIX: Jangan merging file ke data POST. Gunakan validate()
        if (!$this->validate('tipsSave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $post = $this->request->getPost();
            $this->svc->store($post, $this->request->getFile('image'));

            log_admin_activity('create', 'Tips', 'Menambahkan tips baru: ' . ($post['title'] ?? 'Tanpa Judul'));

            // Kirim Notifikasi Broadcast berdasarkan Target
            $targetRaw = $post['target_app'] ?? '';
            $target = strtolower($targetRaw); // 'client' atau 'tukang'

            if ($target === 'client' || $target === 'tukang') {
                $title = "Tips & Artikel Baru";
                $message = "ada tips & artikel baru untuk Anda: " . ($post['title'] ?? 'Cek informasinya sekarang!');
                $this->notifService->sendBulk($target, $title, $message);
            }

            log_admin_activity('create', 'tips', 'Membuat artikel tips baru');
            return redirect()->to('/admin/tips')->with('success', 'Tips & Tricks berhasil ditambahkan dan notifikasi dikirim!');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan tips: ' . $e->getMessage());
        }
    }

    public function uploadEditorImage()
    {
        if (!can('tips_create')) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => 0,
                'message' => 'Akses ditolak.',
            ]);
        }

        $file = $this->request->getFile('image');

        if (!$file || !$file->isValid()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => 0,
                'message' => 'Tidak ada file yang diupload atau file tidak valid.',
            ]);
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => 0,
                'message' => 'Tipe file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP.',
            ]);
        }

        // Max 5 MB
        if ($file->getSize() > 5 * 1024 * 1024) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => 0,
                'message' => 'Ukuran file maksimal 5 MB.',
            ]);
        }

        $newName = $file->getRandomName();
        $uploadPath = FCPATH . 'uploads/tips/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $newName);

        return $this->response->setJSON([
            'success' => 1,
            'file' => [
                'url' => base_url('uploads/tips/' . $newName),
            ],
        ]);
    }

    public function delete($id)
    {
        if (!can('tips_delete')) {
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses untuk menghapus tips.');
        }
        try {
            $this->svc->delete((int) $id);
            log_admin_activity('delete', 'Tips', 'Menghapus tips dengan ID: ' . $id);

            log_admin_activity('delete', 'tips', 'menghapus artikel tips');
            return redirect()->to('/admin/tips')->with('success', 'Data berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/tips')->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!can('tips_create') && !can('tips_update')) { // Asumsi ada tips_update atau gunakan tips_create
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses untuk mengedit tips.');
        }

        try {
            $tips = $this->svc->findOrFail((int) $id);
            return view('App\Modules\Tips\Views\edit', [
                'title' => 'Edit Tips & Tricks',
                'tips' => $tips
            ]);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/tips')->with('error', $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!can('tips_create') && !can('tips_update')) {
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses untuk mengedit tips.');
        }

        if (!$this->validate('tipsUpdate')) { // Menggunakan validasi khusus update
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $post = $this->request->getPost();
            $file = $this->request->getFile('image');

            // File tidak wajib saat update, jadi kita cek apakah diupload
            $this->svc->update((int) $id, $post, $file);

            log_admin_activity('update', 'Tips', 'Mengubah tips dengan ID: ' . $id);

            return redirect()->to('/admin/tips/detail/' . $id)->with('success', 'Tips & Tricks berhasil diperbarui!');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui tips: ' . $e->getMessage());
        }
    }

    public function updateIsActive($id)
    {
        if (!can('tips')) {
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses.');
        }

        try {
            $this->svc->toggleStatus((int) $id);
            log_admin_activity('update_status', 'Tips', 'Mengubah status aktif/draft pada tips ID: ' . $id);

            return redirect()->to('/admin/tips')->with('success', 'Status tips berhasil diperbarui!');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/tips')->with('error', $e->getMessage());
        }
    }
}
