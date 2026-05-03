<?php

namespace App\Controllers\Api;

use App\Models\ProductModel;
use App\Models\SupplierModel;
use App\Models\ProductsRatingModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class ProductApi extends ResourceController
{
    protected $productsRatingModel;
    protected $format = 'json';
    private $jwtKey = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma';

    public function __construct()
    {
        $this->productsRatingModel = new ProductsRatingModel();
    }

    /**
     * HELPER: Mendapatkan ID Supplier (Hanya untuk Tambah/Update/Hapus/List Saya)
     */
    private function getSupplierId()
    {
        try {
            $authHeader = $this->request->getHeaderLine('Authorization');
            if (empty($authHeader)) return null;

            $token = str_replace('Bearer ', '', $authHeader);
            $tokenParts = explode('.', $token);

            if (count($tokenParts) != 3) return null;

            $payload = json_decode(base64_decode($tokenParts[1]), true);

            if (!isset($payload['role']) || $payload['role'] !== 'supplier') {
                return null;
            }

            return $payload['uid'] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    // controller untuk get rating berdasarkan id produk
    public function showrating($id = null)
    {
        if (!$id) {
            return $this->fail('ID Produk tidak boleh kosong', 400);
        }

        try {
            $ratings = $this->productsRatingModel->where('id_product', $id)->findAll();
            $rating['image_url'] = base_url('uploads/products/rating/');

            if (!empty($ratings)) {
                foreach ($ratings as &$rating) {
                    for ($i = 1; $i <= 5; $i++) {
                        if (!empty($rating['gambar' . $i])) {
                            $rating['gambar' . $i] = base_url('uploads/products/rating/' . $rating['gambar' . $i]);
                        } else {
                            $rating['gambar' . $i] = null;
                        }
                    }
                }
            }

            if (!empty($ratings)) {
                return $this->respond([
                    'status'  => 200,
                    'message' => 'Data rating untuk product ' . $id . ' ditemukan.',
                    'data'    => $ratings
                ]);
            } else {
                return $this->respond([
                    'status'  => 200,
                    'message' => 'Belum ada rating untuk product ini.',
                    'data'    => []
                ], 200);
            }
        } catch (Exception $e) {
            return $this->failServerError('Gagal mengambil data rating product: ' . $e->getMessage());
        }
    }

    // controller untuk create rating produk
    public function createRating()
    {
        //validasi input
        $rules = [
            'id_product' => 'required|numeric',
            'rating'    => 'required|in_list[1,2,3,4,5]',
            'comment'   => 'required',
        ];
        $messages = [
            'id_product' => [
                'required' => 'ID Produk wajib diisi.',
                'numeric'  => 'ID Produk harus berupa angka.'
            ],
            'rating' => [
                'required' => 'Rating wajib diisi',
                'in_list' => 'Rating harus berupa angka antara 1 hingga 5'
            ],
            'comment' => [
                'required' => 'Komentar wajib diisi'
            ]
        ];
        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $input = $this->request->getPost();
        $data = [
            'id_product' => $input['id_product'],
            'rating'    => $input['rating'],
            'comment'   => $input['comment'],
        ];

        // Gunakan getFileMultiple agar otomatis selalu menjadi array
        $images = $this->request->getFileMultiple('images');
        $uploadedFileNames = [];

        // Lakukan proses upload hanya jika ada file yang diunggah
        if (!empty($images)) {
            // Cek batasan maksimal 5 gambar
            if (count($images) > 5) {
                return $this->failValidationErrors('Anda hanya boleh mengunggah maksimal 5 gambar.');
            }

            // Pastikan folder tujuan ada, jika tidak, buat folder tersebut
            $uploadPath = 'uploads/products/rating/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Proses dan Pindahkan Setiap Gambar
            foreach ($images as $img) {
                if ($img && $img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move($uploadPath, $newName);
                    $uploadedFileNames[] = $newName;
                }
            }

            // Tambahkan nama file gambar ke data yang akan disimpan
            if (!empty($uploadedFileNames)) {
                foreach ($uploadedFileNames as $index => $fileName) {
                    $data['gambar' . ($index + 1)] = $fileName;
                }
            }
        }

        try {
            //masukkan data ke database
            $newRatingId = $this->productsRatingModel->insert($data);

            // Hitung rata-rata dan total ulasan TERBARU dari tabel products_rating
            $db = \Config\Database::connect();
            $kalkulasi = $db->table('products_rating')
                ->select('AVG(CAST(rating AS UNSIGNED)) as rata_rata, COUNT(id) as total_ulasan')
                ->where('id_product', $input['id_product'])
                ->get()
                ->getRow();

            // Simpan hasil hitungan tersebut ke tabel products
            $db->table('products')->where('id', $input['id_product'])->update([
                'rata_rata_rating' => $kalkulasi->rata_rata,
                'total_ulasan'     => $kalkulasi->total_ulasan,
            ]);

            return $this->respondCreated([
                'status' => 201,
                'message' => 'Rating products berhasil dibuat',
                'data' => ['id' => $newRatingId]
            ]);
        } catch (Exception $e) {
            return $this->respond([
                'status'  => 500,
                'message' => 'Gagal membuat rating products',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // controller api untuk filter berdasarkan kategori
    public function show($id = null)
    {

        $model = new ProductModel();
        // PERBAIKAN: Mengambil daftar kategori unik dari produk yang dimiliki supplier, bukan nama produk.
        $data = $model->select('categories.name, MIN(categories.id) as id')
            ->join('categories', 'categories.id = products.category_id')
            ->groupBy('categories.name')
            ->orderBy('categories.name', 'ASC')
            ->asArray() // Mengembalikan sebagai array agar konsisten
            ->findAll();

        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'list kategori supplier',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Belum ada kategori untuk supplier ini',
                'data' => $data
            ]);
        }
    }

    // controller api untuk filter berdasarkan kota
    public function regions()
    {
        $model = new SupplierModel();

        // 1. Ambil data (hilangkan distinct di query, kita handle di PHP)
        $regions = $model->select('city')
            ->where('city IS NOT NULL')
            ->where('city !=', '')
            ->get()
            ->getResultArray();

        $rawCities = array_map(fn($r) => $r['city'], $regions);

        // 2. Bersihkan dan seragamkan format nama kota
        $cleanCities = [];
        foreach ($rawCities as $city) {
            // Hapus kata imbuhan (str_ireplace tidak mempedulikan huruf besar/kecil)
            $cleanName = str_ireplace(['Kabupaten ', 'Kab. ', 'Kota '], '', $city);

            // Rapikan spasi sisa dan jadikan Title Case (cth: "GROBOGAN" -> "Grobogan")
            $cleanName = ucwords(strtolower(trim($cleanName)));

            $cleanCities[] = $cleanName;
        }

        // 3. Hapus duplikat (Grobogan dan Kabupaten Grobogan sekarang jadi satu)
        $uniqueCities = array_unique($cleanCities);

        // 4. Urutkan sesuai abjad A-Z (Sort juga otomatis mereset index array yang bolong)
        sort($uniqueCities);

        // 5. Cek apakah ada data KOTA (lakukan pengecekan SEBELUM menambah 'Semua Wilayah')
        $hasData = !empty($uniqueCities);

        // 6. Masukkan 'Semua Wilayah' ke urutan paling atas
        array_unshift($uniqueCities, 'Semua Wilayah');

        // 7. Kembalikan response
        if ($hasData) {
            return $this->respond([
                'status'  => true,
                'message' => 'list kota supplier',
                'data'    => $uniqueCities
            ]);
        } else {
            return $this->respond([
                'status'  => false, // Lebih logis menggunakan false jika data kosong
                'message' => 'supplier tidak ada di kota ini',
                'data'    => $uniqueCities
            ]);
        }
    }


    /**
     * --- 1. LIST PRODUK (PUBLIK) ---
     * Digunakan oleh App Client untuk melihat semua barang
     */
    public function index()
    {
        $model = new ProductModel();

        $search = $this->request->getGet('search');
        $limit  = $this->request->getGet('limit') ?? 10;
        $region = $this->request->getGet('region');
        $page   = $this->request->getGet('page') ?? 1;

        $limit  = intval($limit);
        $offset = ($page - 1) * $limit;

        // 1. Siapkan kerangka Query-nya (Hanya merangkai, JANGAN dieksekusi dulu)
        $builder = $model->select('products.*, suppliers.name as supplier_name, suppliers.city as region')
            ->join('suppliers', 'suppliers.id = products.supplier_id');

        // 2. Masukkan semua filter kondisi
        if (!empty($search)) {
            $builder->like('products.name', $search);
        }

        if (!empty($region) && $region != "Semua Wilayah") {
            $builder->like('suppliers.city', $region);
        }

        $builder->where('products.status', 'aktif');

        // 3. Hitung total data menggunakan metode CLONE (Trik yang sangat bagus!)
        $totalBuilder = clone $builder;
        $totalProducts = $totalBuilder->countAllResults(false);

        // 4. Ambil data aslinya dengan limit & offset, BARU eksekusi (get & getResultArray)
        $products = $builder->orderBy('products.id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        // 5. Rapikan URL Gambar
        foreach ($products as &$p) {
            $p['image_url'] = base_url('uploads/products/' . ($p['photo'] ?? 'default.png'));
        }

        // 6. Kembalikan Response JSON
        return $this->respond([
            'status' => true,
            'data'   => $products,
            'pagination' => [
                'current_page'   => (int)$page,
                'has_more_pages' => ($offset + $limit) < $totalProducts,
                'total_products' => $totalProducts
            ]
        ]);
    }

    /**
     * --- 2. LIST PRODUK SAYA (KHUSUS APP SUPPLIER) ---
     * Inilah yang akan dipanggil oleh aplikasi supplier kawan
     */
    public function myProducts()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized('Akses ditolak.');

        $model = new ProductModel();

        // Menggunakan fungsi di model yang sudah kita buat sebelumnya (dengan JOIN kategori)
        $data = $model->getProductsBySupplier($supplierId);

        // Tambahkan image_url lengkap agar Flutter tidak error
        foreach ($data as &$p) {
            $p['image_url'] = base_url('uploads/products/' . ($p['photo'] ?? 'default.png'));
        }

        return $this->respond($data);
    }

    /**
     * --- 3. TAMBAH PRODUK BARU (WAJIB LOGIN & STATUS APPROVED) ---
     */
    public function create()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized('Hanya supplier yang bisa menambah produk.');

        // CEK STATUS AKUN KAWAN
        $supplierModel = new SupplierModel();
        $supplier = $supplierModel->find($supplierId);
        if (!$supplier || $supplier['status'] !== 'approved') {
            return $this->failForbidden('Akses ditolak. Akun Anda belum disetujui oleh admin.');
        }

        $input = $this->request->getPost();
        $file = $this->request->getFile('photo');

        // Gunakan grup validasi 'productSave' dari Config/Validation.php
        $dataToValidate = $input;
        $dataToValidate['photo'] = $file;

        if (!$this->validateData($dataToValidate, 'productSave')) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $photoName = 'default.png';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photoName = $file->getRandomName();
            $file->move('uploads/products/', $photoName);
        }

        $model = new ProductModel();
        try {
            $model->insert([
                'supplier_id' => $supplierId,
                'category_id' => $input['category_id'],
                'name'        => $input['name'],
                'description' => $input['description'] ?? null,
                'price'       => $input['price'],
                'unit'        => $input['unit'] ?? 'pcs',
                'stock'       => $input['stock'],
                'min_order'   => $input['min_order'] ?? 1,
                'status'      => 'aktif',
                'photo'       => $photoName,
            ]);

            return $this->respondCreated(['status' => true, 'message' => 'Produk berhasil ditambahkan.']);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * --- 4. UPDATE PRODUK ---
     */
    public function update($id = null)
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized();

        $model = new ProductModel();
        $product = $model->where(['id' => $id, 'supplier_id' => $supplierId])->first();
        if (!$product) return $this->failNotFound('Produk tidak ditemukan.');

        // Ambil data input
        $input = $this->request->getPost();
        if (empty($input)) {
            $input = $this->request->getJSON(true) ?? $this->request->getRawInput();
        }

        $file = $this->request->getFile('photo');

        // Validasi menggunakan grup 'productUpdate'
        $dataToValidate = $input;
        $dataToValidate['id'] = $id;
        $dataToValidate['photo'] = $file;

        if (!$this->validateData($dataToValidate, 'productUpdate')) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!empty($product['photo']) && file_exists('uploads/products/' . $product['photo'])) {
                unlink('uploads/products/' . $product['photo']);
            }
            $newName = $file->getRandomName();
            $file->move('uploads/products/', $newName);
            $input['photo'] = $newName;
        }

        if (!$model->update($id, $input)) {
            return $this->failValidationErrors($model->errors());
        }

        return $this->respond(['status' => true, 'message' => 'Data produk diperbarui.']);
    }

    /**
     * --- 5. HAPUS PRODUK ---
     */
    public function delete($id = null)
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized();

        $model = new ProductModel();
        $product = $model->where(['id' => $id, 'supplier_id' => $supplierId])->first();
        if (!$product) return $this->failNotFound();

        if (!empty($product['photo']) && file_exists('uploads/products/' . $product['photo'])) {
            unlink('uploads/products/' . $product['photo']);
        }

        $model->delete($id);
        return $this->respondDeleted(['status' => true, 'message' => 'Produk dihapus.']);
    }

    // fungsi untuk mendapatkan produk yang dimiliki supplier tertentu
    public function getBySupplier($supplierId = null)
    {
        if (!$supplierId) return $this->fail('ID Supplier tidak boleh kosong');

        $model = new ProductModel();
        $products = $model->where('supplier_id', $supplierId)->findAll();

        foreach ($products as &$p) {
            $p['image_url'] = base_url('uploads/products/' . ($p['photo'] ?? 'default.png'));
        }

        return $this->respond([
            'status'  => true,
            'message' => 'Produk berhasil diambil.',
            'data'    => $products
        ]);
    }

    public function detailProduct($id = null)
    {
        if (!$id) return $this->fail('ID Produk tidak boleh kosong');

        $model = new ProductModel();
        $product = $model->where('id', $id)->first();
        if (!$product) return $this->failNotFound('Produk tidak ditemukan.');

        $product['image_url'] = base_url('uploads/products/' . ($product['photo'] ?? 'default.png'));

        return $this->respond([
            'status'  => true,
            'message' => 'Produk berhasil diambil.',
            'data'    => $product
        ]);
    }
}
