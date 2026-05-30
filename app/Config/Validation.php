<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\FileRules; // FIX: Gunakan non-strict FileRules untuk file upload
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;
use App\Modules\Users\Config\Validations\UserRules;
use App\Modules\Supplier\Config\Validations\SupplierRules;
use App\Modules\Products\Config\Validations\ProductRules;
use App\Modules\Admin\Config\Validations\AdminRules;
use App\Modules\Autentications\Config\Validations\AuthRules;
use App\Modules\Banners\Config\Validations\BannerRules;
use App\Modules\Construction\Config\Validations\ConstructionRules;
use App\Modules\Design\Config\Validations\DesignRules;
use App\Modules\Notifications\Config\Validations\NotificationRules;
use App\Modules\Orders\Config\Validations\OrderRules;
use App\Modules\PriceEstimate\Config\Validations\PriceEstimateRules;
use App\Modules\Supplier\Config\Validations\PromoRules;
use App\Modules\Renovation\Config\Validations\RenovationRules;
use App\Modules\Admin\Config\Validations\RoleRules;
use App\Modules\SyaratKetentuan\Config\Validations\SyaratKetentuanRules;
use App\Modules\Tips\Config\Validations\TipsRules;
use App\Modules\Tukang\Config\Validations\TukangRules;
use App\Modules\Vouchers\Config\Validations\VoucherRules;
use App\Modules\Wallets\Config\Validations\WalletRules;

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
        'list' => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    // Aturan validasi didefinisikan di dalam file Trait di folder Validations/
}
