<?php

namespace App\Controllers\Api;

use App\Models\TukangRatingModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class TukangRatingController extends ResourceController
{
    protected $tukangRatingModel;
    protected $format    = 'json';

    public function __construct()
    {
        helper(['url', 'form']);
        $this->tukangRatingModel = new TukangRatingModel();
    }

    // =========================================================================
    // 1. API UNTUK MENAMPILKAN RATING BERDASARKAN ID TUKANG
    // =========================================================================
    public function index($id = null)
    {
        if (!$id) {
            return $this->fail('ID Tukang tidak boleh kosong', 400);
        }

        try {
            $ratings = $this->tukangRatingModel->where('id_tukang', $id)->findAll();

            if (!empty($ratings)) {
                return $this->respond([
                    'status'  => 200,
                    'message' => 'Data rating untuk tukang ditemukan.',
                    'data'    => $ratings
                ]);
            } else {
                return $this->respond([
                    'status'  => 200,
                    'message' => 'Belum ada rating untuk tukang ini.',
                    'data'    => []
                ], 200);
            }
        } catch (Exception $e) {
            return $this->failServerError('Gagal mengambil data rating tukang: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 2. API UNTUK MENAMBAHKAN RATING UNTUK TUKANG
    // =========================================================================
    public function create(){
        //validasi input
        $rules = [
            'id_tukang'      => 'required|numeric',
            'skill_score'    => 'required|in_list[1,2,3,4,5]',
            'behavior_score' => 'required|in_list[1,2,3,4,5]',
            'comment'        => 'required',
        ];

        $messages = [
            'id_tukang' => [
                'required' => 'ID Tukang wajib diisi.',
                'numeric'  => 'ID Tukang harus berupa angka.'
            ],
            'skill_score' => [
                'required' => 'Skill Score wajib diisi',
                'in_list' => 'Skill Score harus berupa angka antara 1 hingga 5'
            ],
            'behavior_score' => [
                'required' => 'Behavior Score wajib diisi',
                'in_list' => 'Behavior Score harus berupa angka antara 1 hingga 5'
            ],
            'comment' => [
                'required' => 'Komentar wajib diisi'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getPost() ?? $this->request->getJSON();
        $data = [
            'id_tukang'      => $input['id_tukang'],
            'skill_score'    => $input['skill_score'],
            'behavior_score' => $input['behavior_score'],
            'comment'        => $input['comment'],
        ];

        try {
            //masukkan data ke database
            $newRatingId = $this->tukangRatingModel->insert($data);

            // Hitung rata-rata dan total ulasan TERBARU dari tabel tukang_rating
            $db = \Config\Database::connect();
            $kalkulasi = $db->table('tukang_rating')
                            ->select('AVG(CAST(skill_score AS UNSIGNED)) as rata_rata_skill, AVG(CAST(behavior_score AS UNSIGNED)) as rata_rata_behavior, COUNT(id) as total_ulasan')
                            ->where('id_tukang', $input['id_tukang'])
                            ->get()
                            ->getRow();
            $rata_rata_rating = ($kalkulasi->rata_rata_skill + $kalkulasi->rata_rata_behavior) / 2;

            // Simpan hasil hitungan tersebut ke tabel tukang
            $db->table('tukang')->where('id', $input['id_tukang'])->update([
                'rata_rata_rating'   => $rata_rata_rating,
                'total_ulasan'       => $kalkulasi->total_ulasan,
            ]);

            return $this->respondCreated([
                'status' => 201,
                'message' => 'Rating tukang berhasil dibuat',
                'data' => ['id' => $newRatingId]
            ]);
        } catch (Exception $e) {
            return $this->respond([
                'status' => 500,
                'message' => 'Gagal membuat rating tukang',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}