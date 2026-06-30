<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\AppCategoryModel;
use RuntimeException;

class AppCategoryService
{
    protected AppCategoryModel $model;

    public function __construct()
    {
        $this->model = new AppCategoryModel();
    }

    public function getAll(): array
    {
        return $this->model->orderBy('name', 'ASC')->findAll();
    }

    public function store(array $data): void
    {
        $this->model->insert([
            'name' => $data['name']
        ]);
    }

    public function update(int $id, array $data): void
    {
        $this->model->update($id, [
            'name' => $data['name']
        ]);
    }

    public function delete(int $id): void
    {
        if (!$this->model->find($id)) {
            throw new RuntimeException('Kategori tidak ditemukan.');
        }
        $this->model->delete($id);
    }
}
