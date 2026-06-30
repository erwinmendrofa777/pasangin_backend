<?php

namespace App\Modules\Products\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Products\Services\AppCategoryService;
use RuntimeException;

class AppCategoryController extends BaseController
{
    protected AppCategoryService $svc;

    public function __construct()
    {
        $this->svc = new AppCategoryService();
    }

    public function index()
    {
        if (!can('product_categories_view')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        return view('App\Modules\Products\Views\admin\categories\index', [
            'title' => 'Kelola Kategori Produk',
            'categories' => $this->svc->getAll()
        ]);
    }

    public function store()
    {
        if (!can('product_categories_create')) {
            return redirect()->to('/admin/product-categories')->with('error', 'Anda tidak memiliki akses untuk menambah kategori.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]|is_unique[app_categories.name]',
        ];
        $errors = [
            'name' => [
                'required'   => 'Nama kategori wajib diisi.',
                'min_length' => 'Nama kategori minimal 3 karakter.',
                'max_length' => 'Nama kategori maksimal 100 karakter.',
                'is_unique'  => 'Nama kategori tersebut sudah terdaftar.',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $post = $this->request->getPost();
            $this->svc->store($post);

            log_admin_activity('create', 'AppCategory', 'Menambahkan kategori produk global baru: ' . ($post['name'] ?? ''));

            return redirect()->to('/admin/product-categories')->with('success', 'Kategori produk berhasil ditambahkan!');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!can('product_categories_edit')) {
            return redirect()->to('/admin/product-categories')->with('error', 'Anda tidak memiliki akses untuk mengedit kategori.');
        }

        $data = $this->request->getPost();
        $data['id'] = $id;

        $rules = [
            'name' => "required|min_length[3]|max_length[100]|is_unique[app_categories.name,id,{$id}]",
        ];
        $errors = [
            'name' => [
                'required'   => 'Nama kategori wajib diisi.',
                'min_length' => 'Nama kategori minimal 3 karakter.',
                'max_length' => 'Nama kategori maksimal 100 karakter.',
                'is_unique'  => 'Nama kategori tersebut sudah digunakan oleh data lain.',
            ],
        ];

        if (!$this->validateData($data, $rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->svc->update((int) $id, $data);

            log_admin_activity('update', 'AppCategory', 'Mengubah kategori produk global ID: ' . $id . ' menjadi ' . ($data['name'] ?? ''));

            return redirect()->to('/admin/product-categories')->with('success', 'Kategori produk berhasil diperbarui!');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!can('product_categories_delete')) {
            return redirect()->to('/admin/product-categories')->with('error', 'Anda tidak memiliki akses untuk menghapus kategori.');
        }

        try {
            $this->svc->delete((int) $id);

            log_admin_activity('delete', 'AppCategory', 'Menghapus kategori produk global dengan ID: ' . $id);

            return redirect()->to('/admin/product-categories')->with('success', 'Kategori produk berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/product-categories')->with('error', $e->getMessage());
        }
    }
}
