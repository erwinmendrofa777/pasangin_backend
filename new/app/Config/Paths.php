<?php

namespace Config;

/**
 * Paths Configuration
 *
 * Sejak CI 4.5+, class ini TIDAK BOLEH extends BaseConfig.
 * Ini hanya class sederhana untuk menyimpan lokasi folder.
 */
class Paths
{
    // Lokasi folder system di dalam vendor
    public string $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    // Lokasi folder app (naik satu level dari Config)
    public string $appDirectory = __DIR__ . '/..';

    // Lokasi folder writable
    public string $writableDirectory = __DIR__ . '/../../writable';

    // Lokasi folder tests
    public string $testsDirectory = __DIR__ . '/../../tests';

    // Lokasi folder Views
    public string $viewDirectory = __DIR__ . '/../Views';
}