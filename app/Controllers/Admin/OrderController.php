<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class OrderController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Kita ambil data langsung dari tabel orders kawan
        // Tidak perlu join ke users karena sudah ada recipient_name
        $orders = $this->db->table('orders')
            ->orderBy('id', 'DESC')
            ->get()->getResultArray();

        // Untuk setiap pesanan, kita ambil item dan nama supplier-nya
        foreach ($orders as &$order) {
            $order['items'] = $this->db->table('order_items')
                ->select('order_items.*, products.name as product_name, suppliers.name as supplier_name')
                ->join('products', 'products.id = order_items.product_id')
                ->join('suppliers', 'suppliers.id = products.supplier_id')
                ->where('order_items.order_id', $order['id'])
                ->get()->getResultArray();
        }

        return view('admin/orders/index', [
            'title'  => 'Manajemen Pesanan',
            'orders' => $orders
        ]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $this->db->table('orders')->where('id', $id)->update(['status' => $status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}