<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use RuntimeException;

/**
 * OrderService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen Pesanan (Admin).
 * Controller hanya bertanggung jawab menerima request dan mengembalikan response.
 *
 * Query JOIN ke tabel products & suppliers dilakukan via OrderItemsModel
 * menggunakan CI4 Query Builder yang di-chain langsung dari model.
 */
class OrderService
{
    protected OrderModel      $orderModel;
    protected OrderItemsModel $orderItemsModel;

    // Daftar status pesanan yang sah
    private const ALLOWED_STATUSES = [
        'PENDING',
        'UNPAID',
        'PAID',
        'SETTLEMENT',
        'SHIPPED',
        'COMPLETED',
        'CANCELLED',
    ];

    public function __construct()
    {
        $this->orderModel      = new OrderModel();
        $this->orderItemsModel = new OrderItemsModel();
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
        $orders = $this->orderModel
            ->orderBy('id', 'DESC')
            ->findAll();

        // Eager-load items untuk setiap order
        foreach ($orders as &$order) {
            $order['items'] = $this->getItemsByOrderId((int) $order['id']);
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
        $order = $this->orderModel->find($id);

        if (!$order) {
            throw new RuntimeException('Pesanan tidak ditemukan.');
        }

        $order['items'] = $this->getDetailItemsByOrderId($id);

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

        if (!$this->orderModel->find($id)) {
            throw new RuntimeException('Pesanan tidak ditemukan.');
        }

        $this->orderModel->update($id, ['status' => $status]);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Ambil items untuk halaman list (kolom ringkas: product_name, supplier_name).
     */
    private function getItemsByOrderId(int $orderId): array
    {
        return $this->orderItemsModel
            ->select('order_items.*, products.name as product_name, suppliers.name as supplier_name')
            ->join('products', 'products.id = order_items.product_id')
            ->join('suppliers', 'suppliers.id = products.supplier_id')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }

    /**
     * Ambil items untuk halaman detail (kolom lengkap: + product_photo).
     */
    private function getDetailItemsByOrderId(int $orderId): array
    {
        return $this->orderItemsModel
            ->select('order_items.*, products.name as product_name, products.photo as product_photo, suppliers.name as supplier_name')
            ->join('products', 'products.id = order_items.product_id', 'left')
            ->join('suppliers', 'suppliers.id = products.supplier_id', 'left')
            ->where('order_items.order_id', $orderId)
            ->findAll();
    }
}
