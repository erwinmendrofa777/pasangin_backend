<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TipsModel;

class Tips extends BaseController
{
    protected $tipsModel;

    public function __construct()
    {
        $this->tipsModel = new TipsModel();
    }

    // --------------------------------------------------------------------
    // 1. LIST DATA (INDEX)
    // --------------------------------------------------------------------
    public function index()
    {
        $data = [
            'title' => 'Kelola Tips & Tricks',
            'tips'  => $this->tipsModel->orderBy('id', 'DESC')->findAll()
        ];
        return view('admin/tips/index', $data);
    }

    // --------------------------------------------------------------------
    // 2. FORM TAMBAH (CREATE) --> INI YANG HILANG TADI
    // --------------------------------------------------------------------
    public function create()
    {
        return view('admin/tips/create', ['title' => 'Tambah Tips']);
    }

    // --------------------------------------------------------------------
    // 3. PROSES SIMPAN (STORE) --> INI VALIDASI YANG SUDAH DIPERBAIKI
    // --------------------------------------------------------------------
    public function store()
    {
        // Validasi
        if (!$this->validate([
            'image' => [
                // Support JPG, JPEG, PNG, WEBP. Max 5MB.
                'rules' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,5048]',
                'errors' => [
                    'uploaded' => 'Gambar wajib diupload',
                    'is_image' => 'File bukan gambar valid',
                    'mime_in'  => 'Format harus JPG, PNG, atau WEBP',
                    'max_size' => 'Maksimal 5MB'
                ]
            ],
            'title' => 'required',
            'content' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload Gambar
        $file = $this->request->getFile('image');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/tips', $newName);

            // Simpan DB
            $this->tipsModel->insert([
                'title'      => $this->request->getPost('title'),
                'content'    => $this->request->getPost('content'),
                'target_app' => $this->request->getPost('target_app'),
                'image'      => $newName,
                'is_active'  => 1
            ]);

            // Redirect pakai slash depan biar aman
            return redirect()->to('/admin/tips')->with('success', 'Tips & Tricks berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal upload gambar.');
        }
    }

    // --------------------------------------------------------------------
    // 4. PROSES HAPUS (DELETE)
    // --------------------------------------------------------------------
    public function delete($id)
    {
        $tips = $this->tipsModel->find($id);
        if ($tips) {
            // Hapus file fisik
            $path = 'uploads/tips/' . $tips['image'];
            if (file_exists($path)) {
                unlink($path);
            }
            $this->tipsModel->delete($id);
            return redirect()->to('/admin/tips')->with('success', 'Data berhasil dihapus!');
        }
        return redirect()->to('/admin/tips')->with('error', 'Data tidak ditemukan');
    }
}
