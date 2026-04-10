<?php
// FILE: backend/app/Controllers/Api/CartApi.php

namespace App\Controllers\Api;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class CartApi extends BaseController {
    use ResponseTrait;
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    // Ambil isi keranjang
    public function index() {
        $userId = $this->request->user->uid;
        
        $items = $this->db->table('cart')
            ->select('cart.*, products.name, products.price, products.photo')
            ->join('products', 'products.id = cart.product_id')
            ->where('user_id', $userId)
            ->get()->getResultArray();

        foreach ($items as &$item) {
            $item['image_url'] = base_url('uploads/products/' . $item['photo']);
        }

        if ($items) {
            return $this->respond([
                'status' => true,
                'message' => 'Keranjang Pesanan ditemukan',
                'data' => $items
            ]);
        } else {
           return $this->respond([
                'status' => true,
                'message' => 'Belum ada item di keranjang',
                'data' => $items
            ]);
        }
    }

    // Tambah ke keranjang
    public function add() {
        $userId = $this->request->user->uid;

        $rules = [
            'product_id'        => 'required',
            'quantity'          => 'required|numeric',
        ];

        $messages = [
            'product_id' => [
                'required'    => 'product_id wajib diisi',
            ],
            'quantity' => [
                'required'    => 'quantity wajib diisi.',
                'numeric'     => 'quantity hanya boleh berisi angka.'
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $productId = $this->request->getVar('product_id');
        $qty = $this->request->getVar('quantity') ?? 1;

        $existing = $this->db->table('cart')
            ->where(['user_id' => $userId, 'product_id' => $productId])
            ->get()->getRow();

        if ($existing) {
            $this->db->table('cart')->where('id', $existing->id)->update(['quantity' => $existing->quantity + $qty]);
        } else {
            $this->db->table('cart')->insert(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $qty]);
        }
        
        return $this->respond([
            'status' => true,
            'message' => 'Berhasil ditambahkan ke keranjang'
        ]);
    }

    // UPDATE JUMLAH (QTY)
    public function update() {
        $rules = [
            'id'        => 'required',
            'quantity'  => 'required|numeric',
        ];

        $messages = [
            'id' => [
                'required'    => 'id wajib diisi',
            ],
            'quantity' => [
                'required'    => 'quantity wajib diisi.',
                'numeric'     => 'quantity hanya boleh berisi angka.'
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $id = $this->request->getVar('id');
        $qty = $this->request->getVar('quantity');
        
        if (!$id) return $this->fail('ID tidak ditemukan');

        $this->db->table('cart')->where('id', $id)->update(['quantity' => $qty]);
        return $this->respond(['status' => true, 'message' => 'Qty diperbarui']);
    }

    // HAPUS BARANG
    public function delete() {
        $id = $this->request->getVar('id');

        $item = $this->db->table('cart')->where('id', $id)->get()->getRow();
        
        if (!$item) return $this->fail('Item tidak ditemukan');

        $this->db->table('cart')->where('id', $id)->delete();
        
        return $this->respond(['status' => true, 'message' => 'Item dihapus']);
    }
}