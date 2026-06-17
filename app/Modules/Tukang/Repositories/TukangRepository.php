<?php

namespace App\Modules\Tukang\Repositories;

use App\Modules\Tukang\Models\TukangModel;
use App\Modules\Tukang\Repositories\Contracts\TukangRepositoryInterface;

class TukangRepository implements TukangRepositoryInterface
{
    protected TukangModel $model;

    public function __construct()
    {
        $this->model = new TukangModel();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findWithFcmToken(): array
    {
        return $this->model->db->table('user_fcm_tokens')
            ->select('user_id as id, fcm_token')
            ->where('user_type', 'tukang')
            ->get()
            ->getResultArray();
    }

    public function findAllWithRatings(): array
    {
        return $this->model->db
            ->table('tukang')
            ->select([
                'tukang.*',
                'COALESCE((SELECT ROUND(AVG(skill_score), 1) FROM tukang_rating WHERE tukang_rating.id_tukang = tukang.id), 0) as skill_score',
                'COALESCE((SELECT ROUND(AVG(behavior_score), 1) FROM tukang_rating WHERE tukang_rating.id_tukang = tukang.id), 0) as behavior_score',
                'COALESCE(tukang.rata_rata_rating, 0) as rata_rata_rating',
            ])
            ->orderBy('tukang.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function countAll(): int
    {
        return $this->model->countAllResults();
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function findAllOrderedByName(): array
    {
        $result = $this->model
            ->orderBy('name', 'ASC')
            ->findAll();

        return is_array($result) ? $result : [];
    }

    public function save(array $data): bool
    {
        return (bool) $this->model->save($data);
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
}
