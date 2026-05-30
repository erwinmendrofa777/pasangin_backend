<?php

namespace App\Modules\Orders\Repositories\Contracts;

interface OrderItemsRepositoryInterface
{
    public function findByOrderId(int $orderId): array;
    public function findDetailsByOrderId(int $orderId): array;
    public function getTopSellingProducts(int $limit): array;
}
