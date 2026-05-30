<?php

namespace App\Modules\Construction\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Construction\Models\RabModel;
use CodeIgniter\API\ResponseTrait;

class RabController extends BaseController
{
    use ResponseTrait;

    protected $rabModel;
    protected $db;

    public function __construct()
    {
        $this->rabModel = new RabModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * 1. SIMPAN ATAU UPDATE BARIS PEKERJAAN
     * Mendukung Roman Number, Group Name, dan Proteksi Lock
     */
    public function save_rab_row()
    {
        $id = $this->request->getPost('id');
        $constructionId = $this->request->getPost('construction_id');

        // Ambil data grouping  
        $roman = $this->request->getPost('roman_number') ?: 'I';
        $group = $this->request->getPost('group_name') ?: 'PEKERJAAN';
        $section = $this->request->getPost('section_group');

        $taskName = $this->request->getPost('task_name');
        $volume = (float) ($this->request->getPost('volume') ?? 0);
        $unit = $this->request->getPost('unit');
        $price = (float) ($this->request->getPost('price') ?? 0);

        // Hitung total price otomatis  
        $totalPrice = $volume * $price;

        // Cek jika ini update, apakah sudah dikunci?
        if (!empty($id) && $id != "0") {
            $existing = $this->db->table('construction_rabs')->where('id', $id)->get()->getRowArray();
            if ($existing && $existing['is_locked'] == 1) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Maaf  , baris ini sudah dikunci!'
                ]);
            }
        }

        $data = [
            'construction_id' => $constructionId,
            'roman_number' => $roman,
            'group_name' => $group,
            'sub_group_name' => $section,
            'section_group' => $section,
            'section_name' => $section,
            'activity_name' => $taskName,
            'volume' => $volume,
            'unit' => $unit,
            'current_unit_price' => $price,
            'total_price' => $totalPrice,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            if (empty($id) || $id == "0") {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('construction_rabs')->insert($data);
                $finalId = $this->db->insertID();
                $message = "Berhasil tambah baris baru  !";
            } else {
                $this->db->table('construction_rabs')->where('id', $id)->update($data);
                $finalId = $id;
                $message = "Baris RAB diperbarui  !";
            }

            return $this->response->setJSON([
                'status' => true,
                'id' => $finalId,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * 2. HAPUS BARIS PEKERJAAN (Proteksi Lock)
     */
    public function delete_rab_row($id)
    {
        try {
            $existing = $this->db->table('construction_rabs')->where('id', $id)->get()->getRowArray();
            if ($existing && $existing['is_locked'] == 1) {
                return $this->response->setJSON(['status' => false, 'message' => 'Gagal! Baris ini terkunci  .']);
            }

            if ($this->db->table('construction_rabs')->where('id', $id)->delete()) {
                // Hapus juga relasi materialnya  
                $this->db->table('construction_rab_materials')->where('rab_id', $id)->delete();

                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Baris berhasil dihapus  '
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 3. LOCK & UNLOCK FUNGSI (Untuk Admin)
     */
    public function lock_rab($constructionId)
    {
        $this->db->table('construction_rabs')
            ->where('construction_id', $constructionId)
            ->update(['is_locked' => 1]);

        return redirect()->back()->with('success', 'RAB Berhasil Dikunci  !');
    }

    public function unlock_rab($constructionId)
    {
        $this->db->table('construction_rabs')
            ->where('construction_id', $constructionId)
            ->update(['is_locked' => 0]);

        // Reset rab_total ke 0 saat RAB dibuka kuncinya
        // agar saat dikunci ulang, nilai dihitung fresh dari baris yang dikirim
        $this->db->table('construction_requests')
            ->where('id', $constructionId)
            ->update(['rab_total' => 0]);

        return redirect()->back()->with('success', 'Kunci RAB dibuka  !');
    }

    /**
     * 4. AMBIL PILIHAN MATERIAL
     */
    public function get_rab_materials($rabId)
    {
        $materials = $this->db->table('construction_rab_materials')
            ->select('construction_rab_materials.id, products.name as material_name, products.price')
            ->join('products', 'products.id = construction_rab_materials.product_id')
            ->where('rab_id', $rabId)
            ->get()->getResultArray();

        return $this->response->setJSON($materials);
    }

    /**
     * 5. TAMBAH PILIHAN MATERIAL
     */
    public function add_rab_material()
    {
        $rabId = $this->request->getPost('rab_id');
        $productId = $this->request->getPost('product_id');

        // Cek lock  
        $existing = $this->db->table('construction_rabs')->where('id', $rabId)->get()->getRowArray();
        if ($existing && $existing['is_locked'] == 1) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak bisa tambah, RAB terkunci!']);
        }

        try {
            $this->db->table('construction_rab_materials')->insert([
                'rab_id' => $rabId,
                'product_id' => $productId,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON(['status' => true, 'message' => 'Material ditambahkan  .']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 6. HAPUS PILIHAN MATERIAL
     */
    public function delete_rab_material($id)
    {
        try {
            $this->db->table('construction_rab_materials')->where('id', $id)->delete();
            return $this->response->setJSON(['status' => true, 'message' => 'Material dihapus  .']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 7. SIMPAN SEMUA BARIS (DRAF ATAU LOCK)
     */
    public function save_all_rab($constructionId)
    {
        $rows = $this->request->getPost('rows') ?: [];
        $shouldLock = $this->request->getPost('lock') === 'true' || $this->request->getPost('lock') === true;

        $this->db->transStart();

        $grandTotal = 0;
        $savedIds = [];

        // Loop and save each row
        foreach ($rows as $row) {
            $id = $row['id'] ?? '0';
            $roman = $row['roman_number'] ?: 'I';
            $group = $row['group_name'] ?: 'PEKERJAAN';
            $section = $row['section_group'];
            $taskName = $row['task_name'];
            $volume = (float) ($row['volume'] ?? 0);
            $unit = $row['unit'];
            $price = (float) ($row['price'] ?? 0);
            $totalPrice = $volume * $price;

            $grandTotal += $totalPrice;

            $data = [
                'construction_id' => $constructionId,
                'roman_number' => $roman,
                'group_name' => $group,
                'sub_group_name' => $section,
                'section_group' => $section,
                'section_name' => $section,
                'activity_name' => $taskName,
                'volume' => $volume,
                'unit' => $unit,
                'current_unit_price' => $price,
                'total_price' => $totalPrice,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($shouldLock) {
                $data['is_locked'] = 1;
            }

            if (empty($id) || $id == "0" || $id == 0) {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('construction_rabs')->insert($data);
                $newId = $this->db->insertID();
                $savedIds[] = $newId;
            } else {
                $this->db->table('construction_rabs')->where('id', $id)->update($data);
                $savedIds[] = $id;
            }
        }

        if ($shouldLock) {
            // Lock all rows for this construction
            $this->db->table('construction_rabs')
                ->where('construction_id', $constructionId)
                ->update(['is_locked' => 1]);

            // Hitung ulang total dari DB (bukan hanya dari rows yang dikirim)
            // agar rows yang sudah tersimpan sebelumnya juga ikut dihitung
            $rabRow = $this->db->query(
                "SELECT COALESCE(SUM(total_price), 0) as rab_sum FROM construction_rabs WHERE construction_id = ?",
                [(int) $constructionId]
            )->getRowArray();

            $this->db->table('construction_requests')
                ->where('id', $constructionId)
                ->update(['rab_total' => (float) ($rabRow['rab_sum'] ?? 0)]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal menyimpan data RAB!'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => $shouldLock ? 'RAB Berhasil Disimpan dan Dikunci!' : 'Draf RAB Berhasil Disimpan!'
        ]);
    }

    /**
     * 8. UNDUH TEMPLATE EXCEL KOSONGAN DINAMIS  
     */
    public function download_rab_template($constructionId = null)
    {
        $pekerjaan = 'construction#';
        $lokasi = '-';
        $pemilik = '-';
        $buildingArea = 270;

        if ($constructionId) {
            $project = $this->db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();
            if ($project) {
                $pekerjaan = 'construction#' . $project['id'];
                $lokasi = $project['address'] ?: '-';
                $pemilik = $project['full_name'] ?: '-';
                $buildingArea = (float) ($project['building_area'] ?: 270);
            }
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template RAB');

        // Tampilkan garis kisi-kisi (gridlines)  
        $sheet->setShowGridLines(true);

        // ── 1. HEADER UTAMA (HIJAU TUA SEPERTI GAMBAR  ) ──
        $sheet->setCellValue('A1', 'RENCANA ANGGARAN BIAYA ( RAB )');
        $sheet->mergeCells('A1:F1');
        $sheet->getRowDimension(1)->setRowHeight(35);

        $titleStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 14,
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '008B4B'], // Hijau Tua sesuai dokumen  
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($titleStyle);

        // ── 2. METADATA PROYEK DARI DATABASE (BARIS 3-5) ──
        $sheet->setCellValue('A3', 'Pekerjaan :');
        $sheet->setCellValue('B3', $pekerjaan);
        $sheet->mergeCells('B3:D3');

        $sheet->setCellValue('A4', 'Lokasi :');
        $sheet->setCellValue('B4', $lokasi);
        $sheet->mergeCells('B4:D4');

        $sheet->setCellValue('A5', 'Pemilik :');
        $sheet->setCellValue('B5', $pemilik);
        $sheet->mergeCells('B5:D5');

        $sheet->setCellValue('E3', 'Luas Bangunan : ' . $buildingArea . ' m2');
        $sheet->mergeCells('E3:G3');

        $infoStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A3:F5')->applyFromArray($infoStyle);
        $sheet->getRowDimension(3)->setRowHeight(18);
        $sheet->getRowDimension(4)->setRowHeight(18);
        $sheet->getRowDimension(5)->setRowHeight(18);

        // ── 3. HEADER TABEL (BARIS 7 - WARNA HIJAU MUDA SEPERTI GAMBAR) ──
        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'DAFTAR ITEM PEKERJAAN');
        $sheet->setCellValue('C7', 'VOLUME');
        $sheet->setCellValue('D7', 'SAT');
        $sheet->setCellValue('E7', 'HARGA SATUAN  (Rp)');
        $sheet->setCellValue('F7', 'JUMLAH HARGA   (Rp)');

        $sheet->getRowDimension(7)->setRowHeight(25);

        $tableHeaderStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 10,
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '92D050'], // Hijau Muda seperti di gambar  
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A7:F7')->applyFromArray($tableHeaderStyle);

        // ── 4. KOSONGKAN DATA ROWS AGAR DIISI MANUAL   (BARIS 8 - 150) ──
        $startRow = 8;
        $maxDataRows = 150; // Menyediakan 150 baris kosong  
        $endRow = $startRow + $maxDataRows - 1;

        for ($row = $startRow; $row <= $endRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(20);

            // Formula Jumlah Harga otomatis  : Volume * Harga Satuan (C * E)
            $sheet->setCellValue('F' . $row, '=C' . $row . '*E' . $row);

            // Alignment
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }

        // Terapkan border tipis abu-abu di semua baris kosong
        $sheet->getStyle('A8:F' . $endRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ]
        ]);

        // ── 5. FOOTER SUMMARY BLOCK (JUMLAH TOTAL, DIBULATKAN, HARGA/M2) ──
        $currentRow = $endRow + 2; // Beri jarak 1 baris kosong

        // 5.1 Jumlah Total Row
        $sheet->getRowDimension($currentRow)->setRowHeight(22);
        $sheet->setCellValue('B' . $currentRow, 'Jumlah Total');
        $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
        $sheet->setCellValue('F' . $currentRow, '=SUM(F8:F' . $endRow . ')'); // Jumlahkan semua baris kosong
        $jumlahTotalRow = $currentRow;

        $footerTotalStyle = [
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '92D050'], // Hijau Muda sesuai gambar  
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($footerTotalStyle);
        $currentRow++;

        // 5.2 Dibulatkan Row
        $sheet->getRowDimension($currentRow)->setRowHeight(22);
        $sheet->setCellValue('B' . $currentRow, 'Dibulatkan');
        $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
        $sheet->setCellValue('F' . $currentRow, '=ROUND(F' . $jumlahTotalRow . ', -5)'); // Bulatkan ke ratusan ribu terdekat

        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($footerTotalStyle);
        $currentRow++;

        // 5.3 Harga / m2 Row
        $sheet->getRowDimension($currentRow)->setRowHeight(22);
        $sheet->setCellValue('B' . $currentRow, 'Harga / m2');
        $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
        $sheet->setCellValue('F' . $currentRow, '=F' . $jumlahTotalRow . '/' . ($buildingArea > 0 ? $buildingArea : 1));

        $sheet->getStyle('B' . $currentRow . ':F' . $currentRow)->applyFromArray($footerTotalStyle);

        // ── 6. FORMAT ANGKA & LEBAR KOLOM (NUMERICAL FORMATTING) ──
        // Format desimal khusus untuk menyembunyikan nilai 0.00 agar tetap bersih saat kosong  
        $customNumberFormat = '#,##0.00;-#,##0.00;""';
        $sheet->getStyle('C8:C' . $currentRow)->getNumberFormat()->setFormatCode($customNumberFormat);
        $sheet->getStyle('E8:F' . $currentRow)->getNumberFormat()->setFormatCode($customNumberFormat);

        // Set Lebar Kolom agar tidak terpotong (Auto width)
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(22);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Template_RAB_Proyek.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * 9. IMPORT EXCEL RAB KONS
     */
    public function import_rab_excel($constructionId)
    {
        // Cek lock status  
        $isLocked = $this->db->table('construction_rabs')
            ->where('construction_id', $constructionId)
            ->where('is_locked', 1)
            ->countAllResults() > 0;
        if ($isLocked) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'RAB proyek ini sudah dikunci  , tidak bisa melakukan import!'
            ]);
        }

        $file = $this->request->getFile('excel_file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'File tidak valid atau tidak ditemukan  !'
            ]);
        }

        $ext = $file->getClientExtension();
        if (!in_array(strtolower($ext), ['xlsx', 'xls', 'csv'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Format file harus .xlsx, .xls, atau .csv  !'
            ]);
        }

        $filePath = $file->getTempName();

        try {
            // Load file excel  
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (empty($rows)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'File Excel kosong  !'
                ]);
            }

            // Tracker variable  
            $currentRoman = 'I';
            $currentGroupName = 'PEKERJAAN';
            $currentSectionGroup = '';

            $dataToInsert = [];

            // Skip header (baris pertama)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                $colA = isset($row[0]) ? trim((string) $row[0]) : ''; // Roman / NO
                $colB = isset($row[1]) ? trim((string) $row[1]) : ''; // Uraian pekerjaan / Sub-Grup
                $colC = isset($row[2]) ? trim((string) $row[2]) : ''; // Volume
                $colD = isset($row[3]) ? trim((string) $row[3]) : ''; // Satuan (SAT)
                $colE = isset($row[4]) ? trim((string) $row[4]) : ''; // Harga Satuan

                if (empty($colB)) {
                    continue;
                }

                // Cek baris pengisi / footer  
                $colBLower = strtolower($colB);
                if (
                    str_contains($colBLower, 'sub total') ||
                    str_contains($colBLower, 'jumlah total') ||
                    str_contains($colBLower, 'dibulatkan') ||
                    str_contains($colBLower, 'harga / m2') ||
                    str_contains($colBLower, 'termin') ||
                    str_contains($colBLower, 'pembayaran di transfer') ||
                    str_contains($colBLower, 'rekening')
                ) {
                    continue;
                }

                // 1. Deteksi Grup Utama (Roman Group)
                if (!empty($colA) && preg_match('/^[IVXLCDM]+$/i', $colA)) {
                    $currentRoman = strtoupper($colA);
                    // Buang penomoran di nama grup
                    $cleanGroup = preg_replace('/^' . $currentRoman . '\.?\s*/i', '', $colB);
                    $currentGroupName = trim($cleanGroup) ?: 'PEKERJAAN';
                    $currentSectionGroup = ''; // Reset sub-grup
                    continue;
                }

                // Bersihkan angka desimal / nominal menggunakan parser terpadu  
                $volume = $this->parseNumeric($colC);
                $price = $this->parseNumeric($colE);

                // 2. Deteksi Sub-Grup
                if (empty($volume) && empty($price)) {
                    if (!empty($colA) && is_numeric($colA)) {
                        $currentSectionGroup = trim($colA) . ' ' . $colB;
                    } else {
                        $currentSectionGroup = $colB;
                    }
                    continue;
                }

                // 3. Deteksi Detail Pekerjaan
                if (!empty($volume) || !empty($price)) {
                    $totalPrice = $volume * $price;

                    $dataToInsert[] = [
                        'construction_id' => $constructionId,
                        'roman_number' => $currentRoman,
                        'group_name' => $currentGroupName,
                        'sub_group_name' => $currentSectionGroup,
                        'section_group' => $currentSectionGroup,
                        'section_name' => $currentSectionGroup,
                        'activity_name' => $colB,
                        'volume' => $volume,
                        'unit' => $colD ?: 'unit',
                        'current_unit_price' => $price,
                        'total_price' => $totalPrice,
                        'is_locked' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
            }

            if (empty($dataToInsert)) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Tidak ada baris pekerjaan valid yang terdeteksi  !'
                ]);
            }

            // Simpan ke database
            $this->db->transStart();

            // Hapus data draf lama (karena user setuju untuk overwrite)
            $this->db->table('construction_rabs')
                ->where('construction_id', $constructionId)
                ->delete();

            // Batch insert
            $this->db->table('construction_rabs')->insertBatch($dataToInsert);

            // Hitung ulang total RAB untuk proyek
            $rabRow = $this->db->query(
                "SELECT COALESCE(SUM(total_price), 0) as rab_sum FROM construction_rabs WHERE construction_id = ?",
                [(int) $constructionId]
            )->getRowArray();

            $this->db->table('construction_requests')
                ->where('id', $constructionId)
                ->update(['rab_total' => (float) ($rabRow['rab_sum'] ?? 0)]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Gagal menyimpan data ke database  !'
                ]);
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Berhasil mengimpor ' . count($dataToInsert) . ' baris RAB  !'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Helper untuk memparsing angka desimal/ribuan baik format ID maupun EN  
     */
    private function parseNumeric($val)
    {
        if (is_int($val) || is_float($val)) {
            return (float) $val;
        }

        $val = trim((string) $val);
        if ($val === '') {
            return 0.0;
        }

        // Jika sudah berupa format standar float string (misal "44.5")
        if (preg_match('/^-?\d+(\.\d+)?$/', $val)) {
            return (float) $val;
        }

        // Jika mengandung titik dan koma (misal: "1.234,56" atau "1,234.56")
        if (str_contains($val, '.') && str_contains($val, ',')) {
            $lastDot = strrpos($val, '.');
            $lastComma = strrpos($val, ',');
            if ($lastDot < $lastComma) {
                // Format Indonesia: 1.234,56 -> hapus titik, ganti koma jadi titik
                $val = str_replace('.', '', $val);
                $val = str_replace(',', '.', $val);
            } else {
                // Format US/English: 1,234.56 -> hapus koma
                $val = str_replace(',', '', $val);
            }
        }
        // Jika hanya mengandung koma (misal: "44,5" atau "1,234")
        elseif (str_contains($val, ',')) {
            // Deteksi jika koma berfungsi sebagai desimal (Indonesian decimal)
            $val = str_replace(',', '.', $val);
        }
        // Jika hanya mengandung titik (misal: "2.000.000" or "2.000")
        elseif (str_contains($val, '.')) {
            if (substr_count($val, '.') > 1) {
                // Banyak titik -> Ribuan (Indonesian)
                $val = str_replace('.', '', $val);
            } else {
                // Satu titik. Jika tidak match float standar, biarkan PHP menanganinya.
            }
        }

        return is_numeric($val) ? (float) $val : 0.0;
    }

    /**
     * 10. EXPORT RAB TERKUNCI KE EXCEL SESUAI LAYOUT
     */
    public function export_rab_excel($constructionId)
    {
        // 1. Ambil data proyek
        $project = $this->db->table('construction_requests')->where('id', $constructionId)->get()->getRowArray();
        if (!$project) {
            return redirect()->back()->with('error', 'Data proyek tidak ditemukan.');
        }

        // 2. Ambil data RAB terurut
        $rabList = $this->db->table('construction_rabs')
            ->where('construction_id', $constructionId)
            ->orderBy('roman_number', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();

        $pekerjaan = 'construction#' . $project['id'];
        $lokasi = $project['address'] ?: '-';
        $pemilik = $project['full_name'] ?: '-';
        $buildingArea = (float) ($project['building_area'] ?: 270);

        // Pre-processing groupings
        $groupMetadata = [];
        foreach ($rabList as $rab) {
            $rom = trim($rab['roman_number'] ?: 'I');
            if (!isset($groupMetadata[$rom])) {
                $groupMetadata[$rom] = [
                    'has_subgroups' => false,
                    'subgroups' => [],
                    'rows' => [],
                    'group_name' => $rab['group_name'] ?: 'PEKERJAAN'
                ];
            }
            $section = trim($rab['section_group'] ?? '');
            if ($section !== '') {
                $groupMetadata[$rom]['has_subgroups'] = true;
                if (!in_array($section, $groupMetadata[$rom]['subgroups'])) {
                    $groupMetadata[$rom]['subgroups'][] = $section;
                }
            }
            $groupMetadata[$rom]['rows'][] = $rab;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RAB Proyek');
        $sheet->setShowGridLines(true);

        // ── 1. HEADER UTAMA (HIJAU TUA) ──
        $sheet->setCellValue('A1', 'RENCANA ANGGARAN BIAYA ( RAB )');
        $sheet->mergeCells('A1:F1');
        $sheet->getRowDimension(1)->setRowHeight(35);

        $titleStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 14,
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '008B4B'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($titleStyle);

        // ── 2. METADATA PROYEK (BARIS 3-5) ──
        $sheet->setCellValue('A3', 'Pekerjaan :');
        $sheet->setCellValue('B3', $pekerjaan);
        $sheet->mergeCells('B3:D3');

        $sheet->setCellValue('A4', 'Lokasi :');
        $sheet->setCellValue('B4', $lokasi);
        $sheet->mergeCells('B4:D4');

        $sheet->setCellValue('A5', 'Pemilik :');
        $sheet->setCellValue('B5', $pemilik);
        $sheet->mergeCells('B5:D5');

        $sheet->setCellValue('E3', 'Luas Bangunan : ' . $buildingArea . ' m2');
        $sheet->mergeCells('E3:F3');

        $infoStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 11,
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A3:F5')->applyFromArray($infoStyle);
        $sheet->getRowDimension(3)->setRowHeight(18);
        $sheet->getRowDimension(4)->setRowHeight(18);
        $sheet->getRowDimension(5)->setRowHeight(18);

        // ── 3. HEADER TABEL (BARIS 7) ──
        $sheet->setCellValue('A7', 'NO');
        $sheet->setCellValue('B7', 'DAFTAR ITEM PEKERJAAN');
        $sheet->setCellValue('C7', 'VOLUME');
        $sheet->setCellValue('D7', 'SAT');
        $sheet->setCellValue('E7', 'HARGA SATUAN (Rp)');
        $sheet->setCellValue('F7', 'JUMLAH HARGA (Rp)');

        $sheet->getRowDimension(7)->setRowHeight(25);

        $tableHeaderStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 10,
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '92D050'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A7:F7')->applyFromArray($tableHeaderStyle);

        // ── 4. DATA ROWS LOOP ──
        $currentRow = 8;
        $subtotalRows = []; // Untuk tracker formula grand total

        $borderStyleData = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ]
        ];

        foreach ($groupMetadata as $rom => $meta) {
            // Write Roman Group Header Row
            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $sheet->setCellValue('A' . $currentRow, $rom);
            $sheet->setCellValue('B' . $currentRow, $rom . '. ' . strtoupper($meta['group_name']));

            // Format Group Header Row
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($borderStyleData);
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $startRangeRow = $currentRow; // include group header in sum range is safe as total is 0/empty
            $currentRow++;

            if ($meta['has_subgroups']) {
                $subgroupCounter = 1;
                foreach ($meta['subgroups'] as $subgroup) {
                    // Write Sub Group Header Row
                    $sheet->getRowDimension($currentRow)->setRowHeight(20);

                    // Parse leading number for Column A if it exists
                    $noVal = $subgroupCounter;
                    if (preg_match('/^\d+/', $subgroup, $matches)) {
                        $noVal = $matches[0];
                    }
                    $sheet->setCellValue('A' . $currentRow, $noVal);
                    $sheet->setCellValue('B' . $currentRow, $subgroup);

                    // Format Sub Group Header Row
                    $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($borderStyleData);
                    $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->getFont()->setBold(true);
                    $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $currentRow++;
                    $subgroupCounter++;

                    // Write activity detail rows under this subgroup
                    foreach ($meta['rows'] as $row) {
                        if (trim($row['section_group'] ?? '') === $subgroup) {
                            $sheet->getRowDimension($currentRow)->setRowHeight(20);
                            $sheet->setCellValue('B' . $currentRow, $row['activity_name']);
                            $sheet->setCellValue('C' . $currentRow, $row['volume']);
                            $sheet->setCellValue('D' . $currentRow, $row['unit']);
                            $sheet->setCellValue('E' . $currentRow, $row['current_unit_price']);
                            $sheet->setCellValue('F' . $currentRow, '=C' . $currentRow . '*E' . $currentRow);

                            // Format detail row
                            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($borderStyleData);
                            $sheet->getStyle('C' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheet->getStyle('D' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                            $sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                            $currentRow++;
                        }
                    }
                }
            } else {
                // No subgroups: write detail rows sequentially numbered in column A
                $rowCounter = 1;
                foreach ($meta['rows'] as $row) {
                    $sheet->getRowDimension($currentRow)->setRowHeight(20);
                    $sheet->setCellValue('A' . $currentRow, $rowCounter);
                    $sheet->setCellValue('B' . $currentRow, $row['activity_name']);
                    $sheet->setCellValue('C' . $currentRow, $row['volume']);
                    $sheet->setCellValue('D' . $currentRow, $row['unit']);
                    $sheet->setCellValue('E' . $currentRow, $row['current_unit_price']);
                    $sheet->setCellValue('F' . $currentRow, '=C' . $currentRow . '*E' . $currentRow);

                    // Format detail row
                    $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($borderStyleData);
                    $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('D' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                    $currentRow++;
                    $rowCounter++;
                }
            }

            // Write Subtotal Row
            $endRangeRow = $currentRow - 1;
            $sheet->getRowDimension($currentRow)->setRowHeight(22);
            $sheet->setCellValue('B' . $currentRow, 'SUB TOTAL PEKERJAAN ' . $rom);
            $sheet->mergeCells('B' . $currentRow . ':E' . $currentRow);
            $sheet->setCellValue('F' . $currentRow, '=SUM(F' . $startRangeRow . ':F' . $endRangeRow . ')');

            // Format Subtotal Row
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($borderStyleData);
            $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $subtotalRows[] = 'F' . $currentRow;
            $currentRow++;
        }

        // ── 5. FOOTER SUMMARY BLOCK (JUMLAH TOTAL, DIBULATKAN, HARGA/M2) ──
        $currentRow++; // Skip 1 row

        $footerTotalStyle = [
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '92D050'], // Hijau Muda sesuai gambar
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // 5.1 Jumlah Total Row
        $sheet->getRowDimension($currentRow)->setRowHeight(22);
        $sheet->setCellValue('A' . $currentRow, 'Jumlah Total');
        $sheet->mergeCells('A' . $currentRow . ':E' . $currentRow);
        $sheet->setCellValue('F' . $currentRow, '=' . implode('+', $subtotalRows));
        $jumlahTotalRow = $currentRow;

        $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($footerTotalStyle);
        $currentRow++;

        // 5.2 Dibulatkan Row
        $sheet->getRowDimension($currentRow)->setRowHeight(22);
        $sheet->setCellValue('A' . $currentRow, 'Dibulatkan');
        $sheet->mergeCells('A' . $currentRow . ':E' . $currentRow);
        $sheet->setCellValue('F' . $currentRow, '=ROUND(F' . $jumlahTotalRow . ', -5)');
        $dibulatkanRow = $currentRow;

        $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($footerTotalStyle);
        $currentRow++;

        // 5.3 Harga / m2 Row
        $sheet->getRowDimension($currentRow)->setRowHeight(22);
        $sheet->setCellValue('A' . $currentRow, 'Harga / m2');
        $sheet->mergeCells('A' . $currentRow . ':E' . $currentRow);
        $sheet->setCellValue('F' . $currentRow, '=ROUND(F' . $dibulatkanRow . '/' . ($buildingArea > 0 ? $buildingArea : 270) . ', 0)');

        $sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray($footerTotalStyle);

        // ── 6. FORMAT DATA NUMERIK ──
        $customNumberFormat = '#,##0.00;-#,##0.00;""';
        $sheet->getStyle('C8:C' . $currentRow)->getNumberFormat()->setFormatCode($customNumberFormat);
        $sheet->getStyle('E8:F' . $currentRow)->getNumberFormat()->setFormatCode($customNumberFormat);

        // ── 7. FOOTER PAYMENT & TERMIN BLOCK ──
        $currentRow += 2; // Beri jarak 2 baris kosong

        // bank details on the left (cols B-D), term details on the right (cols E-F)
        $sheet->getRowDimension($currentRow)->setRowHeight(20);
        $sheet->setCellValue('B' . $currentRow, 'Pembayaran di transfer ke Rekening :');
        $sheet->mergeCells('B' . $currentRow . ':D' . $currentRow);
        $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
        $sheet->setCellValue('E' . $currentRow, 'Termin I 30%');
        $sheet->setCellValue('F' . $currentRow, '=F' . $dibulatkanRow . '*0.3');

        $currentRow++;
        $sheet->getRowDimension($currentRow)->setRowHeight(20);
        $sheet->setCellValue('B' . $currentRow, 'BCA a.n PENDOWO TIGA CONSTRUCTION (PT)');
        $sheet->mergeCells('B' . $currentRow . ':D' . $currentRow);
        $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
        $sheet->setCellValue('E' . $currentRow, 'Termin II 30%');
        $sheet->setCellValue('F' . $currentRow, '=F' . $dibulatkanRow . '*0.3');

        $currentRow++;
        $sheet->getRowDimension($currentRow)->setRowHeight(20);
        $sheet->setCellValue('B' . $currentRow, '814.462.044');
        $sheet->mergeCells('B' . $currentRow . ':D' . $currentRow);
        $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
        $sheet->setCellValue('E' . $currentRow, 'Termin III 30%');
        $sheet->setCellValue('F' . $currentRow, '=F' . $dibulatkanRow . '*0.3');

        $currentRow++;
        $sheet->getRowDimension($currentRow)->setRowHeight(20);
        $sheet->setCellValue('E' . $currentRow, 'Termin IV 7,5%');
        $sheet->setCellValue('F' . $currentRow, '=F' . $dibulatkanRow . '*0.075');

        $currentRow++;
        $sheet->getRowDimension($currentRow)->setRowHeight(20);
        $sheet->setCellValue('E' . $currentRow, 'Termin V (Pelunasan) 2,5%');
        $sheet->setCellValue('F' . $currentRow, '=F' . $dibulatkanRow . '*0.025');

        // Format Termin Block
        $terminStartRow = $currentRow - 4;
        $sheet->getStyle('E' . $terminStartRow . ':F' . $currentRow)->applyFromArray([
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ]
        ]);
        $sheet->getStyle('E' . $terminStartRow . ':E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('F' . $terminStartRow . ':F' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('F' . $terminStartRow . ':F' . $currentRow)->getNumberFormat()->setFormatCode($customNumberFormat);

        // Set Lebar Kolom
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(22);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="RAB_Proyek_' . $project['id'] . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}