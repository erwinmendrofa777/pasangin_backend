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
        // Jika BELUM login, lempar ke halaman login
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/admin/login');
        }

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