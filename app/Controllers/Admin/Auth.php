<?php
namespace App\Controllers\Admin;
use CodeIgniter\Controller;

class Auth extends Controller
{

    // Menampilkan halaman form input email
    public function forgotPasswordForm()
    {
        return view('auth/forgot_password');
    }

    // ujicoba lupa password
    public function forgotPassword()
    {
        $email = $this->request->getPost('email');
        $db = \Config\Database::connect();
        
        // 1. Cek apakah email ada di tabel users
        $user = $db->table('users')->where('email', $email)->get()->getRow();
        
        if ($user) {
            // 1. Buat Kode Unik 4 Digit Angka (Contoh: 4829)
            $otpCode = sprintf("%04d", mt_rand(1, 9999));

            // 2. Hapus kode lama jika user request berulang kali
            $db->table('password_reset_tokens')->where('email', $email)->delete();
            
            // 3. Simpan token ke database
            $db->table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $otpCode
            ]);
            
            // 4. Kirim Email menggunakan library bawaan CI4
            $emailService = \Config\Services::email();
            $emailService->setFrom('erwinmendrofa777@gmail.com', 'pasangin'); // Jangan lupa tambahkan ini
            $emailService->setTo($email);
            $emailService->setSubject('Kode Verifikasi Reset Password');

            // Ubah isi pesan menjadi Kode OTP
            $pesan = "<h2>Kode Verifikasi Anda</h2>";
            $pesan .= "<p>Gunakan kode 4 digit berikut untuk mereset password Anda:</p>";
            $pesan .= "<h1 style='letter-spacing: 5px; color: #007bff;'>{$otpCode}</h1>";
            $pesan .= "<p>Jangan berikan kode ini kepada siapapun.</p>";

            $emailService->setMessage($pesan);
                       
            if ($emailService->send()) {
                // Simpan email ke session agar halaman verifikasi tahu siapa yang sedang mereset
                session()->set('reset_email', $email);
                return redirect()->to('/verify-code')->with('success', 'Kode verifikasi telah dikirim ke email Anda.');
            } else {
                return redirect()->back()->with('error', 'Gagal mengirim email verifikasi.');
            }
        }
        
        return redirect()->back()->with('error', 'Email tidak ditemukan.');
    }

    // --- FUNGSI BARU UNTUK VERIFIKASI KODE ---
    public function verifyCodeForm()
    {
        // Cegah akses langsung jika belum input email
        if (!session()->has('reset_email')) {
            return redirect()->to('/forgot-password')->with('error', 'Silakan masukkan email terlebih dahulu.');
        }
        return view('auth/verify_code');
    }

    public function processVerifyCode()
    {
        $inputCode = $this->request->getPost('otp_code');
        $email = session()->get('reset_email');
        $db = \Config\Database::connect();

        // Cek apakah kode cocok dengan email di database
        $cekKode = $db->table('password_reset_tokens')
                      ->where('email', $email)
                      ->where('token', $inputCode)
                      ->get()->getRow();

        if ($cekKode) {
            // Kode benar! Beri tanda di session bahwa user boleh ganti password
            session()->set('is_code_verified', true);
            return redirect()->to('/reset-password')->with('success', 'Kode benar! Silakan buat password baru.');
        }

        return redirect()->back()->with('error', 'Kode verifikasi salah.');
    }

    // --- FUNGSI UPDATE PASSWORD ---
    public function resetPasswordForm()
    {
        // Pastikan user sudah melewati tahap verifikasi kode
        if (!session()->get('is_code_verified')) {
            return redirect()->to('/forgot-password')->with('error', 'Akses ditolak.');
        }
        return view('auth/reset_password');
    }

    //ujicoba update password
    public function updatePassword()
    {
        $newPassword = $this->request->getPost('new_password');
        $email = session()->get('reset_email'); // Ambil email dari session
        $db = \Config\Database::connect();
        
        // Hash password baru
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update password di tabel users
        $db->table('users')->where('email', $email)->update([
            'password' => $hashedPassword
        ]);
        
        // Hapus OTP dari database agar tidak bisa dipakai lagi
        $db->table('password_reset_tokens')->where('email', $email)->delete();
        
        // Bersihkan session
        session()->remove('reset_email');
        session()->remove('is_code_verified');
        
        return redirect()->to('/login')->with('success', 'Password berhasil diubah. Silakan login.');
    }
}