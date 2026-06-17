<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionProgressModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionProgressRepositoryInterface;

/**
 * ConstructionProgressRepository
 */
class ConstructionProgressRepository implements ConstructionProgressRepositoryInterface
{
    protected ConstructionProgressModel $model;

    public function __construct()
    {
        $this->model = new ConstructionProgressModel();
    }

    public function findDetailsByConstructionId(int $constructionId): array
    {
        return $this->model
            ->select('construction_progress.id, construction_progress.id_construction_targets, construction_progress.volume, construction_progress.description as keterangan, construction_progress.status, construction_progress.photo_url as photo, construction_progress.created_at, ct.id_construction_rabs, ct.id_construction_addendum, cr.group_name as rab_group_name, cr.sub_group_name as rab_sub_group_name, ahsp.uraian as rab_activity_name, ca.group_name as addendum_group_name, ca.sub_group_name as addendum_sub_group_name, ca.activity_name as addendum_activity_name')
            ->join('construction_targets ct', 'ct.id = construction_progress.id_construction_targets', 'left')
            ->join('construction_rabs cr', 'cr.id = ct.id_construction_rabs', 'left')
            ->join('ahsp', 'ahsp.id = cr.ahsp_id', 'left')
            ->join('construction_addendum ca', 'ca.id = ct.id_construction_addendum', 'left')
            ->where('construction_progress.construction_id', $constructionId)
            ->orderBy('construction_progress.created_at', 'DESC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function save(array $data): bool
    {
        return (bool) $this->model->save($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function sumVolumeByTargetId(int $targetId): float
    {
        $result = $this->model
            ->selectSum('volume')
            ->where('id_construction_targets', $targetId)
            ->where('status', 'APPROVED')
            ->first();
        return (float) ($result['volume'] ?? 0);
    }

    public function sumVolumeByConstructionId(int $constructionId): float
    {
        $result = $this->model
            ->selectSum('volume')
            ->where('construction_id', $constructionId)
            ->where('status', 'APPROVED')
            ->first();
        return (float) ($result['volume'] ?? 0);
    }
}
