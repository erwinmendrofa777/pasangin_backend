<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // ▼▼▼ INI ADALAH BAGIAN YANG DIPERBAIKI ▼▼▼
    protected $allowedFields    = ['supplier_id', 'name', 'description', 'price', 'stock', 'status', 'photo'];

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
     */
    public function getProductsBySupplier($supplierId)
    {
        return $this->where('supplier_id', $supplierId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
