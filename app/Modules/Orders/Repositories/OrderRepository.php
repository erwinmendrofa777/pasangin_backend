<?php

namespace App\Modules\Orders\Repositories;

use App\Modules\Orders\Models\OrderModel;
use App\Modules\Orders\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    protected OrderModel $model;

    public function __construct()
    {
        $this->model = new OrderModel();
    }

    public function findAllOrderedByIdDesc(): array
    {
        return $this->model->orderBy('id', 'DESC')->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function getMonthlySalesData(string $startDate): array
    {
        return $this->model->select("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as total_orders, SUM(total_price) as total_revenue")
            ->where('created_at >=', $startDate)
            ->groupBy("month")
            ->orderBy("month", "ASC")
            ->get()
            ->getResultArray();
    }
}
