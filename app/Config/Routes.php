<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// --- ROUTE SEMENTARA UNTUK TEST HALAMAN ERROR ---
$routes->get('test-404', function () {
    return view('errors/html/error_404');
});
$routes->get('test-403', function () {
    return view('errors/html/error_403');
});
$routes->get('test-500', function () {
    return view('errors/html/production');
});
$routes->get('test-503', function () {
    return view('errors/html/error_503');
});

// ====================================================================
// --- ROUTE DOKUMENTASI API SWAGGER ---
// ====================================================================
$routes->get('swagger', [\App\Controllers\Swagger::class, 'index']);
$routes->get('swagger/json', [\App\Controllers\Swagger::class, 'json']);


// ====================================================================
// --- 2. GRUP PANEL ADMIN (WAJIB LOGIN ADMIN) ---
// ====================================================================

// Load Module Routes AboutApplication
if (file_exists(APPPATH . 'Modules/AboutApplication/Config/Routes.php')) {
    require APPPATH . 'Modules/AboutApplication/Config/Routes.php';
}

// Load Module Routes Admin
if (file_exists(APPPATH . 'Modules/Admin/Config/Routes.php')) {
    require APPPATH . 'Modules/Admin/Config/Routes.php';
}

// Load Module Routes Autentications
if (file_exists(APPPATH . 'Modules/Autentications/Config/Routes.php')) {
    require APPPATH . 'Modules/Autentications/Config/Routes.php';
}

// Load Module Routes Banners
if (file_exists(APPPATH . 'Modules/Banners/Config/Routes.php')) {
    require APPPATH . 'Modules/Banners/Config/Routes.php';
}

// Load Module Routes Chat
if (file_exists(APPPATH . 'Modules/Chat/Config/Routes.php')) {
    require APPPATH . 'Modules/Chat/Config/Routes.php';
}

// Load Module Routes Construction
if (file_exists(APPPATH . 'Modules/Construction/Config/Routes.php')) {
    require APPPATH . 'Modules/Construction/Config/Routes.php';
}

// Load Module Routes Dashboard
if (file_exists(APPPATH . 'Modules/Dashboard/Config/Routes.php')) {
    require APPPATH . 'Modules/Dashboard/Config/Routes.php';
}

// Load Module Routes Design
if (file_exists(APPPATH . 'Modules/Design/Config/Routes.php')) {
    require APPPATH . 'Modules/Design/Config/Routes.php';
}

// Load Module Routes Notifications
if (file_exists(APPPATH . 'Modules/Notifications/Config/Routes.php')) {
    require APPPATH . 'Modules/Notifications/Config/Routes.php';
}

// Load Module Routes Orders
if (file_exists(APPPATH . 'Modules/Orders/Config/Routes.php')) {
    require APPPATH . 'Modules/Orders/Config/Routes.php';
}

// Load Module Routes PriceEstimate
if (file_exists(APPPATH . 'Modules/PriceEstimate/Config/Routes.php')) {
    require APPPATH . 'Modules/PriceEstimate/Config/Routes.php';
}

// Load Module Routes Product
if (file_exists(APPPATH . 'Modules/Products/Config/Routes.php')) {
    require APPPATH . 'Modules/Products/Config/Routes.php';
}

// Load Module Routes Satuan
if (file_exists(APPPATH . 'Modules/Satuan/Config/Routes.php')) {
    require APPPATH . 'Modules/Satuan/Config/Routes.php';
}

// Load Module Routes AHSP
if (file_exists(APPPATH . 'Modules/AHSP/Config/Routes.php')) {
    require APPPATH . 'Modules/AHSP/Config/Routes.php';
}

// Load Module Routes Renovation
if (file_exists(APPPATH . 'Modules/Renovation/Config/Routes.php')) {
    require APPPATH . 'Modules/Renovation/Config/Routes.php';
}

// Load Module Routes Supplier
if (file_exists(APPPATH . 'Modules/Supplier/Config/Routes.php')) {
    require APPPATH . 'Modules/Supplier/Config/Routes.php';
}

