<?php

namespace App\Modules\Satuan\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Satuan\Services\SatuanService;
use RuntimeException;

class Satuan extends BaseController
{
    protected SatuanService $svc;

    public function __construct()
    {
        $this->svc = new SatuanService();
    }

    public function index()
    {
        if (!can('satuan')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        return view('App\Modules\Satuan\Views\index', [
            'title' => 'Kelola Satuan',
            'satuan' => $this->svc->getAll()
        ]);
    }

    public function store()
    {
        if (!can('satuan_create')) {
            return redirect()->to('/admin/satuan')->with('error', 'Anda tidak memiliki akses untuk menambah satuan.');
        }

        $rules = [
            'nama_satuan' => 'required|min_length[1]|max_length[100]|is_unique[satuan.nama_satuan]',
        ];
        $errors = [
            'nama_satuan' => [
                'required'   => 'Nama satuan wajib diisi.',
                'min_length' => 'Nama satuan minimal 1 karakter.',
                'max_length' => 'Nama satuan maksimal 100 karakter.',
                'is_unique'  => 'Nama satuan tersebut sudah terdaftar.',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $post = $this->request->getPost();
            $this->svc->store($post);

            log_admin_activity('create', 'Satuan', 'Menambahkan satuan baru: ' . ($post['nama_satuan'] ?? ''));

            return redirect()->to('/admin/satuan')->with('success', 'Data satuan berhasil ditambahkan!');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan satuan: ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!can('satuan_edit')) {
            return redirect()->to('/admin/satuan')->with('error', 'Anda tidak memiliki akses untuk mengedit satuan.');
        }

        $data = $this->request->getPost();
        $data['id'] = $id;

        $rules = [
            'nama_satuan' => "required|min_length[1]|max_length[100]|is_unique[satuan.nama_satuan,id,{$id}]",
        ];
        $errors = [
            'nama_satuan' => [
                'required'   => 'Nama satuan wajib diisi.',
                'min_length' => 'Nama satuan minimal 1 karakter.',
                'max_length' => 'Nama satuan maksimal 100 karakter.',
                'is_unique'  => 'Nama satuan tersebut sudah digunakan oleh data lain.',
            ],
        ];

        if (!$this->validateData($data, $rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->svc->update((int) $id, $data);

            log_admin_activity('update', 'Satuan', 'Mengubah satuan ID: ' . $id . ' menjadi ' . ($data['nama_satuan'] ?? ''));

            return redirect()->to('/admin/satuan')->with('success', 'Data satuan berhasil diperbarui!');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui satuan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!can('satuan_delete')) {
            return redirect()->to('/admin/satuan')->with('error', 'Anda tidak memiliki akses untuk menghapus satuan.');
        }

        try {
            $this->svc->delete((int) $id);

            log_admin_activity('delete', 'Satuan', 'Menghapus satuan dengan ID: ' . $id);

            return redirect()->to('/admin/satuan')->with('success', 'Data satuan berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/satuan')->with('error', $e->getMessage());
        }
    }
}
