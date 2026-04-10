<?php
// FILE: backend/app/Controllers/Admin/Auth.php
// PERBAIKAN: Menggunakan nama view yang benar dan logika yang lebih aman.

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    /**
     * Menampilkan halaman login atau melempar ke dashboard jika sudah login.
     */
        public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }
    

    /**
     * Memproses data dari form login.
     */
    public function loginProcess()
    {
        $session = session();
        $model = new UserModel();

        // Ambil input dari form.
        $emailInput    = $this->request->getVar('email');
        $passwordInput = $this->request->getVar('password');

        // Cari user di database yang role-nya 'admin' atau 'superadmin' dan email-nya cocok.
        $user = $model->where('email', $emailInput)
                      ->whereIn('role', ['admin', 'superadmin']) 
                      ->first();

        // Cek apakah user ditemukan dan password cocok.
        if ($user && password_verify($passwordInput, $user['password'])) {
            // === PASSWORD BENAR! ===
            // Buat data untuk session.
            $ses_data = [
                'user_id'    => $user['id'],
                'full_name'  => $user['full_name'] ?? $user['name'] ?? 'Admin', // Lebih aman jika nama kolom beda
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => TRUE
            ];
            $session->set($ses_data);

            // Redirect ke dashboard menggunakan URL Akar.
            return redirect()->to('/admin/dashboard');
        }

        // === JIKA GAGAL (USER TIDAK DITEMUKAN ATAU PASSWORD SALAH) ===
        $session->setFlashdata('error', 'Email atau Password yang Anda masukkan salah.');
        
        // Redirect kembali ke login menggunakan URL Akar.
        return redirect()->to('/admin/login');
    }

    /**
     * Menghapus session dan mengalihkan ke halaman login.
     */
    public function logout()
    {
        session()->destroy();

        // Redirect ke login menggunakan URL Akar.
        return redirect()->to('/admin/login');
    }
}
