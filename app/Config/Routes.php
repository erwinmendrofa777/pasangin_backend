<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// ====================================================================
// --- 1. RUTE LOGIN ADMIN (WEB) ---
// ====================================================================
$routes->get('/', 'Admin\Login::index');
$routes->get('admin/login', 'Admin\Login::index');
$routes->post('admin/login', 'Admin\Login::loginProcess');
$routes->get('admin/logout', 'Admin\Login::logout');


// ====================================================================
// --- 2. GRUP PANEL ADMIN (WAJIB LOGIN ADMIN) ---
// ====================================================================
$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Controllers\Admin'], static function ($routes) {

    // FIX: Sekarang kawan bisa akses /admin tanpa error
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    //MENU USER
    $routes->get('users', 'Users::index');
    $routes->get('users/detail/(:num)', 'Users::detail/$1');
    $routes->get('users/delete/(:num)', 'Users::delete/$1');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->post('users/update_status/(:num)/(:alpha)', 'Users::update_status/$1/$2', ['as' => 'admin.users.update_status']);

    // MENU ADMIN (PEGAWAI)
    $routes->get('admin', 'AdminController::index');
    $routes->get('admin/create', 'AdminController::create');
    $routes->post('admin/store', 'AdminController::store');
    $routes->get('admin/edit/(:num)', 'AdminController::edit/$1');
    $routes->post('admin/update/(:num)', 'AdminController::update/$1');
    $routes->get('admin/delete/(:num)', 'AdminController::delete/$1');

    // MENU ROLES (HAK AKSES)
    $routes->get('roles', 'RoleController::index');
    $routes->get('roles/create', 'RoleController::create');
    $routes->post('roles/store', 'RoleController::store');
    $routes->get('roles/edit/(:num)', 'RoleController::edit/$1');
    $routes->post('roles/update/(:num)', 'RoleController::update/$1');
    $routes->get('roles/delete/(:num)', 'RoleController::delete/$1');

    // MENU PROMO
    $routes->get('promo', 'PromoController::index');
    $routes->get('promo/detail/(:num)', 'PromoController::detail/$1');
    $routes->post('promo/update_status/(:num)/(:alpha)', 'PromoController::update_status/$1/$2');
    $routes->get('promo/delete/(:num)', 'PromoController::delete/$1');

    // MENU SYARAT DAN KETENTUAN
    $routes->get('syarat_ketentuan', 'SyaratKetentuanController::index');
    $routes->get('syarat_ketentuan/detail/(:num)', 'SyaratKetentuanController::detail/$1');
    $routes->get('syarat_ketentuan/delete/(:num)', 'SyaratKetentuanController::delete/$1');
    $routes->get('syarat_ketentuan/create', 'SyaratKetentuanController::create');
    $routes->post('syarat_ketentuan/store', 'SyaratKetentuanController::store');
    $routes->get('syarat_ketentuan/edit/(:num)', 'SyaratKetentuanController::edit/$1');
    $routes->post('syarat_ketentuan/update/(:num)', 'SyaratKetentuanController::update/$1');

    // PENGAJUAN BANNER SUPPLIER
    $routes->get('banner-supplier', 'SupplierBannerController::index');
    $routes->get('banner-supplier/add', 'SupplierBannerController::add');
    $routes->post('banner-supplier/save', 'SupplierBannerController::save');
    $routes->get('banner-supplier/edit/(:num)', 'SupplierBannerController::edit/$1');
    $routes->post('banner-supplier/update/(:num)', 'SupplierBannerController::update/$1');
    $routes->get('banner-supplier/detail/(:num)', 'SupplierBannerController::detail/$1');
    $routes->post('banner-supplier/update-status', 'SupplierBannerController::updateStatus');
    $routes->post('banner-supplier/delete/(:num)', 'SupplierBannerController::delete/$1');

    // MENU NOTIFIKASI
    $routes->get('notification', 'Notification::index');
    $routes->post('notification/send', 'Notification::send');
    $routes->get('notification/create', 'Notification::create');

    // Manajemen Tukang
    $routes->get('tukang', 'Tukang::index');
    $routes->get('tukang/index', 'Tukang::index');
    $routes->get('tukang/create', 'Tukang::create');
    $routes->post('tukang/store', 'Tukang::store');
    $routes->get('tukang/detail/(:num)', 'Tukang::detail/$1');
    $routes->get('tukang/delete/(:num)', 'Tukang::delete/$1');
    $routes->post('tukang/update-status', 'Tukang::update_status');
    $routes->post('tukang/update-verify', 'Tukang::update_verify');

    // Manajemen Pesanan & Wallet
    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/detail/(:num)', 'OrderController::detail/$1');
    $routes->post('orders/update/(:num)', 'OrderController::updateStatus/$1');
    $routes->get('wallet', 'Wallet::index');
    $routes->post('wallet/update-balance', 'Wallet::update_balance');
    $routes->get('wallet/withdrawals', 'Wallet::withdrawals');
    $routes->get('wallet/withdraw-approve/(:num)/(:any)', 'Wallet::update_withdrawal_status/$1/$2');

    // Manajemen Konten
    $routes->get('banner', 'Banner::index');
    $routes->get('banner/create', 'Banner::create');
    $routes->post('banner/store', 'Banner::store');
    $routes->get('banner/delete/(:num)', 'Banner::delete/$1');
    $routes->get('tips/create', 'Tips::create');
    $routes->post('tips/store', 'Tips::store');
    $routes->get('tips/detail/(:num)', 'Tips::show/$1');
    $routes->get('tips/delete/(:num)', 'Tips::delete/$1');
    $routes->resource('tips', ['controller' => 'Tips']);
    $routes->get('vouchers/create', 'Voucher::create');
    $routes->post('vouchers/store', 'Voucher::store');
    $routes->get('vouchers/detail/(:num)', 'Voucher::show/$1');
    $routes->get('vouchers/delete/(:num)', 'Voucher::delete/$1');
    $routes->get('vouchers/update-status/(:num)/(:num)', 'Voucher::updateStatus/$1/$2');
    $routes->resource('vouchers', ['controller' => 'Voucher']);

    // Manajemen Proyek
    $routes->get('design', 'DesignRequests::index');
    $routes->get('design/show/(:num)', 'DesignRequests::show/$1');
    $routes->get('design/delete/(:num)', 'DesignRequests::delete/$1');
    $routes->post('design/update-status/(:num)', 'DesignRequests::updateStatus/$1');
    $routes->post('design/add-survey/(:num)', 'DesignRequests::addSurvey/$1');
    $routes->get('design/delete-survey/(:num)', 'DesignRequests::deleteSurvey/$1');
    $routes->post('design/add-design-result/(:num)', 'DesignRequests::addDesignResult/$1');
    $routes->get('design/delete-design/(:num)', 'DesignRequests::deleteDesign/$1');
    $routes->post('design/add-invoice/(:num)', 'DesignRequests::addInvoice/$1');
    $routes->get('design/verify-payment/(:num)', 'DesignRequests::verifyPayment/$1');
    $routes->get('design/delete-invoice/(:num)', 'DesignRequests::deleteInvoice/$1');
    $routes->post('design/update-progress/(:num)', 'DesignRequests::updateProgress/$1');
    $routes->post('design/create-target/(:num)', 'DesignRequests::createTarget/$1');
    $routes->get('design/delete-target/(:num)/(:num)', 'DesignRequests::deleteTarget/$1/$2');
    $routes->post('design/update-target-progress/(:num)', 'DesignRequests::updateTargetProgress/$1');
    $routes->get('design/approve-design/(:num)', 'DesignRequests::approveDesign/$1');
    $routes->post('design/reject-design/(:num)', 'DesignRequests::rejectDesign/$1');

    $routes->get('construction', 'Construction::index');
    $routes->get('construction/detail/(:num)', 'Construction::detail/$1');
    $routes->post('construction/update-status', 'Construction::updateStatus');
    $routes->post('construction/upload-survey', 'Construction::uploadSurvey');
    $routes->post('construction/upload-design', 'Construction::uploadDesign');
    $routes->post('construction/upload-rab', 'Construction::uploadRab');
    $routes->get('construction/delete-survey/(:num)/(:num)', 'Construction::deleteSurvey/$1/$2');
    $routes->get('construction/delete-design/(:num)/(:num)', 'Construction::deleteDesign/$1/$2');
    $routes->post('construction/add-progress', 'Construction::addProgress');
    $routes->get('construction/delete-progress/(:num)/(:num)', 'Construction::deleteProgress/$1/$2');
    $routes->post('construction/create_invoice', 'Construction::create_invoice');
    $routes->get('construction/delete_invoice/(:num)/(:num)', 'Construction::delete_invoice/$1/$2');
    $routes->post('construction/add-target', 'Construction::add_target');
    $routes->post('construction/update_target_status/(:num)/(:alpha)', 'Construction::update_target_status/$1/$2');
    $routes->get('construction/delete-target/(:num)/(:num)', 'Construction::delete_target/$1/$2');
    $routes->post('construction/update-job-info', 'Construction::update_job_info');
    $routes->post('construction/update_applicant_status', 'Construction::update_applicant_status');
    $routes->post('construction/update_progress_status/(:num)/(:alpha)', 'Construction::update_progress_status/$1/$2');

    //construction target
    $routes->get('construction/target/(:num)', 'Construction::view_target/$1');
    $routes->post('construction/create-target/(:num)', 'Construction::createTarget/$1');
    $routes->post('construction/update-schedule', 'Construction::update_schedule');

    $routes->get('renovation', 'Renovation::index');
    $routes->get('renovation/detail/(:num)', 'Renovation::detail/$1');
    $routes->post('renovation/update_status', 'Renovation::update_status');
    $routes->post('renovation/update-job-info', 'Renovation::update_job_info');
    $routes->post('renovation/save-job-info', 'Renovation::save_job_info');
    $routes->post('renovation/update_applicant_status', 'Renovation::update_applicant_status');
    $routes->post('renovation/add-survey/(:num)', 'Renovation::add_survey/$1');
    $routes->post('renovation/add-design/(:num)', 'Renovation::add_design/$1');
    $routes->get('renovation/delete-design/(:num)/(:num)', 'Renovation::delete_design/$1/$2');
    $routes->post('renovation/add-progress/(:num)', 'Renovation::add_progress/$1');
    $routes->post('renovation/create_invoice', 'Renovation::create_invoice');
    $routes->post('renovation/add-target', 'Renovation::add_target');
    $routes->get('renovation/delete-target/(:num)/(:num)', 'Renovation::delete_target/$1/$2');
    $routes->post('renovation/update_target_status/(:num)/(:alpha)', 'Renovation::update_target_status/$1/$2');
    $routes->get('renovation/delete_invoice/(:num)/(:num)', 'Renovation::delete_invoice/$1/$2');
    $routes->get('renovation/delete_survey/(:num)/(:num)', 'Renovation::delete_survey/$1/$2');
    $routes->post('renovation/update_progress_status/(:num)/(:alpha)', 'Renovation::update_progress_status/$1/$2');

    //renovation target
    $routes->get('renovation/target/(:num)', 'Renovation::view_target/$1');
    $routes->post('renovation/create-target/(:num)', 'Renovation::createTarget/$1');
    $routes->post('renovation/update-schedule', 'Renovation::update_schedule');

    // Manajemen Supplier
    $routes->get('suppliers', 'SupplierController::index', ['as' => 'admin.suppliers.index']);
    $routes->get('suppliers/detail/(:num)', 'SupplierController::detail/$1');
    $routes->get('suppliers/create', 'SupplierController::create');
    $routes->post('suppliers/save', 'SupplierController::save');
    $routes->get('suppliers/edit/(:num)', 'SupplierController::edit/$1');
    $routes->post('suppliers/update/(:num)', 'SupplierController::update/$1');
    $routes->get('suppliers/delete/(:num)', 'SupplierController::delete/$1');
    $routes->post('suppliers/update_status/(:num)/(:alpha)', 'SupplierController::updateStatus/$1/$2', ['as' => 'admin.supplier.update_status']);

    //manajemen produk
    $routes->get('products', 'ProductController::index', ['as' => 'admin.products.index']);
    $routes->get('products/detail/(:num)', 'ProductController::detail/$1');
    $routes->post('products/update_status/(:num)/(:segment)', 'ProductController::updateStatus/$1/$2');
    $routes->get('products/delete/(:num)', 'ProductController::delete/$1');

    // Chat Admin
    $routes->get('chat', 'ChatController::index');
    $routes->group('api/chat', function ($routes) {
        $routes->get('(:num)/messages', 'ChatController::getMessages/$1');
        $routes->post('send', 'ChatController::sendMessage');
    });

    // Fitur RAB construksi & renovasi (SINKRON DENGAN ConstructionApi & RenovationApi)
    $routes->post('construction/save_rab_row', 'RabController::save_rab_row');
    $routes->get('construction/delete_rab_row/(:num)', 'RabController::delete_rab_row/$1');
    $routes->get('construction/get_rab_materials/(:num)', 'RabController::get_rab_materials/$1');
    $routes->post('construction/add_rab_material', 'RabController::add_rab_material');
    $routes->get('construction/delete_rab_material/(:num)', 'RabController::delete_rab_material/$1');
    $routes->get('construction/lock_rab/(:num)', 'RabController::lock_rab/$1');
    $routes->get('construction/unlock_rab/(:num)', 'RabController::unlock_rab/$1');
    $routes->get('kontrak/cetak-pdf/(:num)', 'Surat::exportPdf/$1');

    // Fitur ADDENDUM 
    $routes->post('construction/save_addendum_row', 'Construction::save_addendum_row');
    $routes->get('construction/delete_addendum_row/(:num)', 'Construction::delete_addendum_row/$1');
    $routes->get('construction/get_addendum_materials/(:num)', 'Construction::get_addendum_materials/$1');
    $routes->post('construction/add_addendum_material', 'Construction::add_addendum_material');
    $routes->get('construction/delete_addendum_material/(:num)', 'Construction::delete_addendum_material/$1');
    $routes->get('construction/lock_addendum/(:num)', 'Construction::lock_addendum/$1');
    $routes->get('construction/unlock_addendum/(:num)', 'Construction::unlock_addendum/$1');

    $routes->post('renovation/save_rab_row', 'RenovationRabController::save_rab_row');
    $routes->get('renovation/delete_rab_row/(:num)', 'RenovationRabController::delete_rab_row/$1');
    $routes->get('renovation/get_rab_materials/(:num)', 'RenovationRabController::get_rab_materials/$1');
    $routes->post('renovation/add_rab_material', 'RenovationRabController::add_rab_material');
    $routes->get('renovation/delete_rab_material/(:num)', 'RenovationRabController::delete_rab_material/$1');
    $routes->get('renovation/lock_rab/(:num)', 'RenovationRabController::lock_rab/$1');
    $routes->get('renovation/unlock_rab/(:num)', 'RenovationRabController::unlock_rab/$1');
    $routes->get('renovation/cetak-pdf/(:num)', 'Surat::renovationExportPdf/$1');

    $routes->post('save_rab_row', 'Construction::save_rab_row');
    $routes->get('delete_rab_row/(:num)', 'Construction::delete_rab_row/$1');
    $routes->get('get_rab_materials/(:num)', 'Construction::get_rab_materials/$1');
    $routes->post('add_rab_material', 'Construction::add_rab_material');
    $routes->get('delete_rab_material/(:num)', 'Construction::delete_rab_material/$1');

    // --- Rute untuk Manajemen Estimasi Harga ---
    $routes->get('price-estimate', 'PriceEstimateController::index');
    $routes->post('price-estimate/concept/store', 'PriceEstimateController::storeConcept');
    $routes->post('price-estimate/concept/update/(:num)', 'PriceEstimateController::updateConcept/$1');
    $routes->get('price-estimate/concept/delete/(:num)', 'PriceEstimateController::deleteConcept/$1');
    $routes->post('price-estimate/quality/store', 'PriceEstimateController::storeQuality');
    $routes->post('price-estimate/quality/update/(:num)', 'PriceEstimateController::updateQuality/$1');
    $routes->get('price-estimate/quality/delete/(:num)', 'PriceEstimateController::deleteQuality/$1');
});

