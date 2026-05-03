<?php

namespace App\Services;

use App\Models\TermsOfAgreementModel;
use RuntimeException;

class SyaratKetentuanService
{
    protected TermsOfAgreementModel $termsModel;

    public function __construct()
    {
        $this->termsModel = new TermsOfAgreementModel();
    }

    /**
     * Mengambil data ter-kategori dan statistik.
     */
    public function getDashboardData(): array
    {
        return [
            'proyek_data'   => $this->termsModel->where('target_app', 'PROYEK')->orderBy('id', 'DESC')->findAll(),
            'client_data'   => $this->termsModel->where('target_app', 'CLIENT')->orderBy('id', 'DESC')->findAll(),
            'tukang_data'   => $this->termsModel->where('target_app', 'TUKANG')->orderBy('id', 'DESC')->findAll(),
            'supplier_data' => $this->termsModel->where('target_app', 'SUPPLIER')->orderBy('id', 'DESC')->findAll(),
            'stats'         => [
                'total_client'   => $this->termsModel->where('target_app', 'CLIENT')->countAllResults(),
                'total_tukang'   => $this->termsModel->where('target_app', 'TUKANG')->countAllResults(),
                'total_supplier' => $this->termsModel->where('target_app', 'SUPPLIER')->countAllResults(),
                'total_proyek'   => $this->termsModel->where('target_app', 'PROYEK')->countAllResults(),
            ]
        ];
    }

    public function findOrFail(int $id): array
    {
        $item = $this->termsModel->find($id);
        if (!$item) {
            throw new RuntimeException('Data tidak ditemukan.');
        }
        return $item;
    }

    public function store(array $data): void
    {
        if (!$this->termsModel->save($data)) {
            throw new RuntimeException('Gagal menyimpan data.');
        }
    }

    public function update(int $id, array $data): void
    {
        if (!$this->termsModel->update($id, $data)) {
            throw new RuntimeException('Gagal memperbarui data.');
        }
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id);
        if (!$this->termsModel->delete($id)) {
            throw new RuntimeException('Gagal menghapus data.');
        }
    }
}
