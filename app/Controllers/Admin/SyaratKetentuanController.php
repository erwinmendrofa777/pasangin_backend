<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TermsOfAgreementModel;

class SyaratKetentuanController extends BaseController{
    protected $termsOfAgreementModel;

    public function __construct(){
        $this->termsOfAgreementModel = new TermsOfAgreementModel();
    }

    public function index(){
        $data = [
            'title' => 'Syarat & Ketentuan',
            'data' => $this->termsOfAgreementModel->orderBy('id', 'DESC')->findAll()
        ];
        return view('admin/syarat_ketentuan/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Syarat & Ketentuan'
        ];
        return view('admin/syarat_ketentuan/create', $data);
    }

    public function store()
    {
        $rules = [
            'title'       => 'required',
            'description' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->termsOfAgreementModel->save([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description')
        ]);

        return redirect()->to('admin/syarat_ketentuan')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = $this->termsOfAgreementModel->find($id);
        if (!$item) {
            return redirect()->to('admin/syarat_ketentuan')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Syarat & Ketentuan',
            'data'  => $item
        ];
        return view('admin/syarat_ketentuan/edit', $data);
    }

    public function update($id)
    {
        $item = $this->termsOfAgreementModel->find($id);
        if (!$item) {
            return redirect()->to('admin/syarat_ketentuan')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'title'       => 'required',
            'description' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->termsOfAgreementModel->update($id, [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description')
        ]);

        return redirect()->to('admin/syarat_ketentuan')->with('success', 'Data berhasil diperbarui.');
    }

    public function detail($id)
    {
        $item = $this->termsOfAgreementModel->find($id);
        if (!$item) {
            return redirect()->to('admin/syarat_ketentuan')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Syarat & Ketentuan',
            'data'  => $item
        ];
        return view('admin/syarat_ketentuan/detail', $data);
    }

    public function delete($id)
    {
        $item = $this->termsOfAgreementModel->find($id);
        if (!$item) {
            return redirect()->to('admin/syarat_ketentuan')->with('error', 'Data tidak ditemukan.');
        }

        $this->termsOfAgreementModel->delete($id);

        return redirect()->to('admin/syarat_ketentuan')->with('success', 'Data berhasil dihapus.');
    }
}