<?php

namespace App\Services;

use App\Models\VoucherModel;
use RuntimeException;

class VoucherService
{
    protected VoucherModel $voucherModel;

    private const PATH = 'uploads/vouchers/';

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
    }

    public function getAll(): array
    {
        return $this->voucherModel->orderBy('id', 'DESC')->findAll();
    }

    public function findOrFail(int $id): array
    {
        $voucher = $this->voucherModel->find($id);
        if (!$voucher) {
            throw new RuntimeException('Voucher tidak ditemukan.');
        }
        return $voucher;
    }

    /**
     * Upload gambar dan simpan voucher baru. Code di-uppercase otomatis.
     *
     * @throws RuntimeException
     */
    public function store(array $postData, $file): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('File gambar tidak valid.');
        }

        $imageName = $file->getRandomName();
        $file->move(FCPATH . self::PATH, $imageName);

        $this->voucherModel->save([
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
        $this->voucherModel->update($id, ['is_active' => $status]);
        return ($status == 1) ? 'Voucher berhasil diaktifkan.' : 'Voucher berhasil dinonaktifkan.';
    }

    /**
     * Hapus voucher beserta file gambarnya.
     */
    public function delete(int $id): void
    {
        $voucher  = $this->voucherModel->find($id);
        $filePath = FCPATH . self::PATH . ($voucher['image'] ?? '');

        if ($voucher && !empty($voucher['image']) && is_file($filePath)) {
            unlink($filePath);
        }

        $this->voucherModel->delete($id);
    }
}
