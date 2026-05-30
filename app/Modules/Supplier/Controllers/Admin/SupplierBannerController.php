<?php

namespace App\Modules\Supplier\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Supplier\Services\SupplierBannerService;
use App\Modules\Notifications\Services\NotificationService;
use RuntimeException;

class SupplierBannerController extends BaseController
{
    protected SupplierBannerService $svc;
    protected NotificationService $notifService;

    public function __construct()
    {
        $this->svc = new SupplierBannerService();
        $this->notifService = new NotificationService();
    }

    public function index()
    {
        if (!can('banner_supplier')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat banner supplier.');
        }
        return view('App\Modules\Supplier\Views\banner_supplier/index', [
            'title' => 'Banner Supplier',
            'banners' => $this->svc->getAllWithSupplier(),
        ]);
    }

    public function add()
    {
        if (!can('banner_supplier_create')) {
            return redirect()->to('/admin/banner-supplier')->with('error', 'Akses ditolak.');
        }
        return view('App\Modules\Supplier\Views\banner_supplier/add', [
            'title' => 'Tambah Banner Supplier',
            'suppliers' => $this->svc->getAllSuppliers(),
        ]);
    }

    public function save()
    {
        if (!can('banner_supplier_create')) {
            return redirect()->to('/admin/banner-supplier')->with('error', 'Akses ditolak.');
        }


        // FIX: Jangan merging file ke data POST. Gunakan validate()
        if (!$this->validate('supplierBannerSave')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->svc->store($this->request->getPost(), $this->request->getFile('image'));
            log_admin_activity('create', 'banner suppier', 'menambahkan pengajuan banner supplier');
            return redirect()->to('/admin/banner-supplier')->with('success', 'Banner berhasil ditambahkan.');
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!can('banner_supplier_update')) {
            return redirect()->to('/admin/banner-supplier')->with('error', 'Akses ditolak.');
        }
        try {
            return view('App\Modules\Supplier\Views\banner_supplier/edit', [
                'title' => 'Edit Banner Supplier',
                'banner' => $this->svc->findOrFail((int) $id),
                'suppliers' => $this->svc->getAllSuppliers(),
            ]);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/banner-supplier')->with('error', $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!can('banner_supplier_update')) {
            return redirect()->to('/admin/banner-supplier')->with('error', 'Akses ditolak.');
        }

        // FIX: Jangan merging file ke data POST. Gunakan validate()
        if (!$this->validate('supplierBannerUpdate')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->svc->update((int) $id, $this->request->getPost(), $this->request->getFile('image'));
            log_admin_activity('update', 'banner suppier', 'mengupdate pengajuan banner supplier');
            return redirect()->to('/admin/banner-supplier')->with('success', 'Banner berhasil diperbarui.');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/banner-supplier')->with('error', $e->getMessage());
        }
    }

    public function detail($id)
    {
        if (!can('banner_supplier')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat banner supplier.');
        }
        try {
            return view('App\Modules\Supplier\Views\banner_supplier/detail', [
                'title' => 'Detail Banner Supplier',
                'banner' => $this->svc->findDetailOrFail((int) $id),
            ]);
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/banner-supplier')->with('error', $e->getMessage());
        }
    }

    public function updateStatus()
    {
        if (!can('banner_supplier_status')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Akses ditolak.']);
        }

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if (!$id || !$status) {
            return $this->response->setJSON(['status' => false, 'message' => 'Parameter tidak lengkap.']);
        }

        try {
            // Ambil data banner untuk mendapatkan id_supplier
            $banner = $this->svc->findOrFail((int) $id);

            // Update status
            $this->svc->updateStatus((int) $id, $status);

            // Kirim Notifikasi ke Supplier
            $title = "Status Banner: " . strtoupper($status);
            $message = "Banner promosi Anda telah diperbarui menjadi " . strtoupper($status) . ".";

            $this->notifService->sendPersonal('supplier', (int) $banner['id_supplier'], $title, $message);

            log_admin_activity('update_status', 'banner suppier', 'mengupdate status pengajuan banner supplier');
            return $this->response->setJSON(['status' => true, 'message' => 'Status banner berhasil diperbarui.']);
        } catch (RuntimeException $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        if (!can('banner_supplier_delete')) {
            return redirect()->to('/admin/banner-supplier')->with('error', 'Anda tidak memiliki akses untuk menghapus banner.');
        }
        try {
            $this->svc->delete((int) $id);
            log_admin_activity('delete', 'banner supplier', 'Menghapus pengajuan banner supplier');
            return redirect()->to('/admin/banner-supplier')->with('success', 'Banner berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->to('/admin/banner-supplier')->with('error', $e->getMessage());
        }
    }
}