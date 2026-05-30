<?php

namespace App\Modules\Supplier\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Supplier\Services\SupplierService;
use RuntimeException;

/**
 * SupplierController — Admin
 *
 * Berperan sebagai "polisi lalu lintas":
 *   1. Terima request dari user
 *   2. Cek permission
 *   3. Validasi input dasar (HTTP layer)
 *   4. Delegasikan ke SupplierService untuk logika bisnis
 *   5. Kembalikan response (redirect / view)
 *
 * TIDAK ADA logika bisnis, file handling, atau hashing di sini.
 * Semua itu ada di App\Modules\Supplier\Services\SupplierService.
 */
class SupplierController extends BaseController
{
    protected SupplierService $supplierService;

    public function __construct()
    {
        $this->supplierService = new SupplierService();
    }

    // -------------------------------------------------------------------------
    // 1. LIST SEMUA SUPPLIER
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('suppliers')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data supplier.');
        }

        return view('App\Modules\Supplier\Views\supplier/index', [
            'title' => 'Manajemen Supplier',
            'suppliers' => $this->supplierService->getAllSuppliers(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 2. FORM TAMBAH SUPPLIER
    // -------------------------------------------------------------------------
    public function create()
    {
        if (!can('suppliers_create')) {
            return redirect()->to('/admin/suppliers')->with('error', 'Anda tidak memiliki akses untuk menambah supplier.');
        }

        helper('form');

        return view('App\Modules\Supplier\Views\supplier/create', ['title' => 'Tambah Supplier Baru']);
    }

    // -------------------------------------------------------------------------
    // 3. SIMPAN SUPPLIER BARU
    // -------------------------------------------------------------------------
    public function save()
    {
        if (!can('suppliers_create')) {
            return redirect()->to('/admin/suppliers')->with('error', 'Anda tidak memiliki akses untuk menambah supplier.');
        }

        // Validasi menggunakan grup 'supplierSave' di Config/Validation.php
        if (!$this->validateData($this->request->getPost(), 'supplierSave')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->supplierService->createSupplier(
                $this->request->getPost(),
                $this->request->getFile('logo_url')
            );

            log_admin_activity('create', 'supplier', 'menambahkan akun supplier');
            return redirect()->to('/admin/suppliers')->with('success', 'Data supplier berhasil ditambahkan.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 4. DETAIL SUPPLIER
    // -------------------------------------------------------------------------
    public function detail($id)
    {
        if (!can('suppliers')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data supplier.');
        }

        try {
            $supplier = $this->supplierService->findSupplierOrFail((int) $id);
        } catch (RuntimeException $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException($e->getMessage());
        }

        return view('App\Modules\Supplier\Views\supplier/detail', [
            'title' => 'Detail Supplier',
            'supplier' => $supplier,
        ]);
    }

    // -------------------------------------------------------------------------
    // 5. FORM EDIT SUPPLIER
    // -------------------------------------------------------------------------
    public function edit($id)
    {
        if (!can('suppliers_edit')) {
            return redirect()->to('/admin/suppliers')->with('error', 'Anda tidak memiliki akses untuk mengedit supplier.');
        }

        helper('form');

        try {
            $supplier = $this->supplierService->findSupplierOrFail((int) $id);
        } catch (RuntimeException $e) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException($e->getMessage());
        }

        return view('App\Modules\Supplier\Views\supplier/edit', [
            'title' => 'Edit Data Supplier',
            'supplier' => $supplier,
        ]);
    }

    // -------------------------------------------------------------------------
    // 6. PROSES UPDATE SUPPLIER
    // -------------------------------------------------------------------------
    public function update($id)
    {
        if (!can('suppliers_edit')) {
            return redirect()->to('/admin/suppliers')->with('error', 'Anda tidak memiliki akses untuk mengedit supplier.');
        }

        // Siapkan data untuk divalidasi (termasuk ID untuk placeholder rule is_unique)
        $dataToValidate = $this->request->getPost();
        $dataToValidate['id'] = $id;

        // Validasi menggunakan grup 'supplierUpdate' di Config/Validation.php
        if (!$this->validateData($dataToValidate, 'supplierUpdate')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->supplierService->updateSupplier(
                (int) $id,
                $this->request->getPost(),
                $this->request->getFile('logo_url')
            );

            log_admin_activity('update', 'supplier', 'mengedit akun supplier');
            return redirect()->to('/admin/suppliers')->with('success', 'Data supplier berhasil diperbarui.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    // -------------------------------------------------------------------------
    // 7. UPDATE STATUS SUPPLIER
    // -------------------------------------------------------------------------
    public function updateStatus($id, $status)
    {
        if (!can('suppliers_status')) {
            return redirect()->to('/admin/suppliers')->with('error', 'Anda tidak memiliki akses untuk mengubah status supplier.');
        }

        // Validasi status menggunakan grup 'supplierUpdateStatus'
        if (!$this->validateData(['status' => $status], 'supplierUpdateStatus')) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->supplierService->updateStatus((int) $id, $status);

            log_admin_activity('update_status', 'supplier', 'mengubah status supplier');
            return redirect()->to('/admin/suppliers')
                ->with('success', 'Status supplier berhasil diubah menjadi ' . ucfirst($status) . '.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 8. HAPUS SUPPLIER
    // -------------------------------------------------------------------------
    public function delete($id)
    {
        if (!can('suppliers_delete')) {
            return redirect()->to('/admin/suppliers')->with('error', 'Anda tidak memiliki akses untuk menghapus supplier.');
        }

        try {
            $this->supplierService->deleteSupplier((int) $id);

            log_admin_activity('delete', 'supplier', 'menghapus akun supplier');
            return redirect()->to('/admin/suppliers')->with('success', 'Data supplier berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/suppliers')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
