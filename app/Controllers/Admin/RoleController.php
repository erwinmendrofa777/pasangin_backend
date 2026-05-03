<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\RoleService;
use RuntimeException;

class RoleController extends BaseController
{
    protected RoleService $svc;

    public function __construct()
    {
        $this->svc = new RoleService();
    }

    public function index()
    {
        if (!can('roles')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data role.');
        }

        return view('admin/roles/index', [
            'title' => 'Kelola Role',
            'roles' => $this->svc->getAllRoles()
        ]);
    }

    public function create()
    {
        if (!can('roles_create')) {
            return redirect()->to('/admin/roles')->with('error', 'Anda tidak memiliki akses untuk menambah role.');
        }

        return view('admin/roles/create', [
            'title'           => 'Tambah Role',
            'available_menus' => $this->svc->getAvailableMenus()
        ]);
    }

    public function store()
    {
        if (!can('roles_create')) {
            return redirect()->to('/admin/roles')->with('error', 'Anda tidak memiliki akses untuk menambah role.');
        }

        if (!$this->validateData($this->request->getPost(), 'roleStore')) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        try {
            $this->svc->createRole($this->request->getPost());
            return redirect()->to('/admin/roles')->with('success', 'Role berhasil ditambahkan!');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!can('roles_edit')) {
            return redirect()->to('/admin/roles')->with('error', 'Anda tidak memiliki akses untuk mengedit role.');
        }

        try {
            return view('admin/roles/edit', [
                'title'           => 'Edit Role',
                'role'            => $this->svc->findOrFail((int)$id),
                'available_menus' => $this->svc->getAvailableMenus()
            ]);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/roles')->with('error', $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!can('roles_edit')) {
            return redirect()->to('/admin/roles')->with('error', 'Anda tidak memiliki akses untuk mengedit role.');
        }

        $data = $this->request->getPost();
        $data['id'] = $id;

        if (!$this->validateData($data, 'roleUpdate')) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        try {
            $this->svc->updateRole((int)$id, $this->request->getPost());
            return redirect()->to('/admin/roles')->with('success', 'Role berhasil diupdate!');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!can('roles_delete')) {
            return redirect()->to('/admin/roles')->with('error', 'Anda tidak memiliki akses untuk menghapus role.');
        }

        try {
            $this->svc->deleteRole((int)$id);
            return redirect()->to('/admin/roles')->with('success', 'Role berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
