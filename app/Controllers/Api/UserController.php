<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Modules\Users\Models\UserModel;

class UserController extends BaseController
{
    use ResponseTrait;

    public function update()
    {
        // 1. Ambil ID User dari Token JWT (UID didapat dari payload token)
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token tidak ditemukan.');
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(getenv('JWT_SECRET'), 'HS256'));
        $userId = $decoded->uid;

        $model = new UserModel();
        $user = $model->find($userId);

        if (!$user) {
            return $this->failNotFound('User tidak ditemukan.');
        }

        // 2. Siapkan data yang akan diupdate (Jika field tidak dikirim, gunakan data lama)
        // PERBAIKAN: Tambahkan 'fcm_token' agar notifikasi   bisa jalan!
        $updateData = [
            'full_name' => $this->request->getPost('full_name') ?? $user['full_name'],
            'email' => $this->request->getPost('email') ?? $user['email'],
            'phone_number' => $this->request->getPost('phone_number') ?? $user['phone_number'],
            'address' => $this->request->getPost('address') ?? $user['address'],
        ];

        // Handle FCM Token secara terpisah (multi-perangkat)
        $fcmToken = $this->request->getPost('fcm_token');
        if (!empty($fcmToken)) {
            $tokenRepo = new \App\Modules\Notifications\Repositories\FcmTokenRepository();
            $tokenRepo->upsertToken($userId, 'client', $fcmToken);
        }
        // LOGIKA ENKRIPSI NIK
        $nik = $this->request->getPost('nik');
        if (!empty($nik)) {
            $encrypter = \Config\Services::encrypter();
            $nik_rahasia = $encrypter->encrypt($nik);
            $updateData['nik'] = base64_encode($nik_rahasia);
        }

        // 3. LOGIKA UBAH KATA SANDI (Jika Flutter mengirim password baru)
        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');

        if ($newPassword) {
            // Pastikan password lama diisi
            if (!$oldPassword) {
                return $this->fail('Kata sandi lama wajib diisi untuk keamanan.');
            }
            // Verifikasi apakah kata sandi lama cocok dengan di database
            if (!password_verify($oldPassword, $user['password'])) {
                return $this->fail('Kata sandi lama Anda salah.');
            }
            // Jika cocok, hash password baru dan masukkan ke antrian update
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // 4. LOGIKA UPLOAD FOTO (Kunci: 'photo' dari Flutter)
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();

            // Pastikan folder public/uploads/profile/ sudah ada di server  
            if ($file->move(FCPATH . 'uploads/profile', $newName)) {
                // Simpan URL lengkap ke database di kolom 'avatar'
                $updateData['avatar'] = base_url('uploads/profile/' . $newName);
            }
        }

        // 5. EKSEKUSI UPDATE KE DATABASE
        if ($model->update($userId, $updateData)) {
            $updatedUser = $model->find($userId);

            // Sembunyikan password sebelum dikirim balik ke aplikasi demi keamanan
            unset($updatedUser['password']);

            return $this->respond([
                'status' => true,
                'message' => 'Data berhasil diperbarui secara permanen.',
                'data' => $updatedUser
            ], 200);
        }

