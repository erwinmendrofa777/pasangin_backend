<?php
// FILE: backend/app/Controllers/Api/AuthController.php
// ====================================================================
// === VERSI FINAL - MENGGUNAKAN PATH ABSOLUT PALING AKURAT        ===
// === Berdasarkan Analisis Ulang Semua Log & Screenshot Anda       ===
// ====================================================================

namespace App\Controllers\Api;

// ====================================================================
// MEMAKSA PHP MEMUAT SEMUA FILE DARI LOKASI YANG TEPAT
// Path ini menggunakan:
// 1. Path Absolut Penuh: /home/stuh8812/...
// 2. Nama Folder yang Benar: php-jwt (dengan tanda hubung)
// 3. Lokasi File yang Benar: di dalam folder /src/
// ====================================================================
require_once '/home/stuh8812/backend/app/ThirdParty/php-jwt/src/JWTExceptionWithPayloadInterface.php';
require_once '/home/stuh8812/backend/app/ThirdParty/php-jwt/src/BeforeValidException.php';
require_once '/home/stuh8812/backend/app/ThirdParty/php-jwt/src/ExpiredException.php';
require_once '/home/stuh8812/backend/app/ThirdParty/php-jwt/src/SignatureInvalidException.php';
require_once '/home/stuh8812/backend/app/ThirdParty/php-jwt/src/JWT.php';
require_once '/home/stuh8812/backend/app/ThirdParty/php-jwt/src/JWK.php';
require_once '/home/stuh8812/backend/app/ThirdParty/php-jwt/src/Key.php';
// ====================================================================

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    // ===================================================================
    // === FUNGSI LOGIN ===
    // ===================================================================
    public function login()
    {
        $rules = [
            'phone'    => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            // Bersihkan buffer dan kirim error validasi
            ob_clean();
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $model = new UserModel();
        $user = $model->where('phone_number', $this->request->getVar('phone'))->first();

        if (!$user) {
            // Bersihkan buffer dan kirim error Not Found
            ob_clean();
            return $this->failNotFound('Nomor telepon tidak terdaftar.');
        }

        if (!password_verify($this->request->getVar('password'), $user['password'])) {
            // Bersihkan buffer dan kirim error Unauthorized
            ob_clean();
            return $this->failUnauthorized('Password salah.');
        }

        // HARDCODE KUNCI JWT (Metode Paling Pasti)
        $key = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma';

        $payload = [
            'iss'  => 'https://backend.pasangin.co.id', // Issuer
            'aud'  => 'https://pasangin.co.id',        // Audience
            'iat'  => time(),                          // Waktu token dibuat
            'nbf'  => time(),                          // Token berlaku mulai dari
            'data' => [
                'user_id' => $user['id']
            ]
        ];

        // Panggil dengan NAMESPACE LENGKAP untuk menghindari ambiguitas
        $jwt = \Firebase\JWT\JWT::encode($payload, $key, 'HS256');

        // Hapus data sensitif dari respons
        unset($user['password']);

        // Tambahkan token ke dalam data user yang akan dikirim
        $user['token'] = $jwt;

        // Bersihkan buffer sebelum mengirim respons sukses
        ob_clean();
        return $this->respond([
            'status'  => true,
            'message' => 'Login berhasil.',
            'data'    => $user
        ]);
    }

    // ===================================================================
    // === FUNGSI REGISTER (Pastikan tidak ada yang diubah) ===
    // ===================================================================
    public function register()
    {
        $rules = [
            'name'         => 'required',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'phone_number' => 'required|is_unique[users.phone_number]',
        ];

        if (!$this->validate($rules)) {
            ob_clean();
            return $this->fail($this->validator->getErrors());
        }

        $model = new UserModel();
        $data = [
            'name'         => $this->request->getVar('name'),
            'email'        => $this->request->getVar('email'),
            'password'     => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'phone_number' => $this->request->getVar('phone_number'),
            'role'         => 'client', // Role default
        ];

        try {
            $userId = $model->insert($data);
            $user = $model->find($userId);
            unset($user['password']);

            ob_clean();
            return $this->respondCreated([
                'status'  => true,
                'message' => 'Registrasi berhasil.',
                'data'    => $user
            ]);
        } catch (\Exception $e) {
            ob_clean();
            return $this->failServerError($e->getMessage());
        }
    }

    // =========================================================================
    // === FUNGSI UPDATE FCM TOKEN (Sudah diperbaiki dengan hardcode key) ===
    // =========================================================================
    public function update_fcm_token()
    {
        // HARDCODE KUNCI JWT (Metode Paling Pasti)
        $key = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma';
        
        $header = $this->request->getHeaderLine('Authorization');
        $token = null;

        // Ekstrak token dari header "Bearer {token}"
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        if (is_null($token)) {
            ob_clean();
            return $this->failUnauthorized('Akses ditolak. Token tidak ditemukan.');
        }

        try {
            // Panggil dengan NAMESPACE LENGKAP untuk menghindari ambiguitas
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
            $userId = $decoded->data->user_id;

            $fcmToken = $this->request->getVar('fcm_token');
            if (empty($fcmToken)) {
                ob_clean();
                return $this->failValidationErrors(['fcm_token' => 'FCM Token tidak boleh kosong.']);
            }

            $model = new UserModel();
            $model->update($userId, ['fcm_token' => $fcmToken]);

            ob_clean();
            return $this->respondUpdated([
                'status'  => true,
                'message' => 'FCM Token untuk user ID ' . $userId . ' berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            // Tangani error jika token tidak valid atau kadaluarsa
            ob_clean();
            return $this->failUnauthorized('Akses ditolak. Token tidak valid atau kadaluarsa: ' . $e->getMessage());
        }
    }
}
