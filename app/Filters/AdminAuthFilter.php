<?php
// FILE: backend/app/Filters/AdminAuthFilter.php
// VERSI KEMENANGAN FINAL - MEMPERBAIKI REDIRECT YANG SALAH

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Ambil JWT token dari cookie
        helper('cookie');
        $token = get_cookie('admin_jwt');

        // 2. Jika token kosong atau tidak valid, hancurkan session dan redirect ke login
        if (empty($token) || !($decoded = \App\Libraries\AdminTokenHandler::verify($token))) {
            // Hapus session jika ada sisa data
            session()->destroy();
            // Bersihkan cookie
            \App\Libraries\AdminTokenHandler::deleteCookie();
            
            // Hapus Cookie Session (ci_session) dari browser
            helper('cookie');
            delete_cookie('ci_session');
            
            return redirect()->to('/admin/login')->withCookies();
        }

        // 3. Jika valid, sinkronisasi data ke PHP Session untuk kompatibilitas view/controller
        $sessionData = [
            'user_id'     => $decoded['user_id'],
            'full_name'   => $decoded['full_name'],
            'email'       => $decoded['email'],
            'role'        => $decoded['role'],
            'photo'       => $decoded['photo'],
            'permissions' => $decoded['permissions'],
            'isLoggedIn'  => true
        ];
        session()->set($sessionData);

        // --- Cek Hak Akses Role Dinamis ---
        $uri = service('uri');
        // Pastikan kita di rute admin/xxx
        if ($uri->getTotalSegments() >= 2 && $uri->getSegment(1) === 'admin') {
            $module = $uri->getSegment(2);
            
            // Jika segment 2 adalah 'api', gunakan segment 3 sebagai nama modul
            if ($module === 'api' && $uri->getTotalSegments() >= 3) {
                $module = $uri->getSegment(3);
            }
            
            // Bypass modul umum yang bisa diakses semua admin yang login
            $bypassModules = ['dashboard', 'logout', 'profile', 'notification'];
            if (in_array($module, $bypassModules)) {
                return;
            }

            $userPermissions = session()->get('permissions') ?? [];
            $isSuperAdmin = in_array('super_admin_override', $userPermissions);

            // Cek apakah module ada dalam array permissions
            if (!$isSuperAdmin && !in_array($module, $userPermissions)) {
                return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak: Anda tidak memiliki izin untuk fitur ini.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan saja
    }
}