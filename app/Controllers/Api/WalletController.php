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

    public function getWithdrawalRequests($tukangId = null)
    {
        try {
            // 1. Ambil saldo dari tabel tukang
            $tukang = $this->db->table('tukang')->where('id', $tukangId)->get()->getRowArray();

            // 2. Ambil riwayat transaksi
            $history = $this->db->table('withdrawal_requests')
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
        $bank_name = $json->bank_name;
        $account_number = $json->account_number;
        $account_name = $json->account_name;

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
                'bank_name' => $bank_name,
                'account_number' => $account_number,
                'account_name' => $account_name,
                'status' => 'pending',
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