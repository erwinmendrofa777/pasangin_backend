<?php

namespace App\Modules\AboutApplication\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\AboutApplication\Services\AboutApplicationPasanginService;
use RuntimeException;

class AboutApplicationPasanginController extends BaseController
{
    protected AboutApplicationPasanginService $svc;

    // ID tetap = 1 karena data "About Application" hanya ada satu record
    private const RECORD_ID = 1;

    public function __construct()
    {
        $this->svc = new AboutApplicationPasanginService();
    }

    /**
     * Halaman utama: tampilkan data & form edit
     */
    public function index()
    {
        if (!can('about_application')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        $data = $this->svc->getById(self::RECORD_ID);

        return view('App\Modules\AboutApplication\Views\index', [
            'title' => 'Tentang Aplikasi Pasangin',
            'data' => $data,
        ]);
    }

    /**
     * Simpan / perbarui data About Application
     */
    public function update()
    {
        if (!can('about_application_update')) {
            return redirect()->to('/admin/about_application')->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
        }

        $post = $this->request->getPost();

        $validation = [
            'description' => 'required|min_length[10]',
        ];
        $messages = [
            'description' => [
                'required' => 'Deskripsi wajib diisi.',
                'min_length' => 'Deskripsi terlalu pendek (minimal 10 karakter).',
            ],
        ];

        if (!$this->validateData($post, $validation, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $existing = $this->svc->getById(self::RECORD_ID);

            if ($existing) {
                // Update record yang sudah ada
                $this->svc->update(self::RECORD_ID, $post);
                log_admin_activity('update', 'Tentang Aplikasi', 'Update Data Tentang Aplikasi');
            } else {
                // Buat record baru jika belum ada
                $this->svc->create($post);
                log_admin_activity('create', 'Tentang Aplikasi', 'Tambah Data Tentang Aplikasi');
            }

            return redirect()->to('/admin/about_application')->with('success', 'Data berhasil diperbarui.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
