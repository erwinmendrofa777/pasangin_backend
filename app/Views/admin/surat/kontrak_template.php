<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontrak Pekerjaan Borongan</title>
    <style>
        /* Mengatur ukuran margin asli PDF dari Dompdf */
        @page {
            margin: 1.5cm 2.5cm 1.5cm 2.5cm; 
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            line-height: 1.5;
            color: #000;
            /* Hilangkan background abu-abu karena ini untuk dicetak */
            background-color: #fff; 
        }
        
        /* Utility Classes */
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        
        /* Spacing */
        .mb-1 { margin-bottom: 10px; }
        .mb-2 { margin-bottom: 20px; }
        .mt-2 { margin-top: 20px; }
        .mt-3 { margin-top: 40px; }
        
        h2 { font-size: 18px; text-decoration: underline; margin: 0 0 10px 0; padding: 0; }
        
        /* Tabel Umum */
        table { width: 100%; border-collapse: collapse; }
        .tabel-layout td { vertical-align: top; padding: 2px 0; }
        
        /* Tabel RAB */
        .tabel-rab { margin-bottom: 15px; }
        .tabel-rab th, .tabel-rab td { border: 1px solid #000; padding: 5px; }
        .tabel-rab th { text-align: center; font-weight: bold; }
        
        /* List */
        ul, ol { padding-left: 20px; margin-top: 5px; margin-bottom: 15px; }
        p { margin-top: 0; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="text-center mb-2">
        <h2>KONTRAK PEKERJAAN BORONGAN</h2>
    </div>

    <table class="tabel-layout mb-2">
        <tr>
            <td width="15%">Nomor</td>
            <td width="2%">:</td>
            <td width="83%">(template)</td>
        </tr>
        <tr>
            <td>Proyek</td>
            <td>:</td>
            <td>Proyek #<?= $template_kontrak['construction_id'] ?></td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td>:</td>
            <td><?= $template_kontrak['address_construction'] ?></td>
        </tr>
    </table>

    <div class="text-justify mb-2">
        <p><?= $kalimat_pembuka ?></p>
    </div>

    <table class="tabel-layout mb-1">
        <tr>
            <td width="20%">Nama</td>
            <td width="2%">:</td>
            <td width="78%"><?= $template_kontrak['nama_klien'] ?></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td><?= $template_kontrak['nik_klien'] ?></td>
        </tr>
        <tr>
            <td>Berkedudukan di</td>
            <td>:</td>
            <td class="text-justify"><?= $template_kontrak['address_klien'] ?>  </td>
        </tr>
    </table>
    <p class="mb-2">Selanjutnya disebut PIHAK PERTAMA.</p>

    <table class="tabel-layout mb-1">
        <tr>
            <td width="20%">Nama</td>
            <td width="2%">:</td>
            <td width="78%">HUDAN TOHA INDRAYATA</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>3374112309990003</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>DIREKTUR</td>
        </tr>
        <tr>
            <td>Berkedudukan di</td>
            <td>:</td>
            <td>JL. KRAMAT I, RT 02 RW 09, BANGETAYU WETAN, GENUK, SEMARANG</td>
        </tr>
    </table>
    <p class="text-justify mb-2">Dalam hal ini bertindak untuk dan atas nama PT. PENDOWO TIGA CONSTRUCTION, sebuah badan usaha yang didirikan berdasarkan hukum Indonesia, beralamat di Jalan Ki Ageng Getas Pendowo, RT 02 RW 12, Kelurahan Kuripan, Kecamatan Purwodadi, Kabupaten Grobogan, Selanjutnya disebut PIHAK KEDUA.</p>

    <div class="text-center bold mb-1">PASAL I <br> LINGKUP PEKERJAAN</div>
    <p class="text-justify mb-2">PIHAK PERTAMA memberi tugas kepada PIHAK KEDUA dan PIHAK KEDUA menerima untuk pekerjaan seperti tersebut di atas sesuai dengan RKS dan Gambar Bestek dan Hasil Negosiasi yang sudah disepakati oleh PIHAK PERTAMA DAN PIHAK KEDUA.</p>

    <div class="text-center bold mb-1">PASAL II <br> NILAI KONTRAK</div>
    <p>1. NILAI KONTRAK (Target Waktu Pekerjaan (template))</p>

    <table class="tabel-rab mb-1">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="50%">REKAPITULASI PEKERJAAN</th>
                <th width="25%">TOTAL</th>
                <th width="20%">BOBOT PEKERJAAN</th>
            </tr>
        </thead>
        <tbody>
            <?php $sub_total_rab = array_sum(array_column($rab, 'total_price')); ?>
            <?php foreach ($rab as $key => $value): ?>
                <?php $bobot = $sub_total_rab > 0 ? ($value['total_price'] / $sub_total_rab) * 100 : 0; ?>
                <tr>
                    <td class="text-center"><?= $key + 1 ?></td>
                    <td><?= $value['group_name'] ?></td>
                    <td class="text-right"><?= number_format($value['total_price'], 2) ?></td>
                    <td class="text-center"><?= number_format($bobot, 2) ?>%</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" class="text-right bold">Sub Total</td>
                <td class="text-right bold"><?= number_format($sub_total_rab, 2) ?></td>
                <td class="text-center bold">100%</td>
            </tr>
            <?php 
                $sisa = fmod($sub_total_rab, 10000);
                if ($sisa < 8000) {
                    $dibulatkan = $sub_total_rab - $sisa;
                } else {
                    $dibulatkan = $sub_total_rab - $sisa + 10000;
                }
            ?>
            <tr>
                <td colspan="2" class="text-right bold">Dibulatkan</td>
                <td class="text-right bold"><?= number_format($dibulatkan, 2) ?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" class="text-right bold">Diskon</td>
                <?php $diskon = $template_kontrak['discount_nominal'] ?? 0; ?>
                <td class="text-right bold">(<?= number_format($diskon, 2) ?>)</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" class="text-right bold">GRAND TOTAL</td>
                <?php $grand_total = $dibulatkan - $diskon; ?>
                <td class="text-right bold"><?= number_format($grand_total, 2) ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <p class="mb-2">
        *RAB Terlampir <br>
        Terbilang : <?= ucwords(terbilang($grand_total)) ?> Rupiah,- <br>
    </p>

    <div class="text-center bold mb-1">PASAL III <br> CARA PEMBAYARAN</div>
    <p>1. Pembayaran akan dilaksanakan oleh PIHAK PERTAMA kepada PIHAK KEDUA adalah sebagai berikut:</p>
    <ul style="list-style-type: none; padding-left: 0;">
        <li class="text-justify">- Pembayaran Pertama (Uang Muka) sebesar Rp. (template) saat penandatanganan kontrak ((template)).</li>
        <li class="text-justify">- Pembayaran Kedua sebesar Rp. (template) saat progress pekerjaan mencapai 20% ((template)).</li>
        <li class="text-justify">- Pembayaran Ketiga sebesar Rp. (template) saat progress pekerjaan mencapai 40% ((template)).</li>
        <li class="text-justify">- Pembayaran Keempat sebesar Rp. (template) saat progress pekerjaan mencapai 60% ((template)).</li>
        <li class="text-justify">- Pembayaran Kelima (Pelunasan) sebesar Rp. (template) saat progress mencapai 100%.</li>
        <li class="text-justify">- Pembayaran Keenam (Retensi) sebesar Rp. (template), dibayarkan dua kali (2x), 6 bulan setelah serah terima dan 12 bulan setelah serah terima.</li>
    </ul>
    <p>2. Pembayaran dari PIHAK PERTAMA kepada PIHAK KEDUA dilakukan ke rekening PIHAK KEDUA sebagai berikut:</p>
    
    <table class="tabel-layout mb-2" style="width: 70%; margin-left: 15px;">
        <tr><td width="30%">Nama Bank</td><td width="5%">:</td><td>BCA CABANG UNIT PURWODADI</td></tr>
        <tr><td>Atas Nama</td><td>:</td><td>PENDOWO TIGA CONSTRUCTION, PT</td></tr>
        <tr><td>No. Rekening</td><td>:</td><td>081.446.2044</td></tr>
    </table>

    <div class="text-center bold mb-1">PASAL IV <br> PEKERJAAN TAMBAH/KURANG</div>
    <ol class="text-justify mb-2">
        <li>Pekerjaan tambah / kurang hanya dianggap SAH apabila ada perintah secara tertulis dari PIHAK PERTAMA</li>
        <li>Untuk pekerjaan tambahan yang tidak dapat digolongkan pada jenis kegiatan seperti tercantum pada surat penawaran maka harga satuan ditetapkan pada saat perintah pekerjaan tambahan diberikan.</li>
        <li>Jadwal pelaksanaan pekerjaan tambah / kurang disesuaikan dengan kondisi pekerjaan tambah/kurang tersebut.</li>
    </ol>

    <div class="text-center bold mb-1">PASAL V <br> KEADAAN MEMAKSA (FORCE MAJEUR)</div>
    <ol class="text-justify mb-2">
        <li>Yang dimaksud dengan keadaan memaksa (force majeur) adalah keadaan atau peristiwa yang terjadi di luar kekuasaan atau jangkauan kemampuan kedua belah pihak.</li>
        <li>Termasuk keadaan memaksa adalah bencana alam (gempa bumi, hujan terus menerus, banjir, tanah longsor, kebakaran, sabotase, huru hara, perang, pemberontakan)</li>
        <li>Apabila terjadi keadaan memaksa, PIHAK KEDUA akan memberitahukan kepada PIHAK PERTAMA secara tertulis selambat – lambatnya dalam waktu 7x24jam sejak terjadinya keadaan memaksa tersebut dan dalam jangka waktu 3x24jam sejak adanya pemberitahuan tersebut PIHAK PERTAMA sudah memberikan instruksi / keputusan untuk melanjutkan pekerjaan tersebut.</li>
    </ol>

    <div class="text-center bold mb-1">PASAL VI <br> SANKSI</div>
    <ol class="text-justify mb-2">
        <li>Apabila pekerjaan yang dilakukan tidak sesuai dengan spesifikasi (missal jenis bahan material) yang dibuktikan melalui berita acara resmi dan ditandatangani kedua belah pihak, maka PIHAK KEDUA bertanggung jawab untuk mengerjakan ulang dengan spesifikasi yang sesuai dan biaya yang timbul pada saat pembongkaran ditanggung oleh PIHAK KEDUA.</li>
        <li>Kecuali dalam keadaan seperti tersebut dalam Pasal V ayat 1 maka apabila pekerjaan tidak dapat diselesaikan sesuai dengan waktu yang telah disepakati kedua belah pihak, maka PIHAK KEDUA dikenakan denda 0,1% (nol koma satu persen) dari sisa pekerjaan yang belum terselesaikan setiap hari keterlambatan dengan maksimal denda 5% (lima persen) dari nilai kontrak.</li>
        <li>PIHAK PERTAMA dikenakan denda sebesar 0,1% (nol koma satu persen) per hari dari nilai tagihan untuk setiap keterlambatan pembayaran terhitung sejak tanggal jatuh tempo sampai dengan pembayaran dicairkan dan diterima oleh PIHAK KEDUA. Pembayaran yang ditunda seperti Cek/Giro Mundur dikategorikan sebagai keterlambatan pembayaran.</li>
    </ol>

    <div class="text-center bold mb-1">PASAL VII <br> SENGKETA</div>
    <ol class="text-justify mb-2">
        <li>Apabila terjadi sengketa antara kedua belah pihak, maka penyelesaiaannya diutamakan secara musyawarah.</li>
        <li>Jika musyawarah tersebut tidak mencapai mufakat, maka pengambilan keputusan dilakukan melalui Pengadilan Negeri di Grobogan.</li>
    </ol>

    <div class="text-center bold mb-1">PASAL VIII <br> PEMUTUSAN PERJANJIAN</div>
    <ol class="text-justify mb-2">
        <li>
            PIHAK PERTAMA berhak memutuskan perjanjian ini secara sepihak dengan pemberitahuan secara tertulis 7(tujuh) hari sebelum nya setelah melakukan peringatan / teguran 3(tiga) kali berturut turut, apabila PIHAK KEDUA melakukan hal sebagai berikut :
            <ul style="list-style-type: disc; padding-left: 20px; margin-top: 5px;">
                <li class="mb-1">Dalam 15(lima belas) hari terhitung setelah tanggal surat perjanjian ini tidak atau belum mulai pelaksanaan pekerjaan sebagaimana diatur dalam kontrak ini.</li>
                <li class="mb-1">Dalam 7(tujuh) hari berturut turut tidak melanjutkan pekerjaan yang telah dimulai kecuali disebabkan adanya keadaan memaksa (force majeur)</li>
                <li class="mb-1">Memberikan keterangan tidak benar yang merugikan PIHAK PERTAMA sehubungan dengan pekerjaan pembangunan ini.</li>
                <li class="mb-1">Melakukan penyimpangan – penyimpangan terhadap ketentuan ketentuan dan kesepakatan yang telah ditentukan dalam dokumen kontrak.</li>
                <li class="mb-1">Jika terjadi pemutusan perjanjian ini secara sepihak oleh PIHAK PERTAMA sebagaimana dimaksud dalam ayat 1 pasal ini, PIHAK PERTAMA dapat menunjuk pihak lain untuk melanjutkan pekerjaan tersebut dengan biaya dari PIHAK KEDUA.</li>
            </ul>
        </li>
    </ol>

    <div class="text-center bold mb-1">PASAL IX <br> LAIN LAIN</div>
    <ol class="text-justify mb-2">
        <li>Segala sesuatu yang belum diatur dalam surat perjanjian ini atau perubahan yang dipandang perlu oleh kedua belah pihak akan diatur lebih lanjut dalam surat perjanjian tambahan (Addendum) dan merupakan perjanjian yang tidak terpisahkan dari surat perjanjian ini.</li>
        <li>Surat perjanjian ini hanya dapat diubah atas persetujuan bersama.</li>
        <li>Surat perjanjian ini dibuat dalam rangkap 2 (dua) yang sama kuatnya untuk PIHAK PERTAMA dan PIHAK KEDUA , selebihnuya diberikan kepada yang berkepentingan dan ada hubungan nya dengan perjanjian ini.</li>
    </ol>

    <div class="text-center bold mb-1">PASAL X <br> PENUTUP</div>
    <ol class="text-justify mb-2">
        <li>Kontrak ini dianggap sah setelah ditandatangani oleh kedua belah pihak</li>
        <li>Kontrak ini beserta lampiran lampiran nya merupakan kesatuan yang tidak dapat terpisahkan dibuat dalam 2 (dua) rangkap asli bermaterai Rp.10.000,- (sepuluh ribu rupiah) dan masing masing mempunyai kekuatan hukum yang sama.</li>
    </ol>

    <p class="text-center">Disetujui bersama,<br><?= $template_kontrak['address_construction'] ?>, <?= tanggal_indo($tanggal_kontrak) ?></p>

    <table class="mt-3 text-center" width="100%">
        <tr>
            <td width="50%">
                <p>PIHAK KEDUA,</p>
                <br><br><br><br>
                <p class="bold underline">HUDAN TOHA INDRAYATA</p>
            </td>
            <td width="50%">
                <p>PIHAK PERTAMA</p>
                <br><br><br><br>
                <p class="bold underline"><?= $template_kontrak['nama_klien'] ?></p>
            </td>
        </tr>
    </table>

</body>
</html>