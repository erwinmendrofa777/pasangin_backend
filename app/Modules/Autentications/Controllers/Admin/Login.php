<?php

namespace App\Modules\Autentications\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Admin\Services\AdminLoginService;
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

        return view('App\Modules\Autentications\Views\login');
    }

    /**
     * Memproses data dari form login.
     */
    public function loginProcess()
    {
        $isAjax = $this->request->isAJAX();

        if (!$this->validateData($this->request->getPost(), 'adminLogin')) {
            $errors = implode('<br>', $this->validator->getErrors());
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $errors
                ]);
            }
            session()->setFlashdata('error', $errors);
            return redirect()->back()->withInput();
        }

        $email = (string) $this->request->getVar('email');
        $password = (string) $this->request->getVar('password');
        $fcmToken = $this->request->getVar('fcm_token');

        try {
            $sessionData = $this->svc->attemptLogin($email, $password);
            
            // Generate JWT dan set HttpOnly Cookie
            $token = \App\Libraries\AdminTokenHandler::generate($sessionData);
            \App\Libraries\AdminTokenHandler::setCookie($token);

            session()->set($sessionData);
            
            // LOG AKTIVITAS LOGIN
            log_admin_activity('login', 'Auth', 'Admin berhasil login ke panel');

            // SIMPAN TOKEN KE DATABASE
            if (!empty($fcmToken)) {
                $fcmModel = new \App\Modules\Notifications\Models\FcmTokenModel();

                $existing = $fcmModel->where([
                    'user_id' => $sessionData['user_id'],
                    'user_type' => 'admin',
                    'fcm_token' => $fcmToken
                ])->first();

                if (!$existing) {
                    $fcmModel->insert([
                        'user_id' => $sessionData['user_id'],
                        'user_type' => 'admin',
                        'fcm_token' => $fcmToken,
                    ]);
                }
            }

            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'redirect' => site_url('admin/dashboard')
                ]);
            }
            return redirect()->to('/admin/dashboard')->withCookies();
        } catch (RuntimeException $e) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to('/admin/login');
        }
    }

    /**
     * Menghapus session dan keluar.
     */
    public function logout()
    {
        // LOG AKTIVITAS LOGOUT (Dilakukan sebelum session dihancurkan agar helper bisa baca ID Admin)
        log_admin_activity('logout', 'Auth', 'Admin keluar dari sistem');
        
        // Hapus Cookie JWT
        \App\Libraries\AdminTokenHandler::deleteCookie();
        
        session()->destroy();
        
        // Hapus Cookie Session (ci_session) dari browser
        helper('cookie');
        delete_cookie('ci_session');
        
        return redirect()->to('/admin/login')->withCookies();
    }
}
