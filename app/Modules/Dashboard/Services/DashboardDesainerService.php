<?php

namespace App\Modules\Dashboard\Services;

use App\Modules\Design\Models\DesignRequestModel;
use App\Modules\Construction\Models\ConstructionModel;
use App\Modules\Renovation\Models\RenovationModel;

/**
 * DashboardDesainerService
 *
 * Mengelola data agregat dan statistik untuk proyek desain, konstruksi, dan renovasi.
 */
class DashboardDesainerService
{
    protected DesignRequestModel $designModel;
    protected ConstructionModel $constructionModel;
    protected RenovationModel $renovationModel;

    public function __construct()
    {
        $this->designModel = new DesignRequestModel();
        $this->constructionModel = new ConstructionModel();
        $this->renovationModel = new RenovationModel();
    }

    /**
     * Mengambil statistik akumulasi seluruh proyek dan detail jumlah per status
     */
    public function getDesainerDashboardStats(): array
    {
        // 1. Ambil total keseluruhan untuk masing-masing modul
        $totalDesign = $this->designModel->countAllResults();
        $totalConstruction = $this->constructionModel->countAllResults();
        $totalRenovation = $this->renovationModel->countAllResults();
        $grandTotal = $totalDesign + $totalConstruction + $totalRenovation;

        // 2. Ambil pengelompokan status untuk Desain
        $designStatusRaw = $this->designModel
            ->select('status, COUNT(id) as total')
            ->groupBy('status')
            ->findAll();

        $designMapping = [
            'PENDING' => 'Menunggu Persetujuan',
            'SURVEY_SCHEDULED' => 'Survei Dijadwalkan',
            'PAYMENT_VERIFIED' => 'Pembayaran Terverifikasi',
            'COMPLETED' => 'Selesai',
            'CANCELLED' => 'Dibatalkan'
        ];
        $designByStatus = [];
        foreach ($designMapping as $indoLabel) {
            $designByStatus[$indoLabel] = 0;
        }
        foreach ($designStatusRaw as $row) {
            $statusKey = empty($row['status']) ? 'PENDING' : $row['status'];
            $label = $designMapping[$statusKey] ?? $statusKey;
            if (!isset($designByStatus[$label])) {
                $designByStatus[$label] = 0;
            }
            $designByStatus[$label] += (int) $row['total'];
        }

        // 3. Ambil pengelompokan status untuk Konstruksi
        $constructionStatusRaw = $this->constructionModel
            ->select('status, COUNT(id) as total')
            ->groupBy('status')
            ->findAll();

        $constructionMapping = [
            'PENDING' => 'Menunggu Persetujuan',
            'SURVEY' => 'Fase Survei',
            'DESIGNING' => 'Tahap Perancangan',
            'RAB' => 'Penyusunan RAB',
            'CONSTRUCTION' => 'Pelaksanaan Lapangan',
            'COMPLETED' => 'Selesai',
            'CANCELLED' => 'Dibatalkan'
        ];
        $constructionByStatus = [];
        foreach ($constructionMapping as $indoLabel) {
            $constructionByStatus[$indoLabel] = 0;
        }
        foreach ($constructionStatusRaw as $row) {
            $statusKey = empty($row['status']) ? 'PENDING' : $row['status'];
            $label = $constructionMapping[$statusKey] ?? $statusKey;
            if (!isset($constructionByStatus[$label])) {
                $constructionByStatus[$label] = 0;
            }
            $constructionByStatus[$label] += (int) $row['total'];
        }

        // 4. Ambil pengelompokan status untuk Renovasi
        $renovationStatusRaw = $this->renovationModel
            ->select('status, COUNT(id) as total')
            ->groupBy('status')
            ->findAll();

        $renovationMapping = [
            'PENDING' => 'Menunggu Persetujuan',
            'SURVEY' => 'Fase Survei',
            'DESIGNING' => 'Tahap Perancangan',
            'RAB' => 'Penyusunan RAB',
            'RENOVATION' => 'Pelaksanaan Lapangan',
            'COMPLETED' => 'Selesai',
            'CANCELLED' => 'Dibatalkan'
        ];
        $renovationByStatus = [];
        foreach ($renovationMapping as $indoLabel) {
            $renovationByStatus[$indoLabel] = 0;
        }
        foreach ($renovationStatusRaw as $row) {
            $statusKey = empty($row['status']) ? 'PENDING' : $row['status'];
            $label = $renovationMapping[$statusKey] ?? $statusKey;
            if (!isset($renovationByStatus[$label])) {
                $renovationByStatus[$label] = 0;
            }
            $renovationByStatus[$label] += (int) $row['total'];
        }

        return [
            'totals' => [
                'design' => $totalDesign,
                'construction' => $totalConstruction,
                'renovation' => $totalRenovation,
                'grand_total' => $grandTotal
            ],
            'by_status' => [
                'design' => $designByStatus,
                'construction' => $constructionByStatus,
                'renovation' => $renovationByStatus
            ]
        ];
    }
}
