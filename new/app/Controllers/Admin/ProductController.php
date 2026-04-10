<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel; // Penting untuk memanggil Model

class ProductController extends BaseController
{
    /**
     * Metode ini akan dipanggil oleh rute 'admin/products'.
     * Tugasnya adalah mengambil semua produk dan menampilkannya.
     */
    public function index(){
    // 1. Buat instance dari model produk
    $productModel = new \App\Models\ProductModel();

    // 2. Buat query builder untuk mengambil data
    //    - Pilih semua kolom dari tabel 'products'
    //    - Pilih kolom 'name' dari tabel 'suppliers' dan beri nama alias 'supplier_name'
    //    - Gabungkan (JOIN) dengan tabel 'suppliers' berdasarkan 'suppliers.id = products.supplier_id'
    $products = $productModel->select('products.*, suppliers.name as supplier_name')
                             ->join('suppliers', 'suppliers.id = products.supplier_id', 'left') // Gunakan 'left' join agar produk tetap tampil meskipun supplier dihapus
                             ->findAll(); // findAll() akan mengembalikan array of objects secara default jika modelmu benar

    // 3. Siapkan data untuk dikirim ke view
    $data = [
        'title'    => 'Manajemen Produk',
        'products' => $products
    ];

    // 4. Tampilkan view dengan data yang sudah disiapkan
    return view('admin/products/index', $data);
    }
}
