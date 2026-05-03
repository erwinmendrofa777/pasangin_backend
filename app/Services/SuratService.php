<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use RuntimeException;

class SuratService
{
    protected $db;

    private const UPLOAD_PATH = 'uploads/surat_kontrak/';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['terbilang', 'url']);
    }

    /**
     * Generate PDF Kontrak Konstruksi.
     */
    public function generateConstructionPdf(int $id): array
    {
        $tanggal_kontrak = date('Y-m-d');
        
        $data = [
            'template_kontrak' => $this->db->table('construction_requests')
                ->select('construction_requests.address as address_construction, 
                          construction_requests.id as construction_id, 
                          construction_requests.start_date, 
                          construction_requests.week, 
                          users.full_name as nama_klien, 
                          users.nik as nik_klien, 
                          users.address as address_klien, 
                          vouchers.discount_nominal')
                ->join('users', 'users.id = construction_requests.user_id', 'left')
                ->join('vouchers', 'vouchers.code = construction_requests.voucher_code', 'left')
                ->where('construction_requests.id', $id)
                ->get()->getRowArray(),
            'rab' => $this->db->table('construction_rabs')
                ->select('group_name, SUM(total_price) as total_price')
                ->where('construction_rabs.construction_id', $id)
                ->groupBy('roman_number', 'group_name')
                ->orderBy('roman_number', 'ASC')
                ->get()->getResultArray(),
            'kalimat_pembuka' => tanggal_surat_indo($tanggal_kontrak),
            'tanggal_kontrak' => $tanggal_kontrak,
        ];

        if (!$data['template_kontrak']) {
            throw new RuntimeException('Data konstruksi tidak ditemukan.');
        }

        $this->prepareSuratData($data, $id, $tanggal_kontrak);

        $html     = view('admin/surat/kontrak_template', $data);
        $fileName = 'Kontruksi_kontrak_' . $this->cleanName($data['template_kontrak']['nama_klien'] ?? 'user') . '_' . $id . '.pdf';

        $this->renderAndSavePdf($html, $fileName);

        // Update DB
        $this->db->table('construction_requests')
            ->where('id', $id)
            ->update(['rab_file' => $fileName]);

        return ['fileName' => $fileName, 'html' => $html];
    }

    /**
     * Generate PDF Kontrak Renovasi.
     */
    public function generateRenovationPdf(int $id): array
    {
        $tanggal_kontrak = date('Y-m-d');
        
        $data = [
            'template_kontrak' => $this->db->table('renovation_requests')
                ->select('renovation_requests.address as address_renovation, 
                          renovation_requests.id as renovation_id, 
                          renovation_requests.start_date, 
                          renovation_requests.week, 
                          users.full_name as nama_klien, 
                          users.nik as nik_klien, 
                          users.address as address_klien, 
                          vouchers.discount_nominal')
                ->join('users', 'users.id = renovation_requests.user_id', 'left')
                ->join('vouchers', 'vouchers.code = renovation_requests.voucher_code', 'left')
                ->where('renovation_requests.id', $id)
                ->get()->getRowArray(),
            'rab' => $this->db->table('renovation_rabs')
                ->select('group_name, SUM(total_price) as total_price')
                ->where('renovation_rabs.renovation_id', $id)
                ->groupBy('roman_number', 'group_name')
                ->orderBy('roman_number', 'ASC')
                ->get()->getResultArray(),
            'kalimat_pembuka' => tanggal_surat_indo($tanggal_kontrak),
            'tanggal_kontrak' => $tanggal_kontrak,
        ];

        if (!$data['template_kontrak']) {
            throw new RuntimeException('Data renovasi tidak ditemukan.');
        }

        $this->prepareSuratData($data, $id, $tanggal_kontrak);

        $html     = view('admin/surat/kontrak_template_renovation', $data);
        $fileName = 'Renovasi_kontrak_' . $this->cleanName($data['template_kontrak']['nama_klien'] ?? 'user') . '_' . $id . '.pdf';

        $this->renderAndSavePdf($html, $fileName);

        // Update DB
        $this->db->table('renovation_requests')
            ->where('id', $id)
            ->update(['rab_file' => $fileName]);

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
