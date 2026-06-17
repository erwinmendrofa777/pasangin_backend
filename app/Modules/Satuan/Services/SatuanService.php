<?php

namespace App\Modules\Satuan\Services;

use App\Modules\Satuan\Repositories\SatuanRepository;
use App\Modules\Satuan\Repositories\Contracts\SatuanRepositoryInterface;
use RuntimeException;

class SatuanService
{
    protected SatuanRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = new SatuanRepository();
    }

    public function getAll(): array
    {
        return $this->repository->findAllOrderedByIdDesc();
    }

    public function findOrFail(int $id): array
    {
        $satuan = $this->repository->findById($id);
        if (!$satuan) {
            throw new RuntimeException('Data satuan tidak ditemukan.');
        }
        return $satuan;
    }

    public function store(array $data): void
    {
        $inserted = $this->repository->insert([
            'nama_satuan' => $data['nama_satuan']
        ]);

        if (!$inserted) {
            throw new RuntimeException('Gagal menambahkan data satuan.');
        }
    }

    public function update(int $id, array $data): void
    {
        $this->findOrFail($id);

        $updated = $this->repository->update($id, [
            'nama_satuan' => $data['nama_satuan']
        ]);

        if (!$updated) {
            throw new RuntimeException('Gagal memperbarui data satuan.');
        }
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id);

        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            throw new RuntimeException('Gagal menghapus data satuan.');
        }
    }
}
