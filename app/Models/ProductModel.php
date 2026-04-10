<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table= 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // ▼▼▼ FIELD SUDAH DILENGKAPI DENGAN KATEGORI, UNIT, MIN ORDER, DAN WEIGHT ▼▼▼
    protected $allowedFields    = [
        'supplier_id', 
        'category_id', 
        'name', 
        'description', 
        'price', 
        'unit', 
        'stock', 
        'min_order', 
        'weight', 
        'status', 
        'photo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Fungsi custom untuk mengambil semua produk berdasarkan supplier_id.
     * Sekarang juga mengambil data nama kategori jika diperlukan (opsional).
     */
    public function getProductsBySupplier($supplierId)
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories', 'categories.id = products.category_id', 'left')
                    ->where('products.supplier_id', $supplierId)
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll();
    }
}