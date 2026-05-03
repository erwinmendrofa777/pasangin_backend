<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;
use Config\Validations\UserRules;
use Config\Validations\SupplierRules;
use Config\Validations\ProductRules;
use Config\Validations\AdminRules;
use Config\Validations\AuthRules;
use Config\Validations\BannerRules;
use Config\Validations\ConstructionRules;
use Config\Validations\DesignRules;
use Config\Validations\NotificationRules;
use Config\Validations\OrderRules;
use Config\Validations\PriceEstimateRules;
use Config\Validations\PromoRules;
use Config\Validations\RenovationRules;
use Config\Validations\RoleRules;
use Config\Validations\SyaratKetentuanRules;
use Config\Validations\TipsRules;
use Config\Validations\TukangRules;
use Config\Validations\VoucherRules;
use Config\Validations\WalletRules;

class Validation extends BaseConfig
{
    // Menggunakan trait untuk memisahkan aturan per modul agar file ini tidak menumpuk
    use UserRules, SupplierRules, ProductRules, AdminRules, AuthRules, BannerRules, ConstructionRules, DesignRules, NotificationRules, OrderRules, PriceEstimateRules, PromoRules, RenovationRules, RoleRules, SyaratKetentuanRules, TipsRules, TukangRules, VoucherRules, WalletRules;

    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        \App\Validation\UserRules::class, // Class kustom kita
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    // Aturan validasi didefinisikan di dalam file Trait di folder Validations/
}
