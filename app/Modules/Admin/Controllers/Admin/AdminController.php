<?php

namespace App\Modules\Admin\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Admin\Services\AdminService;
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

        return view('App\Modules\Admin\Views\admin/index', [
            'title' => 'Kelola Admin',
            'admins' => $this->svc->getAllAdmins(),
        ]);
    }

    public function create()
    {
        if (!can('admin_create')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat admin.');
        }

        return view('App\Modules\Admin\Views\admin/create', [
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
        
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $dataToValidate['photo'] = 'uploaded_file';
        } else {
            $dataToValidate['photo'] = null;
        }

        if (!$this->validateData($dataToValidate, 'adminSave')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->svc->createAdmin($this->request->getPost(), $this->request->getFile('photo'));

            $post = $this->request->getPost();
            log_admin_activity('create', 'Admin Users', 'Menambahkan Admin baru: ' . ($post['full_name'] ?? 'Tanpa Nama'));

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
            $admin = $this->svc->findOrFail((int) $id);
            return view('App\Modules\Admin\Views\admin/edit', [
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
        
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $dataToValidate['photo'] = 'uploaded_file';
        } else {
            $dataToValidate['photo'] = null;
        }

        if (!$this->validateData($dataToValidate, 'adminUpdate')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->svc->updateAdmin((int) $id, $this->request->getPost(), $this->request->getFile('photo'));

            // Jika admin yang diedit adalah diri sendiri, perbarui session photo & JWT Cookie
            if ((int) $id === (int) session()->get('user_id')) {
                $updatedAdmin = $this->svc->findOrFail((int) $id);
                if ($updatedAdmin) {
                    session()->set('photo', $updatedAdmin['photo'] ?? null);
                    
                    // Regenerasi token JWT dengan data baru dan perbarui cookie
                    $sessionData = session()->get();
                    $token = \App\Libraries\AdminTokenHandler::generate($sessionData);
                    \App\Libraries\AdminTokenHandler::setCookie($token);
                }
            }

            log_admin_activity('update', 'Admin Users', 'Mengubah data Admin dengan ID: ' . $id);

            return redirect()->to('/admin/admin')->with('success', 'Data admin berhasil diupdate!')->withCookies();
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
            $this->svc->deleteAdmin((int) $id, (int) session()->get('user_id'));

            log_admin_activity('delete', 'Admin Users', 'Menghapus Admin dengan ID: ' . $id);

            return redirect()->to('/admin/admin')->with('success', 'Admin berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
