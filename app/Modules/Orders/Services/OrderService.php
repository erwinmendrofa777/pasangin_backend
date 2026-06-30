<?php

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Repositories\OrderRepository;
use App\Modules\Orders\Repositories\OrderItemsRepository;
use App\Modules\Orders\Repositories\Contracts\OrderRepositoryInterface;
use App\Modules\Orders\Repositories\Contracts\OrderItemsRepositoryInterface;
use RuntimeException;

/**
 * OrderService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Pesanan (Admin).
 * Controller hanya bertanggung jawab menerima request dan mengembalikan response.
 * Sekarang menggunakan Repository Pattern untuk akses data.
 */
class OrderService
{
    protected OrderRepositoryInterface $orderRepository;
    protected OrderItemsRepositoryInterface $orderItemsRepository;

    // Daftar status pesanan yang sah
    private const ALLOWED_STATUSES = [
        'UNPAID',
        'PAID',
        'PROCESSED',
        'LOADING',
        'SHIPPED',
        'ARRIVED',
        'COMPLETED',
        'CANCELLED',
    ];

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->orderItemsRepository = new OrderItemsRepository();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua pesanan beserta item-item-nya (eager-loaded).
     * Setiap order memiliki key 'items' berisi array produk + supplier.
     */
    public function getAllOrdersWithItems(): array
    {
        $orders = $this->orderRepository->findAllOrderedByIdDesc();

        // Eager-load items untuk setiap order
        foreach ($orders as &$order) {
            $order['items'] = $this->orderItemsRepository->findByOrderId((int) $order['id']);
        }

        return $orders;
    }

    /**
     * Ambil satu pesanan beserta item-item detailnya.
     * Melempar RuntimeException jika tidak ditemukan.
     *
     * @throws RuntimeException
     */
    public function findOrderWithItems(int $id): array
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            throw new RuntimeException('Pesanan tidak ditemukan.');
        }

        $order['items'] = $this->orderItemsRepository->findDetailsByOrderId($id);

        return $order;
    }

    // =========================================================================
    // UPDATE STATUS
    // =========================================================================

    /**
     * Perbarui status pesanan.
     *
     * Logika bisnis yang ditangani:
     * - Validasi bahwa status adalah nilai yang sah
     * - Pastikan pesanan ada sebelum diupdate
     *
     * @throws RuntimeException
     */
    public function updateStatus(int $id, string $status): void
    {
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw new RuntimeException('Status pesanan tidak valid: ' . $status);
        }

        if (!$this->orderRepository->findById($id)) {
            throw new RuntimeException('Pesanan tidak ditemukan.');
        }

        $this->orderRepository->update($id, ['status' => $status]);
    }
}
