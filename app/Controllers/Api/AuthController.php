<?php

namespace App\Controllers\Api;

use App\Modules\Users\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class AuthController extends ResourceController
{
    protected $format = 'json';

    // --- LOGIN ---
    public function login()
    {
        $rules = [
            'phone' => 'required|numeric',
            'password' => 'required',
        ];

        $messages = [
            'phone' => [
                'required' => 'Nomor telepon wajib diisi.',
                'numeric' => 'Nomor telepon hanya boleh berisi angka.'
            ],
            'password' => [
                'required' => 'Password wajib diisi.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $model = new UserModel();
        $user = $model->where('phone_number', $this->request->getVar('phone'))->first();

        if (!$user) {
            return $this->failNotFound('Nomor telepon tidak terdaftar.');
        }

        if (!password_verify($this->request->getVar('password'), $user['password'])) {
            return $this->failUnauthorized('Password salah.');
        }

        // Cek status akun — hanya akun 'approved' yang boleh login
        if (($user['status'] ?? '') !== 'approved') {
            return $this->respond([
                'status' => false,
                'message' => 'Akun Anda tidak dapat login. Status akun: ' . ($user['status'] ?? 'tidak diketahui') . '.',
                'status_akun' => $user['status'] ?? null,
            ], 403);
        }

        // Payload data user
        $payload = [
            'iss' => 'https://backend.pasangin.co.id',
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // Berlaku 7 hari
            'uid' => $user['id']
        ];

        // MENGGUNAKAN FUNGSI NATIVE (Tanpa Library)
        $jwt = $this->_generateJWT($payload);

        unset($user['password']);
        $user['token'] = $jwt;

        // --- SIMPAN FCM TOKEN JIKA DIKIRIM SAAT LOGIN ---
        $fcmToken = $this->request->getVar('fcm_token');
        if (!empty($fcmToken)) {
            $tokenRepo = new \App\Modules\Notifications\Repositories\FcmTokenRepository();
            $tokenRepo->upsertToken($user['id'], 'client', $fcmToken);
        }

        if (!empty($user['nik'])) {
            try {
                $encrypter = \Config\Services::encrypter();
                $ciphertext = base64_decode($user['nik']);
                $nik_asli = $encrypter->decrypt($ciphertext);
                $user['nik'] = $nik_asli;
            } catch (\CodeIgniter\Encryption\Exceptions\EncryptionException $e) {
                // Jika dekripsi gagal (kunci berubah, data rusak) set ke null atau handling lain
                $user['nik'] = null;
            } catch (\Exception $e) {
                $user['nik'] = null;
            }
        } else {
            $user['nik'] = null;
        }

        return $this->respond([
            'status' => true,
            'message' => 'Login berhasil.',
            'data' => $user
        ]);
    }

    // --- UPDATE FCM TOKEN ---
    public function updateFcmToken()
    {
        try {
            $userId = $this->request->user->uid;
            $json = $this->request->getJSON();
            $fcmToken = $json->fcm_token ?? null;

            if (empty($fcmToken)) {
                return $this->fail('FCM Token kosong.', 400);
            }

            // Simpan ke tabel baru (multi-perangkat)
            $tokenRepo = new \App\Modules\Notifications\Repositories\FcmTokenRepository();
            $result = $tokenRepo->upsertToken($userId, 'client', $fcmToken);

            return $this->respond([
                'status' => true,
                'message' => 'FCM token berhasil diperbarui.',
                'data' => $result
            ], 200);

        } catch (Exception $e) {
            return $this->failUnauthorized('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * FUNGSI HELPER: GENERATE JWT NATIVE
     */
    private function _generateJWT($payload)
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode($payload);

        $base64UrlHeader = $this->_base64UrlEncode($header);
        $base64UrlPayload = $this->_base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, getenv('JWT_SECRET'), true);
        $base64UrlSignature = $this->_base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }



    private function _base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    // --------------------------
    // MINTA OTP UNTUK REGISTRASI
    // --------------------------
    public function requestOtp()
    {
        // validasi
        $rules = [
            'nomor_telepon' => 'required|numeric|min_length[4]|max_length[16]',
            'role' => 'required|in_list[users,tukang,suppliers]',
        ];

        $messages = [
            'nomor_telepon' => [
                'required' => 'Nomor HP wajib diisi.',
                'numeric' => 'Nomor HP hanya boleh berisi angka.',
                'min_length' => 'Nomor HP minimal 4 digit.',
                'max_length' => 'Nomor HP maksimal 16 digit.'
            ],
            'role' => [
                'required' => 'Role wajib diisi.',
                'in_list' => 'Role tidak valid. Gunakan: users, tukang, atau suppliers.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Ambil data dari request
        $phoneInput = $this->request->getVar('nomor_telepon');
        $role = $this->request->getVar('role');
        $challenge = $this->request->getVar('challenge');

        // Normalisasi nomor telepon ke format dasar (tanpa 0 atau 62 di depan)
        $phoneBase = $phoneInput;
        if (substr($phoneBase, 0, 2) === '62') {
            $phoneBase = substr($phoneBase, 2);
        } elseif (substr($phoneBase, 0, 1) === '0') {
            $phoneBase = substr($phoneBase, 1);
        }

        // Buat variasi nomor untuk dicocokkan di database dan untuk dikirim ke API
        $phoneWith0 = '0' . $phoneBase;
        $phoneWith62 = '62' . $phoneBase;

        // Keamanan: Whitelist tabel dan cek apakah nomor HP tersebut terdaftar
        $db = \Config\Database::connect();

        // Tentukan nama kolom nomor telepon berdasarkan role
        $phoneColumn = ($role === 'users') ? 'phone_number' : 'phone';

        // Cari user di database dengan format 08... atau 628...
        $user = $db->table($role)
            ->groupStart()
            ->where($phoneColumn, $phoneWith0)
            ->orWhere($phoneColumn, $phoneWith62)
            ->groupEnd()
            ->get()->getRow();

        // bedakan login dan registrasi
        if ($user && $challenge === 'registrasi') {
            return $this->fail("Nomor HP ini sudah terdaftar. Silakan login atau gunakan nomor lain.", 409);
        } elseif (!$user && $challenge === 'login') {
            return $this->fail("Nomor HP tidak terdaftar. Silakan registrasi terlebih dahulu.", 404);
        }

        // LANGSUNG MINTA VERIHUBS MENGIRIM OTP
        $client = \Config\Services::curlrequest();

        $payload = [
            'msisdn' => $phoneWith62,
            'template' => 'Kode OTP Aplikasi Pasangin Anda adalah $OTP. Jangan berikan kode ini kepada siapapun.',
            'time_limit' => '300', // Berlaku 5 menit
            'challenge' => $challenge,
        ];

        try {
            $response = $client->post('https://api.verihubs.com/v2/otp/send', [
                'headers' => [
                    'API-Key' => getenv('VERIHUBS_API_KEY'),
                    'App-ID' => getenv('VERIHUBS_APP_ID'),
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 30,
                'http_errors' => false
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody());

            // 4. Cek respons dari Verihubs
            if ($statusCode >= 200 && $statusCode < 300) {
                return $this->respond([
                    'status' => true,
                    'message' => 'OTP berhasil dikirim ke nomor WhatsApp/SMS Anda.'
                ], 200);
            }

            // Jika Verihubs gagal (contoh: saldo habis, format nomor salah)
            return $this->fail('Gagal mengirim OTP: ' . ($body->message ?? 'Pastikan format nomor benar (628...).'), $statusCode);

        } catch (\Exception $e) {
            log_message('error', '[Verihubs OTP Send] Connection Error: ' . $e->getMessage());
            return $this->failServerError('Gagal terhubung ke layanan pengirim OTP.');
        }
    }

    // --------------------------
    // VERIFIKASI OTP UNTUK REGISTRASI
    // --------------------------
    public function verifyOtp()
    {
        //validasi
        $rules = [
            'nomor_telepon' => 'required|numeric|min_length[4]|max_length[16]',
            'role' => 'required|in_list[users,tukang,suppliers]',
            'otp' => 'required|exact_length[6]|numeric',
        ];

        $messages = [
            'otp' => [
                'required' => 'Kode OTP wajib diisi.',
                'exact_length' => 'Kode OTP harus 6 digit.',
                'numeric' => 'Kode OTP hanya boleh berisi angka.'
            ],
            'nomor_telepon' => [
                'required' => 'Nomor HP wajib diisi.',
                'numeric' => 'Nomor HP hanya boleh berisi angka.',
                'min_length' => 'Nomor HP minimal 4 digit.',
                'max_length' => 'Nomor HP maksimal 16 digit.'
            ],
            'role' => [
                'required' => 'Role wajib diisi.',
                'in_list' => 'Role tidak valid. Gunakan: users, tukang, atau suppliers.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // ambil data dari request
        $phoneInput = $this->request->getVar('nomor_telepon');
        $otp = $this->request->getVar('otp');
        $role = $this->request->getVar('role');
        $challenge = $this->request->getVar('challenge');

        // Normalisasi nomor telepon ke format dasar
        $phoneBase = $phoneInput;
        if (substr($phoneBase, 0, 2) === '62') {
            $phoneBase = substr($phoneBase, 2);
        } elseif (substr($phoneBase, 0, 1) === '0') {
            $phoneBase = substr($phoneBase, 1);
        }

        // Buat variasi nomor untuk dicocokkan di database dan untuk dikirim ke API
        $phoneWith62 = '62' . $phoneBase;

        $client = \Config\Services::curlrequest();

        $payload = [
            'msisdn' => $phoneWith62,
            'otp' => (string) $otp,
            'challenge' => $challenge
        ];

        try {
            $response = $client->post('https://api.verihubs.com/v2/otp/verify', [
                'headers' => [
                    'API-Key' => getenv('VERIHUBS_API_KEY'),
                    'App-ID' => getenv('VERIHUBS_APP_ID'),
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 30,
                'http_errors' => false // Penting agar aplikasi tidak crash jika OTP salah
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody());

            // Evaluasi Balasan Verihubs
            if ($statusCode === 200) {
                return $this->respond([
                    'status' => 200,
                    'message' => 'Kode OTP valid.',
                    'data' => [
                        'nomor_telepon' => $phoneInput,
                        'role' => $role
                    ]
                ], 200);
            }

            // Jika OTP Salah (400) atau Kadaluarsa/Tidak Ditemukan (404/422)
            $errorMessage = $body->message ?? 'Kode OTP salah atau sudah kadaluarsa.';
            return $this->failUnauthorized($errorMessage);

        } catch (\Exception $e) {
            log_message('error', '[Verihubs Verify] Error: ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan pada server saat memverifikasi OTP.');
        }
    }

    public function verifyEmail()
    {
        $rules = [
            'email' => 'required|valid_email|',
            'role' => 'required|in_list[users,tukang,suppliers]'
        ];

        $messages = [
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'role' => [
                'required' => 'Role wajib diisi.',
                'in_list' => 'Role tidak valid. Gunakan: users, tukang, atau suppliers.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // ambil data dari request
        $email = $this->request->getVar('email');
        $role = $this->request->getVar('role');

        // validasi
        if ($role == 'users') {
            $model = new UserModel();
            $exists = $model->where('email', $email)->first();
        } elseif ($role == 'tukang') {
            $db = \Config\Database::connect();
            $exists = $db->table('tukang')->where('email', $email)->get()->getRow();
        } elseif ($role == 'suppliers') {
            $db = \Config\Database::connect();
            $exists = $db->table('suppliers')->where('email', $email)->get()->getRow();
        }

        if ($exists) {
            return $this->fail("Email ini sudah terdaftar sebagai {$role}. Silakan gunakan email lain.", 409);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Email tersedia untuk digunakan.'
        ], 200);
    }

    // --------------------------
    // --- REGISTER --- 
    // --------------------------
    public function register()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone_number' => 'required|numeric|min_length[10]|max_length[15]|is_unique[users.phone_number]',
            'password' => 'required|min_length[8]|max_length[255]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap terlalu pendek (minimal 3 karakter).',
                'max_length' => 'Nama lengkap terlalu panjang (maksimal 100 karakter).',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            ],
            'phone_number' => [
                'required' => 'Nomor telepon wajib diisi.',
                'numeric' => 'Nomor telepon harus berupa angka.',
                'min_length' => 'Nomor telepon terlalu pendek (minimal 10 karakter).',
                'max_length' => 'Nomor telepon terlalu panjang (maksimal 15 karakter).',
                'is_unique' => 'Nomor telepon ini sudah terdaftar, silakan gunakan nomor lain.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password terlalu pendek (minimal 8 karakter).',
                'max_length' => 'Password terlalu panjang (maksimal 255 karakter).',
            ],
        ];

        $data = $this->request->getJSON(true);
        if (!$this->validate($rules, $messages)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $userModel = new UserModel();
        try {
            $userModel->save([
                'full_name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => 'client',
            ]);
            return $this->respondCreated(['status' => 'success', 'message' => 'Registrasi berhasil.']);
        } catch (Exception $e) {
            return $this->failServerError('Gagal registrasi.');
        }
    }
}