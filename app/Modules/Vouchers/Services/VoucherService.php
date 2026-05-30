<?php

namespace App\Modules\Vouchers\Services;

use App\Modules\Vouchers\Repositories\VoucherRepository;
use App\Modules\Vouchers\Repositories\Contracts\VoucherRepositoryInterface;
use RuntimeException;

/**
 * VoucherService
 *
 * Mengelola logika bisnis untuk diskon Voucher.
 * Sekarang menggunakan Repository Pattern untuk abstraksi data.
 */
class VoucherService
{
    protected VoucherRepositoryInterface $voucherRepository;

    private const PATH = 'uploads/vouchers/';

    public function __construct()
    {
        $this->voucherRepository = new VoucherRepository();
    }

    /**
     * Ambil semua voucher diurutkan dari terbaru.
     */
    public function getAll(): array
    {
        return $this->voucherRepository->findAllOrderedByIdDesc();
    }

    /**
     * Cari voucher berdasarkan ID atau lempar exception.
     * @throws RuntimeException
     */
    public function findOrFail(int $id): array
    {
        $voucher = $this->voucherRepository->findById($id);
        if (!$voucher) {
            throw new RuntimeException('Voucher tidak ditemukan.');
        }
        return $voucher;
    }

    /**
     * Upload gambar dan simpan voucher baru. Code di-uppercase otomatis.
     * @throws RuntimeException
     */
    public function store(array $postData, $file): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('File gambar tidak valid.');
        }

        $imageName = $file->getRandomName();
        $file->move(FCPATH . self::PATH, $imageName);

        $this->voucherRepository->insert([
            'code'             => strtoupper($postData['code']),
            'name'             => $postData['name'],
            'description'      => $postData['description'] ?? null,
            'discount_nominal' => $postData['discount_nominal'],
            'valid_until'      => $postData['valid_until'],
            'is_active'        => 1,
            'image'            => $imageName,
        ]);
    }

    /**
     * Toggle status aktif/nonaktif voucher.
     */
    public function updateStatus(int $id, int $status): string
    {
        $this->voucherRepository->update($id, ['is_active' => $status]);
        return ($status == 1) ? 'Voucher berhasil diaktifkan.' : 'Voucher berhasil dinonaktifkan.';
    }

    /**
     * Hapus voucher beserta file gambarnya secara fisik.
     */
    public function delete(int $id): void
    {
        $voucher  = $this->voucherRepository->findById($id);
        $filePath = FCPATH . self::PATH . ($voucher['image'] ?? '');

        if ($voucher && !empty($voucher['image']) && is_file($filePath)) {
            unlink($filePath);
        }

        $this->voucherRepository->delete($id);
    }
}
