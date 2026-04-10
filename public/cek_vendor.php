<?php
$path = __DIR__ . '/../vendor/kreait';
if (is_dir($path)) {
    echo "✅ Folder KREAIT DITEMUKAN!";
} else {
    echo "❌ Folder KREAIT TIDAK ADA di: " . $path;
    echo "<br>Isi folder vendor saat ini:<br>";
    $files = scandir(__DIR__ . '/../vendor');
    print_r($files);
}