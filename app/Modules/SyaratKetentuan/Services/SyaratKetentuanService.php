<?php

namespace App\Modules\SyaratKetentuan\Services;

use App\Modules\SyaratKetentuan\Repositories\TermsOfAgreementRepository;
use App\Modules\SyaratKetentuan\Repositories\Contracts\TermsOfAgreementRepositoryInterface;
use RuntimeException;

/**
 * SyaratKetentuanService
 *
 * Mengelola logika bisnis untuk Syarat & Ketentuan aplikasi.
 * Sekarang menggunakan Repository Pattern untuk abstraksi data.
 */
class SyaratKetentuanService
{
    protected TermsOfAgreementRepositoryInterface $termsRepository;

    public function __construct()
    {
        $this->termsRepository = new TermsOfAgreementRepository();
    }

    /**
     * Mengambil data ter-kategori dan statistik untuk dashboard admin.
     */
    public function getDashboardData(): array
    {
        return [
            'proyek_data'   => $this->termsRepository->findByTargetApp('PROYEK'),
            'client_data'   => $this->termsRepository->findByTargetApp('CLIENT'),
            'tukang_data'   => $this->termsRepository->findByTargetApp('TUKANG'),
            'supplier_data' => $this->termsRepository->findByTargetApp('SUPPLIER'),
            'stats'         => [
                'total_client'   => $this->termsRepository->countByTargetApp('CLIENT'),
                'total_tukang'   => $this->termsRepository->countByTargetApp('TUKANG'),
                'total_supplier' => $this->termsRepository->countByTargetApp('SUPPLIER'),
                'total_proyek'   => $this->termsRepository->countByTargetApp('PROYEK'),
            ]
        ];
    }

    /**
     * Ambil detail satu data atau lempar exception.
     * @throws RuntimeException
     */
    public function findOrFail(int $id): array
    {
        $item = $this->termsRepository->findById($id);
        if (!$item) {
            throw new RuntimeException('Data tidak ditemukan.');
        }
        return $item;
    }

    /**
     * Simpan data baru.
     */
    public function store(array $data): void
    {
        if (!$this->termsRepository->insert($data)) {
            throw new RuntimeException('Gagal menyimpan data.');
        }
    }

    /**
     * Update data.
     */
    public function update(int $id, array $data): void
    {
        if (!$this->termsRepository->update($id, $data)) {
            throw new RuntimeException('Gagal memperbarui data.');
        }
    }

    /**
     * Hapus data.
     */
    public function delete(int $id): void
    {
        $this->findOrFail($id);
        if (!$this->termsRepository->delete($id)) {
            throw new RuntimeException('Gagal menghapus data.');
        }
    }
}
