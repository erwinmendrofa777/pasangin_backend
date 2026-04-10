<?php

namespace App\Filters; // <-- PASTIKAN INI BENAR

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SupplierAuth implements FilterInterface // <-- PASTIKAN NAMA CLASS BENAR
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika session penanda login supplier TIDAK ADA
        if (!session()->get('is_supplier_logged_in')) {
            // Lempar kembali ke halaman login
            return redirect()->route('supplier.login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak melakukan apa-apa
    }
}
