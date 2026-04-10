<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;use App\Models\BannerModel;
use App\Models\TipsModel;

class Content extends BaseController
{
    // --- HALAMAN INPUT BANNER ---
    public function banner_create()
    {
        // Tampilkan View Form Banner (Kita buat view-nya di Tahap 4)
        return view('admin/banner_form');
    }

    // --- PROSES SIMPAN BANNER ---
    public function banner_store()
    {
        $model = new BannerModel();
        
        // Ambil File Gambar
        $file = $this->request->getFile('image');
        $target = $this->request->getPost('target_app'); // 'client' atau 'tukang'

        // Validasi Upload
        if ($file->isValid() && ! $file->hasMoved()) {
            // Pindahkan file ke folder public/uploads/banners
            $newName = $file->getRandomName();
            $file->move('uploads/banners', $newName);

            // Simpan ke Database
            $model->insert([
                'title'      => $this->request->getPost('title'),
                'image'      => $newName,
                'target_app' => $target, // <-- INI KUNCINYA
                'is_active'  => 1
            ]);

            return redirect()->to('/admin/banner/create')->with('success', 'Banner Berhasil Diupload untuk aplikasi ' . $target);
        }

        return redirect()->back()->with('error', 'Gagal upload gambar');
    }

    // --- HALAMAN INPUT TIPS ---
    public function tips_create()
    {
        return view('admin/tips_form');
    }

    // --- PROSES SIMPAN TIPS ---
    public function tips_store()
    {
        $model = new TipsModel();
        $file = $this->request->getFile('image');

        if ($file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/tips', $newName);

            $model->insert([
                'title'   => $this->request->getPost('title'),
                'content' => $this->request->getPost('content'),
                'image'   => $newName,
                'is_active' => 1
            ]);

            return redirect()->to('/admin/tips/create')->with('success', 'Tips Berhasil Diupload');
        }

        return redirect()->back()->with('error', 'Gagal upload gambar');
    }
}
