<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // --------------------------------------------------------------------
    // 1. HALAMAN UTAMA (LIST DATA)
    // --------------------------------------------------------------------
    public function index(){
        $data['users'] = $this->userModel->where('role', 'client')->orderBy('id', 'DESC')->findAll();

        return view('admin/users/index', $data);
    }

    // --------------------------------------------------------------------
    // 2. PROSES HAPUS DATA (DELETE)
    // --------------------------------------------------------------------
    public function delete($id)
    {
        // Cek apakah user sedang menghapus dirinya sendiri
        if ($id == session()->get('user_id')) { 
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        // Proteksi Super Admin
        if ($id == 1) {
            return redirect()->back()->with('error', 'Akun Super Admin tidak boleh dihapus.');
        }

        // Cari data
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        try {
            // Hapus file fisik (hanya jika ada nama filenya)
            if (!empty($user['avatar'])) {
                $filePath = FCPATH . 'uploads/users/' . $user['avatar'];
                
                // Cek file_exists DAN pastikan itu bukan folder
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus record di database
            $this->userModel->delete($id);

            return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            // Log error
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // --------------------------------------------------------------------
    // 3. HALAMAN DETAIL USER
    // --------------------------------------------------------------------
    public function detail($id = null){
        // Pastikan ID tidak kosong
        if (!$id) {
            return redirect()->to('/admin/users')->with('error', 'ID User tidak valid.');
        }

        // Query
        $user = $this->userModel->find($id);

        // Cek apakah data ditemukan
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        // Siapkan data untuk View
        $data = [
            'title'   => 'Detail User: ' . $user['full_name'],
            'user'    => $user
        ];
        return view('admin/users/detail', $data);
    }

    // --------------------------------------------------------------------
    // 4. LOGIKA UPDATE STATUS (APPROVED, REJECTED, BANNED, PENDING)
    // --------------------------------------------------------------------
    public function update_Status($id, $status)
    {
        // Validasi status yang diizinkan untuk mencegah input sembarangan dari URL
        $allowed_statuses = ['approved', 'rejected', 'banned', 'pending'];
        if (!in_array($status, $allowed_statuses)) {
            // Jika status tidak valid, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        }

        // Siapkan data untuk disimpan
        $data = [
            'id' => $id,
            'status' => $status
        ];

        // Simpan perubahan ke database menggunakan model
        $this->userModel->save($data);

        // Buat pesan sukses yang dinamis
        $message = "Status user berhasil diubah menjadi " . ucfirst($status) . ".";

        // Kembalikan ke halaman daftar user dengan pesan sukses
        return redirect()->to('/admin/users')->with('success', $message);
    }
}
