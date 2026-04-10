<?php
// FILE: app/ThirdParty/vendor_manual/autoloader.php

spl_autoload_register(function ($class) {
    // Tentukan base direktori tempat semua library kita berada
    $baseDir = __DIR__ . '/';

    // Mapping dari namespace ke nama folder yang sudah kita buat
    $prefixMap = [
        'sngrl\\PhpFirebaseCloudMessaging\\' => 'sngrl/',
        'GuzzleHttp\\Promise\\'              => 'Promises/',
        'GuzzleHttp\\Psr7\\'                 => 'Psr7/',
        'GuzzleHttp\\'                      => 'GuzzleHttp/',
        'Psr\\Http\\Client\\'               => 'PsrHttpClient/',
        'Psr\\Http\\Factory\\'              => 'PsrHttpFactory/',
        'Psr\\Http\\Message\\'              => 'PsrHttpMessage/',
        'Ralouphie\\GetAllHeaders\\'        => 'Ralouphie/',
    ];

    // Cari prefix yang cocok dengan class yang dipanggil
    foreach ($prefixMap as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue; // Lanjut ke prefix berikutnya jika tidak cocok
        }

        // Dapatkan nama file relatif
        $relativeClass = substr($class, $len);
        $file = $baseDir . $dir . str_replace('\\', '/', $relativeClass) . '.php';

        // Jika file ada, load file tersebut
        if (file_exists($file)) {
            require $file;
            return; // Hentikan pencarian
        }
    }
});
