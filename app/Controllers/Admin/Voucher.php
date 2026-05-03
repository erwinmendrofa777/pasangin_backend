<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\VoucherService;
use RuntimeException;

class Voucher extends BaseController
{
    protected VoucherService $svc;

    public function __construct()
    {
        $this->svc = new VoucherService();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!can('vouchers')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        return view('admin/voucher/index', ['title' => 'Kelola Voucher', 'vouchers' => $this->svc->getAll()]);
    }

    public function show($id)
    {
        if (!can('vouchers')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        try {
            $voucher = $this->svc->findOrFail((int)$id);
            return view('admin/voucher/detail', ['title' => 'Detail Voucher - ' . $voucher['name'], 'voucher' => $voucher]);
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('admin/vouchers'))->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        if (!can('vouchers_create')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        return view('admin/voucher/create', ['title' => 'Tambah Voucher Baru']);
    }

    public function store()
    {
        if (!can('vouchers_create')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        $dataToValidate = $this->request->getPost();
        $dataToValidate['image'] = $this->request->getFile('image');

        if (!$this->validateData($dataToValidate, 'voucherSave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->svc->store($this->request->getPost(), $this->request->getFile('image'));
            return redirect()->to(base_url('admin/vouchers'))->with('success', 'Voucher berhasil dibuat!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        if (!can('vouchers_status')) {
            return redirect()->to('/admin/vouchers')->with('error', 'Anda tidak memiliki akses untuk mengubah status voucher.');
        }
        $msg = $this->svc->updateStatus((int)$id, (int)$status);
        return redirect()->back()->with('success', $msg);
    }

    public function delete($id)
    {
        if (!can('vouchers_delete')) {
            return redirect()->to('/admin/vouchers')->with('error', 'Anda tidak memiliki akses untuk menghapus voucher.');
        }
        $this->svc->delete((int)$id);
        return redirect()->to(base_url('admin/vouchers'))->with('success', 'Voucher berhasil dihapus.');
    }
}
