<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AlamatUserModel;
use Exception;

class AlamatUserController extends BaseController
{
    use ResponseTrait;

    public function create(){
        //ambil id user dari jwt
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token tidak ditemukan.');
        }
        
        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(getenv('JWT_SECRET'), 'HS256'));
        $userId = $decoded->uid;

        //validasi
        $rules = [
            'alamat'         => 'required|min_length[3]|max_length[255]',
            'label'          => 'max_length[255]',
            'latitude'       => 'required|decimal',
            'longitude'      => 'required|decimal'
        ];

        $messages = [
            'alamat' => [
                'required'   => 'alamat wajib diisi.',
                'min_length' => 'alamat minimal 3 karakter.',
                'max_length' => 'alamat maksimal 255 karakter.',
            ],
            'label' => [
                'max_length' => 'alamat maksimal 255 karakter.',
            ],
            'latitude' => [
                'required'              => 'Titik latitude wajib diisi.',
                'decimal'               => 'Format latitude harus berupa angka (desimal).',
            ],
            'longitude' => [
                'required'              => 'Titik longitude wajib diisi.',
                'decimal'               => 'Format longitude harus berupa angka (desimal).',
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond([
                'status'  => 'error', 
                'message' => $this->validator->getErrors()
            ], 400);
        }

        $model = new AlamatUserModel();

        //simpan ke database
        try {
            $model->save([
                'id_user'        => $userId,
                'alamat'         => $data['alamat'],
                'label'          => $data['label'],
                'latitude'       => $data['latitude'],
                'longitude'      => $data['longitude'],
                'is_active'      => 0
            ]);

            return $this->respondCreated([
                'status'  => 'success', 
                'message' => 'Alamat berhasil ditambahkan.',
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal: ' . $e->getMessage());
        }
    }

    public function get(){
        //ambil id user dari jwt
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token tidak ditemukan.');
        }
        
        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(getenv('JWT_SECRET'), 'HS256'));
        $userId = $decoded->uid;

        //validasi
        if($userId == null){
            return $this->fail('Token tidak valid.');
        }

        $model = new AlamatUserModel();

        // ambil data
        $alamat = $model->where('id_user', $userId)->get()->getResultArray();
        
        //response
        if($alamat == null){
            return $this->respond([
                'status'  => 'success', 
                'message' => 'Alamat Belum Dibuat.',
                'data'    => []
            ], 200);
        }

        return $this->respond([
            'status'  => 'success', 
            'message' => 'Alamat ditemukan.',
            'data'    => $alamat
        ],200);
    }

    public function delete($id){
        //validasi
        if($id == null){
            return $this->fail('Id tidak boleh kosong.');
        }

        $model = new AlamatUserModel();
        $model->where('id', $id)->delete();

        return $this->respond([
            'status'  => 'success', 
            'message' => 'Alamat berhasil dihapus.'
        ], 200);
    }

    public function put($id = null){
        //ambil id user dari jwt
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token tidak ditemukan.');
        }
        
        try {
            $token = str_replace('Bearer ', '', $authHeader->getValue());
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(getenv('JWT_SECRET'), 'HS256'));
            $userId = $decoded->uid;
        } catch (\Exception $e) {
            return $this->failUnauthorized('Token tidak valid.');
        }

        $model = new AlamatUserModel();

        // 3. VALIDASI KEPEMILIKAN (Sangat Penting!)
        $alamatLama = $model->where(['id' => $id, 'id_user' => $userId])->first();
        if (!$alamatLama) {
            return $this->failNotFound('Alamat tidak ditemukan atau Anda tidak memiliki akses.');
        }

        //validasi
        $rules = [
            'alamat'    => 'required|min_length[3]|max_length[255]',
            'label'     => 'max_length[255]',
            'latitude'  => 'required|numeric|greater_than_equal_to[-90]|less_than_equal_to[90]',
            'longitude' => 'required|numeric|greater_than_equal_to[-180]|less_than_equal_to[180]'
        ];

        $messages = [
            'alamat' => [
                'required'   => 'alamat wajib diisi.',
                'min_length' => 'alamat minimal 3 karakter.',
                'max_length' => 'alamat maksimal 255 karakter.',
            ],
            'label' => [
                'max_length' => 'alamat maksimal 255 karakter.',
            ],
            'latitude' => [
                'required'              => 'Titik latitude wajib diisi.',
                'numeric'               => 'Format latitude harus berupa angka (desimal).',
                'greater_than_equal_to' => 'Nilai latitude tidak valid (minimal -90).',
                'less_than_equal_to'    => 'Nilai latitude tidak valid (maksimal 90).'
            ],
            'longitude' => [
                'required'              => 'Titik longitude wajib diisi.',
                'numeric'               => 'Format longitude harus berupa angka (desimal).',
                'greater_than_equal_to' => 'Nilai longitude tidak valid (minimal -180).',
                'less_than_equal_to'    => 'Nilai longitude tidak valid (maksimal 180).'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond([
                'status'  => 'error', 
                'message' => $this->validator->getErrors()
            ], 400);
        }

        //simpan ke database
        try {
            $model->update($id, [
                'alamat'         => $data['alamat'],
                'label'          => $data['label'] ?? $alamatLama['label'],
                'is_active'      => $data['is_active'] ?? $alamatLama['is_active'],
                'latitude'       => $data['latitude'] ?? $alamatLama['latitude'],
                'longitude'      => $data['longitude'] ?? $alamatLama['longitude']
            ]);

            return $this->respondCreated([
                'status'  => 'success', 
                'message' => 'Berhasil Mengubah Alamat.'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal: ' . $e->getMessage());
        }
    }

    public function patch($id = null){
        //ambil id user dari jwt
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token tidak ditemukan.');
        }
        
        try {
            $token = str_replace('Bearer ', '', $authHeader->getValue());
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key(getenv('JWT_SECRET'), 'HS256'));
            $userId = $decoded->uid;
        } catch (\Exception $e) {
            return $this->failUnauthorized('Token tidak valid.');
        }

        $model = new AlamatUserModel();

        // 2. Cek apakah alamat ini benar milik user tersebut
        $alamat = $model->where(['id' => $id, 'id_user' => $userId])->first();
        if (!$alamat) {
            return $this->failNotFound('Alamat tidak ditemukan.');
        }

        try{
            $db = \Config\Database::connect();
            $db->transStart();
            
            // Langkah A: Set SEMUA alamat milik user ini menjadi is_active = 0
            $model->where('id_user', $userId)->set(['is_active' => 0])->update();

            // Langkah B: Set alamat yang dipilih (berdasarkan $id) menjadi is_active = 1
            $model->update($id, ['is_active' => 1]);

            $db->transComplete();

            if ($db->transStatus() === false) {
            return $this->failServerError('Gagal memperbarui status alamat.');
            }

            return $this->respond([
            'status'  => 'success', 
            'message' => 'Alamat utama berhasil diubah.'
            ]);
        }catch(\Exception $e){
            return $this->failServerError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}