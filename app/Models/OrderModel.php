<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['status']; // Supplier biasanya cuma bisa update status

    /**
     * Mengambil daftar pesanan yang mengandung produk milik supplier tertentu
     */
    public function getOrdersBySupplier($supplierId)
    {
        return $this->select('orders.*')
            ->join('order_items', 'order_items.order_id = orders.id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $supplierId)
            ->groupBy('orders.id') // Agar order tidak duplikat jika beli banyak item dari supplier yang sama
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Mengambil item produk di dalam satu pesanan khusus milik supplier tersebut
     */
    public function getItemsBySupplier($orderId, $supplierId)
    {
        return $this->db->table('order_items')
            ->select('order_items.*, products.name as product_name, products.photo')
            ->join('products', 'products.id = order_items.product_id')
            ->where('order_items.order_id', $orderId)
            ->where('products.supplier_id', $supplierId)
            ->get()
            ->getResultArray();
    }
}