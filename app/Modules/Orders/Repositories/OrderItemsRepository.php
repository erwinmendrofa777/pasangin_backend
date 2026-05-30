<?php

namespace App\Modules\Orders\Repositories;

use App\Modules\Orders\Models\OrderItemsModel;
use App\Modules\Orders\Repositories\Contracts\OrderItemsRepositoryInterface;

class OrderItemsRepository implements OrderItemsRepositoryInterface
{
    protected OrderItemsModel $model;

    public function __construct()
    {
        $this->model = new OrderItemsModel();
    }

    public function findByOrderId(int $orderId): array
    {
        return $this->model
            ->select('order_items.*, products.name as product_name, suppliers.name as supplier_name')
            ->join('products', 'products.id = order_items.product_id')
            ->join('suppliers', 'suppliers.id = products.supplier_id')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }

    public function findDetailsByOrderId(int $orderId): array
    {
        return $this->model
            ->select('order_items.*, products.name as product_name, products.photo as product_photo, suppliers.name as supplier_name')
            ->join('products', 'products.id = order_items.product_id', 'left')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }

    public function getTopSellingProducts(int $limit): array
    {
        return $this->model->select('order_items.*, products.name as product_name, products.photo as product_photo, SUM(order_items.quantity) as total_sales, products.price as product_price, suppliers.name as supplier_name')
            ->join('products', 'products.id = order_items.product_id')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->groupBy('order_items.product_id')
            ->orderBy('total_sales', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
