<?php

namespace App\Modules\Tukang\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Tukang\Services\TukangService;
use RuntimeException;

/**
 * Tukang Controller — Admin
 *
 * Berperan sebagai "polisi lalu lintas":
 *   1. Terima request dari user
 *   2. Cek permission
 *   3. Validasi input (HTTP layer)
 *   4. Delegasikan ke TukangService untuk logika bisnis
 *   5. Kembalikan response (redirect / view)
 *
 * TIDAK ADA upload file, raw query, atau logika bisnis di sini.
 * Semua itu ada di App\Modules\Tukang\Services\TukangService.
 */
class Tukang extends BaseController
{
    protected TukangService $tukangService;

    public function __construct()
    {
        $this->tukangService = new TukangService();
        helper(['form', 'url']);
    }

    // -------------------------------------------------------------------------
    // 1. LIST TUKANG
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('tukang')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data tukang/mitra.');
        }

        return view('App\Modules\Tukang\Views\index', [
            'title'              => 'Daftar Tukang / Mitra',
            'tukang'             => $this->tukangService->getAllTukangWithRating(),
            'constructionTargets' => $this->tukangService->getGroupedConstructionTargets(),
            'groupTransactions'  => $this->tukangService->getGroupTransactionsForAdmin(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 2. DETAIL TUKANG
    // -------------------------------------------------------------------------
    public function detail($id)
    {
        if (!can('tukang')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat data tukang/mitra.');
        }

        try {
            $tukang = $this->tukangService->findTukangWithRatings((int) $id);
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('admin/tukang'))->with('error', $e->getMessage());
        }

        return view('App\Modules\Tukang\Views\detail', [
            'title' => 'Detail Mitra Tukang - ' . $tukang['name'],
            'tukang' => $tukang,
            'ratings' => $tukang['ratings'],
        ]);
    }

    // -------------------------------------------------------------------------
    // 3. FORM TAMBAH TUKANG
    // -------------------------------------------------------------------------
    public function create()
    {
        if (!can('tukang_create')) {
            return redirect()->to('/admin/tukang')->with('error', 'Anda tidak memiliki akses untuk menambah tukang/mitra.');
        }

        $skillModel = new \App\Modules\Tukang\Models\TukangSkillModel();
        $skills = $skillModel->orderBy('skill_name', 'ASC')->findAll();

        return view('App\Modules\Tukang\Views\create', [
            'title' => 'Tambah Mitra Tukang Baru',
            'skills' => $skills
        ]);
    }

    // -------------------------------------------------------------------------
    // 4. SIMPAN TUKANG BARU
    // -------------------------------------------------------------------------
    public function store()
    {
        if (!can('tukang_create')) {
            return redirect()->to('/admin/tukang')->with('error', 'Anda tidak memiliki akses untuk menambah tukang/mitra.');
        }

        // FIX: Jangan merging file ke data POST. Gunakan validate()
        if (!$this->validate('tukangSave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->tukangService->createTukang(
                $this->request->getPost(),
                [
                    'profile_photo' => $this->request->getFile('profile_photo'),
                    'ktp_photo' => $this->request->getFile('ktp_photo'),
                    'selfie_photo' => $this->request->getFile('selfie_photo'),
                ]
            );

            log_admin_activity('create', 'tukang', 'Membuat mitra tukang baru');
            return redirect()->to(base_url('admin/tukang'))->with('success', 'Mitra Tukang berhasil didaftarkan!');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 5. UPDATE VERIFIKASI
    // -------------------------------------------------------------------------
    public function update_verify()
    {
        if (!can('tukang_verify')) {
            return redirect()->to('/admin/tukang')->with('error', 'Anda tidak memiliki akses untuk mengubah status verifikasi tukang/mitra.');
        }

        if (!$this->validateData($this->request->getPost(), 'tukangUpdateVerify')) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->tukangService->updateVerify(
                (int) $this->request->getPost('id'),
                (int) $this->request->getPost('is_verify')
            );

            log_admin_activity('update', 'tukang', 'update status verifikasi tukang');
            return redirect()->back()->with('success', 'Status verifikasi berhasil diperbarui!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 6. UPDATE STATUS
    // -------------------------------------------------------------------------
    public function update_status()
    {
        if (!can('tukang_status')) {
            return redirect()->to('/admin/tukang')->with('error', 'Anda tidak memiliki akses untuk mengubah status tukang/mitra.');
        }

        if (!$this->validateData($this->request->getPost(), 'tukangUpdateStatus')) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->tukangService->updateStatus(
                (int) $this->request->getPost('id'),
                $this->request->getPost('status')
            );

            log_admin_activity('update', 'tukang', 'mengganti status tukang');
            return redirect()->back()->with('success', 'Status mitra tukang berhasil diperbarui!');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 7. HAPUS TUKANG
    // -------------------------------------------------------------------------
    public function delete($id)
    {
        if (!can('tukang_delete')) {
            return redirect()->to('/admin/tukang')->with('error', 'Anda tidak memiliki akses untuk menghapus tukang/mitra.');
        }

        try {
            $this->tukangService->deleteTukang((int) $id);

            log_admin_activity('delete', 'tukang', 'menghapus tukang');
            return redirect()->to(base_url('admin/tukang'))->with('success', 'Data Mitra Tukang berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->to(base_url('admin/tukang'))->with('error', $e->getMessage());
        }
    }
}
