<?php

namespace App\Controllers\Api;

use App\Models\TukangModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class TukangAuthController extends ResourceController
{
    protected $format = 'json';
    // Kunci JWT yang sama agar sinkron antar modul
    private $jwtKey = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma';

    /**
     * 1. LOGIN TUKANG
     * POST: api/tukang/login
     */
    public function login()
    {
        $rules = [
            'phone'    => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $model = new TukangModel();
        $phone = $this->request->getVar('phone');
        
        $user = $model->where('phone', $phone)->first();

        if (!$user) {
            return $this->failNotFound('Nomor telepon Tukang tidak terdaftar kawan.');
        }

        if (!password_verify($this->request->getVar('password'), $user['password'])) {
            return $this->failUnauthorized('Password yang kawan masukkan salah.');
        }

        // Payload JWT kawan
        $payload = [
            'iss'  => 'https://backend.pasangin.co.id',
            'iat'  => time(),
            'exp'  => time() + (60 * 60 * 24 * 7), // Berlaku 7 hari
            'uid'  => $user['id'],
            'role' => 'tukang' 
        ];

        $jwt = $this->_generateJWT($payload);
        
        unset($user['password']);
        $user['token'] = $jwt;

        if (!empty($user['profile_photo'])) {
            $user['profile_photo'] = base_url('uploads/tukang/' . $user['profile_photo']);
        }

        return $this->respond([
            'status'  => true,
            'message' => 'Login Tukang berhasil.',
            'data'    => $user
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
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[tukang.email]',
            'phone'    => 'required|numeric|min_length[10]|max_length[15]|is_unique[tukang.phone]',
            'password' => 'required|min_length[8]|max_length[255]',
        ];

        $messages = [
            'name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap terlalu pendek (minimal 3 karakter).',
                'max_length' => 'Nama lengkap terlalu panjang (maksimal 100 karakter).',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            ],
            'phone' => [
                'required'   => 'Nomor telepon wajib diisi.',
                'numeric'    => 'Nomor telepon harus berupa angka.',
                'min_length' => 'Nomor telepon terlalu pendek (minimal 10 karakter).',
                'max_length' => 'Nomor telepon terlalu panjang (maksimal 15 karakter).',
                'is_unique'  => 'Nomor telepon ini sudah terdaftar, silakan gunakan nomor lain.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password terlalu pendek (minimal 8 karakter).',
                'max_length' => 'Password terlalu panjang (maksimal 255 karakter).',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond(['status' => 'error', 'message' => $this->validator->getErrors()], 400);
        }

        $model = new TukangModel();
        try {
            $model->save([
                'agent_code'       => $data['agent_code'] ?? null,
                'name'             => $data['name'],
                'email'            => $data['email'],
                'phone'            => $data['phone'] ?? $data['phone_number'],
                'password'         => password_hash($data['password'], PASSWORD_DEFAULT),
                'gender'           => $data['gender'] ?? null,
                'dob'              => $data['dob'] ?? null,
                'ktp_address'      => $data['ktp_address'] ?? null,
                'domicile_address' => $data['domicile_address'] ?? null,
                'specialization'   => $data['specialization'] ?? null,
                'status'           => 'Berkas Diproses', 
                'created_at'       => date('Y-m-d H:i:s'),
                'rating_avg'       => '0.0',
                'skill_score'      => '0.0',
                'behavior_score'   => '0.0',
                'registration_step'=> 1
            ]);

            return $this->respondCreated([
                'status' => 'success', 
                'message' => 'Pendaftaran berhasil kawan. Silakan login.'
            ]);
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
            $authHeader = $this->request->getHeaderLine('Authorization');
            if (empty($authHeader)) return $this->failUnauthorized('Token tidak ditemukan.');

            $token = str_replace('Bearer ', '', $authHeader);
            $decoded = $this->_decodeJWT($token);
            
            if (!$decoded || $decoded['role'] !== 'tukang') {
                return $this->failUnauthorized('Akses ditolak.');
            }

            $json = $this->request->getJSON();
            $fcmToken = $json->fcm_token ?? null;

            if (empty($fcmToken)) return $this->fail('FCM Token kosong.', 400);

            $model = new TukangModel();
            // Ambil UID dari token JWT agar tidak bisa dimanipulasi kawan
            $model->update($decoded['uid'], ['fcm_token' => $fcmToken]);

            return $this->respond([
                'status' => true, 
                'message' => 'Token FCM Tukang berhasil diperbarui.'
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
            $authHeader = $this->request->getHeaderLine('Authorization');
            $token = str_replace('Bearer ', '', $authHeader);
            $decoded = $this->_decodeJWT($token);
            
            if (!$decoded) return $this->failUnauthorized('Akses ditolak kawan.');

            $tukangId = $decoded['uid'];
            
            $dataUpdate = [
                'name'           => $this->request->getPost('name'),
                'phone'          => $this->request->getPost('phone'),
                'email'          => $this->request->getPost('email'),
                'specialization' => $this->request->getPost('specialization'),
                'updated_at'     => date('Y-m-d H:i:s')
            ];

            if (!empty($this->request->getPost('password'))) {
                $dataUpdate['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            $file = $this->request->getFile('profile_photo');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('uploads/tukang/', $newName);
                $dataUpdate['profile_photo'] = $newName;
            }

            $model = new TukangModel();
            $model->update($tukangId, $dataUpdate);

            return $this->respond([
                'status' => true, 
                'message' => 'Profil berhasil diperbarui kawan!'
            ]);

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function updateProfileByKtp()
    {
        try{
            $authHeader = $this->request->getHeaderLine('Authorization');
            $token = str_replace('Bearer ', '', $authHeader);
            $decoded = $this->_decodeJWT($token);
            
            if (!$decoded) return $this->failUnauthorized('Akses ditolak kawan.');

            $tukangId = $decoded['uid'];

            $jalan = $this->request->getPost('jalan');
            $rt_rw = $this->request->getPost('rt_rw');
            $kelurahan = $this->request->getPost('kelurahan');
            $kecamatan = $this->request->getPost('kecamatan');
            $kabupaten = $this->request->getPost('kabupaten');
            $provinsi = $this->request->getPost('provinsi');
            $alamat_lengkap = $jalan . ', ' . $rt_rw . ', ' . $kelurahan . ', ' . $kecamatan . ', ' . $kabupaten . ', ' . $provinsi;

            $dataUpdate = [
                'nik'           => $this->request->getPost('nik'),
                'name'          => $this->request->getPost('nama'),
                'dob'           => $this->request->getPost('tanggal_lahir'),
                'ktp_address'   => $alamat_lengkap,
                'is_verify'     => 1
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
            
        }catch(Exception $e){
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
        if (!$user) return $this->failNotFound('Tukang tidak ditemukan kawan.');

        unset($user['password']);
        if (!empty($user['profile_photo'])) {
            $user['profile_photo'] = base_url('uploads/tukang/' . $user['profile_photo']);
        }

        return $this->respond(['status' => true, 'data' => $user]);
    }

    public function extractSync()
    {
        // 1. Ambil file KTP dan Selfie dari request frontend
        $fileKtp  = $this->request->getFile('ktp_image');
        $fileFace = $this->request->getFile('face_image');

        if (!$fileKtp || !$fileKtp->isValid() || !$fileFace || !$fileFace->isValid()) {
            return $this->failValidationErrors('Gambar KTP dan Foto Selfie wajib diunggah.');
        }

        // 2. Konversi kedua gambar menjadi Base64
        $base64Ktp  = base64_encode(file_get_contents($fileKtp->getTempName()));
        $base64Face = base64_encode(file_get_contents($fileFace->getTempName()));

        $client = \Config\Services::curlrequest([
            'timeout' => 60
        ]);
        $apiHeaders = [
            'App-ID'       => getenv('VERIHUBS_APP_ID'),
            'API-Key'      => getenv('VERIHUBS_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        try {
            // ==============================================================
            // TAHAP 1: COMPARE (BANDINGKAN WAJAH KTP VS SELFIE)
            // ==============================================================
            $resCompare = $client->post('https://api.verihubs.com/v1/face/compare', [
                'headers' => $apiHeaders,
                'json'    => [
                    'image_1'            => $base64Ktp,
                    'image_2'            => $base64Face,
                    'is_quality'         => true,
                    'is_attribute'       => true,
                    'is_liveness'        => false, 
                    'validate_quality'   => false,
                    'validate_attribute' => false,
                    'validate_liveness'  => false,
                    'validate_nface'     => true 
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
                'headers'     => $apiHeaders,
                'json'        => ['image' => $base64Ktp],
                'http_errors' => false
            ]);

            $statusKtp = $resKtp->getStatusCode();
            $resultKtp = json_decode($resKtp->getBody(), true);

            // Cek gagal ekstrak KTP (Untuk extract KTP, biasanya ada bungkus 'data')
            if ($statusKtp !== 200 || !isset($resultKtp['data'])) {
                return $this->fail('KTP Format Error: gagal mengambil data ktp', 400);
            }


            // ==============================================================
            // TAHAP 3: SEMUA BERHASIL, KEMBALIKAN DATA KE FRONTEND
            // ==============================================================
            return $this->respond([
                'status'  => 'success',
                'message' => 'Wajah cocok dan KTP berhasil diekstrak.',
                'data'    => [
                    // Kita sertakan status_code dan similarity_status dari respons asli
                    'compare_status' => $resultCompare['status_code'] ?? 'N/A', 
                    'is_match'       => $isMatch,
                    'ktp_data'       => $resultKtp['data'] 
                ]
            ], 200);

        } catch (\Exception $e) {
            return $this->failServerError('Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // --- INTERNAL JWT HELPERS ---

    private function _generateJWT($payload) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode($payload);
        $base64UrlHeader = $this->_base64UrlEncode($header);
        $base64UrlPayload = $this->_base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->jwtKey, true);
        $base64UrlSignature = $this->_base64UrlEncode($signature);
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    private function _decodeJWT($jwt) {
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) != 3) return false;
        $payload = base64_decode($tokenParts[1]);
        $payloadData = json_decode($payload, true);
        if (isset($payloadData['exp']) && ($payloadData['exp'] - time()) < 0) return false;
        return $payloadData;
    }

    private function _base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}