<?php

namespace App\Modules\SyaratKetentuan\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\SyaratKetentuan\Services\SyaratKetentuanService;
use RuntimeException;

class SyaratKetentuanController extends BaseController
{
    protected SyaratKetentuanService $svc;

    public function __construct()
    {
        $this->svc = new SyaratKetentuanService();
    }

    public function index()
    {
        if (!can('syarat_ketentuan')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data syarat & ketentuan.');
        }

        $data = $this->svc->getDashboardData();

        return view('App\Modules\SyaratKetentuan\Views\index', array_merge($data, [
            'title' => 'Syarat & Ketentuan'
        ]));
    }

    public function create()
    {
        if (!can('syarat_ketentuan_create')) {
            return redirect()->to('/admin/syarat_ketentuan')->with('error', 'Anda tidak memiliki akses untuk membuat syarat & ketentuan.');
        }

        return view('App\Modules\SyaratKetentuan\Views\create', [
            'title' => 'Tambah Syarat & Ketentuan'
        ]);
    }

    public function store()
    {
        if (!can('syarat_ketentuan_create')) {
            return redirect()->to('/admin/syarat_ketentuan')->with('error', 'Anda tidak memiliki akses untuk membuat syarat & ketentuan.');
        }

        if (!$this->validateData($this->request->getPost(), 'syaratKetentuanSave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors())->with('error', 'Mohon periksa kembali inputan Anda.');
        }

        try {
            $this->svc->store($this->request->getPost());
            log_admin_activity('create', 'syarat_ketentuan', 'membuat syarat & ketentuan');
            return redirect()->to('admin/syarat_ketentuan')->with('success', 'Data berhasil ditambahkan.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!can('syarat_ketentuan_update')) {
            return redirect()->to('/admin/syarat_ketentuan')->with('error', 'Anda tidak memiliki akses untuk mengedit syarat & ketentuan.');
        }

        try {
            $item = $this->svc->findOrFail((int) $id);
            return view('App\Modules\SyaratKetentuan\Views\edit', [
                'title' => 'Edit Syarat & Ketentuan',
                'data' => $item
            ]);
        } catch (RuntimeException $e) {
            return redirect()->to('admin/syarat_ketentuan')->with('error', $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!can('syarat_ketentuan_update')) {
            return redirect()->to('/admin/syarat_ketentuan')->with('error', 'Anda tidak memiliki akses untuk mengedit syarat & ketentuan.');
        }

        if (!$this->validateData($this->request->getPost(), 'syaratKetentuanSave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->svc->update((int) $id, $this->request->getPost());
            log_admin_activity('update', 'syarat_ketentuan', 'mengupdate syarat & ketentuan');
            return redirect()->to('admin/syarat_ketentuan')->with('success', 'Data berhasil diperbarui.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function detail($id)
    {
        if (!can('syarat_ketentuan')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat syarat & ketentuan.');
        }

        try {
            $item = $this->svc->findOrFail((int) $id);
            return view('App\Modules\SyaratKetentuan\Views\detail', [
                'title' => 'Detail Syarat & Ketentuan',
                'data' => $item
            ]);
        } catch (RuntimeException $e) {
            return redirect()->to('admin/syarat_ketentuan')->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!can('syarat_ketentuan_delete')) {
            return redirect()->to('/admin/syarat_ketentuan')->with('error', 'Anda tidak memiliki akses untuk menghapus syarat & ketentuan.');
        }

        try {
            $this->svc->delete((int) $id);
            log_admin_activity('delete', 'syarat_ketentuan', 'menghapus syarat & ketentuan');
            return redirect()->to('admin/syarat_ketentuan')->with('success', 'Data berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->to('admin/syarat_ketentuan')->with('error', $e->getMessage());
        }
    }
}
