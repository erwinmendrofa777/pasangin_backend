<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\PriceEstimateService;
use RuntimeException;

class PriceEstimateController extends BaseController
{
    protected PriceEstimateService $svc;

    public function __construct()
    {
        $this->svc = new PriceEstimateService();
        helper(['form', 'url']);
    }

    /**
     * Menampilkan daftar semua konsep dan kualitasnya.
     */
    public function index()
    {
        if (!can('price-estimate')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }

        $data = $this->svc->getAllWithQualities();

        return view('admin/price_estimate/index', array_merge($data, [
            'title' => 'Manajemen Estimasi Harga'
        ]));
    }

    // --- Metode untuk Konsep ---

    public function storeConcept()
    {
        if (!can('price-estimate_create')) {
            return redirect()->to('/admin/price-estimate')->with('error', 'Anda tidak memiliki akses untuk membuat konsep.');
        }

        if (!$this->validateData($this->request->getPost(), 'estimateConceptSave')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->svc->createConcept($this->request->getPost());
            return redirect()->to('admin/price-estimate')->with('message', 'Konsep berhasil ditambahkan.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateConcept($id)
    {
        if (!can('price-estimate_update')) {
            return redirect()->to('/admin/price-estimate')->with('error', 'Anda tidak memiliki akses untuk mengedit konsep.');
        }

        if (!$this->validateData($this->request->getPost(), 'estimateConceptSave')) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->svc->updateConcept((int)$id, $this->request->getPost());
            return redirect()->to('admin/price-estimate')->with('message', 'Konsep berhasil diperbarui.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function deleteConcept($id)
    {
        if (!can('price-estimate_delete')) {
            return redirect()->to('/admin/price-estimate')->with('error', 'Anda tidak memiliki akses untuk menghapus konsep.');
        }

        try {
            $this->svc->deleteConcept((int)$id);
            return redirect()->to('admin/price-estimate')->with('message', 'Konsep dan kualitas terkait berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->to('admin/price-estimate')->with('error', $e->getMessage());
        }
    }

    // --- Metode untuk Kualitas ---

    public function storeQuality()
    {
        if (!can('price-estimate_create')) {
            return redirect()->to('/admin/price-estimate')->with('error', 'Anda tidak memiliki akses untuk membuat kualitas.');
        }

        if (!$this->validateData($this->request->getPost(), 'estimateQualitySave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validasi tambahan: max_price >= min_price
        if ($this->request->getPost('max_price') < $this->request->getPost('min_price')) {
            return redirect()->back()->withInput()->with('error', 'Harga maksimum tidak boleh lebih kecil dari harga minimum.');
        }

        try {
            $this->svc->createQuality($this->request->getPost());
            return redirect()->to('admin/price-estimate')->with('message', 'Kualitas berhasil ditambahkan.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateQuality($id)
    {
        if (!can('price-estimate_update')) {
            return redirect()->to('/admin/price-estimate')->with('error', 'Anda tidak memiliki akses untuk memperbarui kualitas.');
        }

        if (!$this->validateData($this->request->getPost(), 'estimateQualitySave')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->svc->updateQuality((int)$id, $this->request->getPost());
            return redirect()->to('admin/price-estimate')->with('message', 'Kualitas berhasil diperbarui.');
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function deleteQuality($id)
    {
        if (!can('price-estimate_delete')) {
            return redirect()->to('/admin/price-estimate')->with('error', 'Anda tidak memiliki akses untuk menghapus kualitas.');
        }

        try {
            $this->svc->deleteQuality((int)$id);
            return redirect()->to('admin/price-estimate')->with('message', 'Kualitas berhasil dihapus.');
        } catch (RuntimeException $e) {
            return redirect()->to('admin/price-estimate')->with('error', $e->getMessage());
        }
    }
}
