<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Modules\Construction\Repositories\ConstructionRabsRepository;

class UnityApi extends BaseController
{
    use ResponseTrait;

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Mendapatkan data rabs berdasarkan construction_requests.id
     * 
     * GET /api/unity/construction-rabs/{constructionRequestId}
     * 
     * @param int|null $constructionRequestId
     * @return \CodeIgniter\HTTP\Response
     */
    public function getRabByConstructionId($constructionRequestId = null)
    {
        if ($constructionRequestId === null) {
            return $this->fail('Construction Request ID tidak boleh kosong.');
        }

        try {
            $repository = new ConstructionRabsRepository();
            $rabs = $repository->findByConstructionId((int) $constructionRequestId);

            $rabData = [];
            foreach ($rabs as $rab) {
                $rabData[] = [
                    'id' => (string) $rab['id'],
                    'construction_id' => (string) ($rab['construction_id'] ?? $constructionRequestId),
                    'roman_number' => (string) ($rab['roman_number'] ?? ''),
                    'group_name' => (string) ($rab['group_name'] ?? ''),
                    'sub_group_name' => (string) ($rab['sub_group_name'] ?? ''),
                    'section_group' => (string) ($rab['section_group'] ?? ''),
                    'section_name' => (string) ($rab['section_name'] ?? ''),
                    'activity_name' => (string) ($rab['activity_name'] ?? ''),
                    'volume' => (string) ($rab['volume'] ?? '0.00'),
                    'unit' => (string) ($rab['unit'] ?? ''),
                    'selected_material_id' => $rab['selected_material_id'] !== null ? (string) $rab['selected_material_id'] : null,
                    'current_unit_price' => (string) ($rab['current_unit_price'] ?? '0.0000'),
                    'total_price' => (string) ($rab['total_price'] ?? '0.0000'),
                    'is_locked' => (string) ($rab['is_locked'] ?? '0'),
                    'created_at' => (string) ($rab['created_at'] ?? ''),
                    'updated_at' => (string) ($rab['updated_at'] ?? ''),
                ];
            }

            return $this->respond([
                'status' => true,
                'message' => empty($rabData) ? 'Belum ada RAB untuk permohonan konstruksi ini' : 'Data RAB permohonan konstruksi berhasil ditemukan',
                'data' => $rabData
            ]);

        } catch (\Throwable $th) {
            return $this->fail('Gagal mendapatkan data RAB: ' . $th->getMessage());
        }
    }
}
