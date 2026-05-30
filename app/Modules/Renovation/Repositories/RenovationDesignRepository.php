<?php

namespace App\Modules\Renovation\Repositories;

use App\Modules\Renovation\Models\RenovationDesignModel;
use App\Modules\Renovation\Repositories\Contracts\RenovationDesignRepositoryInterface;

class RenovationDesignRepository implements RenovationDesignRepositoryInterface
{
    protected RenovationDesignModel $model;

    public function __construct()
    {
        $this->model = new RenovationDesignModel();
    }

    public function findByRequestId(int $id): array
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil design_requests_id dari tabel renovation_designs untuk proyek ini
        $linked = $db->table('renovation_designs')
            ->select('design_requests_id')
            ->where('request_id', $id)
            ->where('design_requests_id IS NOT NULL')
            ->limit(1)
            ->get()
            ->getRowArray();
            
        $designRequestsId = $linked ? $linked['design_requests_id'] : null;

        // 2. Ambil semua desain dari tabel renovation_designs
        $designs = $this->model
            ->select('renovation_designs.*, ua.full_name as admin_name')
            ->join('user_admin ua', 'ua.id = renovation_designs.user_admin_id', 'left')
            ->where('request_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // 3. Ambil semua desain APPROVED dari project_designs jika design_requests_id ada
        if ($designRequestsId) {
            $approvedDesigns = $db->table('project_designs pd')
                ->select('pd.id, pd.user_admin_id, pd.design_name as title, pd.file as file_url, pd.revision_note as comment, pd.created_at, ua.full_name as admin_name, pd.design_request_id as design_requests_id')
                ->join('user_admin ua', 'ua.id = pd.user_admin_id', 'left')
                ->where('pd.design_request_id', $designRequestsId)
                ->where('pd.status', 'APPROVED')
                ->orderBy('pd.created_at', 'DESC')
                ->get()
                ->getResultArray();

            // Sesuaikan fields agar strukturnya konsisten
            foreach ($approvedDesigns as $key => $ad) {
                $approvedDesigns[$key]['request_id'] = $id;
            }

            // Gabungkan kedua list desain dan hilangkan duplikasi file_url
            $existingFiles = array_column($designs, 'file_url');
            foreach ($approvedDesigns as $ad) {
                if (!in_array($ad['file_url'], $existingFiles)) {
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

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }
}
