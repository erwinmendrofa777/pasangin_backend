<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\OrderService;
use RuntimeException;

/**
 * OrderController — Admin
 *
 * Berperan sebagai "polisi lalu lintas":
 *   1. Terima request dari user
 *   2. Cek permission
 *   3. Delegasikan ke OrderService untuk logika bisnis
 *   4. Kembalikan response (redirect / view)
 *
 * TIDAK ADA raw query, JOIN, atau loop data di sini.
 * Semua itu ada di App\Services\OrderService.
 */
class OrderController extends BaseController
{
    protected OrderService $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    // -------------------------------------------------------------------------
    // 1. LIST SEMUA PESANAN
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('orders')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data pesanan.');
        }

        return view('admin/orders/index', [
            'title'  => 'Manajemen Pesanan',
            'orders' => $this->orderService->getAllOrdersWithItems(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 2. DETAIL PESANAN
    // -------------------------------------------------------------------------
    public function detail($id)
    {
        if (!can('orders')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data pesanan.');
        }

        try {
            $order = $this->orderService->findOrderWithItems((int) $id);
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('admin/orders'))->with('error', $e->getMessage());
        }

        return view('admin/orders/detail', [
            'title' => 'Detail Pesanan',
            'order' => $order,
            'items' => $order['items'],
        ]);
    }

    // -------------------------------------------------------------------------
    // 3. UPDATE STATUS PESANAN
    // -------------------------------------------------------------------------
    public function updateStatus($id)
    {
        if (!can('orders_status')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengubah status pesanan.');
        }

        if (!$this->validateData($this->request->getPost(), 'orderUpdateStatus')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->with('error', $errors);
        }

        $status = $this->request->getPost('status');

        try {
            $this->orderService->updateStatus((int) $id, $status);
            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
