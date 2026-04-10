<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    // Copy dari Screenshot kamu:
    public $serverKey    = 'SB-Mid-server-UKNiwjL6WD2HSFzQ4vP8oKeg'; 
    public $clientKey    = 'SB-Mid-client-cPUU3_Z1IMFiC5-Y';

    // WAJIB FALSE karena ini mode Sandbox
    public $isProduction = false; 

    public $isSanitized  = true;
    public $is3ds        = true;
}
