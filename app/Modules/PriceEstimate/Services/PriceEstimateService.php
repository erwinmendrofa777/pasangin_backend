<?php

namespace App\Modules\PriceEstimate\Services;

use App\Modules\PriceEstimate\Repositories\PriceEstimateConceptsRepository;
use App\Modules\PriceEstimate\Repositories\PriceEstimateQualitiesRepository;
use App\Modules\PriceEstimate\Repositories\Contracts\PriceEstimateConceptsRepositoryInterface;
use App\Modules\PriceEstimate\Repositories\Contracts\PriceEstimateQualitiesRepositoryInterface;
use RuntimeException;

/**
 * PriceEstimateService
 *
 * Mengelola logika estimasi harga per m2 berdasarkan konsep dan kualitas.
 * Sekarang menggunakan Repository Pattern untuk akses data.
 */
class PriceEstimateService
{
    protected PriceEstimateConceptsRepositoryInterface  $conceptsRepository;
    protected PriceEstimateQualitiesRepositoryInterface $qualitiesRepository;

    public function __construct()
    {
        $this->conceptsRepository  = new PriceEstimateConceptsRepository();
        $this->qualitiesRepository = new PriceEstimateQualitiesRepository();
    }

    /**
     * Ambil semua konsep beserta kualitasnya dan statistik ringkas.
     */
    public function getAllWithQualities(): array
    {
        $concepts = $this->conceptsRepository->findAllOrderedByCreatedAtAsc();

        foreach ($concepts as &$concept) {
            $concept['qualities'] = $this->qualitiesRepository->findByConceptId((int) $concept['id']);
        }

        return [
            'concepts' => $concepts,
            'stats'    => [
                'total_concepts'  => count($concepts),
                'total_qualities' => $this->qualitiesRepository->countAll(),
            ]
        ];
    }

    // --- Konsep ---

    public function createConcept(array $data): void
    {
        if (!$this->conceptsRepository->insert($data)) {
            throw new RuntimeException('Gagal menambahkan konsep: ' . implode(', ', $this->conceptsRepository->errors()));
        }
    }

    public function updateConcept(int $id, array $data): void
    {
        if (!$this->conceptsRepository->update($id, $data)) {
            throw new RuntimeException('Gagal memperbarui konsep: ' . implode(', ', $this->conceptsRepository->errors()));
        }
    }

    /**
     * Hapus konsep beserta semua kualitas yang terkait secara cascading.
     */
    public function deleteConcept(int $id): void
    {
        // Hapus kualitas terkait dulu
        $this->qualitiesRepository->deleteByConceptId($id);

        if (!$this->conceptsRepository->delete($id)) {
            throw new RuntimeException('Gagal menghapus konsep.');
        }
    }

    // --- Kualitas ---

    public function createQuality(array $data): void
    {
        if (!$this->qualitiesRepository->insert($data)) {
            throw new RuntimeException('Gagal menambahkan kualitas: ' . implode(', ', $this->qualitiesRepository->errors()));
        }
    }

    public function updateQuality(int $id, array $data): void
    {
        if (!$this->qualitiesRepository->update($id, $data)) {
            throw new RuntimeException('Gagal memperbarui kualitas: ' . implode(', ', $this->qualitiesRepository->errors()));
        }
    }

    public function deleteQuality(int $id): void
    {
        if (!$this->qualitiesRepository->delete($id)) {
            throw new RuntimeException('Gagal menghapus kualitas.');
        }
    }
}
