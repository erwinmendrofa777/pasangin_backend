<?php
// FILE: backend_core/app/Config/Filters.php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'invalidchars' => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'login' => \App\Filters\AdminAuthFilter::class, // Filter Admin (Session)
        'auth' => \App\Filters\Auth::class,           // Filter API (JWT)
    ];

    public array $globals = [
        'before' => [
            // Kosongkan agar tidak ada filter yang berjalan di semua halaman secara global
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        // Kita tidak perlu menuliskan filter 'auth' di sini  , 
        // karena kita sudah memasangnya secara spesifik di file Routes.php 
        // agar lebih akurat dan tidak terjadi tabrakan.
    ];
}