<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Modules extends BaseConfig
{
    public $enabled = true;
    public $discoverInComposer = false; // PAKSA FALSE untuk stop loop
    public $composerPackages = ['only' => [], 'exclude' => []];
    public $aliases = [];
}