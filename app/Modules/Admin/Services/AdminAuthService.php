<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Repositories\UserAdminRepository;
use App\Modules\Autentications\Repositories\PasswordResetTokenRepository;
use App\Modules\Admin\Repositories\Contracts\UserAdminRepositoryInterface;
use App\Modules\Autentications\Repositories\Contracts\PasswordResetTokenRepositoryInterface;
use RuntimeException;

class AdminAuthService
{
    protected UserAdminRepositoryInterface $userAdminRepository;
    protected PasswordResetTokenRepositoryInterface $tokenRepository;

    public function __construct()
    {
        $this->userAdminRepository = new UserAdminRepository();
        $this->tokenRepository = new PasswordResetTokenRepository();
    }

    /**
     * Proses pengiriman kode OTP ke email.
     */
    public function sendResetOtp(string $email): void
    {
        // 1. Cek apakah email ada di tabel user_admin menggunakan Repository
        $user = $this->userAdminRepository->findByEmail($email);
        if (!$user) {
            throw new RuntimeException('Email tidak ditemukan.');
        }

        // 2. Buat Kode Unik 4 Digit
        $otpCode = sprintf("%04d", mt_rand(1, 9999));

        // 3. Hapus kode lama & simpan yang baru menggunakan Repository
        $this->tokenRepository->deleteByEmail($email);
        $this->tokenRepository->insert([
            'email' => $email,
            'token' => $otpCode,
            'role'  => 'admin'
        ]);

        // 4. Kirim Email
        $emailService = \Config\Services::email();
        $emailService->setFrom('erwinmendrofa777@gmail.com', 'pasangin');
        $emailService->setTo($email);
        $emailService->setSubject('Kode Verifikasi Reset Password');

        $pesan = "<h2>Kode Verifikasi Anda</h2>";
        $pesan .= "<p>Gunakan kode 4 digit berikut untuk mereset password Anda:</p>";
        $pesan .= "<h1 style='letter-spacing: 5px; color: #007bff;'>{$otpCode}</h1>";
        $pesan .= "<p>Jangan berikan kode ini kepada siapapun.</p>";

        $emailService->setMessage($pesan);

        if (!$emailService->send()) {
            throw new RuntimeException('Gagal mengirim email verifikasi.');
        }
    }

    /**
     * Verifikasi apakah kode OTP cocok.
     */
    public function verifyOtp(string $email, string $inputCode): bool
    {
        $cekKode = $this->tokenRepository->findByEmailAndToken($email, $inputCode);

        if (!$cekKode) {
            throw new RuntimeException('Kode verifikasi salah.');
        }

        return true;
    }

    /**
     * Update password baru dan bersihkan token.
     */
    public function updatePassword(string $email, string $newPassword): void
    {
        // Hash password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update di DB menggunakan Repository
        $this->userAdminRepository->updatePasswordByEmail($email, $hashedPassword);

        // Hapus OTP menggunakan Repository agar tidak bisa dipakai lagi
        $this->tokenRepository->deleteByEmail($email);
    }
}
