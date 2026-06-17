<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionDesignModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionDesignRepositoryInterface;

class ConstructionDesignRepository implements ConstructionDesignRepositoryInterface
{
    protected ConstructionDesignModel $model;

    public function __construct()
    {
        $this->model = new ConstructionDesignModel();
    }

    public function findByConstructionId(int $constructionId): array
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil design_requests_id dari tabel construction_designs untuk proyek ini
        $linked = $db->table('construction_designs')
            ->select('design_requests_id')
            ->where('construction_id', $constructionId)
            ->where('design_requests_id IS NOT NULL')
            ->limit(1)
            ->get()
            ->getRowArray();
            
        $designRequestsId = $linked ? $linked['design_requests_id'] : null;

        // 2. Ambil semua desain dari tabel construction_designs
        $designs = $this->model
            ->select('construction_designs.*, ua.full_name as admin_name')
            ->join('user_admin ua', 'ua.id = construction_designs.user_admin_id', 'left')
            ->where('construction_id', $constructionId)
            ->orderBy('created_at', 'desc')
            ->findAll();

        // 3. Ambil semua desain dari project_designs jika design_requests_id ada
        if ($designRequestsId) {
            $approvedDesigns = $db->table('project_designs pd')
                ->select('pd.id, pd.user_admin_id, pd.design_name as title, pd.file, pd.revision_note as comment, pd.created_at, ua.full_name as admin_name, pd.design_request_id as design_requests_id')
                ->join('user_admin ua', 'ua.id = pd.user_admin_id', 'left')
                ->where('pd.design_request_id', $designRequestsId)
                ->orderBy('pd.created_at', 'desc')
                ->get()
                ->getResultArray();

            // Sesuaikan fields agar strukturnya konsisten
            foreach ($approvedDesigns as $key => $ad) {
                $approvedDesigns[$key]['construction_id'] = $constructionId;
            }

            // Gabungkan kedua list desain dan hilangkan duplikasi file
            $existingFiles = array_column($designs, 'file');
            foreach ($approvedDesigns as $ad) {
                if (!in_array($ad['file'], $existingFiles)) {
                    $designs[] = $ad;
                }
            }

            // Urutkan kembali berdasarkan created_at DESC
            usort($designs, function ($a, $b) {
                return strcmp($b['created_at'], $a['created_at']);
            });
        }

        return $designs;
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function save(array $data): bool
    {
        return (bool) $this->model->save($data);
    }
}
