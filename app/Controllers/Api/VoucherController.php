<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\VoucherModel;

class VoucherController extends ResourceController
{
    // Fungsi ini dipanggil saat Flutter akses GET /api/vouchers
    public function index()
    {
        $model = new VoucherModel();

        // Ambil semua voucher yang IS_ACTIVE = 1 dan belum kadaluarsa
        $data = $model->where('is_active', 1)
                      ->where('valid_until >=', date('Y-m-d'))
                      ->findAll();

        // Tambahkan URL lengkap gambar agar bisa dibaca Flutter
        foreach ($data as &$row) {
            $row['image_url'] = base_url('uploads/vouchers/' . $row['image']);
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Daftar Voucher',
            'data' => $data
        ]);
    }
}