// ====================================================================
// --- 3. API PUBLIK (TANPA TOKEN) ---
// ====================================================================
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    // Auth client
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');

    // Auth Tukang
    $routes->post('tukang/login', 'TukangAuthController::login');
    $routes->post('tukang/register', 'TukangAuthController::register');
    $routes->post('tukang/verify', 'TukangAuthController::extractSync');

    //Auth Supplier
    $routes->post('supplier/login', 'SupplierAuthController::login');
    $routes->post('supplier/register', 'SupplierAuthController::register');

    //otp register untuk client, tukang, dan supplier
    $routes->post('otp/request', 'AuthController::requestOtp');
    $routes->post('otp/verify', 'AuthController::verifyOtp');
    $routes->post('verify-email', 'AuthController::verifyEmail');

    // Konten & Market Umum
    $routes->get('products', 'ProductApi::index');
    $routes->get('products/show', 'ProductApi::show');
    $routes->get('products/getBySupplier/(:num)', 'ProductApi::getBySupplier/$1');
    $routes->get('suppliers/regions', 'ProductApi::regions');

    //konten
    $routes->get('content/banners', 'ContentController::banners');
    $routes->get('content/tips', 'ContentController::tips');
    $routes->get('content/priceEstimate', 'ContentController::price_estimate');

    // Webhook Payment
    $routes->post('payment/notification', 'PaymentApi::notification');

    // meminta kode OTP
    $routes->post('forgot-password', 'AuthAPI::requestOtp');
    $routes->post('verify-otp', 'AuthAPI::verifyOtp');
    $routes->post('reset-password', 'AuthAPI::resetPassword');
});

