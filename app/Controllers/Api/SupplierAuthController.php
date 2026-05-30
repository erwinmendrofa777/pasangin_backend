<?php

namespace App\Controllers\Api;

use App\Modules\Supplier\Models\SupplierModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class SupplierAuthController extends ResourceController
{
    protected $format = 'json';
    protected $db;


    public function __construct()
    {
        helper(['url', 'form']);
        $this->db = \Config\Database::connect();
    }

    /**
     * --- LOGIN SUPPLIER ---
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

        $model = new SupplierModel();
        $phone = $this->request->getVar('phone');

        $user = $model->where('phone', $phone)->first();

        if (!$user) {
            return $this->failNotFound('Nomor telepon Supplier tidak terdaftar.');
        }

        if (!password_verify($this->request->getVar('password'), $user['password'])) {
            return $this->failUnauthorized('Password salah.');
        }

        // --- CEK STATUS AKUN ---
        if ($user['is_active'] == 0 || $user['status'] == 'banned') {
            return $this->respond([
                'status' => false,
                'message' => 'Akun Anda dinonaktifkan atau diblokir oleh admin.',
                'code' => 'AUTH_BANNED'
            ], 403);
        }

        if ($user['status'] == 'rejected') {
            return $this->respond([
                'status' => false,
                'message' => 'Pendaftaran Anda ditolak oleh admin.',
                'code' => 'AUTH_REJECTED'
            ], 403);
        }

        // Payload JWT
        $payload = [
            'iss' => 'https://backend.pasangin.co.id',
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // Berlaku 7 hari
            'uid' => $user['id'],
            'role' => 'supplier'
        ];

        $jwt = $this->_generateJWT($payload);

        unset($user['password']);
        $user['token'] = $jwt;

        // --- SIMPAN FCM TOKEN JIKA DIKIRIM SAAT LOGIN ---
        $fcmToken = $this->request->getVar('fcm_token');
        if (!empty($fcmToken)) {
            $tokenRepo = new \App\Modules\Notifications\Repositories\FcmTokenRepository();
            $tokenRepo->upsertToken($user['id'], 'supplier', $fcmToken);
        }

        // Tambahkan base_url untuk logo
        if (!empty($user['logo_url'])) {
            $user['logo_url'] = base_url('uploads/supplier/' . $user['logo_url']);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Login Supplier berhasil.',
            'data' => $user
        ]);
    }

    /**
     * --- REGISTER SUPPLIER ---
     */
    public function register()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[suppliers.email]',
            'phone' => 'required|numeric|min_length[10]|max_length[15]|is_unique[suppliers.phone]',
            'password' => 'required|min_length[8]|max_length[255]',
            'contact_person' => 'required|min_length[3]|max_length[100]',
            'address' => 'required|min_length[3]|max_length[255]',
            'province' => 'required|min_length[3]|max_length[100]',
            'city' => 'required|min_length[3]|max_length[100]',
            'district' => 'required|min_length[3]|max_length[100]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama toko wajib diisi.',
                'min_length' => 'Nama toko minimal 3 karakter.',
                'max_length' => 'Nama toko maksimal 100 karakter.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique' => 'Email sudah terdaftar.'
            ],
            'phone' => [
                'required' => 'Nomor telepon wajib diisi.',
                'numeric' => 'Nomor telepon harus berupa angka.',
                'min_length' => 'Nomor telepon minimal 10 digit.',
                'max_length' => 'Nomor telepon maksimal 15 digit.',
                'is_unique' => 'Nomor telepon sudah terdaftar.'
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.',
                'max_length' => 'Password maksimal 255 karakter.'
            ],
            'contact_person' => [
                'required' => 'Contact person wajib diisi.',
                'min_length' => 'Contact person minimal 3 karakter.',
                'max_length' => 'Contact person maksimal 100 karakter.'
            ],
            'address' => [
                'required' => 'Alamat wajib diisi.',
                'min_length' => 'Alamat minimal 3 karakter.',
                'max_length' => 'Alamat maksimal 255 karakter.'
            ],
            'province' => [
                'required' => 'Provinsi wajib diisi.',
                'min_length' => 'Provinsi minimal 3 karakter.',
                'max_length' => 'Provinsi maksimal 100 karakter.'
            ],
            'city' => [
                'required' => 'Kota wajib diisi.',
                'min_length' => 'Kota minimal 3 karakter.',
                'max_length' => 'Kota maksimal 100 karakter.'
            ],
            'district' => [
                'required' => 'Kecamatan wajib diisi.',
                'min_length' => 'Kecamatan minimal 3 karakter.',
                'max_length' => 'Kecamatan maksimal 100 karakter.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ], 400);
        }

        $model = new SupplierModel();
        try {
            $model->save([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'contact_person' => $data['contact_person'],
                'address' => $data['address'] ?? null,
                'province' => $data['province'] ?? null,
                'city' => $data['city'] ?? null,
                'district' => $data['district'] ?? null,
                'status' => 'pending',
                'is_active' => 1
            ]);

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Pendaftaran Supplier berhasil. Silakan login.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError('Gagal registrasi: ' . $e->getMessage());
        }
    }

    /**
     * --- UPDATE PROFILE SUPPLIER ---
     */
    public function updateProfile()
    {
        try {

            // TODO - jangan kasi validasi untuk is_verified
            $supplierId = $this->request->user->uid;

            if ($this->request->user->role !== 'supplier') {
                return $this->failUnauthorized('Akses ditolak.');
            }
            $model = new SupplierModel();
            $supplier = $model->find($supplierId);

            if (!$supplier) {
                return $this->failNotFound('Supplier tidak ditemukan.');
            }

            $dataUpdate = [
                'name' => $this->request->getPost('name') ?? $supplier['name'],
                'contact_person' => $this->request->getPost('contact_person') ?? $supplier['contact_person'],
                'phone' => $this->request->getPost('phone') ?? $supplier['phone'],
                'email' => $this->request->getPost('email') ?? $supplier['email'],
                'address' => $this->request->getPost('address') ?? $supplier['address'],
                'province' => $this->request->getPost('province') ?? $supplier['province'],
                'city' => $this->request->getPost('city') ?? $supplier['city'],
                'district' => $this->request->getPost('district') ?? $supplier['district'],
                'latitude' => $this->request->getPost('latitude') ?? $supplier['latitude'],
                'longitude' => $this->request->getPost('longitude') ?? $supplier['longitude'],
                'is_verify' => $this->request->getPost('is_verify') ?? $supplier['is_verify'],
                'nik' => $this->request->getPost('nik') ?? $supplier['nik'],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Update Password jika diisi
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                $dataUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            // Upload Logo
            $file = $this->request->getFile('logo_url');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if (!empty($supplier['logo_url']) && file_exists('uploads/supplier/' . $supplier['logo_url'])) {
                    unlink('uploads/supplier/' . $supplier['logo_url']);
                }
                $newName = $file->getRandomName();
                $file->move('uploads/supplier/', $newName);
                $dataUpdate['logo_url'] = $newName;
            }

            $model->update($supplierId, $dataUpdate);

            $updatedUser = $model->find($supplierId);
            unset($updatedUser['password']);

            if (!empty($updatedUser['logo_url'])) {
                $updatedUser['logo_url'] = base_url('uploads/supplier/' . $updatedUser['logo_url']);
            }

            return $this->respond([
                'status' => true,
                'message' => 'Profil Toko berhasil diperbarui!',
                'data' => $updatedUser
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * --- CHANGE PASSWORD SUPPLIER (NEW) ---
     */
    public function changePassword()
    {
        try {
            $supplierId = $this->request->user->uid;

            $model = new SupplierModel();
            $supplier = $model->find($supplierId);

            $oldPass = $this->request->getVar('old_password');
            $newPass = $this->request->getVar('new_password');

            // 1. Verifikasi Password Lama
            if (!password_verify($oldPass, $supplier['password'])) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Password lama salah.'
                ], 400);
            }

            // 2. Update ke Password Baru
            $model->update($supplierId, [
                'password' => password_hash($newPass, PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respond([
                'status' => true,
                'message' => 'Password berhasil diubah!'
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * --- GET SUPPLIER BEDASARKAN ID ---
     */
    public function getProfile($id = null)
    {
        if (!$id)
            return $this->fail('ID Supplier tidak boleh kosong');

        $supplier = $this->db->table('suppliers')->where('id', $id)->get()->getRow();

        if (!$supplier)
            return $this->failNotFound('Supplier tidak ditemukan');

        // 1. Ambil data dasar & format
        unset($supplier->password); // Keamanan
        $supplier->image_url = !empty($supplier->logo_url) ? base_url('uploads/supplier/' . $supplier->logo_url) : null;
        $supplier->tahun_berdiri = date('Y', strtotime($supplier->created_at));

        // 2. Hitung total produk
        $totalProducts = $this->db->table('products')
            ->where('supplier_id', $id)
            ->countAllResults();
        $supplier->total_produk = $totalProducts;

        // 3. Hitung total pesanan yang diterima
        $totalOrdersQuery = $this->db->table('order_items')
            ->select('orders.id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('products', 'products.id = order_items.product_id')
            ->where('products.supplier_id', $id)
            ->groupBy('orders.id')
            ->get();
        $supplier->jumlah_pesanan = $totalOrdersQuery->getNumRows();

        // 4. Ambil data rating yang sudah dikalkulasi di tabel supplier untuk efisiensi
        $supplier->rata_rata_rating = (float) ($supplier->rata_rata_rating ?? 0);
        $supplier->total_ulasan = (int) ($supplier->total_ulasan ?? 0);

        return $this->respond([
            'status' => true,
            'message' => 'Profil publik supplier ditemukan',
            'data' => $supplier
        ]);
    }

    /**
     * --- UPDATE TOKEN FCM ---
     */
    public function updateFcmToken()
    {
        try {
            $supplierId = $this->request->user->uid;

            $json = $this->request->getJSON();
            $fcmToken = $json->fcm_token ?? null;

            if (empty($fcmToken)) return $this->fail('FCM Token kosong.', 400);

            // Simpan ke tabel baru (multi-perangkat)
            $tokenRepo = new \App\Modules\Notifications\Repositories\FcmTokenRepository();
            $tokenRepo->upsertToken($supplierId, 'supplier', $fcmToken);

            return $this->respond(['status' => true, 'message' => 'Token FCM diperbarui.']);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    // --- JWT HELPERS ---
    private function _generateJWT($payload)
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode($payload);
        $base64UrlHeader = $this->_base64UrlEncode($header);
        $base64UrlPayload = $this->_base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, getenv('JWT_SECRET'), true);
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $this->_base64UrlEncode($signature);
    }



    private function _base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
