<?php

namespace App\Modules\Wallets\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Wallets\Services\WalletService;
use App\Modules\Notifications\Services\NotificationService;
use RuntimeException;

/**
 * Wallet Controller — Admin
 *
 * Berperan sebagai "polisi lalu lintas":
 *   1. Terima request dari user
 *   2. Cek permission
 *   3. Validasi input dasar
 *   4. Delegasikan ke WalletService untuk logika bisnis keuangan
 *   5. Kembalikan response (redirect / view)
 *
 * TIDAK ADA kalkulasi saldo, raw query, atau DB transaction di sini.
 * Semua itu ada di App\Modules\Wallets\Services\WalletService.
 */
class Wallet extends BaseController
{
    protected WalletService $walletService;
    protected NotificationService $notifService;

    public function __construct()
    {
        $this->walletService = new WalletService();
        $this->notifService = new NotificationService();
    }

    // -------------------------------------------------------------------------
    // 1. LIST TUKANG & SALDO
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('wallet')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data saldo.');
        }

        return view('App\Modules\Wallets\Views\index', [
            'title' => 'Manajemen Saldo Tukang',
            'tukang' => $this->walletService->getAllTukang(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 2. PROSES TAMBAH / KURANG SALDO MANUAL
    // -------------------------------------------------------------------------
    public function update_balance()
    {
        if (!can('wallet_manage')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengelola saldo.');
        }

        if (!$this->validateData($this->request->getPost(), 'walletUpdateBalance')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $post = $this->request->getPost();
            $this->walletService->updateBalance(
                (int) $post['tukang_id'],
                (float) $post['amount'],
                $post['type'],
                $post['description'] ?? ''
            );

            // Kirim Notifikasi ke Tukang
            $tukangId = (int) $post['tukang_id'];
            $amountFormatted = number_format($post['amount'], 0, ',', '.');
            $typeLabel = ($post['type'] === 'income') ? 'Penambahan' : 'Pengurangan';
            $title = "Update Saldo Dompet";
            $message = "Halo! Ada {$typeLabel} saldo sebesar Rp {$amountFormatted} ke dompet Anda. Keterangan: " . ($post['description'] ?? '-');

            $this->notifService->sendPersonal('tukang', $tukangId, $title, $message);

            log_admin_activity('update', 'wallet', 'mengupdate saldo');
            return redirect()->to(base_url('admin/wallet'))->with('success', 'Saldo berhasil diperbarui dan notifikasi dikirim  !');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 3. LIST PERMINTAAN PENARIKAN DANA
    // -------------------------------------------------------------------------
    public function withdrawals()
    {
        if (!can('wallet_withdraw_request')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat permintaan penarikan dana.');
        }

        return view('App\Modules\Wallets\Views\withdrawals', [
            'title' => 'Permintaan Penarikan Dana',
            'requests' => $this->walletService->getAllWithdrawalRequests(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 4. UPDATE STATUS WITHDRAWAL (SETUJUI / TOLAK)
    // -------------------------------------------------------------------------
    public function update_withdrawal_status($id, $status)
    {
        if (!can('wallet_withdraw_request')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengubah status permintaan penarikan dana.');
        }

        if (!$this->validateData(['status' => $status], 'walletUpdateWithdrawStatus')) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            // Dapatkan data penarikan sebelum diupdate untuk tahu siapa tukangnya
            $request = $this->walletService->findWithdrawalOrFail((int) $id);

            // Delegasikan status update ke service
            $this->walletService->updateWithdrawalStatus((int) $id, $status);

            // Kirim Notifikasi ke Tukang jika sukses disetujui / ditolak manual
            if ($request && !empty($request['tukang_id'])) {
                $tukangId = (int) $request['tukang_id'];
                $amount = number_format($request['amount'], 0, ',', '.');
                $title = "Update Penarikan Dana";

                if ($status === 'approved') {
                    $message = "Permintaan penarikan dana sebesar Rp {$amount} telah DISETUJUI. Dana akan segera dikirim ke rekening Anda.";
                } elseif ($status === 'rejected') {
                    $message = "Maaf, permintaan penarikan dana sebesar Rp {$amount} telah DITOLAK. Silakan hubungi admin untuk informasi lebih lanjut.";
                } else {
                    $message = "Status permintaan penarikan dana sebesar Rp {$amount} Anda telah diperbarui menjadi " . strtoupper($status);
                }

                $this->notifService->sendPersonal('tukang', $tukangId, $title, $message);
            }

            log_admin_activity('update', 'wallet', 'mengupdate status penarikan dana');
            return redirect()->back()->with('success', 'Status penarikan diperbarui dan notifikasi telah dikirim.');
        } catch (\Throwable $e) {
            // Jika otomatis ditolak karena saldo kurang
            if (strpos($e->getMessage(), 'diubah menjadi rejected') !== false) {
                if (isset($request) && !empty($request['tukang_id'])) {
                    $tukangId = (int) $request['tukang_id'];
                    $amount = number_format($request['amount'], 0, ',', '.');
                    $title = "Penarikan Dana Ditolak";
                    $message = "Maaf, permintaan penarikan dana sebesar Rp {$amount} telah DITOLAK karena saldo Anda tidak cukup.";
                    $this->notifService->sendPersonal('tukang', $tukangId, $title, $message);
                }
                log_admin_activity('update', 'wallet', 'menolak otomatis penarikan dana karena saldo tidak cukup');
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
