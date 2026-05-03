<?php

namespace App\Services;

use App\Models\SupplierBannerModel;
use App\Models\SupplierModel;
use RuntimeException;

class SupplierBannerService
{
    protected SupplierBannerModel $bannerModel;
    protected SupplierModel       $supplierModel;

    private const PATH = 'uploads/supplier/banner/';

    public function __construct()
    {
        $this->bannerModel   = new SupplierBannerModel();
        $this->supplierModel = new SupplierModel();
    }

    public function getAllWithSupplier(): array
    {
        return $this->bannerModel
            ->select('supplier_banner.*, suppliers.name as supplier_name')
            ->join('suppliers', 'suppliers.id = supplier_banner.id_supplier')
            ->orderBy('supplier_banner.id', 'DESC')
            ->findAll();
    }

    public function getAllSuppliers(): array
    {
        return $this->supplierModel->orderBy('name', 'ASC')->findAll();
    }

    public function findOrFail(int $id): array
    {
        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            throw new RuntimeException('Data tidak ditemukan.');
        }
        return $banner;
    }

    public function findDetailOrFail(int $id): array
    {
        $banner = $this->bannerModel
            ->select('supplier_banner.*, suppliers.name as supplier_name, suppliers.email as supplier_email, suppliers.phone as supplier_phone')
            ->join('suppliers', 'suppliers.id = supplier_banner.id_supplier')
            ->where('supplier_banner.id', $id)
            ->first();

        if (!$banner) {
            throw new RuntimeException('Banner tidak ditemukan.');
        }
        return $banner;
    }

    /**
     * Simpan banner baru (admin-created = APPROVED by default).
     *
     * @throws RuntimeException
     */
    public function store(array $postData, $file): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('File gambar tidak valid.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . self::PATH, $newName);

        $this->bannerModel->insert([
            'id_supplier' => $postData['id_supplier'],
            'title'       => $postData['title'],
            'image'       => $newName,
            'start_date'  => $postData['start_date'],
            'end_date'    => $postData['end_date'],
            'note'        => $postData['note'] ?? null,
            'status'      => 'APPROVED',
        ]);
    }

    /**
     * Update banner — ganti gambar jika file baru dikirim.
     *
     * @throws RuntimeException
     */
    public function update(int $id, array $postData, $file): void
    {
        $banner = $this->findOrFail($id);

        $data = [
            'id_supplier' => $postData['id_supplier'],
            'title'       => $postData['title'],
            'start_date'  => $postData['start_date'],
            'end_date'    => $postData['end_date'],
            'note'        => $postData['note'] ?? null,
        ];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus gambar lama
            $oldPath = FCPATH . self::PATH . $banner['image'];
            if (is_file($oldPath)) {
                unlink($oldPath);
            }

            $newName       = $file->getRandomName();
            $file->move(FCPATH . self::PATH, $newName);
            $data['image'] = $newName;
        }

        $this->bannerModel->update($id, $data);
    }

    /**
     * Update status banner (AJAX).
     *
     * @throws RuntimeException
     */
    public function updateStatus(int $id, string $status): void
    {
        $result = $this->bannerModel->update($id, ['status' => $status]);
        if (!$result) {
            throw new RuntimeException('Gagal memperbarui status.');
        }
    }

    /**
     * Hapus banner beserta file fisiknya.
     *
     * @throws RuntimeException
     */
    public function delete(int $id): void
    {
        $banner   = $this->findOrFail($id);
        $filePath = FCPATH . self::PATH . $banner['image'];

        if (is_file($filePath)) {
            unlink($filePath);
        }

        $this->bannerModel->delete($id);
    }
}
