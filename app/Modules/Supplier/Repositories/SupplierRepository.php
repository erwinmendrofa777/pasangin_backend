<?php

namespace App\Modules\Supplier\Repositories;

use App\Modules\Supplier\Models\SupplierModel;
use App\Modules\Supplier\Repositories\Contracts\SupplierRepositoryInterface;

/**
 * SupplierRepository
 *
 * Implementasi konkrit dari SupplierRepositoryInterface menggunakan CodeIgniter 4 Model.
 */
class SupplierRepository implements SupplierRepositoryInterface
{
    protected SupplierModel $model;

    public function __construct()
    {
        $this->model = new SupplierModel();
    }

    /**
     * Ambil semua supplier, diurutkan berdasarkan nama (ASC).
     */
    public function findAllSuppliers(): array
    {
        return $this->model
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function countAll(): int
    {
        return $this->model->countAllResults();
    }

    /**
     * Cari supplier berdasarkan ID.
     */
    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findWithFcmToken(): array
    {
        return $this->model->db->table('user_fcm_tokens')
            ->select('user_id as id, fcm_token')
            ->where('user_type', 'supplier')
            ->get()
            ->getResultArray();
    }

    /**
     * Simpan data supplier (insert atau update).
     */
    public function save(array $data): bool
    {
        return (bool) $this->model->save($data);
    }

    /**
     * Masukkan supplier baru.
     */
    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    /**
     * Update data supplier berdasarkan ID.
     */
    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    /**
     * Hapus supplier berdasarkan ID.
     */
    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function searchForDropdown(string $term): array
    {
        $builder = $this->model->builder();
        $builder->select('id, name, phone');
                
        if (!empty($term)) {
            $builder->groupStart()
                    ->like('name', $term)
                    ->orLike('phone', $term)
                    ->groupEnd();
        }
        
        $query = $builder->limit(20)->get()->getResultArray();
        
        $results = [];
        foreach ($query as $row) {
            $results[] = ['id' => $row['id'], 'text' => $row['name'] . ' (' . $row['phone'] . ')'];
        }
        
        return $results;
    }

    /**
     * Ambil error dari model.
     */
    public function errors(): array
    {
        return $this->model->errors();
    }
}
