<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    /**
     * Menampilkan halaman login.
     * Logika: Jika sudah ada session, langsung ke dashboard.
     * Jika belum, tampilkan view login.
     */
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }

        // Pastikan file view ada di: app/Views/admin/login.php
        return view('admin/login');
    }

    /**
     * Memproses data dari form login.
     */
    public function loginProcess()
    {
        $session = session();
        $model = new UserModel();

        $emailInput    = $this->request->getVar('email');
        $passwordInput = $this->request->getVar('password');

        // Cari user yang emailnya cocok dan memiliki role admin/superadmin
        $user = $model->where('email', $emailInput)
                      ->whereIn('role', ['admin', 'superadmin']) 
                      ->first();

        if ($user && password_verify($passwordInput, $user['password'])) {
            $ses_data = [
                'user_id'    => $user['id'],
                'full_name'  => $user['full_name'] ?? $user['name'] ?? 'Admin',
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => TRUE
            ];
            $session->set($ses_data);

            return redirect()->to('/admin/dashboard');
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        $session->setFlashdata('error', 'Email atau Password salah.');
        return redirect()->to('/admin/login');
    }

    /**
     * Menghapus session dan keluar.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
}