<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VoucherModel;

class Voucher extends BaseController
{
    protected $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
        helper(['form', 'url']);
    }

    // Tampilkan Tabel Voucher
    public function index()
    {
        $data = [
            'title'    => 'Kelola Voucher',
            'vouchers' => $this->voucherModel->findAll()
        ];
        return view('admin/voucher/index', $data);
    }

    // Buka Form Tambah (Yang tadi Error 404)
    public function create()
    {
        $data = ['title' => 'Tambah Voucher Baru'];
        return view('admin/voucher/create', $data);
    }

    // Proses Simpan ke Database
    public function store()
    {
        $rules = [
            'code'             => 'required|is_unique[vouchers.code]',
            'name'             => 'required',
            'discount_nominal' => 'required|numeric',
            'valid_until'      => 'required',
            'image'            => 'uploaded[image]|max_size[image,2048]|is_image[image]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fileImage = $this->request->getFile('image');
        $imageName = $fileImage->getRandomName();
        $fileImage->move('uploads/vouchers', $imageName);

        $this->voucherModel->save([
            'code'             => strtoupper($this->request->getPost('code')),
            'name'             => $this->request->getPost('name'),
            'description'      => $this->request->getPost('description'),
            'discount_nominal' => $this->request->getPost('discount_nominal'),
            'valid_until'      => $this->request->getPost('valid_until'),
            'is_active'        => 1,
            'image'            => $imageName
        ]);

        return redirect()->to(base_url('admin/vouchers'))->with('success', 'Voucher berhasil dibuat!');
    }

    public function delete($id)
    {
        $voucher = $this->voucherModel->find($id);
        if ($voucher && $voucher['image'] != '' && file_exists('uploads/vouchers/' . $voucher['image'])) {
            unlink('uploads/vouchers/' . $voucher['image']);
        }

        $this->voucherModel->delete($id);
        return redirect()->to(base_url('admin/vouchers'))->with('success', 'Voucher berhasil dihapus.');
    }
}