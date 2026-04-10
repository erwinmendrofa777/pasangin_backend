<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class ProductApi extends BaseController
{
    use ResponseTrait;

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Mengambil produk dengan pagination
     */
    public function index()
    {
        // --- 1. AMBIL PARAMETER HALAMAN & ATUR BATAS ITEM ---
        $page = $this->request->getGet('page') ?? 1;
        $limit = 10; // Jumlah item per halaman
        $offset = ($page - 1) * $limit;

        // --- 2. BUAT QUERY UNTUK MENGHITUNG TOTAL PRODUK ---
        $totalProducts = $this->db->table('products')->where('is_active', 1)->countAllResults();

        // --- 3. BUAT QUERY UTAMA DENGAN LIMIT & OFFSET ---
        $products = $this->db->table('products')
            ->select('products.*, suppliers.name as supplier_name, suppliers.logo_url as supplier_logo')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->where('products.is_active', 1)
            ->orderBy('products.name', 'ASC')
            ->limit($limit, $offset) // Terapkan pagination di sini
            ->get()->getResultArray();

        // Logic untuk melengkapi URL gambar (tetap sama)
        foreach ($products as &$product) {
            if ($product['image_url'] && !filter_var($product['image_url'], FILTER_VALIDATE_URL)) {
                $product['image_url'] = base_url('uploads/products/' . $product['image_url']);
            }
            if ($product['supplier_logo'] && !filter_var($product['supplier_logo'], FILTER_VALIDATE_URL)) {
                $product['supplier_logo'] = base_url('uploads/suppliers/' . $product['supplier_logo']);
            }
        }
        
        // --- 4. KEMBALIKAN DATA BESERTA INFO PAGINATION ---
        return $this->respond([
            'status' => true,
            'data' => $products,
            'pagination' => [
                'current_page' => (int)$page,
                'total_products' => $totalProducts,
                'products_per_page' => $limit,
                'has_more_pages' => ($page * $limit) < $totalProducts
            ]
        ]);
    }
}
