<?php

namespace App\Services;

use App\Models\PriceEstimateConceptsModel;
use App\Models\PriceEstimateQualitiesModel;
use RuntimeException;

class PriceEstimateService
{
    protected PriceEstimateConceptsModel  $conceptsModel;
    protected PriceEstimateQualitiesModel $qualitiesModel;

    public function __construct()
    {
        $this->conceptsModel  = new PriceEstimateConceptsModel();
        $this->qualitiesModel = new PriceEstimateQualitiesModel();
    }

    /**
     * Ambil semua konsep beserta kualitasnya dan statistik ringkas.
     */
    public function getAllWithQualities(): array
    {
        $concepts = $this->conceptsModel->orderBy('created_at', 'ASC')->findAll();

        foreach ($concepts as &$concept) {
            $concept['qualities'] = $this->qualitiesModel
                ->where('concept_id', $concept['id'])
                ->orderBy('min_price', 'ASC')
                ->findAll();
        }

        return [
            'concepts' => $concepts,
            'stats'    => [
                'total_concepts'  => count($concepts),
                'total_qualities' => $this->qualitiesModel->countAllResults(),
            ]
        ];
    }

    // --- Konsep ---

    public function createConcept(array $data): void
    {
        if (!$this->conceptsModel->insert($data)) {
            throw new RuntimeException('Gagal menambahkan konsep: ' . implode(', ', $this->conceptsModel->errors()));
        }
    }

    public function updateConcept(int $id, array $data): void
    {
        if (!$this->conceptsModel->update($id, $data)) {
            throw new RuntimeException('Gagal memperbarui konsep: ' . implode(', ', $this->conceptsModel->errors()));
        }
    }

    /**
     * Hapus konsep beserta semua kualitas yang terkait secara cascading.
     */
    public function deleteConcept(int $id): void
    {
        // Hapus kualitas terkait dulu (manual cascade jika DB tidak handle)
        $this->qualitiesModel->where('concept_id', $id)->delete();

        if (!$this->conceptsModel->delete($id)) {
            throw new RuntimeException('Gagal menghapus konsep.');
        }
    }

    // --- Kualitas ---

    public function createQuality(array $data): void
    {
        if (!$this->qualitiesModel->insert($data)) {
            throw new RuntimeException('Gagal menambahkan kualitas: ' . implode(', ', $this->qualitiesModel->errors()));
        }
    }

    public function updateQuality(int $id, array $data): void
    {
        if (!$this->qualitiesModel->update($id, $data)) {
            throw new RuntimeException('Gagal memperbarui kualitas: ' . implode(', ', $this->qualitiesModel->errors()));
        }
    }

    public function deleteQuality(int $id): void
    {
        if (!$this->qualitiesModel->delete($id)) {
            throw new RuntimeException('Gagal menghapus kualitas.');
        }
    }
}
