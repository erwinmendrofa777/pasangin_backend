<?php

namespace Config;

class Paths
{
    // Dari Config -> naik ke app -> naik ke backend_core -> masuk ke vendor/codeigniter4/framework/system
    // Jadi cukup ../../vendor/codeigniter4/framework/system
    public string $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    // Dari Config -> naik ke app
    public string $appDirectory = __DIR__ . '/..';

    // Dari Config -> naik ke app -> naik ke backend_core -> masuk ke writable
    public string $writableDirectory = __DIR__ . '/../../writable';

    // Dari Config -> naik ke app -> naik ke backend_core -> masuk ke tests
    public string $testsDirectory = __DIR__ . '/../../tests';

    // Dari Config -> naik ke Views
    public string $viewDirectory = __DIR__ . '/../Views';
}