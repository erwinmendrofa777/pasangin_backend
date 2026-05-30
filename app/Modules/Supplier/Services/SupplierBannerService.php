<?php

namespace App\Modules\Supplier\Services;

use App\Modules\Supplier\Repositories\SupplierBannerRepository;
use App\Modules\Supplier\Repositories\SupplierRepository;
use App\Modules\Supplier\Repositories\Contracts\SupplierBannerRepositoryInterface;
use App\Modules\Supplier\Repositories\Contracts\SupplierRepositoryInterface;
use RuntimeException;

/**
 * SupplierBannerService
 *
 * Mengelola logika bisnis untuk banner promosi supplier.
 * Menggunakan Repository Pattern untuk abstraksi data.
 */
class SupplierBannerService
{
    protected SupplierBannerRepositoryInterface $bannerRepository;
    protected SupplierRepositoryInterface       $supplierRepository;

    private const PATH = 'uploads/supplier/banner/';

    public function __construct()
    {
        $this->bannerRepository   = new SupplierBannerRepository();
        $this->supplierRepository = new SupplierRepository();
    }

    /**
     * Ambil semua banner beserta nama supplier-nya.
     */
    public function getAllWithSupplier(): array
    {
        return $this->bannerRepository->findAllWithSupplier();
    }

    /**
     * Ambil semua daftar supplier untuk dropdown pilih supplier.
     */
    public function getAllSuppliers(): array
    {
        return $this->supplierRepository->findAllSuppliers();
    }

    /**
     * Ambil detail banner berdasarkan ID atau lempar exception.
     * @throws RuntimeException
     */
    public function findOrFail(int $id): array
    {
        $banner = $this->bannerRepository->findById($id);
        if (!$banner) {
            throw new RuntimeException('Data tidak ditemukan.');
        }
        return $banner;
    }

    /**
     * Ambil detail banner lengkap dengan data supplier.
     * @throws RuntimeException
     */
    public function findDetailOrFail(int $id): array
    {
        $banner = $this->bannerRepository->findDetailWithSupplier($id);

        if (!$banner) {
            throw new RuntimeException('Banner tidak ditemukan.');
        }
        return $banner;
    }

    /**
     * Simpan banner baru (admin-created = APPROVED by default).
     * @throws RuntimeException
     */
    public function store(array $postData, $file): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('File gambar tidak valid.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . self::PATH, $newName);

        $this->bannerRepository->insert([
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

        $this->bannerRepository->update($id, $data);
    }

    /**
     * Update status banner (AJAX).
     * @throws RuntimeException
     */
    public function updateStatus(int $id, string $status): void
    {
        if (!$this->bannerRepository->update($id, ['status' => $status])) {
            throw new RuntimeException('Gagal memperbarui status.');
        }
    }

    /**
     * Hapus banner beserta file fisiknya.
     * @throws RuntimeException
     */
    public function delete(int $id): void
    {
        $banner   = $this->findOrFail($id);
        $filePath = FCPATH . self::PATH . $banner['image'];

        if (is_file($filePath)) {
            unlink($filePath);
        }

        $this->bannerRepository->delete($id);
    }
}