// Load Module Routes SyaratKetentuan
if (file_exists(APPPATH . 'Modules/SyaratKetentuan/Config/Routes.php')) {
    require APPPATH . 'Modules/SyaratKetentuan/Config/Routes.php';
}

// Load Module Routes Tips
if (file_exists(APPPATH . 'Modules/Tips/Config/Routes.php')) {
    require APPPATH . 'Modules/Tips/Config/Routes.php';
}

// Load Module Routes Tukang
if (file_exists(APPPATH . 'Modules/Tukang/Config/Routes.php')) {
    require APPPATH . 'Modules/Tukang/Config/Routes.php';
}

// Load Module Routes Users
if (file_exists(APPPATH . 'Modules/Users/Config/Routes.php')) {
    require APPPATH . 'Modules/Users/Config/Routes.php';
}

// Load Module Routes Vouchers
if (file_exists(APPPATH . 'Modules/Vouchers/Config/Routes.php')) {
    require APPPATH . 'Modules/Vouchers/Config/Routes.php';
}

// Load Module Routes Wallets
if (file_exists(APPPATH . 'Modules/Wallets/Config/Routes.php')) {
    require APPPATH . 'Modules/Wallets/Config/Routes.php';
}

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
    $routes->get('tukang/skills', 'TukangAuthController::getSkills');

    // Auth Supplier
    $routes->post('supplier/login', 'SupplierAuthController::login');
    $routes->post('supplier/register', 'SupplierAuthController::register');

    // otp register untuk client, tukang, dan supplier
    $routes->post('otp/request', 'AuthController::requestOtp');
    $routes->post('otp/verify', 'AuthController::verifyOtp');
    $routes->post('verify-email', 'AuthController::verifyEmail');

    // client
    $routes->post('user/request-otp', 'UserController::requestOtp');
    $routes->post('user/verify-otp', 'UserController::verifyOtp');
    $routes->post('user/activate-account/confirm', 'UserController::confirmActivateAccount');

    // Konten & Market Umum
    $routes->get('products', 'ProductApi::index');
    $routes->get('products/show', 'ProductApi::show');
    $routes->get('products/getBySupplier/(:num)', 'ProductApi::getBySupplier/$1');
    $routes->get('suppliers/regions', 'ProductApi::regions');
    $routes->get('supplier/public-profile/(:num)', 'SupplierProfileApi::index/$1');

    //konten
    $routes->get('content/banners', 'ContentController::banners');
    $routes->get('content/tips', 'ContentController::tips');
    $routes->get('content/priceEstimate', 'ContentController::price_estimate');

    // Webhook Payment
    $routes->post('payment/notification', 'PaymentApi::notification');

    // Pengaturan Pajak & Biaya Aplikasi
    $routes->get('settings/tax-fee', 'SettingsApi::getTaxFeeSettings');


    // meminta kode OTP
    $routes->post('forgot-password', 'AuthAPI::requestOtp');
    $routes->post('verify-otp', 'AuthAPI::verifyOtp');
    $routes->post('reset-password', 'AuthAPI::resetPassword');
    $routes->post('forgot-password-email', 'AuthAPI::requestOtpByEmail');
    $routes->post('verify-otp-email', 'AuthAPI::verifyOtpByEmail');

    // Unity API
    $routes->get('unity/construction-rabs/(:num)', 'UnityApi::getRabByConstructionId/$1');
});

