<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\PromoService;
use RuntimeException;

class PromoController extends BaseController
{
    protected PromoService $svc;

    public function __construct()
    {
        $this->svc = new PromoService();
    }

    public function index()
    {
        if (!can('promo')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        $result = $this->svc->getAllWithStats();
        return view('admin/promos/index', array_merge($result, ['title' => 'Manajemen Promo']));
    }

    public function detail($id = null)
    {
        if (!can('promo')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        if (!$id) {
            return redirect()->to('/admin/promo')->with('error', 'Promo tidak valid.');
        }
        try {
            $promo = $this->svc->findDetailOrFail((int)$id);
            return view('admin/promos/detail', ['title' => 'Detail Promo', 'promo' => $promo]);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/promo')->with('error', $e->getMessage());
        }
    }

    public function update_status($id, $status)
    {
        if (!can('promo_status')) {
            return redirect()->to('/admin/promo')->with('error', 'Anda tidak memiliki akses untuk mengubah status promo.');
        }

        // Validasi status menggunakan grup 'promoUpdateStatus'
        if (!$this->validateData(['status' => $status], 'promoUpdateStatus')) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()));
        }

        if (!$id) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }
        $msg = $this->svc->updateStatus((int)$id, $status);
        return redirect()->back()->with('success', $msg);
    }

    public function delete($id)
    {
        if (!can('promo_delete')) {
            return redirect()->to('/admin/promo')->with('error', 'Anda tidak memiliki akses untuk menghapus promo.');
        }
        if (!$id) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.');
        }
        $this->svc->delete((int)$id);
        return redirect()->to(base_url('admin/promo'))->with('success', 'Promo berhasil dihapus.');
    }
}
