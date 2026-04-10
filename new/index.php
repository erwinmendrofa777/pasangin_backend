<?php

// 1. Definisikan FCPATH
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// 2. Load Autoloader Vendor
require_once FCPATH . 'vendor/autoload.php';

// 3. Load Paths (PENTING: Harus sebelum bootstrap)
require_once FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();

// 4. Load Framework Bootstrapper (Tanpa pesan upgrade)
require_once $paths->systemDirectory . '/bootstrap.php';

// 5. Jalankan Aplikasi
$app = Config\Services::codeigniter();
$app->initialize();
$app->run();