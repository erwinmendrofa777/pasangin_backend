<?php

namespace App\Services;

use App\Models\TipsModel;
use RuntimeException;

class TipsService
{
    protected TipsModel $tipsModel;

    private const PATH = 'uploads/tips/';

    public function __construct()
    {
        $this->tipsModel = new TipsModel();
    }

    public function getAll(): array
    {
        return $this->tipsModel->orderBy('id', 'DESC')->findAll();
    }

    public function findOrFail(int $id): array
    {
        $tips = $this->tipsModel->find($id);
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

        $this->tipsModel->insert([
            'title'      => $postData['title'],
            'content'    => $postData['content'],
            'target_app' => $postData['target_app'] ?? null,
            'image'      => $newName,
            'is_active'  => 1,
        ]);
    }

    /**
     * Hapus tips beserta file gambarnya.
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

        $this->tipsModel->delete($id);
    }
}
