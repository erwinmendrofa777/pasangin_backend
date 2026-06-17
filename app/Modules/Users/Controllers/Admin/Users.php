<?php

namespace App\Modules\Users\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Users\Services\UserService;
use RuntimeException;

/**
 * Users Controller — Admin
 *
 * Berperan sebagai "polisi lalu lintas":
 *   1. Terima request dari user
 *   2. Cek permission
 *   3. Validasi input dasar
 *   4. Delegasikan ke UserService untuk logika bisnis
 *   5. Kembalikan response (redirect / view)
 *
 * TIDAK ADA logika bisnis, file handling, atau sanitasi data di sini.
 * Semua itu ada di App\Modules\Users\Services\UserService.
 */
class Users extends BaseController
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    // -------------------------------------------------------------------------
    // 1. HALAMAN UTAMA (LIST DATA)
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('users')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data user.');
        }

        $data['users'] = $this->userService->getAllClientsWithCounts();

        return view('App\Modules\Users\Views\index', $data);
    }

    // -------------------------------------------------------------------------
    // 2. HAPUS USER
    // -------------------------------------------------------------------------
    public function delete($id)
    {
        if (!can('users_delete')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak memiliki akses untuk menghapus user.');
        }

        try {
            $this->userService->deleteUser((int) $id, (int) session()->get('user_id'));
            log_admin_activity('delete', 'user', 'menghapus user');
            return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 3. DETAIL USER
    // -------------------------------------------------------------------------
    public function detail($id = null)
    {
        if (!can('users')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data user.');
        }

        try {
            $user = $this->userService->findUserOrFail((int) $id);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/users')->with('error', $e->getMessage());
        }

        return view('App\Modules\Users\Views\detail', [
            'title' => 'Detail User: ' . $user['full_name'],
            'user' => $user,
        ]);
    }

    // -------------------------------------------------------------------------
    // 4. UPDATE STATUS (approved / rejected / banned / pending)
    // -------------------------------------------------------------------------
    public function update_Status($id, $status)
    {
        if (!can('users_status')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak memiliki akses untuk mengubah status user.');
        }

        // Validasi status menggunakan grup 'userUpdateStatus'
        if (!$this->validateData(['status' => $status], 'userUpdateStatus')) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->userService->updateStatus((int) $id, $status);
            log_admin_activity('update_status', 'user', 'mengganti status user');
            return redirect()->to('/admin/users/detail/' . $id)
                ->with('success', 'Status user berhasil diubah menjadi ' . ucfirst($status) . '.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 5. HALAMAN EDIT USER
    // -------------------------------------------------------------------------
    public function edit($id = null)
    {
        if (!can('users_edit')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak memiliki akses untuk mengedit user.');
        }

        try {
            $user = $this->userService->findUserOrFail((int) $id);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/users')->with('error', $e->getMessage());
        }

        return view('App\Modules\Users\Views\edit', [
            'title' => 'Edit User: ' . $user['full_name'],
            'user' => $user,
        ]);
    }

    // -------------------------------------------------------------------------
    // 6. PROSES UPDATE USER
    // -------------------------------------------------------------------------
    public function update($id = null)
    {
        if (!can('users_edit')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak memiliki akses untuk mengedit user.');
        }

        // Siapkan data untuk divalidasi (termasuk ID untuk placeholder rule is_unique)
        $dataToValidate = $this->request->getPost();
        $dataToValidate['id'] = $id;

        // Validasi menggunakan grup 'userUpdate' yang ada di Config/Validation.php
        if (!$this->validateData($dataToValidate, 'userUpdate')) {
            $errors = implode(' ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        try {
            $this->userService->updateUser(
                (int) $id,
                $this->request->getPost(),
                $this->request->getFile('avatar')
            );

            log_admin_activity('update', 'user', 'mengedit data user');
            return redirect()->to('/admin/users')->with('success', 'Data user berhasil diperbarui!');
        } catch (RuntimeException $e) {
            log_message('error', '[Users::update] ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data.');
        }
    }

    // -------------------------------------------------------------------------
    // 7. AJAX ENDPOINT: GET ORDERS
    // -------------------------------------------------------------------------
    public function get_orders($userId)
    {
        if (!can('users')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki akses.']);
        }

        $db = \Config\Database::connect();
        $orders = $db->table('orders')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $orders
        ]);
    }

    // -------------------------------------------------------------------------
    // 8. AJAX ENDPOINT: GET PROJECTS
    // -------------------------------------------------------------------------
    public function get_projects($userId)
    {
        if (!can('users')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki akses.']);
        }

        $db = \Config\Database::connect();
        $construction = $db->table('construction_requests')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $design = $db->table('design_requests')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $renovation = $db->table('renovation_requests')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'construction' => $construction,
                'design'       => $design,
                'renovation'   => $renovation
            ]
        ]);
    }
}
