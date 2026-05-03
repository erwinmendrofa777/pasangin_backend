<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\BannerService;
use RuntimeException;

class Banner extends BaseController
{
    protected BannerService $svc;

    public function __construct()
    {
        $this->svc = new BannerService();
    }

    public function index()
    {
        if (!can('banner')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
        return view('admin/banner/index', ['title' => 'Kelola Banner', 'banners' => $this->svc->getAll()]);
    }

    public function create()
    {
        if (!can('banner_create')) {
            return redirect()->to('/admin/banner')->with('error', 'Anda tidak memiliki akses untuk menambah banner.');
        }
        return view('admin/banner/create', ['title' => 'Tambah Banner']);
    }

    public function store()
    {
        if (!can('banner_create')) {
            return redirect()->to('/admin/banner')->with('error', 'Anda tidak memiliki akses untuk menambah banner.');
        }

        // Validasi menggunakan grup 'bannerSave' di Config/Validation.php
        $dataToValidate = $this->request->getPost();
        $dataToValidate['image'] = $this->request->getFile('image');

        if (!$this->validateData($dataToValidate, 'bannerSave')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->svc->store($this->request->getPost(), $this->request->getFile('image'));
            return redirect()->to('/admin/banner')->with('success', 'Banner berhasil ditambahkan!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!can('banner_delete')) {
            return redirect()->to('/admin/banner')->with('error', 'Anda tidak memiliki akses untuk menghapus banner.');
        }

        try {
            $this->svc->delete((int)$id);
            return redirect()->to('/admin/banner')->with('success', 'Banner berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/banner')->with('error', $e->getMessage());
        }
    }
}
