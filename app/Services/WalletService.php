<?php

namespace App\Services;

use App\Models\TukangModel;
use App\Models\TukangTransactionsModel;
use App\Models\WithdrawalRequestsModel;
use Config\Database;
use RuntimeException;

/**
 * WalletService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen saldo tukang
 * dan permintaan penarikan dana.
 *
 * Logika keuangan (hitung saldo, cek kecukupan dana, catat transaksi) adalah
 * domain bisnis yang paling wajib dipisahkan dari Controller.
 */
class WalletService
{
    protected TukangModel              $tukangModel;
    protected TukangTransactionsModel  $transactionModel;
    protected WithdrawalRequestsModel  $withdrawalModel;

    // Jenis transaksi yang sah
    private const ALLOWED_TYPES = ['income', 'withdraw'];

    // Status withdrawal yang sah
    private const ALLOWED_WITHDRAWAL_STATUSES = ['approved', 'rejected', 'pending'];

    public function __construct()
    {
        $this->tukangModel      = new TukangModel();
        $this->transactionModel = new TukangTransactionsModel();
        $this->withdrawalModel  = new WithdrawalRequestsModel();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua tukang beserta saldonya, diurutkan A-Z.
     */
    public function getAllTukang(): array
    {
        return $this->tukangModel
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Ambil semua permintaan penarikan dana beserta nama & telepon tukang-nya.
     */
    public function getAllWithdrawalRequests(): array
    {
        return $this->withdrawalModel
            ->select('withdrawal_requests.*, tukang.name as tukang_name, tukang.phone')
            ->join('tukang', 'tukang.id = withdrawal_requests.tukang_id')
            ->orderBy('withdrawal_requests.created_at', 'DESC')
            ->findAll();
    }

    // =========================================================================
    // UPDATE BALANCE (BISNIS INTI)
    // =========================================================================

    /**
     * Tambah atau kurangi saldo tukang secara manual oleh Admin.
     *
     * Logika bisnis yang ditangani:
     * - Validasi tipe transaksi (income / withdraw)
     * - Cek apakah saldo cukup jika tipe withdraw
     * - Hitung saldo baru
     * - Update saldo tukang & catat riwayat transaksi dalam satu DB transaction
     *
     * @param int    $tukangId
     * @param float  $amount
     * @param string $type        'income' atau 'withdraw'
     * @param string $description Keterangan transaksi
     * @throws RuntimeException
     */
    public function updateBalance(int $tukangId, float $amount, string $type, string $description): void
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            throw new RuntimeException('Tipe transaksi tidak valid.');
        }

        $tukang = $this->tukangModel->find($tukangId);
        if (!$tukang) {
            throw new RuntimeException('Data tukang tidak ditemukan.');
        }

        $currentBalance = (float) $tukang['balance'];

        // Aturan bisnis: saldo tidak boleh minus
        if ($type === 'withdraw' && $currentBalance < $amount) {
            throw new RuntimeException('Gagal! Saldo tukang tidak cukup.');
        }

        $newBalance = $type === 'income'
            ? $currentBalance + $amount
            : $currentBalance - $amount;

        $db = Database::connect();
        $db->transStart();

        try {
            // Update saldo tukang
            $this->tukangModel->update($tukangId, ['balance' => $newBalance]);

            // Catat riwayat transaksi via TukangTransactionsModel
            $this->transactionModel->insert([
                'tukang_id'   => $tukangId,
                'amount'      => $amount,
                'type'        => $type,
                'description' => $description,
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new RuntimeException('Gagal mencatat transaksi ke database.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            throw new RuntimeException($e->getMessage());
        }
    }

    // =========================================================================
    // UPDATE WITHDRAWAL STATUS
    // =========================================================================

    /**
     * Ubah status permintaan penarikan dana (approved / rejected / pending).
     *
     * Logika bisnis yang ditangani:
     * - Validasi status yang diizinkan
     * - Pastikan request ada sebelum diupdate
     *
     * @throws RuntimeException
     */
    public function updateWithdrawalStatus(int $id, string $status): void
    {
        if (!in_array($status, self::ALLOWED_WITHDRAWAL_STATUSES, true)) {
            throw new RuntimeException('Status penarikan tidak valid: ' . $status);
        }

        if (!$this->withdrawalModel->find($id)) {
            throw new RuntimeException('Permintaan penarikan tidak ditemukan.');
        }

        $this->withdrawalModel->update($id, ['status' => $status]);
    }
}
