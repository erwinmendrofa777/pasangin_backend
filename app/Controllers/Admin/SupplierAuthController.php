<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SupplierModel;

class SupplierAuthController extends BaseController
{
    public function login()
    {
        if (session()->get('is_supplier_logged_in')) {
            return redirect()->to(site_url('supplier/dashboard'));
        }
        return view('admin/supplier_login');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $supplierModel = new SupplierModel();

        // ▼▼▼ BARIS YANG DIPERBAIKI ADA DI SINI ▼▼▼
        $supplier = $supplierModel->where('email', $email)->first();

        // Memeriksa email, password, dan status 'approved'
        if (!$supplier || !password_verify($password, $supplier['password']) || $supplier['status'] !== 'approved') {
            return redirect()->back()->withInput()->with('error', 'Email, password, atau status akun tidak valid.');
        }

        $sessionData = [
            'supplier_id'             => $supplier['id'],
            'supplier_name'           => $supplier['name'],
            'supplier_email'          => $supplier['email'],
            'is_supplier_logged_in'   => true,
        ];
        session()->set($sessionData);

        return redirect()->to(site_url('supplier/dashboard'));
    }

    public function register()
    {
        return view('admin/supplier_register');
    }

    public function attemptRegister()
    {
        $rules = [
            'name'         => 'required|min_length[3]',
            'email'        => 'required|valid_email|is_unique[suppliers.email]',
            'password'     => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataToSave = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'   => 'pending',
        ];

        $supplierModel = new SupplierModel();
        if ($supplierModel->save($dataToSave)) {
            return redirect()->to(site_url('supplier/login'))->with('success', 'Pendaftaran berhasil! Akun Anda akan segera diverifikasi oleh Admin.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('supplier/login'));
    }
}
