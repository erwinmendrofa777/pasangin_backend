<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BannerModel;

class Banner extends BaseController
{
    protected $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new BannerModel();
    }

    // --------------------------------------------------------------------
    // 1. HALAMAN UTAMA (LIST DATA)
    // --------------------------------------------------------------------
    public function index()
    {
        $data = [
            'title'   => 'Kelola Banner',
            'banners' => $this->bannerModel->orderBy('id', 'DESC')->findAll()
        ];
        return view('admin/banner/index', $data);
    }

    // --------------------------------------------------------------------
    // 2. HALAMAN FORM TAMBAH
    // --------------------------------------------------------------------
    public function create()
    {
        return view('admin/banner/create', ['title' => 'Tambah Banner']);
    }

    // --------------------------------------------------------------------
    // 3. PROSES SIMPAN DATA (CREATE)
    // --------------------------------------------------------------------
    public function store()
    {
        // A. Validasi Input (Wajib Gambar)
        if (!$this->validate([
            'image' => [
                'rules' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
                'errors' => [
                    'uploaded' => 'Pilih gambar terlebih dahulu',
                    'is_image' => 'File yang dipilih bukan gambar',
                    'mime_in'  => 'Format gambar harus JPG, JPEG, atau PNG',
                    'max_size' => 'Ukuran gambar maksimal 2MB'
                ]
            ]
        ])) {
            // Kalau gagal, balik lagi ke form bawa error-nya
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // B. Proses Upload Gambar
        $file = $this->request->getFile('image');
        
        if ($file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName(); // Generate nama acak biar unik
            $file->move('uploads/banners', $newName); // Pindah ke folder public/uploads/banners

            // C. Simpan ke Database
            $this->bannerModel->insert([
                'title'      => $this->request->getPost('title'),
                'target_app' => $this->request->getPost('target_app'), // client atau tukang
                'image'      => $newName,
                'is_active'  => 1 // Default Aktif
            ]);

            // D. Redirect Sukses (FIX: Pakai Slash Depan)
            return redirect()->to('/admin/banner')->with('success', 'Banner berhasil ditambahkan!');
        }

        return redirect()->back()->with('error', 'Gagal mengupload gambar. Silakan coba lagi.');
    }

    // --------------------------------------------------------------------
    // 4. PROSES HAPUS DATA (DELETE)
    // --------------------------------------------------------------------
    public function delete($id)
    {
        // Cari data dulu
        $banner = $this->bannerModel->find($id);

        if ($banner) {
            // Hapus file fisik gambar jika ada (Biar server gak penuh sampah)
            $filePath = 'uploads/banners/' . $banner['image'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Hapus record di database
            $this->bannerModel->delete($id);
            
            // Redirect Sukses (FIX: Pakai Slash Depan)
            return redirect()->to('/admin/banner')->with('success', 'Banner berhasil dihapus!');
        }

        return redirect()->to('/admin/banner')->with('error', 'Data banner tidak ditemukan.');
    }
}
