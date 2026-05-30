<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // Maps to /home/stuh8812/backend_core/app
        'App\Modules' => APPPATH . 'Modules',
        'Config' => APPPATH . 'Config',

        // TAMBAHKAN BARIS INI UNTUK LIBRARY JWT KAMU:
        // 'Firebase\\JWT\\' => APPPATH . 'ThirdParty/php-jwt/',
        'Firebase\\JWT' => APPPATH . 'ThirdParty/php-jwt/src',
    ];

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     */
    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Jika kamu punya helper custom yang sering dipakai, bisa masukkan di sini
     */
    public $helpers = ['url', 'form', 'array', 'setting', 'permission'];
}