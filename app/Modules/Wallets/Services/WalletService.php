<?php

namespace App\Modules\Wallets\Services;

use App\Modules\Tukang\Repositories\TukangRepository;
use App\Modules\Tukang\Repositories\TukangTransactionsRepository;
use App\Modules\Wallets\Repositories\WithdrawalRequestsRepository;
use App\Modules\Tukang\Repositories\Contracts\TukangRepositoryInterface;
use App\Modules\Tukang\Repositories\Contracts\TukangTransactionsRepositoryInterface;
use App\Modules\Wallets\Repositories\Contracts\WithdrawalRequestsRepositoryInterface;
use Config\Database;
use RuntimeException;

/**
 * WalletService
 *
 * Menampung semua logika bisnis yang berkaitan dengan manajemen saldo tukang
 * dan permintaan penarikan dana.
 * Sekarang menggunakan Repository Pattern untuk abstraksi data.
 */
class WalletService
{
    protected TukangRepositoryInterface $tukangRepository;
    protected TukangTransactionsRepositoryInterface $transactionRepository;
    protected WithdrawalRequestsRepositoryInterface $withdrawalRepository;

    // Jenis transaksi yang sah
    private const ALLOWED_TYPES = ['income', 'withdraw'];

    // Status withdrawal yang sah
    private const ALLOWED_WITHDRAWAL_STATUSES = ['approved', 'rejected', 'pending'];

    public function __construct()
    {
        $this->tukangRepository = new TukangRepository();
        $this->transactionRepository = new TukangTransactionsRepository();
        $this->withdrawalRepository = new WithdrawalRequestsRepository();
    }

    // =========================================================================
    // READ
    // =========================================================================

    /**
     * Ambil semua tukang beserta saldonya, diurutkan A-Z.
     */
    public function getAllTukang(): array
    {
        return $this->tukangRepository->findAllOrderedByName();
    }

    /**
     * Ambil semua permintaan penarikan dana beserta nama & telepon tukang-nya.
     */
    public function getAllWithdrawalRequests(): array
    {
        return $this->withdrawalRepository->findAllWithTukang();
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

        $tukang = $this->tukangRepository->findById($tukangId);
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
            $this->tukangRepository->update($tukangId, ['balance' => $newBalance]);

            // Catat riwayat transaksi via TukangTransactionsRepository
            $this->transactionRepository->insert([
                'tukang_id' => $tukangId,
                'amount' => $amount,
                'type' => $type,
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
     * Cari permintaan penarikan dana berdasarkan ID atau lempar exception.
     * @throws RuntimeException
     */
    public function findWithdrawalOrFail(int $id): array
    {
        $request = $this->withdrawalRepository->findById($id);
        if (!$request) {
            throw new RuntimeException('Permintaan penarikan tidak ditemukan.');
        }
        return $request;
    }

    /**
     * Ubah status permintaan penarikan dana (approved / rejected / pending).
     * @throws RuntimeException
     */
    public function updateWithdrawalStatus(int $id, string $status): void
    {
        if (!in_array($status, self::ALLOWED_WITHDRAWAL_STATUSES, true)) {
            throw new RuntimeException('Status penarikan tidak valid: ' . $status);
        }

        $this->findWithdrawalOrFail($id);

        $this->withdrawalRepository->update($id, ['status' => $status]);
    }
}
