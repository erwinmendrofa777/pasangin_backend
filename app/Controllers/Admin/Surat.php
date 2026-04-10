<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

// Import class Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

class Surat extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function exportPdf($id)
    {
        helper('terbilang');
        $tanggal_kontrak = date('Y-m-d');
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);

        $data = [
            // construction_rabs - construction_requests - users
            'template_kontrak' => $this->db->table('construction_requests')
                                            ->select('construction_requests.address as address_construction,
                                                    construction_requests.id as construction_id,
                                                    users.full_name as nama_klien,
                                                    users.nik as nik_klien,
                                                    users.address as address_klien,
                                                    vouchers.discount_nominal')
                                            ->join('users', 'users.id = construction_requests.user_id','left')
                                            ->join('vouchers', 'vouchers.code = construction_requests.voucher_code','left')
                                            ->where('construction_requests.id', $id)
                                            ->get()->getRowArray(),
            'rab' => $this->db->table('construction_rabs')
                                ->select('group_name, SUM(total_price) as total_price')
                                ->where('construction_rabs.construction_id', $id)
                                ->groupBy('roman_number','group_name')
                                ->orderBy('roman_number', 'ASC')
                                ->get()->getResultArray(),
            'kalimat_pembuka' => tanggal_surat_indo($tanggal_kontrak),
            'tanggal_kontrak' => $tanggal_kontrak,
        ];

        // 2. Ambil output HTML dari View
        // Fungsi view() mengembalikan string HTML jika dipanggil seperti ini
        $html = view('admin/surat/kontrak_template', $data);

        // 3. Konfigurasi Dompdf (Opsional tapi disarankan)
        $options = new Options();
        // Mengizinkan Dompdf untuk memuat gambar dari URL/path luar jika nanti Anda butuh logo
        $options->set('isRemoteEnabled', true); 
        
        // 4. Inisialisasi Dompdf dengan konfigurasi
        $dompdf = new Dompdf($options);

        // 5. Masukkan string HTML ke dalam Dompdf
        $dompdf->loadHtml($html);

        // 6. Atur ukuran kertas dan orientasinya
        // Ukuran: 'A4', 'letter', 'legal', dll. Orientasi: 'portrait' atau 'landscape'
        $dompdf->setPaper('A4', 'portrait');

        // 7. Render (proses konversi) HTML menjadi PDF
        $dompdf->render();
        ob_end_clean();

        // Save PDF to server
        $output = $dompdf->output();
        $nama_klien = $data['template_kontrak']['nama_klien'] ?? 'user';
        $clean_nama = preg_replace('/[^A-Za-z0-9\-]/', '_', $nama_klien);

        $fileName = 'Kontruksi_kontrak_' . $clean_nama .'_'. $id . '.pdf';
        
        $uploadPath = FCPATH . 'uploads/surat_kontrak/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        file_put_contents($uploadPath . $fileName, $output);
        
        // Update database
        $this->db->table('construction_requests')
                 ->where('id', $id)
                 ->update(['rab_file' => $fileName]);

        // 8. Outputkan file PDF ke browser
        // Parameter "Attachment" => 1 akan membuat file otomatis terunduh.
        // Jika ingin PDF hanya ditampilkan (preview) di browser, ubah menjadi "Attachment" => 0.
        $dompdf->stream($fileName, ["Attachment" => 0]);
        exit();
    }

    public function renovationExportPdf($id)
    {
        helper('terbilang');
        $tanggal_kontrak = date('Y-m-d');
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);

        $data = [
            // construction_rabs - construction_requests - users
            'template_kontrak' => $this->db->table('renovation_requests')
                                            ->select('renovation_requests.address as address_renovation,
                                                    renovation_requests.id as renovation_id,
                                                    users.full_name as nama_klien,
                                                    users.nik as nik_klien,
                                                    users.address as address_klien,
                                                    vouchers.discount_nominal')
                                            ->join('users', 'users.id = renovation_requests.user_id','left')
                                            ->join('vouchers', 'vouchers.code = renovation_requests.voucher_code','left')
                                            ->where('renovation_requests.id', $id)
                                            ->get()->getRowArray(),
            'rab' => $this->db->table('renovation_rabs')
                                ->select('group_name, SUM(total_price) as total_price')
                                ->where('renovation_rabs.renovation_id', $id)
                                ->groupBy('roman_number','group_name')
                                ->orderBy('roman_number', 'ASC')
                                ->get()->getResultArray(),
            'kalimat_pembuka' => tanggal_surat_indo($tanggal_kontrak),
            'tanggal_kontrak' => $tanggal_kontrak,
        ];

        // 2. Ambil output HTML dari View
        // Fungsi view() mengembalikan string HTML jika dipanggil seperti ini
        $html = view('admin/surat/kontrak_template_renovation', $data);

        // 3. Konfigurasi Dompdf (Opsional tapi disarankan)
        $options = new Options();
        // Mengizinkan Dompdf untuk memuat gambar dari URL/path luar jika nanti Anda butuh logo
        $options->set('isRemoteEnabled', true); 
        
        // 4. Inisialisasi Dompdf dengan konfigurasi
        $dompdf = new Dompdf($options);

        // 5. Masukkan string HTML ke dalam Dompdf
        $dompdf->loadHtml($html);

        // 6. Atur ukuran kertas dan orientasinya
        // Ukuran: 'A4', 'letter', 'legal', dll. Orientasi: 'portrait' atau 'landscape'
        $dompdf->setPaper('A4', 'portrait');

        // 7. Render (proses konversi) HTML menjadi PDF
        $dompdf->render();
        ob_end_clean();

        // Save PDF to server
        $output = $dompdf->output();
        $nama_klien = $data['template_kontrak']['nama_klien'] ?? 'user';
        $clean_nama = preg_replace('/[^A-Za-z0-9\-]/', '_', $nama_klien);

        $fileName = 'Renovasi_kontrak_' . $clean_nama .'_'. $id . '.pdf';
        
        $uploadPath = FCPATH . 'uploads/surat_kontrak/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        file_put_contents($uploadPath . $fileName, $output);
        
        // Update database
        $this->db->table('renovation_requests')
                 ->where('id', $id)
                 ->update(['rab_file' => $fileName]);

        // 8. Outputkan file PDF ke browser
        // Parameter "Attachment" => 1 akan membuat file otomatis terunduh.
        // Jika ingin PDF hanya ditampilkan (preview) di browser, ubah menjadi "Attachment" => 0.
        $dompdf->stream($fileName, ["Attachment" => 0]);
        exit();
    }
}