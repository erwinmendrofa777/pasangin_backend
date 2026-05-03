<?php

namespace App\Services;

use RuntimeException;

class AdminAuthService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Proses pengiriman kode OTP ke email.
     */
    public function sendResetOtp(string $email): void
    {
        // 1. Cek apakah email ada di tabel user_admin
        $user = $this->db->table('user_admin')->where('email', $email)->get()->getRow();
        if (!$user) {
            throw new RuntimeException('Email tidak ditemukan.');
        }

        // 2. Buat Kode Unik 4 Digit
        $otpCode = sprintf("%04d", mt_rand(1, 9999));

        // 3. Hapus kode lama & simpan yang baru
        $this->db->table('password_reset_tokens')->where('email', $email)->delete();
        $this->db->table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $otpCode
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
        $cekKode = $this->db->table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $inputCode)
            ->get()->getRow();

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

        // Update di DB
        $this->db->table('user_admin')->where('email', $email)->update([
            'password' => $hashedPassword
        ]);

        // Hapus OTP agar tidak bisa dipakai lagi
        $this->db->table('password_reset_tokens')->where('email', $email)->delete();
    }
}
