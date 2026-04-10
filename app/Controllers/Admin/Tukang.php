<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Tukang extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title'     => 'Daftar Tukang / Mitra',
            'tukang'    => $this->db->table('tukang')
                            ->select('tukang.*, COALESCE(ROUND(AVG(tukang_rating.skill_score), 1), 0) as skill_score, COALESCE(ROUND(AVG(tukang_rating.behavior_score), 1), 0) as behavior_score, COALESCE(tukang.rata_rata_rating, 0) as rata_rata_rating')
                            ->join('tukang_rating', 'tukang.id = tukang_rating.id_tukang', 'left')
                            ->groupBy('tukang.id')
                            ->get()->getResultArray(),
        ];

        return view('admin/tukang/index', $data);
    }
    
    // Tambahkan fungsi ini di dalam class Tukang
    public function update_stats()
    {
        $id = $this->request->getPost('id');
        
        $data = [
            'status'         => $this->request->getPost('status'),
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $this->db->table('tukang')->where('id', $id)->update($data);

        return redirect()->back()->with('success', 'Data statistik tukang berhasil diperbarui!');
    }

    // Fungsi untuk update status (Approved/Rejected) kawan
    public function update_status()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $this->db->table('tukang')->where('id', $id)->update([
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Status mitra tukang berhasil diperbarui!');
    }
}