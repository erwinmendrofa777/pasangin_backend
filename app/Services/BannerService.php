<?php

namespace App\Services;

use App\Models\BannerModel;
use RuntimeException;

class BannerService
{
    protected BannerModel $bannerModel;

    private const PATH = 'uploads/banners/';

    public function __construct()
    {
        $this->bannerModel = new BannerModel();
    }

    public function getAll(): array
    {
        return $this->bannerModel->orderBy('id', 'DESC')->findAll();
    }

    /**
     * Upload gambar dan simpan record banner baru.
     *
     * @throws RuntimeException
     */
    public function store(array $postData, $file): void
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('Gagal mengupload gambar. Silakan coba lagi.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . self::PATH, $newName);

        $this->bannerModel->insert([
            'title'      => $postData['title'],
            'target_app' => $postData['target_app'],
            'image'      => $newName,
            'is_active'  => 1,
        ]);
    }

    /**
     * Hapus banner beserta file fisiknya.
     *
     * @throws RuntimeException
     */
    public function delete(int $id): void
    {
        $banner = $this->bannerModel->find($id);

        if (!$banner) {
            throw new RuntimeException('Data banner tidak ditemukan.');
        }

        $filePath = FCPATH . self::PATH . $banner['image'];
        if (is_file($filePath)) {
            unlink($filePath);
        }

        $this->bannerModel->delete($id);
    }
}
