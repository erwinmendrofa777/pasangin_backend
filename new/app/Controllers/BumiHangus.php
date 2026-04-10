<?php
// FILE: backend/app/Controllers/BumiHangus.php
// CONTROLLER PALING SEDERHANA DI DUNIA

namespace App\Controllers;

class BumiHangus extends BaseController
{
    public function index()
    {
        // JANGAN LAKUKAN APA-APA. JANGAN PROSES DATA. JANGAN REDIRECT.
        // HANYA TAMPILKAN PESAN INI DAN MATI.
        echo "<h1>OPERASI BUMI HANGUS BERHASIL. SISTEM HIDUP.</h1>";
        echo "<p>Masalahnya 100% ada di dalam logika Controller Auth.php atau Filter Anda.</p>";
        exit; // Hentikan eksekusi di sini.
    }
}
