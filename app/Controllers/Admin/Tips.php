<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\TipsService;
use RuntimeException;

class Tips extends BaseController
{
    protected TipsService $svc;

    public function __construct()
    {
        $this->svc = new TipsService();
    }

    public function index()
    {
        if (!can('tips')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        return view('admin/tips/index', ['title' => 'Kelola Tips & Tricks', 'tips' => $this->svc->getAll()]);
    }

    public function show($id)
    {
        if (!can('tips')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        try {
            $tips = $this->svc->findOrFail((int)$id);
            return view('admin/tips/detail', ['title' => 'Detail Tips - ' . $tips['title'], 'tips' => $tips]);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/tips')->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        if (!can('tips_create')) {
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses untuk membuat tips.');
        }
        return view('admin/tips/create', ['title' => 'Tambah Tips']);
    }

    public function store()
    {
        if (!can('tips_create')) {
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses untuk membuat tips.');
        }

        $dataToValidate = $this->request->getPost();
        $dataToValidate['image'] = $this->request->getFile('image');

        if (!$this->validateData($dataToValidate, 'tipsSave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->svc->store($this->request->getPost(), $this->request->getFile('image'));
            return redirect()->to('/admin/tips')->with('success', 'Tips & Tricks berhasil ditambahkan!');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!can('tips_delete')) {
            return redirect()->to('/admin/tips')->with('error', 'Anda tidak memiliki akses untuk menghapus tips.');
        }
        try {
            $this->svc->delete((int)$id);
            return redirect()->to('/admin/tips')->with('success', 'Data berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/tips')->with('error', $e->getMessage());
        }
    }
}
