<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VoucherModel;

class Voucher extends BaseController
{    protected $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
    }

    // 1. TAMPILKAN LIST VOUCHER (Halaman Utama)
    public function index()
    {
        $data = [
            'title' => 'Kelola Voucher',
            'vouchers' => $this->voucherModel->findAll()
        ];
        // Pastikan kamu punya file layout, kalau belum sesuaikan view-nya
        return view('admin/voucher/index', $data);
    }

    // 2. FORM TAMBAH VOUCHER
    public function create()
    {
        $data = ['title' => 'Tambah Voucher Baru'];
        return view('admin/voucher/create', $data);
    }

    // 3. PROSES SIMPAN DATA
    public function store()
    {
        // Validasi Input
        if (!$this->validate([
            'code' => 'required|is_unique[vouchers.code]',
            'name' => 'required',
            'discount_nominal' => 'required|numeric',
            'valid_until' => 'required',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]' // Wajib ada gambar
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload Gambar
        $fileImage = $this->request->getFile('image');
        $imageName = $fileImage->getRandomName();
        $fileImage->move('uploads/vouchers', $imageName);

        // Simpan ke Database
        $this->voucherModel->save([
            'code' => strtoupper($this->request->getPost('code')),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'discount_nominal' => $this->request->getPost('discount_nominal'),
            'valid_until' => $this->request->getPost('valid_until'),
            'is_active' => 1, // Default aktif
            'image' => $imageName
        ]);

        return redirect()->to('/admin/vouchers')->with('success', 'Voucher berhasil dibuat!');
    }

    // 4. HAPUS VOUCHER
    public function delete($id)
    {
        // Hapus gambar lama dulu (opsional, biar bersih)
        $voucher = $this->voucherModel->find($id);
        if ($voucher['image'] != '' && file_exists('uploads/vouchers/' . $voucher['image'])) {
            unlink('uploads/vouchers/' . $voucher['image']);
        }

        $this->voucherModel->delete($id);
        return redirect()->to('/admin/vouchers')->with('success', 'Voucher dihapus.');
    }
}
