<?php

namespace App\Modules\Vouchers\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Vouchers\Services\VoucherService;
use App\Modules\Notifications\Services\NotificationService;
use RuntimeException;

class Voucher extends BaseController
{
    protected VoucherService $svc;
    protected NotificationService $notifService;

    public function __construct()
    {
        $this->svc = new VoucherService();
        $this->notifService = new NotificationService();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!can('vouchers')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        return view('App\Modules\Vouchers\Views\index', ['title' => 'Kelola Voucher', 'vouchers' => $this->svc->getAll()]);
    }

    public function show($id)
    {
        if (!can('vouchers')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        try {
            $voucher = $this->svc->findOrFail((int) $id);
            return view('App\Modules\Vouchers\Views\detail', ['title' => 'Detail Voucher - ' . $voucher['name'], 'voucher' => $voucher]);
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('admin/vouchers'))->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        if (!can('vouchers_create')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        return view('App\Modules\Vouchers\Views\create', ['title' => 'Tambah Voucher Baru']);
    }

    public function store()
    {
        if (!can('vouchers_create')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        // FIX: Jangan merging file ke data POST. Gunakan validate()
        if (!$this->validate('voucherSave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $post = $this->request->getPost();
            $this->svc->store($post, $this->request->getFile('image'));

            // Kirim Notifikasi ke Seluruh Client (Broadcast)
            $title = "Voucher Promo Baru!";
            $message = "ada voucher promo baru: " . ($post['code'] ?? 'Promo Spesial') . ". Gunakan sekarang sebelum kehabisan!";
            $this->notifService->sendBulk('client', $title, $message);

            log_admin_activity('create', 'voucher', 'membuat voucher baru');
            return redirect()->to(base_url('admin/vouchers'))->with('success', 'Voucher berhasil dibuat dan notifikasi dikirim ke seluruh client!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        if (!can('vouchers_status')) {
            return redirect()->to('/admin/vouchers')->with('error', 'Anda tidak memiliki akses untuk mengubah status voucher.');
        }
        $msg = $this->svc->updateStatus((int) $id, (int) $status);
        log_admin_activity('update_status', 'voucher', 'mengubah data voucher');
        return redirect()->back()->with('success', $msg);
    }

    public function delete($id)
    {
        if (!can('vouchers_delete')) {
            return redirect()->to('/admin/vouchers')->with('error', 'Anda tidak memiliki akses untuk menghapus voucher.');
        }
        $this->svc->delete((int) $id);
        log_admin_activity('delete', 'voucher', 'menghapus data voucher');
        return redirect()->to(base_url('admin/vouchers'))->with('success', 'Voucher berhasil dihapus.');
    }
}
