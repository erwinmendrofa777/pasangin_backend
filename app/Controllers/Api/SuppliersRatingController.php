<?php

namespace App\Controllers\Api;

use App\Modules\Supplier\Models\SuppliersRatingModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class SuppliersRatingController extends ResourceController
{
    protected $suppliersRatingModel;
    protected $format    = 'json';

    public function __construct()
    {
        helper(['url', 'form']);
        $this->suppliersRatingModel = new SuppliersRatingModel();
    }

    // =========================================================================
    // 1. API UNTUK MENAMPILKAN RATING BERDASARKAN ID SUPPLIER
    // =========================================================================
    public function index($id = null)
    {
        if (!$id) {
            return $this->fail('ID Supplier tidak boleh kosong', 400);
        }

        try {
            $ratings = $this->suppliersRatingModel->where('id_supplier', $id)->findAll();
            $rating['image_url'] = base_url('uploads/rating/');
            if (!empty($ratings)) {
                foreach ($ratings as &$rating) {
                    for ($i = 1; $i <= 5; $i++) {
                        if (!empty($rating['gambar' . $i])) {
                            $rating['gambar' . $i] = base_url('uploads/rating/' . $rating['gambar' . $i]);
                        } else {
                            $rating['gambar' . $i] = null;
                        }
                    }
                }
            }

            if (!empty($ratings)) {
                return $this->respond([
                    'status'  => 200,
                    'message' => 'Data rating untuk supplier ' . $id . ' ditemukan.',
                    'data'    => $ratings
                ]);
            } else {
                return $this->respond([
                    'status'  => 200,
                    'message' => 'Belum ada rating untuk supplier ini.',
                    'data'    => []
                ], 200);
            }
        } catch (Exception $e) {
            return $this->failServerError('Gagal mengambil data rating supplier: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 2. API UNTUK MENAMBAHKAN RATING UNTUK SUPPLIER
    // =========================================================================
    public function create(){
        //validasi input
        $rules = [
            'id_supplier'      => 'required|numeric',
            'rating'           => 'required|in_list[1,2,3,4,5]',
            'comment'          => 'required',
        ];

        $messages = [
            'id_supplier' => [
                'required' => 'ID Supplier wajib diisi.',
                'numeric'  => 'ID Supplier harus berupa angka.'
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
            'id_supplier' => $input['id_supplier'],
            'rating'      => $input['rating'],
            'comment'     => $input['comment'],
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
            $uploadPath = 'uploads/rating/';
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
            $newRatingId = $this->suppliersRatingModel->insert($data);

            // Hitung rata-rata dan total ulasan TERBARU dari tabel suppliers_rating
            $db = \Config\Database::connect();
            $kalkulasi = $db->table('suppliers_rating')
                            ->select('AVG(CAST(rating AS UNSIGNED)) as rata_rata, COUNT(id) as total_ulasan')
                            ->where('id_supplier', $input['id_supplier'])
                            ->get()
                            ->getRow();

            // Simpan hasil hitungan tersebut ke tabel suppliers
            $db->table('suppliers')->where('id', $input['id_supplier'])->update([
                'rata_rata_rating' => $kalkulasi->rata_rata,
                'total_ulasan'     => $kalkulasi->total_ulasan,
            ]);

            return $this->respondCreated([
                'status' => 201,
                'message' => 'Rating supplier berhasil dibuat',
                'data' => ['id' => $newRatingId]
            ]);
        } catch (Exception $e) {
            return $this->respond([
                'status' => 500,
                'message' => 'Gagal membuat rating supplier',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}