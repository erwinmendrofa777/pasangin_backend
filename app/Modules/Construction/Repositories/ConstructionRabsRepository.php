<?php

namespace App\Modules\Construction\Repositories;

use App\Modules\Construction\Models\ConstructionRabsModel;
use App\Modules\Construction\Repositories\Contracts\ConstructionRabsRepositoryInterface;

class ConstructionRabsRepository implements ConstructionRabsRepositoryInterface
{
    protected ConstructionRabsModel $model;

    public function __construct()
    {
        $this->model = new ConstructionRabsModel();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function findGroupedSummaryByConstructionId(int $id): array
    {
        $this->recalculateUnlockedRabs($id);

        return $this->model
            ->select('group_name, SUM(total_price) as total_price')
            ->where('construction_id', $id)
            ->groupBy('roman_number, group_name')
            ->orderBy('roman_number', 'ASC')
            ->findAll();
    }

    public function findByConstructionId(int $constructionId): array
    {
        $this->recalculateUnlockedRabs($constructionId);

        return $this->model
            ->select('rabs.*, ahsp.uraian as activity_name, ahsp.kode as ahsp_kode, (SELECT COALESCE(SUM(harga_satuan * koefisien), 0) FROM ahsp_tenaga_kerja WHERE ahsp_tenaga_kerja.ahsp_id = rabs.ahsp_id) AS ahsp_tenaga_kerja_total')
            ->join('ahsp', 'ahsp.id = rabs.ahsp_id', 'left')
            ->where('construction_id', $constructionId)
            ->orderBy('roman_number', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function insert(array $data): int
    {
        $this->model->insert($data);
        return (int) $this->model->getInsertID();
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function lockByConstructionId(int $constructionId): bool
    {
        return (bool) $this->model->where('construction_id', $constructionId)->update(null, ['is_locked' => 1]);
    }

    public function unlockByConstructionId(int $constructionId): bool
    {
        return (bool) $this->model->where('construction_id', $constructionId)->update(null, ['is_locked' => 0]);
    }

    private function recalculateUnlockedRabs(int $constructionId): void
    {
        $db = \Config\Database::connect();
        
        // 1. Ambil semua baris RAB yang tidak terkunci
        $unlockedRabs = $db->table('rabs')
            ->where('construction_id', $constructionId)
            ->where('is_locked', 0)
            ->get()->getResultArray();
            
        if (empty($unlockedRabs)) {
            return;
        }
        
        $rabIds = array_column($unlockedRabs, 'id');
        $ahspIds = array_unique(array_filter(array_column($unlockedRabs, 'ahsp_id')));
        
        if (empty($ahspIds)) {
            return;
        }
        
        // 2. Ambil tenaga kerja untuk AHSP terkait
        $laborRows = $db->table('ahsp_tenaga_kerja')
            ->select('ahsp_id, SUM(harga_satuan * koefisien) as total')
            ->whereIn('ahsp_id', $ahspIds)
            ->groupBy('ahsp_id')
            ->get()->getResultArray();
            
        $laborMap = [];
        foreach ($laborRows as $lr) {
            $laborMap[$lr['ahsp_id']] = (float) $lr['total'];
        }
        
        // 3. Ambil required bahan untuk AHSP terkait
        $bahanRows = $db->table('ahsp_bahan')
            ->whereIn('ahsp_id', $ahspIds)
            ->get()->getResultArray();
            
        $requiredBahanMap = [];
        foreach ($bahanRows as $br) {
            $requiredBahanMap[$br['ahsp_id']][] = $br;
        }
        
        // 4. Ambil semua produk
        $allProducts = $db->table('products')
            ->select('id, name, price')
            ->get()->getResultArray();
            
        $productMap = [];
        foreach ($allProducts as $p) {
            $productMap[$p['id']] = $p;
        }
        
        // 5. Ambil selected materials untuk rabIds terkait
        $selectedMaterials = $db->table('rab_materials')
            ->whereIn('rab_id', $rabIds)
            ->where('selected', 1)
            ->get()->getResultArray();
            
        $selectedMap = [];
        foreach ($selectedMaterials as $sm) {
            $selectedMap[$sm['rab_id']][$sm['ahsp_bahan_id']] = $sm['product_id'];
        }
        
        // 6. Hitung ulang masing-masing baris
        foreach ($unlockedRabs as $row) {
            $rabId = (int) $row['id'];
            $ahspId = $row['ahsp_id'];
            
            $totalTenaga = $laborMap[$ahspId] ?? 0.0;
            $totalBahan = 0.0;
            $required = $requiredBahanMap[$ahspId] ?? [];
            
            foreach ($required as $rb) {
                $koef = (float) ($rb['koefisien'] ?? 0);
                $bahanId = $rb['id'];
                
                if (isset($selectedMap[$rabId][$bahanId])) {
                    $selProdId = $selectedMap[$rabId][$bahanId];
                    if (isset($productMap[$selProdId])) {
                        $totalBahan += $koef * (float) $productMap[$selProdId]['price'];
                    }
                } else {
                    // Fallback matching
                    $bahanUraianClean = strtolower(trim($rb['uraian'] ?? ''));
                    $matchedProductPrice = 0.0;
                    
                    foreach ($allProducts as $p) {
                        $pNameClean = strtolower(trim($p['name'] ?? ''));
                        if ($pNameClean === $bahanUraianClean || strpos($pNameClean, $bahanUraianClean) !== false || strpos($bahanUraianClean, $pNameClean) !== false) {
                            $matchedProductPrice = (float) $p['price'];
                            break;
                        }
                    }
                    $totalBahan += $koef * $matchedProductPrice;
                }
            }
            
            $newUnitPrice = $totalTenaga + $totalBahan;
            $oldUnitPrice = (float) $row['current_unit_price'];
            
            if (abs($newUnitPrice - $oldUnitPrice) > 0.01) {
                $volume = (float) $row['volume'];
                $totalPrice = $volume * $newUnitPrice;
                
                $db->table('rabs')
                    ->where('id', $rabId)
                    ->update([
                        'current_unit_price' => $newUnitPrice,
                        'total_price' => $totalPrice,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
        }
    }
}
