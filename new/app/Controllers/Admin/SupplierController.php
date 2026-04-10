<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SupplierModel;

class SupplierController extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    // Fungsi untuk menampilkan list semua supplier (tidak perlu diubah)
    public function index()
    {
        $data = [
            'title'     => 'Manajemen Supplier',
            'suppliers' => $this->supplierModel->orderBy('name', 'ASC')->findAll(),
        ];
        // Di sini saya asumsikan nama file view-nya adalah 'admin/supplier/index'
        // Jika kamu pakai 'layout/app', mungkin nama filenya berbeda, tapi logikanya sama.
        return view('admin/supplier/index', $data); 
    }

    // Fungsi untuk menampilkan form tambah supplier
    public function create()
    {
        // ==============================================
        // TAMBAHKAN BARIS INI UNTUK MEMUAT FORM HELPER
        // ==============================================
        helper('form');

        $data = [
            'title' => 'Tambah Supplier Baru',
        ];
        return view('admin/supplier/create', $data);
    }
    
        /**
     * Memperbarui status supplier (approved, rejected, banned).
     * Fungsi ini dipanggil dari tombol aksi di halaman daftar supplier.
     *
     * @param int    $id     ID dari supplier yang akan diubah.
     * @param string $status Status baru ('approved', 'rejected', 'banned').
     */
    public function updateStatus($id, $status)
    {
        // Validasi status yang diizinkan untuk mencegah input sembarangan dari URL
        $allowed_statuses = ['approved', 'rejected', 'banned', 'pending'];
        if (!in_array($status, $allowed_statuses)) {
            // Jika status tidak valid, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        }

        // Siapkan data untuk disimpan
        $data = [
            'id' => $id,
            'status' => $status
        ];

        // Jika statusnya 'approved', set juga 'is_active' menjadi 1
        if ($status === 'approved') {
            $data['is_active'] = 1;
        } else {
            // Untuk status lain (rejected, banned, pending), set 'is_active' menjadi 0
            $data['is_active'] = 0;
        }

        // Simpan perubahan ke database menggunakan model
        $this->supplierModel->save($data);

        // Buat pesan sukses yang dinamis
        $message = "Status supplier berhasil diubah menjadi " . ucfirst($status) . ".";

        // Kembalikan ke halaman daftar supplier dengan pesan sukses
        return redirect()->to('/admin/suppliers')->with('success', $message);
    }


    // Fungsi untuk menyimpan data supplier (tidak perlu diubah)
    public function save()
    {
        // ... kode simpan data ...
        if (!$this->validate($this->supplierModel->getValidationRules())) {
            // Saat redirect withInput(), pesan validasi otomatis dibawa
            return redirect()->to('/admin/suppliers/create')->withInput();
        }

        $this->supplierModel->save([
            'name'           => $this->request->getPost('name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'phone'          => $this->request->getPost('phone'),
            'address'        => $this->request->getPost('address'),
            'is_active'      => $this->request->getPost('is_active'),
        ]);

        session()->setFlashdata('success', 'Data supplier berhasil ditambahkan.');
        return redirect()->to('/admin/suppliers');
    }

    // Fungsi untuk menampilkan form edit supplier
    public function edit($id)
    {
        // ==============================================
        // TAMBAHKAN JUGA DI SINI
        // ==============================================
        helper('form');

        $data = [
            'title'    => 'Edit Data Supplier',
            'supplier' => $this->supplierModel->find($id),
        ];

        if (empty($data['supplier'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Supplier dengan ID ' . $id . ' tidak ditemukan.');
        }

        return view('admin/supplier/edit', $data);
    }

    // ... sisa fungsi update() dan delete() tidak perlu diubah ...
    public function update($id)
    {
        // ...
        if (!$this->validate($this->supplierModel->getValidationRules())) {
            return redirect()->to('/admin/suppliers/edit/' . $id)->withInput();
        }
        // ...
        $this->supplierModel->update($id, [
            'name'           => $this->request->getPost('name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'phone'          => $this->request->getPost('phone'),
            'address'        => $this->request->getPost('address'),
            'is_active'      => $this->request->getPost('is_active'),
        ]);

        session()->setFlashdata('success', 'Data supplier berhasil diupdate.');
        return redirect()->to('/admin/suppliers');
    }

    public function delete($id)
    {
        $this->supplierModel->delete($id);
        session()->setFlashdata('success', 'Data supplier berhasil dihapus.');
        return redirect()->to('/admin/suppliers');
    }
}
