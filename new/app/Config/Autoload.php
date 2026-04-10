<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    public $psr4 = [
        APP_NAMESPACE     => APPPATH,
        'Config'          => APPPATH . 'Config',
        'Firebase\\JWT\\' => APPPATH . 'ThirdParty/php-jwt/',
    ];

    public $classmap = [];

    // PASTIKAN HANYA ADA SATU BARIS INI
    public $files = [];

    // WAJIB ADA UNTUK CI 4.5+
    public $helpers = [];
}