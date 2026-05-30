<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class WalletController extends ResourceController
{
    protected $format = 'json';
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Mengambil Saldo & Riwayat Transaksi
     * GET api/tukang/wallet/{tukang_id}
     */
    public function getWalletInfo($tukangId = null)
    {
        try {
            // 1. Ambil saldo dari tabel tukang
            $tukang = $this->db->table('tukang')->where('id', $tukangId)->get()->getRowArray();

            // 2. Ambil riwayat transaksi
            $history = $this->db->table('tukang_transactions')
                ->where('tukang_id', $tukangId)
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResultArray();

            return $this->respond([
                'status' => true,
                'balance' => $tukang['balance'] ?? 0,
                'history' => $history
            ], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Mengirim Permintaan Penarikan Uang
     * POST api/tukang/withdraw
     */
    public function requestWithdrawal()
    {
        $json = $this->request->getJSON();
        $id = $json->tukang_id;
        $amount = $json->amount;

        try {
            // Cek apakah saldo cukup
            $tukang = $this->db->table('tukang')->where('id', $id)->get()->getRowArray();
            if ($tukang['balance'] < $amount) {
                return $this->fail('Saldo tidak mencukupi  .', 400);
            }

            // Simpan permintaan withdraw
            $this->db->table('withdrawal_requests')->insert([
                'tukang_id' => $id,
                'amount' => $amount,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Potong saldo tukang (Opsional: atau potong setelah approved oleh admin)
            $newBalance = $tukang['balance'] - $amount;
            $this->db->table('tukang')->where('id', $id)->update(['balance' => $newBalance]);

            // Catat di riwayat transaksi
            $this->db->table('tukang_transactions')->insert([
                'tukang_id' => $id,
                'amount' => $amount,
                'type' => 'withdraw',
                'description' => 'Penarikan Saldo (Pending)',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated([
                'status' => true,
                'message' => 'Permintaan penarikan berhasil dikirim  !'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}