        return $this->fail('Gagal memperbarui data ke database.');
    }


    // ==========================================================
    // ENDPOINT 1: MINTA OTP UNTUK HAPUS AKUN
    // POST api/user/request-otp
    // ==========================================================
    public function requestOtp()
    {
        $email = $this->request->getVar('email');

        $model = new UserModel();
        $user = $model->where('email', $email)->first();
        if (!$user) {
            return $this->failNotFound('Akun tidak ditemukan.');
        }

        $email = $user['email'];
        // 3. Buat OTP 6 digit
        $otpCode = sprintf("%06d", mt_rand(0, 999999));

        // 4. Simpan OTP ke tabel password_reset_tokens
        $db = \Config\Database::connect();
        // Hapus token lama jika ada
        $db->table('password_reset_tokens')
            ->where('email', $email)
            ->where('role', 'user')
            ->delete();
        // Simpan token baru
        $db->table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $otpCode,
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // 4. Kirim email menggunakan konfigurasi SMTP dari .env
        $emailService = \Config\Services::email();
        $emailService->setFrom(getenv('email.SMTPUser'), 'Pasangin');
        $emailService->setTo($email);
        $emailService->setSubject('Konfirmasi Penghapusan Akun');

        $namaUser = $user['full_name'] ?? 'Pengguna';
        $pesan = "<div style='font-family:Arial,sans-serif;max-width:480px;margin:auto;border:1px solid #eee;border-radius:8px;padding:32px;'>";
        $pesan .= "<h2 style='color:#e53e3e;'>⚠️ Konfirmasi Hapus Akun</h2>";
        $pesan .= "<p>Halo, <strong>{$namaUser}</strong>.</p>";
        $pesan .= "<p>Kami menerima permintaan untuk menghapus akun Anda secara permanen dari aplikasi <strong>Pasangin</strong>.</p>";
        $pesan .= "<p>Gunakan kode OTP berikut untuk mengkonfirmasi penghapusan akun:</p>";
        $pesan .= "<div style='text-align:center;margin:24px 0;'>";
        $pesan .= "<span style='font-size:36px;font-weight:bold;letter-spacing:10px;color:#e53e3e;'>{$otpCode}</span>";
        $pesan .= "</div>";
        $pesan .= "<p style='color:#718096;font-size:13px;'>Kode ini berlaku selama <strong>10 menit</strong>.</p>";
        $pesan .= "<p style='color:#718096;font-size:13px;'>Jika Anda tidak meminta ini, abaikan email ini. Akun Anda <strong>tidak akan</strong> dihapus.</p>";
        $pesan .= "<hr style='border:none;border-top:1px solid #eee;margin:24px 0;'>";
        $pesan .= "<p style='color:#a0aec0;font-size:12px;'>Tim Pasangin</p>";
        $pesan .= "</div>";

        $emailService->setMessage($pesan);

        if (!$emailService->send()) {
            log_message('error', '[DeleteAccount] Gagal mengirim email OTP: ' . $emailService->printDebugger(['headers']));
            return $this->failServerError('Gagal mengirim email OTP. Silakan coba lagi.');
        }

        return $this->respond([
            'status' => true,
            'message' => 'Kode OTP telah dikirim ke email ' . $email . '. Berlaku selama 10 menit.',
        ], 200);
    }

    // ==========================================================
    // ENDPOINT 2: KONFIRMASI OTP
    // POST api/user/delete-account/confirm
    // ==========================================================
    public function verifyOtp()
    {
        $email = $this->request->getVar('email');

        $model = new UserModel();
        $user = $model->where('email', $email)->first();
        if (!$user) {
            return $this->failNotFound('Akun tidak ditemukan.');
        }

        $email = $user['email'];
        $otpInput = $this->request->getVar('otp');

        // 3. Cek OTP di database
        $db = \Config\Database::connect();
        $cekOtp = $db->table('password_reset_tokens')
            ->where('email', $email)
            ->where('role', 'user')
            ->where('token', $otpInput)
            ->get()->getRow();

        if (!$cekOtp) {
            return $this->fail(
                'Kode OTP tidak valid atau salah.',
            );
        }

        // 4. Cek kadaluarsa OTP (10 menit = 600 detik)
        $waktuDibuat = strtotime($cekOtp->created_at);
        $waktuSekarang = time();
        if (($waktuSekarang - $waktuDibuat) > 600) {
            $db->table('password_reset_tokens')
                ->where('email', $email)
                ->where('role', 'user')
                ->delete();
            return $this->fail('Kode OTP sudah kadaluarsa. Silakan minta kode baru.');
        }

        // 5. Hapus OTP dari database
        $db->table('password_reset_tokens')
            ->where('email', $email)
            ->where('role', 'user')
            ->delete();

        return $this->respond([
            'status' => true,
            'message' => 'Kode OTP berhasil terkonfirmasi.',
        ], 200);
    }

    // ==========================================================
    // ENDPOINT 3: KONFIRMASI MENONAKTIFKAN AKUN
    // KONFIRMASI MENONAKTIFKAN AKUN
    // ==========================================================
    public function confirmInactivateAccount()
    {
        // 1. Ambil user dari token JWT
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token tidak ditemukan.');
        }
        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(getenv('JWT_SECRET'), 'HS256'));
        $userId = $decoded->uid;

        // 2. Koneksi database
        $db = \Config\Database::connect();

        // 3. Update status user menjadi nonaktif
        $db->table('users')
            ->where('id', $userId)
            ->update([
                'status' => 'nonaktif',
            ]);

        return $this->respond([
            'status' => true,
            'message' => 'Akun Anda telah berhasil dinonaktifkan.',
        ], 200);
    }

    // ==========================================================
    // ENDPOINT 3: KONFIRMASI AKTIFKAN AKUN
    // POST api/user/recovery-account/confirm
    // ==========================================================
    public function confirmActivateAccount()
    {
        $email = $this->request->getVar('email');

        $model = new UserModel();
        $user = $model->where('email', $email)->first();
        if (!$user) {
            return $this->failNotFound('Akun tidak ditemukan.');
        }

        $email = $user['email'];

        // 2. Koneksi database
        $db = \Config\Database::connect();

        // 3. Update status user menjadi nonaktif
        $db->table('users')
            ->where('email', $email)
            ->update([
                'status' => 'approved',
            ]);

        return $this->respond([
            'status' => true,
            'message' => 'Akun Anda telah berhasil diaktifkan.',
        ], 200);
    }

    // ==========================================================
    // ENDPOINT 5: HAPUS AKUN
    // POST api/user/delete-account/execute
    // ==========================================================
    public function confirmDeleteAccount()
    {
        // 1. Ambil user dari token JWT
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token tidak ditemukan.');
        }
        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(getenv('JWT_SECRET'), 'HS256'));
        $userId = $decoded->uid;

        $model = new UserModel();
        $user = $model->find($userId);
        if (!$user) {
            return $this->failNotFound('Akun tidak ditemukan.');
        }

        $email = $user['email'];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Chat & Messages
            $conversationIds = $db->table('conversations')
                ->select('id')
                ->where('client_id', $userId)
                ->where('client_type', 'client')
                ->get()->getResultArray();
            $convIds = array_column($conversationIds, 'id');

            if (!empty($convIds)) {
                $db->table('messages')->whereIn('conversation_id', $convIds)->delete();
            }
            $db->table('conversations')
                ->where('client_id', $userId)
                ->where('client_type', 'client')
                ->delete();

            // 2. Data Personal & Akses
            $db->table('alamat_user')->where('id_user', $userId)->delete();
            $db->table('notifications')->where('target_id', $userId)->where('target_type', 'client')->delete();
            $db->table('user_fcm_tokens')->where('user_id', $userId)->where('user_type', 'client')->delete();
            $db->table('password_reset_tokens')->where('email', $email)->delete();

            // 3. Proyek Konstruksi
            $constProjects = $db->table('construction_requests')->select('id')->where('user_id', $userId)->get()->getResultArray();
            $constIds = array_column($constProjects, 'id');
            if (!empty($constIds)) {
                $db->table('construction_surveys')->whereIn('construction_id', $constIds)->delete();
                $db->table('construction_designs')->whereIn('construction_id', $constIds)->delete();
                $db->table('construction_progress')->whereIn('construction_id', $constIds)->delete();
                $db->table('construction_invoices')->whereIn('construction_id', $constIds)->delete();
                $db->table('construction_attendance')->whereIn('id_construction', $constIds)->delete();
                $db->table('construction_agreements')->whereIn('construction_id', $constIds)->delete();
                $db->table('construction_targets')->whereIn('construction_id', $constIds)->delete();
                $db->table('construction_addendum')->whereIn('construction_id', $constIds)->delete();

                $rabIds = array_column($db->table('construction_rabs')->select('id')->whereIn('construction_id', $constIds)->get()->getResultArray(), 'id');
                if (!empty($rabIds)) {
                    $db->table('construction_rab_materials')->whereIn('rab_id', $rabIds)->delete();
                }
                $db->table('construction_rabs')->whereIn('construction_id', $constIds)->delete();
                $db->table('job_applications')->where('project_type', 'construction')->whereIn('project_id', $constIds)->delete();
                $db->table('construction_requests')->whereIn('id', $constIds)->delete();
            }

            // 4. Proyek Renovasi
            $renoProjects = $db->table('renovation_requests')->select('id')->where('user_id', $userId)->get()->getResultArray();
            $renoIds = array_column($renoProjects, 'id');
            if (!empty($renoIds)) {
                $db->table('renovation_surveys')->whereIn('request_id', $renoIds)->delete();
                $db->table('renovation_designs')->whereIn('request_id', $renoIds)->delete();
                $db->table('renovation_progress')->whereIn('renovation_id', $renoIds)->delete();
                $db->table('renovation_invoices')->whereIn('renovation_id', $renoIds)->delete();
                $db->table('renovation_attendance')->whereIn('id_renovation', $renoIds)->delete();
                $db->table('renovation_agreements')->whereIn('renovation_id', $renoIds)->delete();
                $db->table('renovation_targets')->whereIn('renovation_id', $renoIds)->delete();

                $renoRabIds = array_column($db->table('renovation_rabs')->select('id')->whereIn('renovation_id', $renoIds)->get()->getResultArray(), 'id');
                if (!empty($renoRabIds)) {
                    $db->table('renovation_rab_materials')->whereIn('rab_id', $renoRabIds)->delete();
                }
                $db->table('renovation_rabs')->whereIn('renovation_id', $renoIds)->delete();
                $db->table('job_applications')->where('project_type', 'renovation')->whereIn('project_id', $renoIds)->delete();
                $db->table('renovation_requests')->whereIn('id', $renoIds)->delete();
            }

            // 5. Proyek Desain
            $designProjects = $db->table('design_requests')->select('id')->where('user_id', $userId)->get()->getResultArray();
            $designIds = array_column($designProjects, 'id');
            if (!empty($designIds)) {
                $db->table('project_surveys')->whereIn('design_request_id', $designIds)->delete();
                $db->table('project_designs')->whereIn('design_request_id', $designIds)->delete();
                $db->table('project_invoices')->whereIn('design_request_id', $designIds)->delete();
                $db->table('design_targets')->whereIn('design_request_id', $designIds)->delete();
                $db->table('design_requests')->whereIn('id', $designIds)->delete();
            }

            // 6. Orders
            $orders = $db->table('orders')->select('id')->where('user_id', $userId)->get()->getResultArray();
            $orderIds = array_column($orders, 'id');
            if (!empty($orderIds)) {
                $db->table('order_items')->whereIn('order_id', $orderIds)->delete();
                $db->table('orders')->whereIn('id', $orderIds)->delete();
            }

            // 7. Akun Utama
            $db->table('users')->where('id', $userId)->delete();

            $db->transCommit();
            return $this->respond([
                'status' => true,
                'message' => 'Akun Anda dan semua data terkait telah berhasil dihapus secara permanen.',
            ], 200);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[DeleteAccount] Error cascading delete user #' . $userId . ': ' . $e->getMessage());
            return $this->failServerError('Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }
}
