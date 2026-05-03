<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\SupplierBannerModel;
use Exception;

class SupplierBannerController extends BaseController
{
    use ResponseTrait;
    protected $supplierBanner;

    public function __construct()
    {
        $this->supplierBanner = new SupplierBannerModel();
    }

    /**
     * Helper to get supplier ID from JWT Token
     */
    private function getSupplierId()
    {
        try {
            $authHeader = $this->request->getHeaderLine('Authorization');
            if (empty($authHeader))
                return null;

            $token = str_replace('Bearer ', '', $authHeader);
            $tokenParts = explode('.', $token);
            if (count($tokenParts) != 3)
                return null;

            $payload = json_decode(base64_decode($tokenParts[1]), true);
            return $payload['uid'] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * List all banners for the authenticated supplier
     */
    public function index()
    {
        $id_supplier = $this->getSupplierId();
        if (!$id_supplier) {
            return $this->failUnauthorized('Supplier tidak ditemukan.');
        }

        $banners = $this->supplierBanner->where('id_supplier', $id_supplier)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($banners as &$banner) {
            $banner['image_url'] = base_url('uploads/supplier/banner/' . $banner['image']);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Data banner berhasil dimuat.',
            'data' => $banners,
        ]);
    }

    /**
     * Create a new banner with image upload
     */
    public function create()
    {
        $id_supplier = $this->getSupplierId();
        if (!$id_supplier) {
            return $this->failUnauthorized('Supplier tidak ditemukan.');
        }

        // Validation Rules
        $rules = [
            'title' => 'required|max_length[255]',
            'image' => 'uploaded[image]|mime_in[image,image/jpeg,image/png,image/jpg]|max_size[image,3072]',
        ];

        $messages = [
            'title' => [
                'required' => 'Judul banner wajib diisi.',
                'max_length' => 'Judul banner tidak boleh lebih dari 255 karakter.',
            ],
            'image' => [
                'uploaded' => 'Gambar banner wajib diunggah.',
                'mime_in' => 'Format gambar harus berupa JPEG, JPG, atau PNG.',
                'max_size' => 'Ukuran gambar maksimal 3MB.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            // Handle File Upload
            $img = $this->request->getFile('image');
            $newName = $img->getRandomName();
            $img->move('uploads/supplier/banner/', $newName);

            $data = [
                'id_supplier' => $id_supplier,
                'title' => $this->request->getPost('title'),
                'image' => $newName,
                'status' => 'PENDING'
            ];

            $data['image_url'] = base_url('uploads/supplier/banner/' . $data['image']);


            $save = $this->supplierBanner->insert($data);

            $data['id'] = $save;

            return $this->respondCreated([
                'status' => true,
                'message' => 'Banner supplier berhasil diajukan.',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->failServerError('Terjadi kesalahan saat menyimpan banner.');
        }
    }

    /**
     * Show one banner detail (Public/Client)
     */
    public function show($id = null)
    {
        $id_supplier = $this->getSupplierId();
        if (!$id_supplier) {
            return $this->failUnauthorized('Supplier tidak ditemukan.');
        }

        $banner = $this->supplierBanner->where('id', $id)->where('id_supplier', $id_supplier)->first();
        if (!$banner) {
            return $this->failNotFound('Banner tidak ditemukan');
        }

        $banner['image_url'] = base_url('uploads/supplier/banner/' . $banner['image']);

        return $this->respond([
            'status' => true,
            'data' => $banner
        ]);
    }

    /**
     * Get all approved banners for Client App
     */
    public function getApprovedBanners()
    {
        $this->cleanupExpiredBanners(); // Hapus data yang sudah expired sebelum memuat

        $today = date('Y-m-d');

        $banners = $this->supplierBanner->where('status', 'APPROVED')
            ->where('start_date <=', $today)
            ->where('end_date >=', $today)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        foreach ($banners as &$banner) {
            $banner['image_url'] = base_url('uploads/supplier/banner/' . $banner['image']);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Banner berhasil dimuat.',
            'data' => $banners
        ]);
    }

    /**
     * Update an existing banner
     */
    public function update($id = null)
    {
        $id_supplier = $this->getSupplierId();
        $banner = $this->supplierBanner->find($id);

        if (!$banner || $banner['id_supplier'] != $id_supplier) {
            return $this->failNotFound('Banner tidak ditemukan.');
        }

        $rules = [
            'title' => 'required|max_length[255]',
        ];

        // Optional image update
        $img = $this->request->getFile('image');
        if ($img && $img->isValid()) {
            $rules['image'] = 'mime_in[image,image/jpeg,image/png,image/jpg]|max_size[image,3072]';
        }

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $data = [
                'title' => $this->request->getPost('title'),
                'status' => 'PENDING' // Reset to pending on edit
            ];

            if ($img && $img->isValid() && !$img->hasMoved()) {
                // Remove old image
                if (file_exists('uploads/supplier/banner/' . $banner['image'])) {
                    unlink('uploads/supplier/banner/' . $banner['image']);
                }

                $newName = $img->getRandomName();
                $img->move('uploads/supplier/banner/', $newName);
                $data['image'] = $newName;
                $data['image_url'] = base_url('uploads/supplier/banner/' . $data['image']);
            }

            $this->supplierBanner->update($id, $data);

            $data['id'] = $id;

            return $this->respond([
                'status' => true,
                'message' => 'Banner supplier berhasil diperbarui.',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->failServerError('Gagal memperbarui data banner.');
        }
    }

    /**
     * Delete a banner and its file
     */
    public function delete($id = null)
    {
        $id_supplier = $this->getSupplierId();
        $banner = $this->supplierBanner->find($id);

        if (!$banner || $banner['id_supplier'] != $id_supplier) {
            return $this->failNotFound('Banner tidak ditemukan.');
        }

        try {
            // Delete associated file
            if (file_exists('uploads/supplier/banner/' . $banner['image'])) {
                unlink('uploads/supplier/banner/' . $banner['image']);
            }

            $this->supplierBanner->delete($id);

            return $this->respondDeleted([
                'status' => true,
                'message' => 'Banner supplier berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return $this->failServerError('Gagal menghapus banner.');
        }
    }
    /**
     * Delete banners that have passed their end_date
     */
    private function cleanupExpiredBanners()
    {
        $today = date('Y-m-d');

        // Cari banner yang sudah lewat tanggal berakhirnya (end_date < today)
        // Pastikan field end_date tidak NULL untuk menghindari penghapusan yang tidak diinginkan
        $expiredBanners = $this->supplierBanner->where('end_date <', $today)
            ->where('end_date !=', null)
            ->findAll();

        foreach ($expiredBanners as $banner) {
            // Hapus file gambar secara fisik dari server
            $filePath = 'uploads/supplier/banner/' . $banner['image'];
            if (!empty($banner['image']) && file_exists($filePath)) {
                unlink($filePath);
            }
            // Hapus data dari database
            $this->supplierBanner->delete($banner['id']);
        }
    }
}