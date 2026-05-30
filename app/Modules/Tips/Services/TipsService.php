<?php

namespace App\Modules\Tips\Services;

use App\Modules\Tips\Repositories\TipsRepository;
use App\Modules\Tips\Repositories\Contracts\TipsRepositoryInterface;
use RuntimeException;

/**
 * TipsService
 *
 * Mengelola logika bisnis untuk konten Tips & Artikel.
 * Sekarang menggunakan Repository Pattern untuk abstraksi data.
 */
class TipsService
{
    protected TipsRepositoryInterface $tipsRepository;

    private const PATH = 'uploads/tips/';

    public function __construct()
    {
        $this->tipsRepository = new TipsRepository();
    }

    /**
     * Ambil semua tips diurutkan dari terbaru.
     */
    public function getAll(): array
    {
        return $this->tipsRepository->findAllOrderedByIdDesc();
    }

    /**
     * Cari tips berdasarkan ID atau lempar exception.
     * @throws RuntimeException
     */
    public function findOrFail(int $id): array
    {
        $tips = $this->tipsRepository->findById($id);
        if (!$tips) {
            throw new RuntimeException('Tips tidak ditemukan.');
        }
        return $tips;
    }

    /**
     * Upload gambar dan simpan tips baru.
     *
     * @throws RuntimeException
     */
    public function store(array $postData, $file): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('Gagal upload gambar.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . self::PATH, $newName);

        $this->tipsRepository->insert([
            'title'      => $postData['title'],
            'content'    => $postData['content'],
            'target_app' => $postData['target_app'] ?? null,
            'image'      => $newName,
            'is_active'  => 1,
        ]);
    }

    /**
     * Hapus tips beserta file gambarnya secara fisik.
     *
     * @throws RuntimeException
     */
    public function delete(int $id): void
    {
        $tips     = $this->findOrFail($id);
        $filePath = FCPATH . self::PATH . ($tips['image'] ?? '');

        if (!empty($tips['image']) && is_file($filePath)) {
            unlink($filePath);
        }

        $this->tipsRepository->delete($id);
    }

    /**
     * Ganti status aktif tips (toggle).
     *
     * @throws RuntimeException
     */
    public function toggleStatus(int $id): void
    {
        $tips = $this->findOrFail($id);
        $newStatus = ($tips['is_active'] == 1) ? 0 : 1;

        $this->tipsRepository->update($id, ['is_active' => $newStatus]);
    }

    /**
     * Update tips beserta gambar (jika ada).
     *
     * @throws RuntimeException
     */
    public function update(int $id, array $postData, $file = null): void
    {
        $tips = $this->findOrFail($id);

        $updateData = [
            'title'      => $postData['title'],
            'content'    => $postData['content'],
            'target_app' => $postData['target_app'] ?? null,
            'is_active'  => $postData['is_active'] ?? $tips['is_active'],
        ];

        // Jika ada file gambar baru yang diunggah
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . self::PATH, $newName);

            // Hapus gambar lama jika ada
            $oldFilePath = FCPATH . self::PATH . ($tips['image'] ?? '');
            if (!empty($tips['image']) && is_file($oldFilePath)) {
                unlink($oldFilePath);
            }

            $updateData['image'] = $newName;
        }

        $this->tipsRepository->update($id, $updateData);
    }
}
