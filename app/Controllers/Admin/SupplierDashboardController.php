<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel; // Pastikan ini ada

/**
 * Controller ini menangani semua halaman di dalam dasbor supplier
 * setelah mereka berhasil login.
 */
class SupplierDashboardController extends BaseController
{
    /**
     * Menampilkan halaman dasbor utama.
     * URL: /supplier/dashboard
     */
    public function index()
    {
        $data = [
            'title' => 'Halaman Dasbor',
        ];
        return view('admin/dashboard_supplier', $data);
    }

    /**
     * Menampilkan halaman daftar produk milik supplier.
     * URL: /supplier/produk
     */
    public function produk()
    {
        $productModel = new ProductModel();
        $supplierId = session()->get('supplier_id');

        $data = [
            'title'    => 'Manajemen Produk',
            'products' => $productModel->getProductsBySupplier($supplierId),
        ];

        return view('admin/produk_supplier', $data);
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     * URL: /supplier/produk/new
     */
    public function new()
    {
        $data = [
            'title' => 'Tambah Produk Baru',
        ];
        return view('admin/produk_form', $data);
    }

    /**
     * Menyimpan data produk baru dari form ke database, termasuk upload foto.
     * URL: /supplier/produk/create (method POST)
     */
    public function create()
    {
        // 1. Logika Upload Foto
        $photoFile = $this->request->getFile('photo');
        $photoName = 'default.jpg'; // Nama file default

        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $photoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/products', $photoName);
        }

        // 2. Siapkan data untuk disimpan
        $dataToSave = [
            'supplier_id' => session()->get('supplier_id'),
            'name'        => $this->request->getPost('name'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status'),
            'photo'       => $photoName, // Simpan nama file foto
        ];

        // 3. Simpan data ke database
        $productModel = new ProductModel();
        if ($productModel->insert($dataToSave)) {
            return redirect()->to(site_url('supplier/produk'))->with('success', 'Produk baru berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan produk.');
        }
    }

    /**
     * Menampilkan form untuk mengedit produk yang ada.
     * URL: /supplier/produk/edit/{id}
     */
    public function edit($id = null)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);

        // Pastikan produk ada dan dimiliki oleh supplier yang login
        if (!$product || $product['supplier_id'] != session()->get('supplier_id')) {
            return redirect()->to(site_url('supplier/produk'))->with('error', 'Produk tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $data = [
            'title'   => 'Edit Produk',
            'product' => $product,
        ];
        return view('admin/produk_form_edit', $data);
    }

    /**
     * Memproses update data produk dari form edit.
     * URL: /supplier/produk/update/{id} (method POST)
     */
    public function update($id = null)
    {
        $productModel = new ProductModel();
        $existingProduct = $productModel->find($id);

        // Keamanan: Pastikan produk ada dan dimiliki oleh supplier yang login
        if (!$existingProduct || $existingProduct['supplier_id'] != session()->get('supplier_id')) {
             return redirect()->to(site_url('supplier/produk'))->with('error', 'Aksi tidak diizinkan.');
        }

        // 1. Siapkan data dari form
        $dataToSave = [
            'name'        => $this->request->getPost('name'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status'),
        ];
        
        // 2. Logika untuk update foto (hanya jika ada foto baru yang di-upload)
        $photoFile = $this->request->getFile('photo');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $photoName = $photoFile->getRandomName();
            $photoFile->move(FCPATH . 'uploads/products', $photoName);
            $dataToSave['photo'] = $photoName; // Tambahkan foto baru ke data update

            // Hapus foto lama jika bukan 'default.jpg'
            if ($existingProduct['photo'] && $existingProduct['photo'] != 'default.jpg') {
                $oldPhotoPath = FCPATH . 'uploads/products/' . $existingProduct['photo'];
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
        }

        // 3. Lakukan update ke database
        if ($productModel->update($id, $dataToSave)) {
            return redirect()->to(site_url('supplier/produk'))->with('success', 'Produk berhasil diperbarui!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui produk.');
        }
    }

    /**
     * Menghapus produk dari database.
     * URL: /supplier/produk/delete/{id} (method POST)
     */
    public function delete($id = null)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);

        // Keamanan: Pastikan produk ada dan dimiliki oleh supplier yang login
        if (!$product || $product['supplier_id'] != session()->get('supplier_id')) {
            return redirect()->to(site_url('supplier/produk'))->with('error', 'Aksi tidak diizinkan.');
        }
        
        // Hapus foto dari server sebelum hapus data dari DB
        if ($product['photo'] && $product['photo'] != 'default.jpg') {
            $photoPath = FCPATH . 'uploads/products/' . $product['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        // Hapus data dari database
        if ($productModel->delete($id)) {
            return redirect()->to(site_url('supplier/produk'))->with('success', 'Produk berhasil dihapus.');
        } else {
            return redirect()->to(site_url('supplier/produk'))->with('error', 'Gagal menghapus produk.');
        }
    }
}
