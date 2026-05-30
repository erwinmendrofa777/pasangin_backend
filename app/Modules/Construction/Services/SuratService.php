<?php

namespace App\Modules\Construction\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Modules\Construction\Repositories\ConstructionRepository;
use App\Modules\Renovation\Repositories\RenovationRepository;
use App\Modules\Construction\Repositories\ConstructionRabsRepository;
use App\Modules\Renovation\Repositories\RenovationRabsRepository;
use App\Modules\Construction\Repositories\Contracts\ConstructionRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationRepositoryInterface;
use App\Modules\Construction\Repositories\Contracts\ConstructionRabsRepositoryInterface;
use App\Modules\Renovation\Repositories\Contracts\RenovationRabsRepositoryInterface;
use RuntimeException;

/**
 * SuratService
 *
 * Mengelola pembuatan dokumen surat kontrak (PDF) untuk Konstruksi dan Renovasi.
 * Menggunakan Repository Pattern untuk pengambilan data template dan RAB.
 */
class SuratService
{
    protected ConstructionRepositoryInterface     $constructionRepository;
    protected RenovationRepositoryInterface       $renovationRepository;
    protected ConstructionRabsRepositoryInterface $constructionRabRepository;
    protected RenovationRabsRepositoryInterface   $renovationRabRepository;

    private const UPLOAD_PATH = 'uploads/surat_kontrak/';

    public function __construct()
    {
        $this->constructionRepository    = new ConstructionRepository();
        $this->renovationRepository      = new RenovationRepository();
        $this->constructionRabRepository = new ConstructionRabsRepository();
        $this->renovationRabRepository   = new RenovationRabsRepository();
        helper(['terbilang', 'url']);
    }

    /**
     * Generate PDF Kontrak Konstruksi.
     */
    public function generateConstructionPdf(int $id): array
    {
        $tanggal_kontrak = date('Y-m-d');
        
        $data = [
            'template_kontrak' => $this->constructionRepository->findContractDetails($id),
            'rab'              => $this->constructionRabRepository->findGroupedSummaryByConstructionId($id),
            'kalimat_pembuka'  => tanggal_surat_indo($tanggal_kontrak),
            'tanggal_kontrak'  => $tanggal_kontrak,
        ];

        if (!$data['template_kontrak']) {
            throw new RuntimeException('Data konstruksi tidak ditemukan.');
        }

        $this->prepareSuratData($data, $id, $tanggal_kontrak);

        $html     = view('App\Modules\Construction\Views\surat/kontrak_template', $data);
        $fileName = 'Kontruksi_kontrak_' . $this->cleanName($data['template_kontrak']['nama_klien'] ?? 'user') . '_' . $id . '.pdf';

        $this->renderAndSavePdf($html, $fileName);

        // Update DB
        $this->constructionRepository->update($id, ['rab_file' => $fileName]);

        return ['fileName' => $fileName, 'html' => $html];
    }

    /**
     * Generate PDF Kontrak Renovasi.
     */
    public function generateRenovationPdf(int $id): array
    {
        $tanggal_kontrak = date('Y-m-d');
        
        $data = [
            'template_kontrak' => $this->renovationRepository->findContractDetails($id),
            'rab'              => $this->renovationRabRepository->findGroupedSummaryByRenovationId($id),
            'kalimat_pembuka'  => tanggal_surat_indo($tanggal_kontrak),
            'tanggal_kontrak'  => $tanggal_kontrak,
        ];

        if (!$data['template_kontrak']) {
            throw new RuntimeException('Data renovasi tidak ditemukan.');
        }

        $this->prepareSuratData($data, $id, $tanggal_kontrak);

        $html     = view('App\Modules\Construction\Views\surat/kontrak_template_renovation', $data);
        $fileName = 'Renovasi_kontrak_' . $this->cleanName($data['template_kontrak']['nama_klien'] ?? 'user') . '_' . $id . '.pdf';

        $this->renderAndSavePdf($html, $fileName);

        // Update DB
        $this->renovationRepository->update($id, ['rab_file' => $fileName]);

        return ['fileName' => $fileName, 'html' => $html];
    }

    /**
     * Common logic for preparing surat data (nomor surat, target waktu).
     */
    private function prepareSuratData(array &$data, int $id, string $tanggal_kontrak): void
    {
        $romawiBulan = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $bulanRomawi = $romawiBulan[date('n', strtotime($tanggal_kontrak))];
        $tahun       = date('Y', strtotime($tanggal_kontrak));
        
        $data['nomor_surat'] = "{$id}/PK/PTC/{$bulanRomawi}/{$tahun}";

        if (isset($data['template_kontrak']['week'])) {
            $hari = $data['template_kontrak']['week'] * 7;
            $bulan = floor($hari / 30);
            $data['template_kontrak']['target_waktu'] = $bulan . ' Bulan / ' . $hari . ' hari kalender';
        } else {
            $data['template_kontrak']['target_waktu'] = '- Bulan / - hari kalender';
        }
    }

    /**
     * Render PDF and save to physical storage.
     */
    private function renderAndSavePdf(string $html, string $fileName): void
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('f4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $uploadPath = FCPATH . self::UPLOAD_PATH;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        file_put_contents($uploadPath . $fileName, $output);
    }

    /**
     * Helper to clean file name.
     */
    private function cleanName(string $name): string
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '_', $name);
    }

    /**
     * Stream PDF to browser.
     */
    public function streamPdf(string $html, string $fileName): void
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('f4', 'portrait');
        $dompdf->render();
        
        ob_end_clean();
        $dompdf->stream($fileName, ["Attachment" => 0]);
        exit();
    }
}
