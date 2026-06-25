<?php

namespace App\Controllers\Api;

use App\Modules\Tukang\Models\TukangModel;
use App\Modules\Notifications\Models\FcmTokenModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class TukangAuthController extends ResourceController
{
    protected $format = 'json';

    /**
     * 1. LOGIN TUKANG
     * POST: api/tukang/login
     */
    public function login()
    {
        $rules = [
            'phone' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $model = new TukangModel();
        $fcmModel = new FcmTokenModel();
        $phone = $this->request->getVar('phone');

        $user = $model->where('phone', $phone)->first();

        if (!$user) {
            return $this->failNotFound('Nomor telepon Tukang tidak terdaftar  .');
        }

        // Query FCM setelah $user dipastikan ada
        $fcm = $fcmModel->where('user_id', $user['id'])->where('user_type', 'tukang')->first();

        if (!password_verify($this->request->getVar('password'), $user['password'])) {
            return $this->failUnauthorized('Password yang   masukkan salah.');
        }

        // Payload JWT  
        $payload = [
            'iss' => 'https://backend.pasangin.co.id',
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // Berlaku 7 hari
            'uid' => $user['id'],
            'role' => $user['role'] ?? 'tukang'
        ];

        $jwt = $this->_generateJWT($payload);

        unset($user['password']);
        $user['token'] = $jwt;

        // --- SIMPAN FCM TOKEN JIKA DIKIRIM SAAT LOGIN ---
        $fcmToken = $this->request->getVar('fcm_token');
        if (!empty($fcmToken)) {
            $tokenRepo = new \App\Modules\Notifications\Repositories\FcmTokenRepository();
            $tokenRepo->upsertToken($user['id'], 'tukang', $fcmToken);
        }

        if (!empty($user['profile_photo'])) {
            $user['profile_photo'] = base_url('uploads/tukang/' . $user['profile_photo']);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Login Tukang berhasil.',
            'data' => $user,
            'is_notification_enabled' => $fcm['is_notification_enabled'] ?? true
        ]);
    }

    /**
     * 2. REGISTER TUKANG
     * POST: api/tukang/register
     */
    public function register()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[tukang.email]',
            'phone' => 'required|numeric|min_length[10]|max_length[15]|is_unique[tukang.phone]',
            'password' => 'required|min_length[8]|max_length[255]',
            'role' => 'permit_empty|in_list[mandor,tukang]',
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
            'phone' => [
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
            'role' => [
                'in_list' => 'Peran (role) harus berupa mandor atau tukang.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $model = new TukangModel();
        try {
            $db = \Config\Database::connect();
            $db->transStart();

            $model->save([
                'agent_code' => $data['agent_code'] ?? null,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? $data['phone_number'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'gender' => $data['gender'] ?? null,
                'dob' => $data['dob'] ?? null,
                'ktp_address' => $data['ktp_address'] ?? null,
                'domicile_address' => $data['domicile_address'] ?? null,
                'status' => 'Berkas Diproses',
                'created_at' => date('Y-m-d H:i:s'),
                'rating_avg' => '0.0',
                'skill_score' => '0.0',
                'behavior_score' => '0.0',
                'registration_step' => 1,
                'role' => $data['role'] ?? 'tukang'
            ]);

            $tukangId = $model->getInsertID();
            if ($tukangId) {
                $skillsInput = $data['skills'] ?? $data['specialization'] ?? null;
                $skillMapModel = new \App\Modules\Tukang\Models\TukangSkillMapModel();
                $skillMapModel->syncSkills($tukangId, $skillsInput);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal menyimpan data registrasi mitra tukang.');
            }

            // Kirim notifikasi ke Admin (Permission: tukang_verify)
            $notifService = new \App\Modules\Notifications\Services\NotificationService();
            $notifService->sendToPermission(
                'tukang_create',
                'Pendaftaran Tukang Baru',
                "Tukang baru bernama " . $data['name'] . " telah mendaftar. Silakan cek dan verifikasi berkasnya."
            );

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Pendaftaran berhasil  . Silakan login.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * GET: api/tukang/skills
     * Mendapatkan daftar keahlian (skills) master dari tabel tukang_skill
     */
    public function getSkills()
    {
        try {
            $model = new \App\Modules\Tukang\Models\TukangSkillModel();
            $skills = $model->orderBy('skill_name', 'ASC')->findAll();

            return $this->respond([
                'status' => true,
                'message' => 'Daftar keahlian berhasil diambil.',
                'data' => $skills
            ], 200);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 3. UPDATE FCM TOKEN (VERSI AMAN)
     * POST: api/tukang/update-fcm
     */
    public function updateFcmToken()
    {
        try {
            $tukangId = $this->request->user->uid;

            if (!in_array($this->request->user->role, ['tukang', 'mandor'])) {
                return $this->failUnauthorized('Akses ditolak.');
            }

            $json = $this->request->getJSON();
            $fcmToken = $json->fcm_token ?? null;

            if (empty($fcmToken))
                return $this->fail('FCM Token kosong.', 400);

            // Simpan ke tabel baru (multi-perangkat)
            $tokenRepo = new \App\Modules\Notifications\Repositories\FcmTokenRepository();
            $result = $tokenRepo->upsertToken($tukangId, 'tukang', $fcmToken);

            return $this->respond([
                'status' => true,
                'message' => 'Token FCM Tukang berhasil diperbarui.',
                'data' => $result
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 4. UPDATE PROFILE TUKANG
     * POST: api/tukang/update-profile
     */
    public function updateProfile()
    {
        try {
            $tukangId = $this->request->user->uid;

            $json = $this->request->getJSON(true);

            $dataUpdate = [
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->request->getPost('name') !== null) {
                $dataUpdate['name'] = $this->request->getPost('name');
            } elseif (isset($json['name'])) {
                $dataUpdate['name'] = $json['name'];
            }

            if ($this->request->getPost('phone') !== null) {
                $dataUpdate['phone'] = $this->request->getPost('phone');
            } elseif (isset($json['phone'])) {
                $dataUpdate['phone'] = $json['phone'];
            }

            if ($this->request->getPost('email') !== null) {
                $dataUpdate['email'] = $this->request->getPost('email');
            } elseif (isset($json['email'])) {
                $dataUpdate['email'] = $json['email'];
            }

            if (!empty($this->request->getPost('password'))) {
                $dataUpdate['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            } elseif (!empty($json['password'])) {
                $dataUpdate['password'] = password_hash($json['password'], PASSWORD_DEFAULT);
            }

            $file = $this->request->getFile('profile_photo');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/tukang/', $newName);
                $dataUpdate['profile_photo'] = $newName;
            }

            // Tentukan apakah parameter keahlian dikirim dalam request
            $hasSkillsKey = false;
            $skillsInput = null;

            if ($this->request->getPost('skills') !== null) {
                $skillsInput = $this->request->getPost('skills');
                $hasSkillsKey = true;
            } elseif ($this->request->getPost('specialization') !== null) {
                $skillsInput = $this->request->getPost('specialization');
                $hasSkillsKey = true;
            }

            if (!$hasSkillsKey && is_array($json)) {
                if (isset($json['skills'])) {
                    $skillsInput = $json['skills'];
                    $hasSkillsKey = true;
                } elseif (isset($json['specialization'])) {
                    $skillsInput = $json['specialization'];
                    $hasSkillsKey = true;
                }
            }

            $model = new TukangModel();
            $db = \Config\Database::connect();
            $db->transStart();

            $model->update($tukangId, $dataUpdate);

            if ($hasSkillsKey) {
                $skillMapModel = new \App\Modules\Tukang\Models\TukangSkillMapModel();
                $skillMapModel->syncSkills($tukangId, $skillsInput);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new Exception('Gagal memperbarui profil mitra.');
            }

            return $this->respond([
                'status' => true,
                'message' => 'Profil berhasil diperbarui  !'
            ]);

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function updateProfileByKtp()
    {
        try {
            $tukangId = $this->request->user->uid;

            $jalan = $this->request->getPost('jalan');
            $rt_rw = $this->request->getPost('rt_rw');
            $kelurahan = $this->request->getPost('kelurahan');
            $kecamatan = $this->request->getPost('kecamatan');
            $kabupaten = $this->request->getPost('kabupaten');
            $provinsi = $this->request->getPost('provinsi');
            $alamat_lengkap = $jalan . ', ' . $rt_rw . ', ' . $kelurahan . ', ' . $kecamatan . ', ' . $kabupaten . ', ' . $provinsi;

            $dataUpdate = [
                'nik' => $this->request->getPost('nik'),
                'name' => $this->request->getPost('nama'),
                'dob' => $this->request->getPost('tanggal_lahir'),
                'ktp_address' => $alamat_lengkap,
                'is_verify' => 1
            ];

            $file = $this->request->getFile('selfie_photo');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/tukang/selfie/', $newName);
                $dataUpdate['selfie_photo'] = $newName;
            }

            $model = new TukangModel();
            $model->update($tukangId, $dataUpdate);

            return $this->respond([
                'status' => true,
                'message' => 'Profil berhasil diperbarui'
            ]);

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * 5. GET PROFILE DATA
     * GET: api/tukang/profile/{id}
     */
    public function getProfile($id = null)
    {
        $model = new TukangModel();
        $user = $model->find($id);
        if (!$user)
            return $this->failNotFound('Tukang tidak ditemukan  .');

        unset($user['password']);
        if (!empty($user['profile_photo'])) {
            $user['profile_photo'] = base_url('uploads/tukang/' . $user['profile_photo']);
        }

        // Ambil data keahlian dari tabel junction
        $skillMapModel = new \App\Modules\Tukang\Models\TukangSkillMapModel();
        $user['skills'] = $skillMapModel->getSkillsByTukangId($user['id']);

        return $this->respond(['status' => true, 'data' => $user]);
    }

    public function extractSync()
    {
        // 1. Ambil file KTP dan Selfie dari request frontend
        $fileKtp = $this->request->getFile('ktp_image');
        $fileFace = $this->request->getFile('face_image');

        if (!$fileKtp || !$fileKtp->isValid() || !$fileFace || !$fileFace->isValid()) {
            return $this->failValidationErrors('Gambar KTP dan Foto Selfie wajib diunggah.');
        }

        // 2. Konversi kedua gambar menjadi Base64
        $base64Ktp = base64_encode(file_get_contents($fileKtp->getTempName()));
        $base64Face = base64_encode(file_get_contents($fileFace->getTempName()));

        $client = \Config\Services::curlrequest([
            'timeout' => 60
        ]);
        $apiHeaders = [
            'App-ID' => getenv('VERIHUBS_APP_ID'),
            'API-Key' => getenv('VERIHUBS_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        try {
            // ==============================================================
            // TAHAP 1: COMPARE (BANDINGKAN WAJAH KTP VS SELFIE)
            // ==============================================================
            $resCompare = $client->post('https://api.verihubs.com/v1/face/compare', [
                'headers' => $apiHeaders,
                'json' => [
                    'image_1' => $base64Ktp,
                    'image_2' => $base64Face,
                    'is_quality' => true,
                    'is_attribute' => true,
                    'is_liveness' => false,
                    'validate_quality' => false,
                    'validate_attribute' => false,
                    'validate_liveness' => false,
                    'validate_nface' => true
                ],
                'http_errors' => false
            ]);

            $statusCompare = $resCompare->getStatusCode();
            $resultCompare = json_decode($resCompare->getBody(), true);

            // Cek jika HTTP bukan 200 ATAU JSON error (gagal terhubung ke API)
            if ($statusCompare !== 200) {
                return $this->fail('Compare Format Error: gagal mencocokkan foto ktp dengan foto selfie', 400);
            }

            // PERBAIKAN: Ambil langsung dari root JSON sesuai contoh Anda
            $isMatch = $resultCompare['similarity_status'] ?? false;

            if (!$isMatch) {
                return $this->fail('Verifikasi Gagal: Wajah tidak cocok dengan foto di KTP.', 400);
            }

            // ==============================================================
            // TAHAP 2: JIKA WAJAH COCOK, EKSTRAK DATA KTP
            // ==============================================================
            // (Verihubs Extract KTP biasanya pake v2, pastikan sesuai dokumentasi ya)
            $resKtp = $client->post('https://api.verihubs.com/v2/ktp/extract', [
                'headers' => $apiHeaders,
                'json' => ['image' => $base64Ktp],
                'http_errors' => false
            ]);

            $statusKtp = $resKtp->getStatusCode();
            $resultKtp = json_decode($resKtp->getBody(), true);

            // Cek gagal ekstrak KTP (Untuk extract KTP, biasanya ada bungkus 'data')
            if ($statusKtp !== 200 || !isset($resultKtp['data'])) {
                return $this->fail('KTP Format Error: gagal mengambil data ktp', 400);
            }


            // ==============================================================
            // TAHAP 3: SEMUA BERHASIL, KIRIM NOTIFIKASI KE ADMIN & KEMBALIKAN DATA
            // ==============================================================

            // Ambil nama dari hasil ekstrak KTP (jika ada)
            $namaUser = $resultKtp['data']['nama'] ?? 'Seorang Tukang/supplier';

            $notifService = new \App\Modules\Notifications\Services\NotificationService();
            $notifService->sendToPermission(
                'tukang_verify',
                'Verifikasi Biometrik Berhasil',
                "Tukang atau Supplier atas nama {$namaUser} telah berhasil melewati verifikasi wajah dan KTP."
            );

            return $this->respond([
                'status' => 'success',
                'message' => 'Wajah cocok dan KTP berhasil diekstrak.',
                'data' => [
                    // Kita sertakan status_code dan similarity_status dari respons asli
                    'compare_status' => $resultCompare['status_code'] ?? 'N/A',
                    'is_match' => $isMatch,
                    'ktp_data' => $resultKtp['data']
                ]
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError('Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // --- INTERNAL JWT HELPERS ---

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
}