<?php

namespace App\Modules\Banners\Repositories;

use App\Modules\Banners\Models\BannerModel;
use App\Modules\Banners\Repositories\Contracts\BannerRepositoryInterface;

/**
 * BannerRepository
 */
class BannerRepository implements BannerRepositoryInterface
{
    protected BannerModel $model;

    public function __construct()
    {
        $this->model = new BannerModel();
    }

    public function findAllOrderedByIdDesc(): array
    {
        return $this->model->orderBy('id', 'DESC')->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }
}
