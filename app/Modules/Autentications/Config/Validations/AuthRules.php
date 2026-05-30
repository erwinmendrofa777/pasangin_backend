<?php

namespace App\Modules\Autentications\Config\Validations;

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
    /**
     * Aturan validasi untuk login pengguna
     */
    public array $authLogin = [
        'phone'    => 'required|numeric',
        'password' => 'required',
    ];

    public array $authLogin_errors = [
        'phone' => [
            'required'   => 'Nomor telepon wajib diisi.',
            'numeric'    => 'Nomor telepon hanya boleh berisi angka.'
        ],
        'password' => [
            'required' => 'Password wajib diisi.'
        ]
    ];

    /**
     * Aturan validasi untuk registrasi pengguna
     */
    public array $authRegister = [
        'name'         => 'required|min_length[3]|max_length[100]',
        'email'        => 'required|valid_email|is_unique[users.email]',
        'phone_number' => 'required|numeric|min_length[10]|max_length[15]|is_unique[users.phone_number]',
        'password'     => 'required|min_length[8]|max_length[255]',
    ];

    public array $authRegister_errors = [
        'name' => [
            'required'   => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap terlalu pendek (minimal 3 karakter).',
            'max_length' => 'Nama lengkap terlalu panjang (maksimal 100 karakter).',
        ],
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique'   => 'Email ini sudah terdaftar, silakan gunakan email lain.',
        ],
        'phone_number' => [
            'required'   => 'Nomor telepon wajib diisi.',
            'numeric'    => 'Nomor telepon harus berupa angka.',
            'min_length' => 'Nomor telepon terlalu pendek (minimal 10 karakter).',
            'max_length' => 'Nomor telepon terlalu panjang (maksimal 15 karakter).',
            'is_unique'  => 'Nomor telepon ini sudah terdaftar, silakan gunakan nomor lain.',
        ],
        'password' => [
            'required'   => 'Password wajib diisi.',
            'min_length' => 'Password terlalu pendek (minimal 8 karakter).',
            'max_length' => 'Password terlalu panjang (maksimal 255 karakter).',
        ],
    ];

    /**
     * Aturan validasi untuk request OTP (Registrasi / Lupa Password)
     */
    public array $authRequestOtp = [
        'nomor_telepon' => 'required|numeric|min_length[4]|max_length[16]', 
        'role'          => 'required|in_list[users,tukang,suppliers,user,supplier]',
    ];

    public array $authRequestOtp_errors = [
        'nomor_telepon' => [
            'required'   => 'Nomor HP wajib diisi.',
            'numeric'    => 'Nomor HP hanya boleh berisi angka.',
            'min_length' => 'Nomor HP minimal 4 digit.',
            'max_length' => 'Nomor HP maksimal 16 digit.'
        ],
        'role' => [
            'required' => 'Role wajib diisi.',
            'in_list'  => 'Role tidak valid.'
        ]
    ];

    /**
     * Aturan validasi untuk verifikasi OTP
     */
    public array $authVerifyOtp = [
        'nomor_telepon' => 'required|numeric|min_length[4]|max_length[16]', 
        'role'          => 'required|in_list[users,tukang,suppliers,user,supplier]',
        'otp'           => 'required|numeric',
    ];

    public array $authVerifyOtp_errors = [
        'otp' => [
            'required' => 'Kode OTP wajib diisi.',
            'numeric'  => 'Kode OTP hanya boleh berisi angka.'
        ],
        'nomor_telepon' => [
            'required'   => 'Nomor HP wajib diisi.',
            'numeric'    => 'Nomor HP hanya boleh berisi angka.',
            'min_length' => 'Nomor HP minimal 4 digit.',
            'max_length' => 'Nomor HP maksimal 16 digit.'
        ],
        'role' => [
            'required' => 'Role wajib diisi.',
            'in_list'  => 'Role tidak valid.'
        ]
    ];

    /**
     * Aturan validasi untuk verifikasi email
     */
    public array $authVerifyEmail = [
        'email' => 'required|valid_email',
        'role'  => 'required|in_list[users,tukang,suppliers,user,supplier]'
    ];

    public array $authVerifyEmail_errors = [
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
        ],
        'role' => [
            'required' => 'Role wajib diisi.',
            'in_list'  => 'Role tidak valid.'
        ]
    ];
}
