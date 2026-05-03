<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AdminService;
use RuntimeException;

class AdminController extends BaseController
{
    protected AdminService $svc;

    public function __construct()
    {
        $this->svc = new AdminService();
    }

    public function index()
    {
        if (!can('admin')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data admin.');
        }

        return view('admin/admin/index', [
            'title'  => 'Kelola Admin',
            'admins' => $this->svc->getAllAdmins(),
        ]);
    }

    public function create()
    {
        if (!can('admin_create')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat admin.');
        }

        return view('admin/admin/create', [
            'title' => 'Tambah Admin',
            'roles' => $this->svc->getAllRoles(),
        ]);
    }

    public function store()
    {
        if (!can('admin_create')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat admin.');
        }

        // Validasi menggunakan grup 'adminSave' dari Config/Validation.php
        $dataToValidate = $this->request->getPost();
        $dataToValidate['photo'] = $this->request->getFile('photo');

        if (!$this->validateData($dataToValidate, 'adminSave')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->svc->createAdmin($this->request->getPost(), $this->request->getFile('photo'));
            return redirect()->to('/admin/admin')->with('success', 'Admin berhasil ditambahkan!');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!can('admin_edit')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit admin.');
        }

        try {
            $admin = $this->svc->findOrFail((int)$id);
            return view('admin/admin/edit', [
                'title' => 'Edit Admin',
                'admin' => $admin,
                'roles' => $this->svc->getAllRoles(),
            ]);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/admin')->with('error', $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!can('admin_edit')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit admin.');
        }

        // Validasi menggunakan grup 'adminUpdate'
        $dataToValidate = $this->request->getPost();
        $dataToValidate['id'] = $id; // Untuk placeholder {id} di rule is_unique
        $dataToValidate['photo'] = $this->request->getFile('photo');

        if (!$this->validateData($dataToValidate, 'adminUpdate')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->svc->updateAdmin((int)$id, $this->request->getPost(), $this->request->getFile('photo'));
            return redirect()->to('/admin/admin')->with('success', 'Data admin berhasil diupdate!');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!can('admin_delete')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus admin.');
        }

        try {
            $this->svc->deleteAdmin((int)$id, (int)session()->get('user_id'));
            return redirect()->to('/admin/admin')->with('success', 'Admin berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
