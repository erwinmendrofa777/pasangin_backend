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
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan saja
    }
}