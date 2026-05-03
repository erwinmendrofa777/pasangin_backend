<?php

namespace Config\Validations;

trait WalletRules
{
    /**
     * Aturan validasi untuk penyesuaian saldo manual oleh admin
     */
    public array $walletUpdateBalance = [
        'tukang_id'   => 'required|numeric',
        'amount'      => 'required|numeric|greater_than[0]',
        'type'        => 'required|in_list[income,withdraw]',
        'description' => 'required|min_length[3]|max_length[255]',
    ];

    public array $walletUpdateBalance_errors = [
        'amount' => [
            'greater_than' => 'Jumlah saldo harus lebih dari 0.',
        ],
        'type' => [
            'in_list' => 'Tipe transaksi harus income (tambah) atau withdraw (kurangi).',
        ],
    ];

    /**
     * Aturan validasi untuk update status penarikan dana
     */
    public array $walletUpdateWithdrawStatus = [
        'status' => 'required|in_list[approved,rejected,pending]',
    ];

    public array $walletUpdateWithdrawStatus_errors = [
        'status' => [
            'in_list' => 'Status penarikan tidak valid (approved, rejected, atau pending).',
        ],
    ];
}
