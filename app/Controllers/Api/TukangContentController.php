<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Modules\Banners\Models\BannerModel;
use App\Modules\Tips\Models\TipsModel;

class TukangContentController extends ResourceController
{
    protected $format = 'json';

    /**
     * Mengambil Banner khusus aplikasi Tukang
     * Rute: GET /api/tukang/banners
     */
    public function banners()
    {
        try {
            $model = new BannerModel();
            // PERBAIKAN: Filter hanya untuk aplikasi tukang  
            $data = $model->where('is_active', 1)
                ->where('target_app', 'tukang')
                ->orderBy('id', 'DESC')
                ->findAll();

            $finalData = [];
            foreach ($data as $row) {
                $finalData[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'image_url' => !empty($row['image']) ? base_url('uploads/banners/' . $row['image']) : null,
                ];
            }

            return $this->respond([
                'status' => true,
                'message' => 'Banner tukang berhasil diambil.',
                'data' => $finalData
            ]);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * Mengambil Tips khusus aplikasi Tukang
     * Rute: GET /api/tukang/tips
     */
    public function tips()
    {
        try {
            $model = new TipsModel();
            // Tips biasanya umum, tapi kita filter jika   ingin spesifik tukang
            $data = $model->where('is_active', 1)
                ->orderBy('id', 'DESC')
                ->findAll();

            $finalData = [];
            foreach ($data as $row) {
                $finalData[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'content' => $row['content'],
                    'image_url' => !empty($row['image']) ? base_url('uploads/tips/' . $row['image']) : null,
                ];
            }

            return $this->respond([
                'status' => true,
                'message' => 'Tips tukang berhasil diambil.',
                'data' => $finalData
            ]);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}