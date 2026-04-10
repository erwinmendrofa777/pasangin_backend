<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

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
        // PERBAIKAN: Tambahkan 'fcm_token' agar notifikasi kawan bisa jalan!
        $updateData = [
            'full_name'    => $this->request->getPost('full_name') ?? $user['full_name'],
            'email'        => $this->request->getPost('email') ?? $user['email'],
            'phone_number' => $this->request->getPost('phone_number') ?? $user['phone_number'],
            'address'      => $this->request->getPost('address') ?? $user['address'],
            'fcm_token'    => $this->request->getPost('fcm_token') ?? $user['fcm_token'],
        ];
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
            
            // Pastikan folder public/uploads/profile/ sudah ada di server kawan
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
                'status'  => true,
                'message' => 'Data berhasil diperbarui secara permanen.',
                'data'    => $updatedUser
            ], 200);
        }

        return $this->fail('Gagal memperbarui data ke database.');
    }
}