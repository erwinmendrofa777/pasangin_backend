<?php

namespace App\Modules\AboutApplication\Services;

use App\Modules\AboutApplication\Repositories\Contracts\AboutApplicationPasanginRepositoryInterface;

class AboutApplicationPasanginService
{
    protected AboutApplicationPasanginRepositoryInterface $repo;

    public function __construct()
    {
        $this->repo = new \App\Modules\AboutApplication\Repositories\AboutApplicationPasanginRepository();
    }

    /**
     * Ambil data about application berdasarkan ID.
     */
    public function getById(int $id): ?array
    {
        return $this->repo->findById($id);
    }

    /**
     * Tambah data about application baru.
     */
    public function create(array $data): bool
    {
        return $this->repo->insert([
            'description' => $data['description'] ?? '',
        ]);
    }

    /**
     * Perbarui data about application.
     * Jika data belum ada (ID tidak ditemukan), kembalikan false.
     */
    public function update(int $id, array $data): bool
    {
        $existing = $this->repo->findById($id);
        if (!$existing) {
            return false;
        }

        return $this->repo->update($id, [
            'description' => $data['description'] ?? $existing['description'],
        ]);
    }
}
