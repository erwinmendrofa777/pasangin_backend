<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * URL dasar aplikasi kamu.
     */
    public string $baseURL = 'https://backend.pasangin.co.id/';

    /**
     * SOLUSI ERROR LINE 246:
     * Hostname yang diizinkan untuk mengakses aplikasi.
     */
    public array $allowedHostnames = ['backend.pasangin.co.id'];

    /**
     * Pengaturan lainnya (Pastikan tipe data string/bool/array sesuai)
     */
    public string $indexPage = '';
    public string $uriProtocol = 'REQUEST_URI';
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $appTimezone = 'Asia/Jakarta';
    public string $charset = 'UTF-8';
    public bool $forceGlobalSecureRequests = false;
    public array $proxyIPs = [];
    public bool $CSPEnabled = false;

    /**
     * Pengaturan Cookie agar Firefox tidak "Redirect Properly"
     */
    public string $cookiePrefix   = '';
    public string $cookieDomain   = '';
    public string $cookiePath     = '/';
    public bool $cookieSecure     = true; // Set false jika SSL belum stabil di semua browser
    public bool $cookieHTTPOnly   = true;
    public string $cookieSameSite = 'Lax';
}