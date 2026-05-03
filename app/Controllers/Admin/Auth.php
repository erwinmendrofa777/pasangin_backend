<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AdminAuthService;
use RuntimeException;

class Auth extends BaseController
{
    protected AdminAuthService $svc;

    public function __construct()
    {
        $this->svc = new AdminAuthService();
    }

    /**
     * Menampilkan halaman form input email
     */
    public function forgotPasswordForm()
    {
        return view('auth/forgot_password');
    }

    /**
     * Proses kirim OTP
     */
    public function forgotPassword()
    {
        // Validasi menggunakan grup 'authForgot'
        if (!$this->validateData($this->request->getPost(), 'authForgot')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $email = $this->request->getPost('email');

        try {
            $this->svc->sendResetOtp($email);
            // Simpan email ke session agar halaman verifikasi tahu siapa yang sedang mereset
            session()->set('reset_email', $email);
            return redirect()->to('/verify-code')->with('success', 'Kode verifikasi telah dikirim ke email Anda.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Form verifikasi kode
     */
    public function verifyCodeForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->to('/forgot-password')->with('error', 'Silakan masukkan email terlebih dahulu.');
        }
        return view('auth/verify_code');
    }

    /**
     * Proses cek kode
     */
    public function processVerifyCode()
    {
        // Validasi menggunakan grup 'authVerify'
        if (!$this->validateData($this->request->getPost(), 'authVerify')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $inputCode = $this->request->getPost('otp_code');
        $email     = session()->get('reset_email');

        try {
            $this->svc->verifyOtp($email, $inputCode);
            // Kode benar! Beri tanda di session bahwa user boleh ganti password
            session()->set('is_code_verified', true);
            return redirect()->to('/reset-password')->with('success', 'Kode benar! Silakan buat password baru.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Form reset password baru
     */
    public function resetPasswordForm()
    {
        if (!session()->get('is_code_verified')) {
            return redirect()->to('/forgot-password')->with('error', 'Akses ditolak.');
        }
        return view('auth/reset_password');
    }

    /**
     * Update password ke database
     */
    public function updatePassword()
    {
        // Validasi menggunakan grup 'authReset'
        if (!$this->validateData($this->request->getPost(), 'authReset')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $newPassword = $this->request->getPost('new_password');
        $email       = session()->get('reset_email');

        try {
            $this->svc->updatePassword($email, $newPassword);

            // Bersihkan session
            session()->remove(['reset_email', 'is_code_verified']);

            return redirect()->to('/login')->with('success', 'Password berhasil diubah. Silakan login.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}