<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\ProductService;
use RuntimeException;

/**
 * ProductController — Admin
 *
 * Berperan sebagai "polisi lalu lintas":
 *   1. Terima request dari user
 *   2. Cek permission
 *   3. Delegasikan ke ProductService untuk logika bisnis
 *   4. Kembalikan response (redirect / view)
 *
 * TIDAK ADA logika bisnis, query builder, atau file handling di sini.
 * Semua itu ada di App\Services\ProductService.
 */
class ProductController extends BaseController
{
    protected ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    // -------------------------------------------------------------------------
    // 1. LIST SEMUA PRODUK
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('products')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data produk.');
        }

        return view('admin/products/index', [
            'title'    => 'Manajemen Produk',
            'products' => $this->productService->getAllProducts(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 2. DETAIL PRODUK
    // -------------------------------------------------------------------------
    public function detail($id)
    {
        if (!can('products')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data produk.');
        }

        try {
            $product = $this->productService->findProductWithDetails((int) $id);
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('admin/products'))->with('error', $e->getMessage());
        }

        return view('admin/products/detail', [
            'title'   => 'Detail Produk',
            'product' => $product,
            'ratings' => $product['ratings'],
        ]);
    }

    // -------------------------------------------------------------------------
    // 3. HAPUS PRODUK
    // -------------------------------------------------------------------------
    public function delete($id)
    {
        if (!can('products_delete')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus produk.');
        }

        try {
            $this->productService->deleteProduct((int) $id);
            return redirect()->to(base_url('admin/products'))->with('success', 'Produk berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('admin/products'))->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 4. UPDATE STATUS PRODUK
    // -------------------------------------------------------------------------
    public function updateStatus($id, $status)
    {
        if (!can('products_status')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengubah status produk.');
        }

        // Validasi status menggunakan grup 'productUpdateStatus'
        if (!$this->validateData(['status' => $status], 'productUpdateStatus')) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->productService->updateStatus((int) $id, $status);
            return redirect()->back()->with('success', 'Status produk berhasil diubah.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