// ====================================================================
// --- 4. API PRIVATE (WAJIB TOKEN JWT) ---
// ====================================================================
$routes->group('api', ['namespace' => 'App\Controllers\Api', 'filter' => 'auth'], static function ($routes) {

    // MODUL SUPPLIER (PRODUK SAYA, PESANAN, STATS)
    $routes->get('supplier/stats', 'SupplierOrderApi::stats');
    $routes->get('supplier/my-products', 'ProductApi::myProducts');
    $routes->get('supplier/product/(:num)', 'ProductApi::detailProduct/$1');
    $routes->get('supplier/orders', 'SupplierOrderApi::index');
    $routes->post('supplier/orders/update-status/(:num)', 'SupplierOrderApi::updateStatus/$1');
    $routes->post('supplier/withdraw', 'SupplierOrderApi::withdraw');
    $routes->get('supplier/withdrawals', 'SupplierOrderApi::withdrawalHistory');
    $routes->post('supplier/change-password', 'SupplierAuthController::changePassword');

    // API promo supplier
    $routes->get('supplier/promos', 'PromoApi::index');
    $routes->post('supplier/promos/create', 'PromoApi::create');
    $routes->post('supplier/promos/delete/(:num)', 'PromoApi::delete/$1');
    $routes->get('supplier/promos/show/(:num)', 'PromoApi::show/$1');
    $routes->get('supplier/promos/all', 'PromoApi::getAllPromo');

    // API Rating Supplier
    $routes->get('supplier/ratings/(:num)', 'SuppliersRatingController::index/$1');
    $routes->post('supplier/ratings/create', 'SuppliersRatingController::create');

    // API Ongkir Supplier
    $routes->post('supplier/ongkir/create', 'SupplierOngkirApi::create');
    $routes->post('supplier/ongkir/update/(:num)', 'SupplierOngkirApi::update/$1');
    $routes->post('supplier/ongkir/delete/(:num)', 'SupplierOngkirApi::delete/$1');
    $routes->get('supplier/ongkir/get', 'SupplierOngkirApi::getOngkirByIdSupplier');
    $routes->get('supplier/ongkir', 'SupplierOngkirApi::getAllOngkir');

    // Supplier Banner
    $routes->get('supplier/banner', 'SupplierBannerController::index');
    $routes->post('supplier/banner/create', 'SupplierBannerController::create');
    $routes->get('supplier/banner/show/(:num)', 'SupplierBannerController::show/$1');
    $routes->post('supplier/banner/update/(:num)', 'SupplierBannerController::update/$1');
    $routes->post('supplier/banner/delete/(:num)', 'SupplierBannerController::delete/$1');

    //get approved banner untuk client app
    $routes->get('supplier/banner/approved', 'SupplierBannerController::getApprovedBanners');

    // Detail & Dokumen Proyek
    $routes->post('design/submit', 'DesignController::submit');
    $routes->get('design/history/(:num)', 'DesignController::history/$1');
    $routes->get('design/requests/detail/(:num)', 'DesignController::show/$1');
    $routes->get('design/surveys/(:num)', 'DesignController::surveys/$1');
    $routes->get('design/surveys/detail/(:num)', 'DesignController::detailSurveys/$1');
    $routes->patch('design/surveys/send_comment/(:num)', 'DesignController::sendCommentSurvey/$1');
    $routes->get('design/designs/(:num)', 'DesignController::designs/$1');
    $routes->get('design/targets/(:num)', 'DesignController::targets/$1');
    $routes->get('design/progress/(:num)', 'DesignController::progress/$1');
    $routes->post('design/progress/(:num)', 'DesignController::updateProgress/$1');
    $routes->get('design/invoices/(:num)', 'DesignController::invoices/$1');

    // === MODUL KONSTRUKSI (SINKRON DENGAN ConstructionApi) ===
    $routes->post('construction/submit', 'ConstructionApi::submit');
    $routes->get('construction/project/(:num)', 'ConstructionApi::project/$1');
    $routes->get('construction/detail/(:num)', 'ConstructionApi::detail/$1');
    $routes->get('construction/surveys/(:num)', 'ConstructionApi::surveys/$1');
    $routes->patch('construction/surveys/send_comment/(:num)', 'ConstructionApi::sendCommentSurvey/$1');
    $routes->get('construction/designs/(:num)', 'ConstructionApi::designs/$1');
    $routes->patch('construction/designs/send_comment/(:num)', 'ConstructionApi::sendCommentDesign/$1');
    $routes->get('construction/progress/(:num)', 'ConstructionApi::progress/$1');
    $routes->get('construction/progressByUser', 'ConstructionApi::progressByUser');
    $routes->get('construction/invoices/(:num)', 'ConstructionApi::invoices/$1');
    $routes->get('construction/rabs/(:num)', 'ConstructionApi::rabs/$1');
    $routes->get('construction/targets', 'ConstructionApi::targets');
    $routes->get('construction/targetsByUser', 'ConstructionApi::targetsByUser');
    $routes->patch('construction/select-material', 'ConstructionApi::select_material');
    $routes->patch('construction/finalize-rab', 'ConstructionApi::finalize_rab');

    // Fitur syarat ketentuan
    $routes->get('syarat-ketentuan/(:any)', 'AgreementController::getTermsOfAgreement/$1');
    $routes->post('construction/agreements/batch', 'AgreementController::constructionAgreementsBatch');
    $routes->post('renovation/agreements/batch', 'AgreementController::renovationAgreementsBatch');

    //fitur kontrak
    $routes->get('construction/contract/(:num)', 'ContractApi::construction_contract/$1');
    $routes->get('renovation/contract/(:num)', 'ContractApi::renovation_contract/$1');

    // === MODUL RENOVASI (RenovationApi) ===
    $routes->post('renovation/submit', 'RenovationApi::submit');
    $routes->get('renovation/projects/(:num)', 'RenovationApi::projects/$1');
    $routes->get('renovation/detail/(:num)', 'RenovationApi::detail/$1');
    $routes->get('renovation/surveys/(:num)', 'RenovationApi::surveys/$1');
    $routes->patch('renovation/surveys/send_comment/(:num)', 'RenovationApi::sendCommentSurvey/$1');
    $routes->get('renovation/designs/(:num)', 'RenovationApi::designs/$1');
    $routes->patch('renovation/designs/send_comment/(:num)', 'RenovationApi::sendCommentDesign/$1');
    $routes->get('renovation/progress/(:num)', 'RenovationApi::progress/$1');
    $routes->get('renovation/progressByUser', 'RenovationApi::progressByUser');
    $routes->get('renovation/invoices/(:num)', 'RenovationApi::invoices/$1');
    $routes->get('supplier/profile/(:num)', 'SupplierAuthController::getProfile/$1');
    $routes->get('construction/project/(:num)', 'Construction::history/$1');
    $routes->get('renovation/projects/(:num)', 'Renovation::history/$1');
    $routes->get('renovation/rabs/(:num)', 'RenovationApi::rabs/$1');
    $routes->get('renovation/targets/(:num)', 'RenovationApi::targets/$1');
    $routes->get('renovation/targetsByUser', 'RenovationApi::targetsByUser');
    $routes->patch('renovation/select-material', 'RenovationApi::select_material');
    $routes->patch('renovation/finalize-rab', 'RenovationApi::finalize_rab');

    //alamat user
    $routes->post('alamat', 'AlamatUserController::create');
    $routes->get('alamat', 'AlamatUserController::get');
    $routes->put('alamat/(:num)', 'AlamatUserController::put/$1');
    $routes->patch('alamat/(:num)', 'AlamatUserController::patch/$1');
    $routes->delete('alamat/(:num)', 'AlamatUserController::delete/$1');

    // Resource CRUD
    $routes->resource('products', ['controller' => 'ProductApi', 'except' => ['index', 'regions', 'show']]);
    $routes->post('products/ratings/create', 'ProductApi::createRating');
    $routes->get('products/ratings/(:num)', 'ProductApi::showrating/$1');
    $routes->resource('categories', ['controller' => 'CategoryApi']);
    $routes->post('supplier/update-profile', 'SupplierAuthController::updateProfile');
    $routes->post('supplier/update-fcm', 'SupplierAuthController::updateFcmToken');


    // Modul Lainnya (Client & Tukang)
    $routes->get('vouchers', 'VoucherController::index');
    $routes->group('cart', function ($routes) {
        $routes->get('/', 'CartApi::index');
        $routes->post('add', 'CartApi::add');
        $routes->post('update', 'CartApi::update');
        $routes->post('delete', 'CartApi::delete');
    });
    $routes->post('checkout', 'OrderApi::checkout');
    $routes->get('orders/history', 'OrderApi::history');
    $routes->get('orders/detail/(:any)', 'OrderApi::detail/$1');
    $routes->get('payment/check_status/(:any)', 'PaymentApi::checkStatus/$1');
    $routes->get('payment/token/(:num)', 'PaymentApi::getPaymentToken/$1');
    $routes->get('payment/token/design/(:num)', 'PaymentApi::getDesignPaymentToken/$1');
    $routes->get('payment/token/construction/(:num)', 'PaymentApi::getConstructionPaymentToken/$1');
    $routes->get('payment/token/renovation/(:num)', 'PaymentApi::getRenovationPaymentToken/$1');

    $routes->get('orders/transaction-detail/(:any)', 'Api\OrderApi::transactionDetail/$1');
    $routes->get('orders/transaction-history', 'Api\OrderApi::transactionHistory');
    $routes->post('orders/webhook-midtrans', 'Api\OrderApi::webhookMidtrans');

    // Tukang Private Actions
    $routes->post('tukang/update-profile', 'TukangAuthController::updateProfile');
    $routes->get('tukang/profile/(:num)', 'TukangAuthController::getProfile/$1');
    $routes->get('tukang/jobs/construction', 'TukangJobApi::getConstructionJobs');
    $routes->get('tukang/jobs/renovation', 'TukangJobApi::getRenovationJobs');
    $routes->get('tukang/my-applications/(:num)', 'TukangJobApi::getMyApplications/$1');
    $routes->post('tukang/submit-progress', 'TukangJobApi::submitProgress');
    $routes->get('tukang/my-targets/(:num)', 'TukangJobApi::getMyTargets/$1');
    $routes->post('tukang/job-submit', 'JobApplicationController::submit');
    $routes->get('tukang/wallet/(:num)', 'WalletController::getWalletInfo/$1');
    $routes->post('tukang/withdraw', 'WalletController::requestWithdrawal');
    $routes->get('tukang/application-status/(:num)', 'TukangJobApi::getApplicationStatus/$1');
    $routes->get('tukang/banners', 'TukangContentController::banners');
    $routes->post('tukang/update-ktp', 'TukangAuthController::updateProfileByKtp');
    $routes->get('tukang/progress/(:num)', 'TukangJobApi::getConstructionProgress/$1');
    $routes->post('tukang/progress', 'TukangJobApi::createConstructionProgress');
    $routes->get('tukang/renovation/progress/(:num)', 'TukangJobApi::getRenovationProgress/$1');
    $routes->post('tukang/renovation/progress', 'TukangJobApi::createRenovationProgress');

    // rating tukang
    $routes->get('tukang/ratings/(:num)', 'TukangRatingController::index/$1');
    $routes->post('tukang/ratings/create', 'TukangRatingController::createRatingTukangConstruction');

    // Chat API
    $routes->group('chat', function ($routes) {
        $routes->get('all/(:num)', 'ChatController::getAllConversationsForUser/$1');
        $routes->get('messages/(:num)', 'ChatController::getMessages/$1');
        $routes->post('send', 'ChatController::sendMessage');
        $routes->post('create_or_get', 'ChatController::createOrGetConversation');
        $routes->get('notifications', 'NotificationApi::index'); // Untuk riwayat notif di HP
    });

    // client
    $routes->post('user/update', 'UserController::update');

    // =========================================================================
    // === PERBAIKAN: RUTE NOTIFIKASI UNIVERSAL (ANY) ===
    // =========================================================================
    // Menangani api/tukang/notifications atau api/client/notifications
    $routes->get('(:any)/notifications/(:num)', 'NotificationController::index/$1/$2');
    $routes->post('(:any)/notifications/mark-read', 'NotificationController::markAsRead/$1');
    $routes->post('(:any)/notifications/mark-all-read', 'NotificationController::markAllAsRead/$1');
    $routes->delete('(:any)/notifications/delete/(:num)/(:num)', 'NotificationController::deleteNotification/$1/$2/$3');
    $routes->get('(:any)/notifications/unread-count/(:num)', 'NotificationController::unreadCount/$1/$2');
});

$routes->get('api/notifications', 'Api\NotificationApi::index');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
