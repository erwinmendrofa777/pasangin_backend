<?php

namespace Config\Validations;

trait AuthRules
{
    /**
     * Aturan validasi untuk permintaan reset password (forgot password)
     */
    public array $authForgot = [
        'email' => 'required|valid_email',
    ];

    public array $authForgot_errors = [
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
        ],
    ];

    /**
     * Aturan validasi untuk verifikasi kode OTP
     */
    public array $authVerify = [
        'otp_code' => 'required|numeric|exact_length[4]',
    ];

    public array $authVerify_errors = [
        'otp_code' => [
            'required' => 'Kode OTP wajib diisi.',
            'numeric' => 'Kode OTP harus berupa angka.',
            'exact_length' => 'Kode OTP harus berjumlah 4 digit.',
        ],
    ];

    /**
     * Aturan validasi untuk reset password baru
     */
    public array $authReset = [
        'new_password'      => 'required|min_length[6]',
        'confirm_password'  => 'required|matches[new_password]',
    ];

    public array $authReset_errors = [
        'new_password' => [
            'required' => 'Password baru wajib diisi.',
            'min_length' => 'Password minimal 6 karakter.',
        ],
        'confirm_password' => [
            'required' => 'Konfirmasi password wajib diisi.',
            'matches' => 'Konfirmasi password tidak cocok.',
        ],
    ];

    /**
     * Aturan validasi untuk login admin
     */
    public array $adminLogin = [
        'email'    => 'required|valid_email',
        'password' => 'required',
    ];

    public array $adminLogin_errors = [
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
        ],
        'password' => [
            'required' => 'Password wajib diisi.',
        ],
    ];
}
