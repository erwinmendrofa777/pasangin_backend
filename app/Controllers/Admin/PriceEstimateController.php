<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PriceEstimateConceptsModel;
use App\Models\PriceEstimateQualitiesModel;

class PriceEstimateController extends BaseController
{
    protected $conceptsModel;
    protected $qualitiesModel;

    public function __construct()
    {
        $this->conceptsModel = new PriceEstimateConceptsModel();
        $this->qualitiesModel = new PriceEstimateQualitiesModel();
        helper(['form', 'url']);
    }

    /**
     * Menampilkan daftar semua konsep dan kualitasnya.
     * Metode ini akan mengambil semua data dan mengirimkannya ke view.
     */
    public function index()
    {
        $concepts = $this->conceptsModel->orderBy('created_at', 'ASC')->findAll();

        foreach ($concepts as &$concept) {
            $concept['qualities'] = $this->qualitiesModel->where('concept_id', $concept['id'])->orderBy('min_price', 'ASC')->findAll();
        }

        $data = [
            'title' => 'Manajemen Estimasi Harga',
            'concepts' => $concepts
        ];

        // Mengarahkan ke view yang akan kita buat.
        return view('admin/price_estimate/index', $data);
    }

    // --- Metode untuk Konsep ---

    /**
     * Menyimpan konsep baru ke database.
     */
    public function storeConcept()
    {
        if ($this->conceptsModel->insert($this->request->getPost())) {
            return redirect()->to('admin/price-estimate')->with('message', 'Konsep berhasil ditambahkan.');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->conceptsModel->errors());
    }

    /**
     * Memperbarui konsep yang sudah ada.
     */
    public function updateConcept($id)
    {
        if ($this->conceptsModel->update($id, $this->request->getPost())) {
            return redirect()->to('admin/price-estimate')->with('message', 'Konsep berhasil diperbarui.');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->conceptsModel->errors());
    }

    /**
     * Menghapus konsep beserta semua kualitas yang terkait.
     */
    public function deleteConcept($id)
    {
        // Hapus dulu semua kualitas yang terkait dengan konsep ini
        $this->qualitiesModel->where('concept_id', $id)->delete();

        if ($this->conceptsModel->delete($id)) {
            return redirect()->to('admin/price-estimate')->with('message', 'Konsep dan kualitas terkait berhasil dihapus.');
        }
        
        return redirect()->to('admin/price-estimate')->with('error', 'Gagal menghapus konsep.');
    }

    // --- Metode untuk Kualitas ---

    /**
     * Menyimpan kualitas baru untuk sebuah konsep.
     */
    public function storeQuality()
    {
        $rules = [
            'concept_id' => 'required|is_not_unique[price_estimate_concepts.id]',
            'label' => 'required|string|max_length[255]',
            'description' => 'required|string|max_length[255]',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric|greater_than_equal_to['.$this->request->getPost('min_price').']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($this->qualitiesModel->insert($this->request->getPost())) {
            return redirect()->to('admin/price-estimate')->with('message', 'Kualitas berhasil ditambahkan.');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->qualitiesModel->errors());
    }

    /**
     * Memperbarui kualitas yang sudah ada.
     */
    public function updateQuality($id)
    {
        if ($this->qualitiesModel->update($id, $this->request->getPost())) {
            return redirect()->to('admin/price-estimate')->with('message', 'Kualitas berhasil diperbarui.');
        }
        
        return redirect()->back()->withInput()->with('errors', $this->qualitiesModel->errors());
    }

    /**
     * Menghapus satu data kualitas.
     */
    public function deleteQuality($id)
    {
        if ($this->qualitiesModel->delete($id)) {
            return redirect()->to('admin/price-estimate')->with('message', 'Kualitas berhasil dihapus.');
        }
        
        return redirect()->to('admin/price-estimate')->with('error', 'Gagal menghapus kualitas.');
    }
}