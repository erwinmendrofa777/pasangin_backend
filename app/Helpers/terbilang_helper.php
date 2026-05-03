<?php

if (!function_exists('terbilang')) {
    function terbilang($angka) {
        $angka = abs($angka);
        $baca  = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $terbilang = "";

        if ($angka < 12) {
            $terbilang = " " . $baca[$angka];
        } else if ($angka < 20) {
            $terbilang = terbilang($angka - 10) . " Belas ";
        } else if ($angka < 100) {
            $terbilang = terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
        } else if ($angka < 200) {
            $terbilang = " Seratus" . terbilang($angka - 100);
        } else if ($angka < 1000) {
            $terbilang = terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
        } else if ($angka < 2000) {
            $terbilang = " Seribu" . terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $terbilang = terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $terbilang = terbilang($angka / 1000000) . " Juta " . terbilang($angka % 1000000);
        } else if ($angka < 1000000000000) {
            $terbilang = terbilang($angka / 1000000000) . " Miliar " . terbilang($angka % 1000000000);
        } else if ($angka < 1000000000000000) {
            $terbilang = terbilang($angka / 1000000000000) . " Triliun " . terbilang($angka % 1000000000000);
        }

        return trim($terbilang);
    }
}

if (!function_exists('tanggal_surat_indo')) {
    function tanggal_surat_indo($tanggal_input) {
        $waktu = strtotime($tanggal_input);

        // Array Hari
        $hari_array = array(
            'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 
            'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'
        );
        $hari = $hari_array[date('D', $waktu)];

        // Array Bulan
        $bulan_array = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        $bulan = $bulan_array[date('n', $waktu)];

        // Tanggal dan Tahun dieja dengan fungsi terbilang
        $tgl_ejaan = ucwords(terbilang(date('j', $waktu)));
        $tahun_ejaan = ucwords(terbilang(date('Y', $waktu)));

        // Menggabungkan menjadi format kalimat lengkap
        return "Pada hari ini, <b>$hari</b> tanggal <b>$tgl_ejaan</b> bulan <b>$bulan</b> tahun <b>$tahun_ejaan</b> kami yang bertanda tangan di bawah ini :";
    }
}

if (!function_exists('tanggal_indo')) {
    function tanggal_indo($tanggal) {
        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
        return (int)$pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }
}
