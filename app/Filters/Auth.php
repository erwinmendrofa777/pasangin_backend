<?php
// FILE: backend_core/app/Filters/Auth.php (PERBAIKAN FINAL TANPA TERMINAL)

namespace App\Filters;

// --- PERBAIKAN: Tambahkan kembali require_once untuk memuat library secara manual ---
require_once APPPATH . '../app/ThirdParty/php-jwt/src/JWT.php';
require_once APPPATH . '../app/ThirdParty/php-jwt/src/Key.php';
// Kita tidak perlu memuat semua file exception, JWT.php akan menanganinya.

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception; // Import class Exception global

// Kita tidak perlu `use Firebase\JWT\JWT` atau `Key` lagi karena sudah dimuat manual.

class Auth implements FilterInterface
{
    /**
     * This is called before the controller is executed.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $key        = getenv('JWT_SECRET'); // Ambil dari .env
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader)) {
            // Jika header otorisasi tidak ada, langsung tolak akses
            return Services::response()
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Akses ditolak. Token otorisasi tidak ditemukan.'
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        try {
            // Ambil token dari format "Bearer {token}"
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            } else {
                // Jika format bukan "Bearer {token}", tolak akses
                return Services::response()
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Format token tidak valid.'
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }

            // --- PERBAIKAN: Gunakan backslash (\) di depan nama class ---
            // Ini memberitahu PHP untuk mencari class dari 'namespace global'
            // karena kita tidak menggunakan 'use'.
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));

            // Simpan data user yang sudah di-decode agar bisa digunakan di controller.
            service('request')->user = $decoded;

        } catch (Exception $e) { // Tangkap semua jenis Exception dari JWT atau lainnya
            // Jika token tidak valid, kedaluwarsa, atau ada error lain
            log_message('error', '[AuthFilter] Exception: ' . $e->getMessage());

            return Services::response()
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Akses ditolak. Token tidak valid atau kedaluwarsa.'
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * This is called after the controller has executed.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi yang perlu dilakukan setelah controller selesai
    }
}
