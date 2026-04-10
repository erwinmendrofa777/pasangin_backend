<?php

// Pastikan namespace ini sesuai dengan lokasi file (app/Controllers/Api)
namespace App\Controllers\Api;

// Gunakan ResourceController dari CodeIgniter untuk membuat API dengan mudah
use CodeIgniter\RESTful\ResourceController;
// Panggil model yang akan digunakan di bagian atas agar rapi
use App\Models\BannerModel;
use App\Models\TipsModel;

// ======================================================================
// <<<--- INI DIA PERBAIKAN UTAMANYA ---
// Nama class diubah dari 'Content' menjadi 'ContentController'
// agar cocok dengan yang dicari oleh file Routes.php Anda.
// ======================================================================
class ContentController extends ResourceController
{
    // Beritahu CodeIgniter bahwa kita akan selalu mengembalikan data dalam format JSON.
    protected $format = 'json';

    /**
     * =======================================================================
     * FUNGSI UNTUK MENGAMBIL DATA TIPS
     * Rute: GET /api/content/tips
     * =======================================================================
     */
    public function tips()
    {
        try {
            $model = new TipsModel();
            
            // Ambil data yang statusnya aktif saja, urutkan dari yang terbaru
            $data = $model->where('is_active', 1)
                          ->orderBy('id', 'DESC')
                          ->findAll();

            // Modifikasi data untuk menambahkan URL lengkap ke gambar
            $finalData = [];
            foreach ($data as $row) {
                // PERBAIKAN: Pastikan kolom 'image' tidak kosong sebelum membuat URL
                $imageUrl = null; // Defaultnya null
                if (!empty($row['image'])) {
                    // Hanya buat URL jika ada nama file gambar
                    $imageUrl = base_url('uploads/tips/' . $row['image']);
                }

                $finalData[] = [
                    'id'         => $row['id'],
                    'title'      => $row['title'],
                    'content'    => $row['content'],
                    // 'target_app' => $row['target_app'], // Bisa diaktifkan jika perlu
                    'image_url'  => $imageUrl, 
                    'created_at' => $row['created_at']
                ];
            }

            // PERBAIKAN: Menggunakan format respons standar yang lebih informatif
            return $this->respond([
                'status'  => true, // Gunakan boolean true/false untuk status
                'message' => 'Data tips berhasil diambil.',
                'data'    => $finalData
            ]);

        } catch (\Exception $e) {
            // Jika terjadi error (misal: koneksi db gagal), kirim error 500
            // Menambahkan pesan error asli untuk mempermudah debug
            return $this->failServerError('Terjadi kesalahan pada server: ' . $e->getMessage());
        }
    }

    /**
     * =======================================================================
     * FUNGSI UNTUK MENGAMBIL DATA BANNER
     * Rute: GET /api/content/banners
     * =======================================================================
     */
    public function banners()
    {
        try {
            $model = new BannerModel();
            $data = $model->where('is_active', 1)->orderBy('id', 'DESC')->findAll();

            $finalData = [];
            foreach ($data as $row) {
                $imageUrl = null;
                if (!empty($row['image'])) {
                    $imageUrl = base_url('uploads/banners/' . $row['image']);
                }

                $finalData[] = [
                    'id'        => $row['id'],
                    'image_url' => $imageUrl,
                    // 'target_app' => $row['target_app'] // Bisa diaktifkan jika perlu
                ];
            }

            // PERBAIKAN: Menggunakan format respons standar
            return $this->respond([
                'status'  => true,
                'message' => 'Data banner berhasil diambil.',
                'data'    => $finalData
            ]);

        } catch (\Exception $e) {
            // Menambahkan pesan error asli untuk mempermudah debug
            return $this->failServerError('Terjadi kesalahan pada server: ' . $e->getMessage());
        }
    }
}
