<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\WalletService;
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
 * Semua itu ada di App\Services\WalletService.
 */
class Wallet extends BaseController
{
    protected WalletService $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    // -------------------------------------------------------------------------
    // 1. LIST TUKANG & SALDO
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('wallet')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data saldo.');
        }

        return view('admin/wallet/index', [
            'title'  => 'Manajemen Saldo Tukang',
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
            $this->walletService->updateBalance(
                (int)   $this->request->getPost('tukang_id'),
                (float) $this->request->getPost('amount'),
                        $this->request->getPost('type'),
                        $this->request->getPost('description') ?? ''
            );

            return redirect()->to(base_url('admin/wallet'))->with('success', 'Saldo berhasil diperbarui kawan!');
        } catch (RuntimeException $e) {
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

        return view('admin/wallet/withdrawals', [
            'title'    => 'Permintaan Penarikan Dana',
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
            $this->walletService->updateWithdrawalStatus((int) $id, $status);
            return redirect()->back()->with('success', 'Status penarikan diperbarui kawan.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
