<?php

namespace App\Modules\Wallets\Config\Validations;

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
        'tukang_id' => [
            'required' => 'ID Tukang wajib diisi.',
            'numeric'  => 'ID Tukang harus berupa angka.',
        ],
        'amount' => [
            'required'     => 'Jumlah saldo wajib diisi.',
            'numeric'      => 'Jumlah saldo harus berupa angka.',
            'greater_than' => 'Jumlah saldo harus lebih dari 0.',
        ],
        'type' => [
            'required' => 'Tipe transaksi wajib dipilih.',
            'in_list'  => 'Tipe transaksi harus income (tambah) atau withdraw (kurangi).',
        ],
        'description' => [
            'required'   => 'Deskripsi transaksi wajib diisi.',
            'min_length' => 'Deskripsi minimal 3 karakter.',
            'max_length' => 'Deskripsi maksimal 255 karakter.',
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
            'required' => 'Status penarikan wajib dipilih.',
            'in_list'  => 'Status penarikan tidak valid (approved, rejected, atau pending).',
        ],
    ];
}
