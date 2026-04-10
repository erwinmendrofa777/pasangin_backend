<?php

namespace App\Controllers\Api;

use App\Models\SupplierModel;
use CodeIgniter\RESTful\ResourceController;

class SupplierProfileApi extends ResourceController
{
    protected $format = 'json';
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * --- GET PUBLIC PROFILE SUPPLIER ---
     * Menggabungkan rating, total produk, pesanan, dan tahun berdiri
     * Rute: api/supplier/public-profile/(:num)
     */
    public function index($id = null)
    {
        if (!$id) {
            return $this->fail('ID Supplier tidak boleh kosong');
        }

        $supplierModel = new SupplierModel();
        $supplier = $supplierModel->find($id);

        if (!$supplier) {
            return $this->failNotFound('Supplier tidak ditemukan');
        }

        // 1. Ambil data dasar & format
        unset($supplier['password']); // Keamanan
        $supplier['image_url'] = !empty($supplier['logo_url']) ? base_url('uploads/supplier/' . $supplier['logo_url']) : null;
        $supplier['tahun_berdiri'] = date('Y', strtotime($supplier['created_at']));

        // 2. Hitung total produk
        $totalProducts = $this->db->table('products')
                                  ->where('supplier_id', $id)
                                  ->countAllResults();
        $supplier['total_produk'] = $totalProducts;

        // 3. Hitung total pesanan yang diterima
        $totalOrdersQuery = $this->db->table('order_items')
            ->select('orders.id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $id)
            ->whereIn('orders.status', ['PAID', 'SETTLEMENT', 'PROCESSED', 'SHIPPED', 'COMPLETED'])
            ->groupBy('orders.id')
            ->get();
        $supplier['jumlah_pesanan'] = $totalOrdersQuery->getNumRows();

        // 4. Ambil data rating yang sudah dikalkulasi di tabel supplier untuk efisiensi
        $supplier['rata_rata_rating'] = (float) ($supplier['rata_rata_rating'] ?? 0);
        $supplier['total_ulasan'] = (int) ($supplier['total_ulasan'] ?? 0);

        return $this->respond([
            'status'  => true,
            'message' => 'Profil publik supplier ditemukan',
            'data'    => $supplier
        ]);
    }
}