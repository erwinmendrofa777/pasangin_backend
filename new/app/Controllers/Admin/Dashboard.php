<?php
// FILE: backend/app/Controllers/Admin/Dashboard.php
// VERSI FINAL - SESUAI DENGAN TEMPLATE STISLA

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    /**
     * Metode ini akan menampilkan halaman utama dashboard admin
     * dengan memanggil file view yang sesuai.
     */
    public function index()
    {
        // Tugas Controller sekarang sangat sederhana:
        // Cukup panggil file view untuk dashboard.
        // File view 'admin/dashboard' akan mengurus sisanya,
        // termasuk memanggil header dan footer.
        return view('admin/dashboard');
    }
}