// ====================================================================
// --- 4. API PRIVATE (WAJIB TOKEN JWT) ---
// ====================================================================
$routes->group('api', ['namespace' => 'App\Controllers\Api', 'filter' => 'auth'], static function ($routes) {

    // MODUL SUPPLIER (PRODUK SAYA, PESANAN, STATS)
    $routes->get('supplier/stats', 'SupplierOrderApi::stats');
    $routes->get('supplier/sales-analytics', 'SupplierOrderApi::salesAnalytics');
    $routes->get('supplier/my-products', 'ProductApi::myProducts');
    $routes->get('supplier/product/(:num)', 'ProductApi::detailProduct/$1');
    $routes->get('supplier/orders', 'SupplierOrderApi::index');
    $routes->post('supplier/orders/update-status/(:num)', 'SupplierOrderApi::updateStatus/$1');
    $routes->post('supplier/withdraw', 'SupplierOrderApi::withdraw');
    $routes->get('supplier/withdrawals', 'SupplierOrderApi::withdrawalHistory');
    $routes->get('supplier/transactions', 'SupplierOrderApi::transactionHistory');
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
    $routes->get('supplier/ongkir/show/(:num)', 'SupplierOngkirApi::showOngkirByIdSupplier/$1');
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
    $routes->get('design/rabs/(:num)', 'DesignController::rabs/$1');
    $routes->post('design/buy-revision', 'DesignController::buyRevisionQuota');
    $routes->patch('design/select-material', 'DesignController::select_material');

    // === MODUL KONSTRUKSI (SINKRON DENGAN ConstructionApi) ===
    $routes->post('construction/submit', 'ConstructionApi::submit');
    $routes->post('construction/submit-both', 'ConstructionApi::submitConstructionAndDesignRequests');
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
    $routes->get('construction/progress-list/(:num)', 'ConstructionApi::progressList/$1');
    $routes->patch('construction/progress/approve/(:num)', 'ConstructionApi::approveProgress/$1');
    $routes->patch('construction/progress/reject/(:num)', 'ConstructionApi::rejectProgress/$1');

    // absensi konstruksi dan renovasi
    $routes->get('construction/attendance-projects/(:num)', 'TukangJobApi::getProjectListForAttendance/$1');
    $routes->get('renovation/attendance-projects/(:num)', 'TukangJobApi::getRenovationListForAttendance/$1');
    $routes->post('construction/send-checkin-attendance/(:num)', 'ConstructionApi::SendAttendance/$1');
    $routes->post('construction/send-checkout-attendance/(:num)', 'ConstructionApi::SendCheckoutAttendance/$1');

    // Pengajuan bahan & alat konstruksi oleh Tukang
    $routes->get('construction/material-submissions', 'ConstructionApi::getMaterialSubmissions');
    $routes->get('construction/material-submissions/(:num)', 'ConstructionApi::getMaterialSubmission/$1');
    $routes->post('construction/material-submissions', 'ConstructionApi::createMaterialSubmission');
    $routes->put('construction/material-submissions/(:num)', 'ConstructionApi::updateMaterialSubmission/$1');
    $routes->post('construction/material-submissions/(:num)', 'ConstructionApi::updateMaterialSubmission/$1');
    $routes->delete('construction/material-submissions/(:num)', 'ConstructionApi::deleteMaterialSubmission/$1');

    // Fitur syarat ketentuan

    // Fitur tentang aplikasi (dipindah ke Modules/AboutApplication/Config/Routes.php)

    //fitur kontrak
    $routes->get('construction/contract/(:num)', 'ContractApi::construction_contract/$1');
    $routes->get('renovation/contract/(:num)', 'ContractApi::renovation_contract/$1');

    // === MODUL RENOVASI (RenovationApi) ===
    $routes->post('renovation/submit', 'RenovationApi::submit');
    $routes->post('renovation/submit-both', 'RenovationApi::submitRenovationAndDesignRequests');
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
    $routes->get('renovation/progress-list/(:num)', 'RenovationApi::progressList/$1');
    $routes->patch('renovation/progress/approve/(:num)', 'RenovationApi::approveProgress/$1');
    $routes->patch('renovation/progress/reject/(:num)', 'RenovationApi::rejectProgress/$1');

    // absensi renovasi
    $routes->post('renovation/send-checkin-attendance/(:num)', 'RenovationApi::SendAttendance/$1');
    $routes->post('renovation/send-checkout-attendance/(:num)', 'RenovationApi::SendCheckoutAttendance/$1');

    // Pengajuan bahan & alat renovasi oleh Tukang
    $routes->get('renovation/material-submissions', 'RenovationApi::getMaterialSubmissions');
    $routes->get('renovation/material-submissions/(:num)', 'RenovationApi::getMaterialSubmission/$1');
    $routes->post('renovation/material-submissions', 'RenovationApi::createMaterialSubmission');
    $routes->put('renovation/material-submissions/(:num)', 'RenovationApi::updateMaterialSubmission/$1');
    $routes->post('renovation/material-submissions/(:num)', 'RenovationApi::updateMaterialSubmission/$1');
    $routes->delete('renovation/material-submissions/(:num)', 'RenovationApi::deleteMaterialSubmission/$1');

    //alamat user

    // Resource CRUD
    $routes->resource('products', ['controller' => 'ProductApi', 'except' => ['index', 'regions', 'show']]);
    $routes->post('products/(:num)', 'ProductApi::update/$1');
    $routes->post('products/ratings/create', 'ProductApi::createRating');
    $routes->get('products/ratings/(:num)', 'ProductApi::showrating/$1');
    $routes->get('products/ratings/supplier', 'ProductApi::showRatingBySupplier');
    $routes->resource('supplier/categories', ['controller' => 'SupplierCategoryApi']);
    $routes->post('supplier/update-profile', 'SupplierAuthController::updateProfile');
    $routes->post('supplier/update-fcm', 'SupplierAuthController::updateFcmToken');


    $routes->get('satuan', 'SatuanApi::index');
    $routes->get('app-categories', 'AppCategoryApi::index');

    // Modul Lainnya (Client & Tukang)
    $routes->group('cart', function ($routes) {
        $routes->get('/', 'CartApi::index');
        $routes->post('add', 'CartApi::add');
        $routes->post('update', 'CartApi::update');
        $routes->post('delete', 'CartApi::delete');
    });
    $routes->post('checkout', 'OrderApi::checkout');
    $routes->get('orders/history', 'OrderApi::history');
    $routes->get('orders/detail/(:any)', 'OrderApi::detail/$1');
    $routes->delete('orders/delete/(:any)', 'OrderApi::delete/$1');
    $routes->post('orders/mandor-confirm/(:num)', 'OrderApi::mandorConfirm/$1');
    $routes->post('orders/complete/(:num)', 'OrderApi::complete/$1');
    $routes->get('payment/check_status/(:any)', 'PaymentApi::checkStatus/$1');
    $routes->get('payment/token/(:num)', 'PaymentApi::getPaymentToken/$1');
    $routes->get('payment/token/design/(:num)(/(:any))?', 'PaymentApi::getDesignPaymentToken/$1/$3');
    $routes->get('payment/token/construction/(:num)(/(:any))?', 'PaymentApi::getConstructionPaymentToken/$1/$3');
    $routes->get('payment/token/renovation/(:num)(/(:any))?', 'PaymentApi::getRenovationPaymentToken/$1/$3');

    $routes->get('orders/transaction-detail/(:any)', 'Api\OrderApi::transactionDetail/$1');
    $routes->get('orders/transaction-history', 'Api\OrderApi::transactionHistory');
    $routes->post('orders/webhook-midtrans', 'Api\OrderApi::webhookMidtrans');

    // Tukang Private Actions
    $routes->post('tukang/update-profile', 'TukangAuthController::updateProfile');
    $routes->post('tukang/update-fcm', 'TukangAuthController::updateFcmToken');

    $routes->group('tukang/group', function ($routes) {
        $routes->post('create', 'TukangGroupApi::create');
        $routes->get('detail', 'TukangGroupApi::getGroup');
        $routes->post('update', 'TukangGroupApi::update');
        $routes->post('member-status', 'TukangGroupApi::updateMemberStatus');
        $routes->post('remove-member', 'TukangGroupApi::removeMember');
        $routes->post('join', 'TukangGroupApi::join');
        $routes->post('leave', 'TukangGroupApi::leave');
        $routes->get('my-status', 'TukangGroupApi::myStatus');
        $routes->get('requests', 'TukangGroupApi::myRequests');
        $routes->post('distribute-bulk', 'TukangGroupApi::distributeBulk');
        $routes->get('transactions', 'TukangGroupApi::transactions');
        $routes->get('job-balances', 'TukangGroupApi::jobBalances');
        $routes->get('pending-distributions', 'TukangGroupApi::pendingDistributions');
        $routes->post('vote-distribution', 'TukangGroupApi::voteDistribution');
    });
    $routes->get('tukang/profile/(:num)', 'TukangAuthController::getProfile/$1');
    $routes->get('tukang/jobs/construction', 'TukangJobApi::getConstructionJobs');
    $routes->get('tukang/jobs/renovation', 'TukangJobApi::getRenovationJobs');
    $routes->get('tukang/my-applications/(:num)', 'TukangJobApi::getMyApplications/$1');
    $routes->post('tukang/submit-progress', 'TukangJobApi::submitProgress');
    $routes->get('tukang/my-targets/construction/(:num)', 'TukangJobApi::getMyConstructionTargets/$1');
    $routes->get('tukang/my-targets/renovation/(:num)', 'TukangJobApi::getMyRenovationTargets/$1');
    $routes->post('tukang/job-submit', 'JobApplicationController::submit');
    $routes->get('tukang/application-status/(:num)', 'TukangJobApi::getApplicationStatus/$1');
    $routes->get('tukang/banners', 'TukangContentController::banners');
    $routes->get('tukang/tips', 'TukangContentController::tips');

    $routes->post('tukang/update-ktp', 'TukangAuthController::updateProfileByKtp');
    $routes->get('tukang/progress/(:num)', 'TukangJobApi::getConstructionProgress/$1');
    $routes->post('tukang/progress', 'TukangJobApi::createConstructionProgress');
    $routes->get('tukang/renovation/progress/(:num)', 'TukangJobApi::getRenovationProgress/$1');
    $routes->post('tukang/renovation/progress', 'TukangJobApi::createRenovationProgress');

    // rating tukang
    $routes->get('tukang/ratings/(:num)', 'TukangRatingController::index/$1');
    $routes->post('tukang/ratings/create', 'TukangRatingController::createRatingTukangConstruction');
    $routes->post('tukang/ratings/create-renovation', 'TukangRatingController::createRatingTukangRenovation');

    // Chat API
    $routes->group('chat', function ($routes) {
        // Customer Service Chat (Klien / Tukang ↔ Admin)
        $routes->get('cs/all/(:num)', 'CSChatController::getCSConversations/$1');
        $routes->get('cs/messages/(:num)', 'CSChatController::getCSMessages/$1');
        $routes->post('cs/send', 'CSChatController::sendCSMessage');
        $routes->post('cs/create_or_get', 'CSChatController::createCSConversation');

        // Supplier Chat (Klien ↔ Supplier)
        $routes->get('supplier/all/(:num)', 'SupplierChatController::getSupplierConversations/$1');
        $routes->get('supplier/messages/(:num)', 'SupplierChatController::getSupplierMessages/$1');
        $routes->post('supplier/send', 'SupplierChatController::sendSupplierMessage');
        $routes->post('supplier/create_or_get', 'SupplierChatController::createSupplierConversation');

        $routes->get('notifications', 'NotificationApi::index'); // Untuk riwayat notif di HP

        // Rute Chat Proyek (Khusus Client)
        $routes->get('project/all/(:num)', 'ProjectChatController::getAllProjectConversationsForUser/$1');
        $routes->get('project/messages/(:num)', 'ProjectChatController::getProjectMessages/$1');
        $routes->post('project/send', 'ProjectChatController::sendProjectMessage');
        $routes->post('project/create_or_get', 'ProjectChatController::createOrGetProjectConversation');
    });

    // client
    $routes->post('user/update', 'UserController::update');
    $routes->post('user/update-fcm', 'AuthController::updateFcmToken');
    $routes->post('user/inactivate-account/confirm', 'UserController::confirmInactivateAccount');
    $routes->post('user/delete-account/confirm', 'UserController::confirmDeleteAccount');


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

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
