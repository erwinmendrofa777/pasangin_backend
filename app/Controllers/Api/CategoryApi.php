<?php

namespace App\Controllers\Api;

use App\Modules\Supplier\Models\CategoryModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
class CategoryApi extends ResourceController
{
    protected $format = 'json';

    /**
     * HELPER: Mendapatkan ID Supplier dari Token JWT di Header
     */
    private function getSupplierId()
    {
        if (isset($this->request->user) && $this->request->user->role === 'supplier') {
            return $this->request->user->uid;
        }
        return null;
    }

    /**
     * --- 1. LIST KATEGORI SAYA ---
     * GET: /api/categories
     */
    public function index()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized('pengguna tidak valid.');

        $model = new CategoryModel();

        $data = $model->where('supplier_id', $supplierId)
            ->orderBy('name', 'ASC')
            ->findAll();

        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'list kategori supplier',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada kategori untuk supplier ini',
                'data' => $data
            ]);
        }
    }

    /**
     * --- 2. TAMBAH KATEGORI BARU ---
     * POST: /api/categories
     */
    public function create()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized('pengguna tidak valid.');

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]'
        ];

        $messages = [
            'name' => [
                'required' => 'Nama kategori wajib diisi.',
                'min_length' => 'Nama kategori minimal 3 karakter.',
                'max_length' => 'Nama kategori maksimal 100 karakter.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $model = new CategoryModel();

        try {
            $model->insert([
                'supplier_id' => $supplierId,
                'name' => $data['name']
            ]);

            return $this->respondCreated([
                'status' => true,
                'message' => 'Kategori berhasil dibuat.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * --- 3. UPDATE KATEGORI ---
     * PUT: /api/categories/(:num)
     */
    public function update($id = null)
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized();

        $model = new CategoryModel();
        // Pastikan kategori tersebut milik supplier yang login
        $category = $model->where(['id' => $id, 'supplier_id' => $supplierId])->first();

        if (!$category)
            return $this->failNotFound('supplier tidak memiliki kategori');

        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        try {
            $model->update($id, ['name' => $data['name']]);
            return $this->respond([
                'status' => true,
                'message' => 'Kategori berhasil diupdate.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * --- 4. HAPUS KATEGORI ---
     * DELETE: /api/categories/(:num)
     */
    public function delete($id = null)
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId)
            return $this->failUnauthorized();

        $model = new CategoryModel();
        $category = $model->where(['id' => $id, 'supplier_id' => $supplierId])->first();

        if (!$category)
            return $this->failNotFound('Akses ditolak.');

        try {
            $model->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'Kategori berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}