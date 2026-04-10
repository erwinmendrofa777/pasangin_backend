<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Wallet extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Tampilkan semua tukang beserta saldonya
    public function index()
    {
        $data = [
            'title'   => 'Manajemen Saldo Tukang',
            'tukang'  => $this->db->table('tukang')->orderBy('name', 'ASC')->get()->getResultArray()
        ];
        return view('admin/wallet/index', $data);
    }

    // Proses Tambah/Kurang Saldo Manual oleh Admin
    public function update_balance()
    {
        $tukangId    = $this->request->getPost('tukang_id');
        $amount      = $this->request->getPost('amount');
        $type        = $this->request->getPost('type'); // income atau withdraw
        $description = $this->request->getPost('description');

        try {
            $this->db->transStart();

            // 1. Ambil data tukang
            $tukang = $this->db->table('tukang')->where('id', $tukangId)->get()->getRowArray();
            $currentBalance = $tukang['balance'];

            // 2. Hitung saldo baru
            if ($type == 'income') {
                $newBalance = $currentBalance + $amount;
            } else {
                if ($currentBalance < $amount) {
                    return redirect()->back()->with('error', 'Gagal! Saldo tukang tidak cukup.');
                }
                $newBalance = $currentBalance - $amount;
            }

            // 3. Update saldo di tabel tukang
            $this->db->table('tukang')->where('id', $tukangId)->update(['balance' => $newBalance]);

            // 4. Catat ke riwayat transaksi
            $this->db->table('tukang_transactions')->insert([
                'tukang_id'   => $tukangId,
                'amount'      => $amount,
                'type'        => $type,
                'description' => $description,
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            $this->db->transComplete();

            return redirect()->to(base_url('admin/wallet'))->with('success', 'Saldo berhasil diperbarui kawan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Tampilkan daftar permintaan tarik uang (Withdrawal)
    public function withdrawals()
    {
        $data = [
            'title'    => 'Permintaan Penarikan Dana',
            'requests' => $this->db->table('withdrawal_requests')
                                   ->select('withdrawal_requests.*, tukang.name as tukang_name, tukang.phone')
                                   ->join('tukang', 'tukang.id = withdrawal_requests.tukang_id')
                                   ->orderBy('withdrawal_requests.created_at', 'DESC')
                                   ->get()->getResultArray()
        ];
        return view('admin/wallet/withdrawals', $data);
    }

    // Update Status Withdraw (Setujui/Tolak)
    public function update_withdrawal_status($id, $status)
    {
        $this->db->table('withdrawal_requests')->where('id', $id)->update([
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Status penarikan diperbarui kawan.');
    }
}