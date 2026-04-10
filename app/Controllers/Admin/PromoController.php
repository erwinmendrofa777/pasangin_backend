<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PromoModel;

class PromoController extends BaseController{

    protected $promoModel;

    public function __construct()
    {
        $this->promoModel = new PromoModel();
    }

    // --------------------------------------------------------------------
    // HALAMAN UTAMA PROMO
    // --------------------------------------------------------------------
    public function index(){
        $data = [
            'title'    => 'Manajemen Promo',
            'promos' => $this->promoModel->select('promos.*, suppliers.name as supplier_name')
                                            ->join('suppliers', 'suppliers.id = promos.supplier_id', 'left')
                                            ->orderBy('id', 'DESC')
                                            ->findAll(),
        ];
        return view('admin/promos/index', $data);
    }

    // --------------------------------------------------------------------
    // 7. HALAMAN DETAIL PROMO
    // --------------------------------------------------------------------
    public function detail($id = null){
        // Pastikan ID tidak kosong
        if (!$id) {
            return redirect()->to('/admin/promo')->with('error', 'Promo tidak valid.');
        }

        // Query
        $promo = $this->promoModel->select('promos.*, suppliers.name as supplier_name')
                                    ->join('suppliers', 'suppliers.id = promos.supplier_id', 'left')
                                    ->find($id);

        // Cek apakah data ditemukan
        if (!$promo) {
            return redirect()->to('/admin/promo')->with('error', 'Promo tidak ditemukan.');
        }

        // Siapkan data untuk View
        $data = [
            'title'     => 'Detail Promo',
            'promo'     => $promo,
        ];

        return view('admin/promos/detail', $data);
    }
}

?>