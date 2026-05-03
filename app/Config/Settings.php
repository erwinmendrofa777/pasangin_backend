<?php

namespace Config;

use CodeIgniter\Settings\Config\Settings as BaseSettings;

class Settings extends BaseSettings
{
    /**
     * The available handlers.
     * Mengubah dari 'database' menjadi 'array' agar tidak memerlukan
     * tabel database 'settings'.
     *
     * @var list<string>
     */
    public $handlers = ['array'];
}
