<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BannerModel;
use App\Models\TipsModel;

class Content extends BaseController
{
    // --- HALAMAN INPUT BANNER ---
    public function banner_create()
    {
                return view('admin/banner/create');
    }

    // --- PROSES SIMPAN BANNER ---
    public function banner_store()
    {
        $model = new BannerModel();
        
        $file = $this->request->getFile('image');
        $target = $this->request->getPost('target_app'); // 'client' atau 'tukang'

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            // Pindahkan ke folder public/uploads/banners agar bisa diakses App kawan
            $file->move(FCPATH . 'uploads/banners', $newName);

            $model->insert([
                'title'      => $this->request->getPost('title'),
                'image'      => $newName,
                'target_app' => $target,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to(base_url('admin/banner/create'))->with('success', 'Banner Berhasil Diupload untuk aplikasi ' . $target);
        }

        return redirect()->back()->with('error', 'Gagal upload gambar. Pastikan file terpilih dan formatnya benar.');
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

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/tips', $newName);

            $model->insert([
                'title'      => $this->request->getPost('title'),
                'content'    => $this->request->getPost('content'),
                'image'      => $newName,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to(base_url('admin/tips/create'))->with('success', 'Tips Berhasil Diupload');
        }

        return redirect()->back()->with('error', 'Gagal upload gambar tips');
    }
}