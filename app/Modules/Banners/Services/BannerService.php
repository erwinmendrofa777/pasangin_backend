<?php

namespace App\Modules\Banners\Services;

use App\Modules\Banners\Repositories\BannerRepository;
use App\Modules\Banners\Repositories\Contracts\BannerRepositoryInterface;
use RuntimeException;

class BannerService
{
    protected BannerRepositoryInterface $bannerRepository;

    private const PATH = 'uploads/banners/';

    public function __construct()
    {
        $this->bannerRepository = new BannerRepository();
    }

    public function getAll(): array
    {
        return $this->bannerRepository->findAllOrderedByIdDesc();
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

        $this->bannerRepository->insert([
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
        $banner = $this->bannerRepository->findById($id);

        if (!$banner) {
            throw new RuntimeException('Data banner tidak ditemukan.');
        }

        $filePath = FCPATH . self::PATH . $banner['image'];
        if (is_file($filePath)) {
            unlink($filePath);
        }

        $this->bannerRepository->delete($id);
    }
}
