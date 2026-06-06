<?php

namespace App\Modules\Wallets\Services;

use App\Modules\Wallets\Models\AdminBalanceModel;
use App\Modules\Wallets\Models\AdminTransactionModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;
use RuntimeException;

class AdminBalanceService
{
    protected AdminBalanceModel $balanceModel;
    protected AdminTransactionModel $transactionModel;

    public function __construct()
    {
        $this->balanceModel = new AdminBalanceModel();
        $this->transactionModel = new AdminTransactionModel();
    }

    /**
     * Mengambil saldo platform internal lokal saat ini.
     * 
     * @return float
     */
    public function getBalance(): float
    {
        $row = $this->balanceModel->find(1);
        return $row ? (float) $row['balance'] : 0.00;
    }

    /**
     * Menambahkan transaksi mutasi internal platform (deposit/expense).
     * 
     * @param float  $amount
     * @param string $type        'income' atau 'expense'
     * @param string $source       Kategori transaksi (e.g. 'order_app_fee', 'manual_deposit', 'manual_withdrawal')
     * @param string|null $referenceId ID referensi terkait (e.g. order_id)
     * @param string|null $description Deskripsi transaksi
     * @throws RuntimeException
     */
    public function addTransaction(float $amount, string $type, string $source, ?string $referenceId = null, ?string $description = null): void
    {
        if (!in_array($type, ['income', 'expense'], true)) {
            throw new RuntimeException('Tipe transaksi tidak valid: ' . $type);
        }

        if ($amount <= 0) {
            throw new RuntimeException('Nominal transaksi harus lebih besar dari 0.');
        }

        $db = Database::connect();
        $db->transStart();

        try {
            // Ambil saldo saat ini dengan lock untuk menghindari race condition
            $currentBalanceRow = $db->query("SELECT balance FROM admin_balance WHERE id = 1 FOR UPDATE")->getRowArray();
            $currentBalance = $currentBalanceRow ? (float) $currentBalanceRow['balance'] : 0.00;

            if ($type === 'expense' && $currentBalance < $amount && $source !== 'balance_adjustment') {
                throw new RuntimeException('Gagal! Saldo platform tidak mencukupi.');
            }

            $newBalance = $type === 'income'
                ? $currentBalance + $amount
                : $currentBalance - $amount;

            // 1. Update saldo
            $this->balanceModel->update(1, [
                'balance' => $newBalance,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // 2. Catat transaksi
            $this->transactionModel->insert([
                'amount' => $amount,
                'type' => $type,
                'source' => $source,
                'reference_id' => $referenceId,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new RuntimeException('Gagal memproses mutasi saldo admin.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Mengambil riwayat mutasi transaksi internal platform.
     * 
     * @param int $limit
     * @return array
     */
    public function getTransactionHistory(int $limit = 100): array
    {
        return $this->transactionModel->orderBy('created_at', 'DESC')->limit($limit)->findAll();
    }

    /**
     * Memanggil API Midtrans untuk mendapatkan saldo Payin saat ini.
     * Hanya mengambil saldo kategori 'payin' sesuai instruksi user.
     * 
     * @return array Array berisi ['balance' => float, 'error' => bool, 'message' => string]
     */
    public function getMidtransPayinBalance(): array
    {
        $serverKey = getenv('MIDTRANS_SERVER_KEY');
        $isProduction = filter_var(getenv('MIDTRANS_IS_PRODUCTION'), FILTER_VALIDATE_BOOLEAN);

        if (empty($serverKey)) {
            return [
                'balance' => 0.00,
                'error' => true,
                'message' => 'MIDTRANS_SERVER_KEY tidak ditemukan di environment.'
            ];
        }

        // Tentukan Base URL
        $baseUrl = $isProduction
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        $url = $baseUrl . '/v1/balance/mutation';

        // Rentang waktu: 24 jam terakhir ke sekarang
        $startTime = date('Y-m-d\TH:i:sP', time() - 86400);
        $endTime = date('Y-m-d\TH:i:sP');

        // Parameter query
        $queryParams = [
            'currency' => 'IDR',
            'start_time' => $startTime,
            'end_time' => $endTime
        ];

        try {
            // Gunakan CodeIgniter Curl Client
            $client = \Config\Services::curlrequest([
                'timeout' => 10,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
                ]
            ]);

            $response = $client->get($url, [
                'query' => $queryParams,
                'http_errors' => false // Jangan lempar exception pada HTTP error, baca status code
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();

            if ($statusCode !== 200) {
                $errData = json_decode($body, true);
                $errMsg = $errData['error_messages'][0] ?? 'HTTP Error ' . $statusCode;
                return [
                    'balance' => 0.00,
                    'error' => true,
                    'message' => 'Gagal mengambil saldo dari Midtrans: ' . $errMsg
                ];
            }

            $data = json_decode($body, true);
            if (empty($data)) {
                return [
                    'balance' => 0.00,
                    'error' => true,
                    'message' => 'Respon Midtrans kosong atau tidak valid.'
                ];
            }

            // Cari wallet dengan source = 'payin'
            $payinBalance = 0.00;
            $foundPayin = false;

            if (isset($data['wallets']) && is_array($data['wallets'])) {
                foreach ($data['wallets'] as $wallet) {
                    if (isset($wallet['source']) && strtolower($wallet['source']) === 'payin') {
                        $payinBalance = (float) ($wallet['closing_balance_overall'] ?? 0);
                        $foundPayin = true;
                        break;
                    }
                }
            }

            // Jika tidak ditemukan wallet payin khusus, fallback ke closing_balance_overall utama
            if (!$foundPayin && isset($data['closing_balance_overall'])) {
                $payinBalance = (float) $data['closing_balance_overall'];
            }

            return [
                'balance' => $payinBalance,
                'error' => false,
                'message' => 'Success'
            ];

        } catch (\Exception $e) {
            log_message('error', 'Midtrans Balance API Error: ' . $e->getMessage());
            return [
                'balance' => 0.00,
                'error' => true,
                'message' => 'Terjadi kesalahan koneksi ke Midtrans: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Rekonsiliasi saldo internal dengan saldo live Midtrans.
     * Membuat penyesuaian (adjustment) jika terjadi selisih.
     * 
     * @param float $midtransLiveBalance
     * @throws RuntimeException
     */
    public function reconcileWithMidtrans(float $midtransLiveBalance): void
    {
        $localBalance = $this->getBalance();
        $diff = $midtransLiveBalance - $localBalance;

        if (round($diff, 2) === 0.00) {
            return; // Saldo sudah pas
        }

        $type = $diff > 0 ? 'income' : 'expense';
        $amount = abs($diff);

        $this->addTransaction(
            $amount,
            $type,
            'balance_adjustment',
            'reconcile',
            'Penyesuaian saldo agar sinkron dengan live Midtrans (selisih: Rp ' . number_format($diff, 2, ',', '.') . ')'
        );
    }
}

