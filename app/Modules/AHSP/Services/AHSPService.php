<?php

namespace App\Modules\AHSP\Services;

use App\Modules\AHSP\Repositories\AHSPRepository;
use App\Modules\AHSP\Repositories\Contracts\AHSPRepositoryInterface;
use RuntimeException;

class AHSPService
{
    protected AHSPRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = new AHSPRepository();
    }

    public function getAll(): array
    {
        $list = $this->repository->findAllOrderedByIdDesc();
        return array_map(fn($item) => $this->repository->findWithChildren($item['id']), $list);
    }

    public function findOrFail(int $id): array
    {
        $ahsp = $this->repository->findById($id);
        if (!$ahsp) {
            throw new RuntimeException('Data AHSP tidak ditemukan.');
        }
        return $ahsp;
    }

    public function findWithChildrenOrFail(int $id): array
    {
        $ahsp = $this->repository->findWithChildren($id);
        if (!$ahsp) {
            throw new RuntimeException('Data AHSP tidak ditemukan.');
        }
        return $ahsp;
    }

    public function store(array $data): void
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $ahspId = $this->repository->insert([
            'kode' => $data['kode'],
            'uraian' => $data['uraian']
        ]);

        if (!$ahspId) {
            throw new RuntimeException('Gagal menambahkan data AHSP.');
        }

        $bahan = $data['bahan'] ?? [];
        $tenagaKerja = $data['tenaga_kerja'] ?? [];
        $this->repository->saveChildren($ahspId, $bahan, $tenagaKerja);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new RuntimeException('Gagal menyimpan detail data AHSP.');
        }
    }

    public function update(int $id, array $data): void
    {
        $this->findOrFail($id);

        $db = \Config\Database::connect();
        $db->transStart();

        $updated = $this->repository->update($id, [
            'kode' => $data['kode'],
            'uraian' => $data['uraian']
        ]);

        if (!$updated) {
            throw new RuntimeException('Gagal memperbarui data AHSP.');
        }

        $bahan = $data['bahan'] ?? [];
        $tenagaKerja = $data['tenaga_kerja'] ?? [];
        $this->repository->saveChildren($id, $bahan, $tenagaKerja);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new RuntimeException('Gagal memperbarui detail data AHSP.');
        }
    }

    public function delete(int $id): void
    {
        $this->findOrFail($id);

        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            throw new RuntimeException('Gagal menghapus data AHSP.');
        }
    }
}
