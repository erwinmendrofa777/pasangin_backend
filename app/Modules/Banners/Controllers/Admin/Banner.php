<?php

namespace App\Modules\Banners\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Banners\Services\BannerService;
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
        return view('App\Modules\Banners\Views\index', ['title' => 'Kelola Banner', 'banners' => $this->svc->getAll()]);
    }

    public function create()
    {
        if (!can('banner_create')) {
            return redirect()->to('/admin/banner')->with('error', 'Anda tidak memiliki akses untuk menambah banner.');
        }
        return view('App\Modules\Banners\Views\create', ['title' => 'Tambah Banner']);
    }

    public function store()
    {
        if (!can('banner_create')) {
            return redirect()->to('/admin/banner')->with('error', 'Anda tidak memiliki akses untuk menambah banner.');
        }

        if (!$this->validate('bannerSave')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->svc->store($this->request->getPost(), $this->request->getFile('image'));

            //catat ke log admin
            log_admin_activity('create', 'Banner', 'Tambah Data Banner');

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
            $this->svc->delete((int) $id);
            log_admin_activity('delete', 'Banner', 'Tambah Data Banner');
            return redirect()->to('/admin/banner')->with('success', 'Banner berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/banner')->with('error', $e->getMessage());
        }
    }
}
