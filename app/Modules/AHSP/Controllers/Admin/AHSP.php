<?php

namespace App\Modules\AHSP\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\AHSP\Services\AHSPService;
use RuntimeException;
use Throwable;

class AHSP extends BaseController
{
    protected AHSPService $svc;

    public function __construct()
    {
        $this->svc = new AHSPService();
    }

    public function index()
    {
        if (!can('ahsp')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        return view('App\Modules\AHSP\Views\index', [
            'title' => 'Kelola AHSP',
            'ahsp'  => $this->svc->getAll()
        ]);
    }

    public function show($id)
    {
        if (!can('ahsp')) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Anda tidak memiliki akses untuk melihat detail ini.'
            ]);
        }

        try {
            $data = $this->svc->findWithChildrenOrFail((int) $id);
            return $this->response->setJSON([
                'status' => true,
                'data'   => $data
            ]);
        } catch (Throwable $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store()
    {
        if (!can('ahsp_create')) {
            return redirect()->to('/admin/ahsp')->with('error', 'Anda tidak memiliki akses untuk menambah AHSP.');
        }

        if (!$this->validate('ahspSave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $post = $this->request->getPost();
            $this->svc->store($post);

            log_admin_activity('create', 'AHSP', 'Menambahkan AHSP baru: ' . ($post['kode'] ?? '') . ' - ' . ($post['uraian'] ?? ''));

            return redirect()->to('/admin/ahsp')->with('success', 'Data AHSP berhasil ditambahkan!');
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan AHSP: ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!can('ahsp_edit')) {
            return redirect()->to('/admin/ahsp')->with('error', 'Anda tidak memiliki akses untuk mengedit AHSP.');
        }

        $post = $this->request->getPost();
        $post['id'] = $id;

        if (!$this->validateData($post, 'ahspUpdate')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->svc->update((int) $id, $post);

            log_admin_activity('update', 'AHSP', 'Mengubah AHSP ID: ' . $id . ' menjadi ' . ($post['kode'] ?? '') . ' - ' . ($post['uraian'] ?? ''));

            return redirect()->to('/admin/ahsp')->with('success', 'Data AHSP berhasil diperbarui!');
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui AHSP: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!can('ahsp_delete')) {
            return redirect()->to('/admin/ahsp')->with('error', 'Anda tidak memiliki akses untuk menghapus AHSP.');
        }

        try {
            $this->svc->delete((int) $id);

            log_admin_activity('delete', 'AHSP', 'Menghapus AHSP dengan ID: ' . $id);

            return redirect()->to('/admin/ahsp')->with('success', 'Data AHSP berhasil dihapus!');
        } catch (Throwable $e) {
            return redirect()->to('/admin/ahsp')->with('error', 'Gagal menghapus AHSP: ' . $e->getMessage());
        }
    }
}
