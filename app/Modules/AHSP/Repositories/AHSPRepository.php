<?php

namespace App\Modules\AHSP\Repositories;

use App\Modules\AHSP\Models\AHSPModel;
use App\Modules\AHSP\Models\AHSPBahanModel;
use App\Modules\AHSP\Models\AHSPTenagaKerjaModel;
use App\Modules\AHSP\Repositories\Contracts\AHSPRepositoryInterface;

class AHSPRepository implements AHSPRepositoryInterface
{
    protected AHSPModel $model;
    protected AHSPBahanModel $bahanModel;
    protected AHSPTenagaKerjaModel $tenagaKerjaModel;

    public function __construct()
    {
        $this->model = new AHSPModel();
        $this->bahanModel = new AHSPBahanModel();
        $this->tenagaKerjaModel = new AHSPTenagaKerjaModel();
    }

    public function findAllOrderedByIdDesc(): array
    {
        return $this->model->orderBy('kode', 'ASC')->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findWithChildren(int $id): ?array
    {
        $ahsp = $this->findById($id);
        if (!$ahsp) {
            return null;
        }

        $ahsp['bahan'] = $this->bahanModel->where('ahsp_id', $id)->findAll();
        $ahsp['tenaga_kerja'] = $this->tenagaKerjaModel->where('ahsp_id', $id)->findAll();

        return $ahsp;
    }

    public function insert(array $data): int
    {
        $this->model->insert($data);
        return $this->model->getInsertID();
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->bahanModel->where('ahsp_id', $id)->delete();
        $this->tenagaKerjaModel->where('ahsp_id', $id)->delete();
        $this->model->delete($id);

        $db->transComplete();
        return $db->transStatus();
    }

    public function saveChildren(int $ahspId, array $bahan, array $tenagaKerja): void
    {
        $this->bahanModel->where('ahsp_id', $ahspId)->delete();
        $this->tenagaKerjaModel->where('ahsp_id', $ahspId)->delete();

        if (!empty($bahan)) {
            $bahanData = [];
            foreach ($bahan as $b) {
                if (empty($b['uraian']))
                    continue;
                $bahanData[] = [
                    'ahsp_id' => $ahspId,
                    'kode' => !empty($b['kode']) ? $b['kode'] : null,
                    'uraian' => $b['uraian'],
                    'satuan' => !empty($b['satuan']) ? $b['satuan'] : '',
                    'koefisien' => (float) ($b['koefisien'] ?? 0)
                ];
            }
            if (!empty($bahanData)) {
                $this->bahanModel->insertBatch($bahanData);
            }
        }

        if (!empty($tenagaKerja)) {
            $tkData = [];
            foreach ($tenagaKerja as $t) {
                if (empty($t['uraian']))
                    continue;
                $tkData[] = [
                    'ahsp_id' => $ahspId,
                    'kode' => !empty($t['kode']) ? $t['kode'] : null,
                    'uraian' => $t['uraian'],
                    'satuan' => !empty($t['satuan']) ? $t['satuan'] : '',
                    'koefisien' => (float) ($t['koefisien'] ?? 0),
                    'harga_satuan' => (float) ($t['harga_satuan'] ?? 0)
                ];
            }
            if (!empty($tkData)) {
                $this->tenagaKerjaModel->insertBatch($tkData);
            }
        }
    }
}
