<?php

namespace Config;

use CodeIgniter\Settings\Config\Settings as BaseSettings;

class Settings extends BaseSettings
{
    // Hapus 'array' di depannya agar tidak error
    public $handlers = ['array'];

    // Paksa tabel dikosongkan (meskipun tidak dipakai)
    public $databaseTable = 'settings';
}
