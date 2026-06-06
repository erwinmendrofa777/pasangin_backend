<?php

namespace App\Modules\Wallets\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Wallets\Services\AdminBalanceService;
use RuntimeException;

class AdminBalance extends BaseController
{
    protected AdminBalanceService $balanceService;

    public function __construct()
    {
        $this->balanceService = new AdminBalanceService();
    }

    /**
     * Tampilan utama Saldo Admin & Mutasi
     */
    public function index()
    {
        $isAccounting = (strtolower(session()->get('role') ?? '') === 'accounting' || can('dashboard_accounting'));

        if (!can('admin_balance_view') && !$isAccounting) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data saldo admin.');
        }

        // Ambil Saldo Lokal (Internal)
        $localBalance = $this->balanceService->getBalance();

        // Ambil Saldo Live Midtrans (Payin)
        $midtransData = $this->balanceService->getMidtransPayinBalance();
        
        // Ambil Riwayat Transaksi Internal
        $history = $this->balanceService->getTransactionHistory();

        return view('App\Modules\Wallets\Views\admin_balance\index', [
            'title'          => 'Manajemen Saldo Admin',
            'localBalance'   => $localBalance,
            'midtransBalance'=> $midtransData['balance'],
            'midtransError'  => $midtransData['error'],
            'midtransMessage'=> $midtransData['message'],
            'history'        => $history,
        ]);
    }

    /**
     * Proses Deposit Manual Saldo Platform
     */
    public function deposit()
    {
        if (!can('admin_balance_manage')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengelola saldo admin.');
        }

        $rules = [
            'amount'      => 'required|numeric|greater_than[0]',
            'description' => 'permit_empty|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $amount = (float)$this->request->getPost('amount');
        $description = $this->request->getPost('description') ?: 'Deposit manual oleh admin';

        try {
            $this->balanceService->addTransaction(
                $amount, 
                'income', 
                'manual_deposit', 
                null, 
                $description
            );

            log_admin_activity('update', 'admin_balance', 'Melakukan deposit manual sebesar Rp ' . number_format($amount, 0, ',', '.'));
            return redirect()->back()->with('success', 'Deposit manual berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Proses Penarikan/Pengeluaran Manual Saldo Platform
     */
    public function withdraw()
    {
        if (!can('admin_balance_manage')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengelola saldo admin.');
        }

        $rules = [
            'amount'      => 'required|numeric|greater_than[0]',
            'description' => 'permit_empty|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $amount = (float)$this->request->getPost('amount');
        $description = $this->request->getPost('description') ?: 'Penarikan manual oleh admin';

        try {
            $this->balanceService->addTransaction(
                $amount, 
                'expense', 
                'manual_withdrawal', 
                null, 
                $description
            );

            log_admin_activity('update', 'admin_balance', 'Melakukan penarikan manual sebesar Rp ' . number_format($amount, 0, ',', '.'));
            return redirect()->back()->with('success', 'Penarikan manual berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Sinkronkan Saldo Lokal dengan Live Midtrans Payin
     */
    public function sync()
    {
        if (!can('admin_balance_manage')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Anda tidak memiliki akses untuk menyinkronkan saldo admin.']);
        }

        try {
            // Ambil Saldo Live Midtrans
            $midtransData = $this->balanceService->getMidtransPayinBalance();
            if ($midtransData['error']) {
                throw new \RuntimeException($midtransData['message']);
            }

            $liveBalance = (float)$midtransData['balance'];
            
            // Jalankan rekonsiliasi
            $this->balanceService->reconcileWithMidtrans($liveBalance);

            log_admin_activity('update', 'admin_balance', 'Melakukan sinkronisasi saldo dengan live Midtrans (Saldo: Rp ' . number_format($liveBalance, 0, ',', '.') . ')');

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Sinkronisasi saldo berhasil! Saldo lokal disesuaikan dengan Midtrans.',
                'newBalance' => $liveBalance
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal menyinkronkan saldo: ' . $e->getMessage()
            ]);
        }
    }
}

