<?php
namespace App\Controllers\Api;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class AuthAPI extends Controller
{
    use ResponseTrait;

    // ==========================================================
    // 1. ENDPOINT REQUEST OTP
    // ==========================================================
    public function requestOtp()
    {
        // 1. Validasi Input (Nomor HP dan Role wajib ada)
        $rules = [
            'msisdn' => 'required|numeric|min_length[9]', 
            'role'   => 'required|in_list[user,tukang,supplier]'
        ];

        $messages = [
            'msisdn' => [
                'required'   => 'Nomor HP wajib diisi.',
                'numeric'    => 'Nomor HP hanya boleh berisi angka.',
                'min_length' => 'Nomor HP tidak valid.'
            ],
            'role' => [
                'required' => 'Role wajib diisi.',
                'in_list'  => 'Role tidak valid. Gunakan: user, tukang, atau supplier.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $noHp = $this->request->getVar('msisdn');
        $role = $this->request->getVar('role');

        // 2. Keamanan: Whitelist tabel dan cek apakah nomor HP tersebut terdaftar
        $allowedRoles = [
            'user'     => 'users',
            'tukang'   => 'tukang', 
            'supplier' => 'suppliers'
        ];

        $tableName = $allowedRoles[$role];
        $db = \Config\Database::connect();

        // CATATAN: Pastikan 'phone' adalah nama kolom nomor HP di tabel Anda
        $user = $db->table($tableName)->where('phone', $noHp)->get()->getRow();
        
        if (!$user) {
            return $this->failNotFound("Nomor HP tidak terdaftar sebagai {$role}.");
        }

        // ==========================================
        // 3. LANGSUNG MINTA VERIHUBS MENGIRIM OTP
        // (Tidak ada lagi proses INSERT/DELETE token di database lokal)
        // ==========================================
        $client = \Config\Services::curlrequest();
        
        $payload = [
            'msisdn'       => $noHp,
            'template'     => 'Kode OTP Anda adalah $OTP. Jangan berikan kode ini kepada siapapun.',
            // KITA TIDAK MENGIRIMKAN 'otp' LAGI. Verihubs yang akan membuatkannya otomatis!
            'time_limit'   => '300', // Berlaku 5 menit
            'challenge'    => 'lupa_password', 
            // callback_url opsional, bisa dihapus jika tidak dipakai
        ];

        try {
            $response = $client->post('https://api.verihubs.com/v2/otp/send', [
                'headers' => [
                    'API-Key' => getenv('VERIHUBS_API_KEY'),
                    'App-ID'  => getenv('VERIHUBS_APP_ID'),
                    'Accept'  => 'application/json',
                ],
                'json'        => $payload,
                'timeout'     => 30,
                'http_errors' => false
            ]);

            $statusCode = $response->getStatusCode();
            $body       = json_decode($response->getBody());

            // 4. Cek respons dari Verihubs
            if ($statusCode >= 200 && $statusCode < 300) {
                return $this->respond([
                    'status'  => true,
                    'message' => 'OTP berhasil dikirim ke nomor WhatsApp/SMS Anda.',
                    // Opsional: Anda bisa mengirimkan ID referensi jika Verihubs mengembalikannya
                    // 'data' => ['reference_id' => $body->data->reference_id ?? null] 
                ], 200);
            }

            // Jika Verihubs gagal (contoh: saldo habis, format nomor salah)
            return $this->fail('Gagal mengirim OTP: ' . ($body->message ?? 'Pastikan format nomor benar (628...).'), $statusCode);

        } catch (\Exception $e) {
            log_message('error', '[Verihubs OTP Send] Connection Error: ' . $e->getMessage());
            return $this->failServerError('Gagal terhubung ke layanan pengirim OTP.');
        }
    }


    // ==========================================================
    // 1B. ENDPOINT REQUEST OTP VIA EMAIL
    // ==========================================================
    public function requestOtpByEmail()
    {
        // 1. Validasi Input (Email dan Role wajib ada)
        $rules = [
            'email' => 'required|valid_email',
            'role'  => 'required|in_list[user,tukang,supplier]'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.'
            ],
            'role' => [
                'required' => 'Role wajib diisi.',
                'in_list'  => 'Role tidak valid. Gunakan: user, tukang, atau supplier.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $role  = $this->request->getVar('role');

        // 2. Keamanan: Whitelist tabel dan cek apakah email tersebut terdaftar
        $allowedRoles = [
            'user'     => 'users',
            'tukang'   => 'tukang', 
            'supplier' => 'suppliers'
        ];

        $tableName = $allowedRoles[$role];
        $db = \Config\Database::connect();

        $user = $db->table($tableName)->where('email', $email)->get()->getRow();
        
        if (!$user) {
            return $this->failNotFound("Email tidak terdaftar sebagai {$role}.");
        }

        // 3. Generate 6 digit OTP code
        $otpCode = sprintf("%06d", mt_rand(0, 999999));

        // 4. Simpan OTP ke database password_reset_tokens
        // Hapus token lama untuk email & role ini
        $db->table('password_reset_tokens')
           ->where('email', $email)
           ->where('role', $role)
           ->delete();

        // Simpan token baru
        $db->table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => $otpCode,
            'role'       => $role,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 5. Kirim email menggunakan konfigurasi SMTP dari .env
        $emailService = \Config\Services::email();
        $fromEmail = getenv('email.SMTPUser') ?: 'pasanginapp@gmail.com';
        $emailService->setFrom($fromEmail, 'Pasangin');
        $emailService->setTo($email);
        $emailService->setSubject('Kode OTP Lupa Password');

        $namaUser = $user->name ?? $user->full_name ?? 'Pengguna';
        $pesan = "<div style='font-family:Arial,sans-serif;max-width:480px;margin:auto;border:1px solid #eee;border-radius:8px;padding:32px;'>";
        $pesan .= "<h2 style='color:#007bff;margin-top:0;'>🔑 Kode Verifikasi Lupa Password</h2>";
        $pesan .= "<p>Halo, <strong>{$namaUser}</strong>.</p>";
        $pesan .= "<p>Kami menerima permintaan untuk mereset password akun Anda di aplikasi <strong>Pasangin</strong>.</p>";
        $pesan .= "<p>Gunakan kode OTP 6 digit berikut untuk melanjutkan proses reset password:</p>";
        $pesan .= "<div style='text-align:center;margin:24px 0;'>";
        $pesan .= "<span style='font-size:36px;font-weight:bold;letter-spacing:10px;color:#007bff;'>{$otpCode}</span>";
        $pesan .= "</div>";
        $pesan .= "<p style='color:#718096;font-size:13px;'>Kode ini berlaku selama <strong>15 menit</strong>.</p>";
        $pesan .= "<p style='color:#718096;font-size:13px;'>Jika Anda tidak meminta ini, abaikan email ini. Password Anda tidak akan diubah.</p>";
        $pesan .= "<hr style='border:none;border-top:1px solid #eee;margin:24px 0;'>";
        $pesan .= "<p style='color:#a0aec0;font-size:12px;'>Tim Pasangin</p>";
        $pesan .= "</div>";

        $emailService->setMessage($pesan);

        if (!$emailService->send()) {
            log_message('error', '[Forgot Password Email] Gagal mengirim email OTP: ' . $emailService->printDebugger(['headers']));
            return $this->failServerError('Gagal mengirim email OTP. Silakan coba lagi.');
        }

        return $this->respond([
            'status'  => true,
            'message' => 'Kode OTP berhasil dikirim ke email Anda. Silakan periksa kotak masuk atau spam.',
        ], 200);
    }

    // ==========================================================
    // 1C. ENDPOINT VERIFY OTP VIA EMAIL
    // ==========================================================
    public function verifyOtpByEmail()
    {
        // 1. Validasi Input (Email, OTP 6 digit, dan Role)
        $rules = [
            'email' => 'required|valid_email',
            'otp'   => 'required|exact_length[6]|numeric',
            'role'  => 'required|in_list[user,tukang,supplier]'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.'
            ],
            'otp' => [
                'required'     => 'Kode OTP wajib diisi.',
                'exact_length' => 'Kode OTP harus 6 digit.',
                'numeric'      => 'Kode OTP hanya boleh berisi angka.'
            ],
            'role' => [
                'required' => 'Role wajib diisi.',
                'in_list'  => 'Role tidak valid. Gunakan: user, tukang, atau supplier.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $this->request->getVar('email');
        $otp   = $this->request->getVar('otp');
        $role  = $this->request->getVar('role');

        // 2. Cek OTP di database
        $db = \Config\Database::connect();
        $cekOtp = $db->table('password_reset_tokens')
                     ->where('email', $email)
                     ->where('role', $role)
                     ->where('token', $otp)
                     ->get()->getRow();

        if (!$cekOtp) {
            return $this->failUnauthorized('Kode OTP tidak valid atau salah.');
        }

        // 3. Logika kadaluarsa (15 Menit = 900 detik)
        $waktuDibuat = strtotime($cekOtp->created_at);
        $waktuSekarang = time();

        if (($waktuSekarang - $waktuDibuat) > 900) {
            $db->table('password_reset_tokens')
               ->where('email', $email)
               ->where('role', $role)
               ->delete();
            return $this->failUnauthorized('Kode OTP sudah kadaluarsa. Silakan minta kode baru.');
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Kode OTP valid. Silakan lanjutkan ke pembuatan password baru.',
            'data'    => [
                'email' => $email,
                'role'  => $role
            ]
        ], 200);
    }

    // ==========================================================
    // 2. ENDPOINT VERIFY OTP
    // ==========================================================
    public function verifyOtp()
    {
        // 1. Sesuaikan validasi: Email menjadi msisdn (nomor HP), OTP menjadi 6 digit
        $rules = [
            'msisdn' => 'required|numeric|min_length[9]',
            'otp'    => 'required|exact_length[6]|numeric', // Sesuaikan jika Verihubs Anda diset 4 digit
            'role'   => 'required|in_list[user,tukang,supplier]'
        ];

        $messages = [
            'msisdn' => [
                'required'   => 'Nomor HP wajib diisi.',
                'numeric'    => 'Nomor HP hanya boleh berisi angka.',
                'min_length' => 'Nomor HP tidak valid.'
            ],
            'otp' => [
                'required'     => 'Kode OTP wajib diisi.',
                'exact_length' => 'Kode OTP harus 6 digit.',
                'numeric'      => 'Kode OTP hanya boleh berisi angka.'
            ],
            'role' => [
                'required' => 'Role (tipe akun) wajib diisi.',
                'in_list'  => 'Role tidak valid. Gunakan: user, tukang, atau supplier.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // 2. Tangkap data dari frontend
        $noHp = $this->request->getVar('msisdn');
        $otp  = $this->request->getVar('otp');
        $role = $this->request->getVar('role'); 
        
        // (Opsional) Jika Anda ingin memastikan kembali nomor HP terdaftar di tabel sesuai rolenya
        // bisa lakukan query ke DB seperti di requestOtp. Jika tidak, bisa langsung tembak Verihubs.

        // ==========================================
        // 3. VERIFIKASI LANGSUNG KE VERIHUBS
        // (Logika kadaluarsa dan kecocokan OTP diurus Verihubs)
        // ==========================================
        $client = \Config\Services::curlrequest();

        $payload = [
            'msisdn' => $noHp,
            'otp'    => (string) $otp,
            // Opsional: Jika di requestOtp Anda mengirim 'challenge', tambahkan juga di sini
            // 'challenge' => 'lupa_password' 
        ];

        try {
            $response = $client->post('https://api.verihubs.com/v2/otp/verify', [
                'headers' => [
                    'API-Key' => getenv('VERIHUBS_API_KEY'),
                    'App-ID'  => getenv('VERIHUBS_APP_ID'),
                    'Accept'  => 'application/json',
                ],
                'json'        => $payload,
                'timeout'     => 30,
                'http_errors' => false // Penting agar aplikasi tidak crash jika OTP salah
            ]);

            $statusCode = $response->getStatusCode();
            $body       = json_decode($response->getBody());

            // 4. Evaluasi Balasan Verihubs
            if ($statusCode === 200) {
                // OTP Benar dan Belum Kadaluarsa
                return $this->respond([
                    'status'  => 200,
                    'message' => 'Kode OTP valid. Silakan lanjutkan ke pembuatan password baru.',
                    // Anda bisa melempar 'role' dan 'msisdn' kembali ke frontend untuk proses reset password
                    'data'    => [
                        'msisdn' => $noHp,
                        'role'   => $role
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

    // ==========================================================
    // 3. ENDPOINT RESET PASSWORD
    // ==========================================================
    public function resetPassword()
    {
        $rules = [
            'email'        => 'required|valid_email',
            'otp'          => 'required|min_length[4]|max_length[6]|numeric',
            'role'         => 'required|in_list[user,tukang,supplier]',
            'new_password' => 'required|min_length[6]'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.'
            ],
            'otp' => [
                'required'   => 'Kode OTP wajib diisi.',
                'min_length' => 'Kode OTP minimal 4 digit.',
                'max_length' => 'Kode OTP maksimal 6 digit.',
                'numeric'    => 'Kode OTP hanya boleh berisi angka.'
            ],
            'role' => [
                'required' => 'Role (tipe akun) wajib diisi.',
                'in_list'  => 'Role tidak valid. Gunakan: user, tukang, atau supplier.'
            ],
            'new_password' => [
                'required'   => 'Password baru wajib diisi.',
                'min_length' => 'Password baru minimal 6 karakter.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email       = $this->request->getVar('email');
        $otp         = $this->request->getVar('otp');
        $role        = $this->request->getVar('role'); // Tangkap parameter role
        $newPassword = $this->request->getVar('new_password');

        // 1. Whitelist tabel untuk keamanan SQL Injection
        $allowedRoles = [
            'user'     => 'users',
            'tukang'   => 'tukang', 
            'supplier' => 'suppliers'
        ];

        $tableName = $allowedRoles[$role]; // Menentukan tabel mana yang akan di-update
        $db = \Config\Database::connect();

        // 2. VALIDASI ULANG OTP & ROLE (Sangat penting demi keamanan!)
        $cekOtp = $db->table('password_reset_tokens')
                     ->where('email', $email)
                     ->where('role', $role)
                     ->where('token', $otp)
                     ->get()->getRow();

        if (!$cekOtp) {
            return $this->failUnauthorized('Kode OTP tidak valid atau salah.');
        }

        // 3. LOGIKA KADALUARSA (15 Menit = 900 detik)
        $waktuDibuat = strtotime($cekOtp->created_at);
        $waktuSekarang = time();

        if (($waktuSekarang - $waktuDibuat) > 900) {
            $db->table('password_reset_tokens')
               ->where('email', $email)
               ->where('role', $role)
               ->delete();
            return $this->failUnauthorized('Sesi Anda telah habis karena melewati 15 menit. Silakan ulangi proses lupa password dari awal.');
        }

        // 4. Hash password baru
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // 5. Update password secara DINAMIS ke tabel yang sesuai (users / tukang / suppliers)
        $db->table($tableName)->where('email', $email)->update([
            'password' => $hashedPassword
        ]);
        
        // 6. Hapus OTP dari database agar tidak bisa dipakai ulang
        $db->table('password_reset_tokens')
           ->where('email', $email)
           ->where('role', $role)
           ->delete();

        return $this->respond([
            'status'  => 200,
            'message' => "Password untuk {$role} berhasil diubah. Silakan login dengan password baru."
        ]);
    }

}