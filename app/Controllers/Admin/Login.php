<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AdminLoginService;
use RuntimeException;

class Login extends BaseController
{
    protected AdminLoginService $svc;

    public function __construct()
    {
        $this->svc = new AdminLoginService();
    }

    /**
     * Menampilkan halaman login.
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
        if (!$this->validateData($this->request->getPost(), 'adminLogin')) {
            $errors = implode('<br>', $this->validator->getErrors());
            session()->setFlashdata('error', $errors);
            return redirect()->back()->withInput();
        }

        $email    = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        try {
            $sessionData = $this->svc->attemptLogin((string)$email, (string)$password);
            session()->set($sessionData);

            return redirect()->to('/admin/dashboard');
        } catch (RuntimeException $e) {
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to('/admin/login');
        }
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
