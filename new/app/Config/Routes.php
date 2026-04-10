<?php
// FILE: backend/app/Config/Routes.php (VERSI FINAL - 100% LENGKAP)

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// PENTING: setAutoRoute(false) adalah praktik terbaik untuk keamanan dan performa.
// Ini memaksa kita untuk mendefinisikan semua rute secara eksplisit.
$routes->setAutoRoute(false); 

// Route #1: Halaman utama website akan mengarah ke halaman login admin.
$routes->get('/', 'Admin\Auth::index');

// Route #2: Rute untuk menampilkan & memproses halaman login/logout.
// Rute-rute ini SENGAJA DITARUH DI LUAR GRUP 'admin' agar tidak terkena filter.
$routes->get('admin/login', 'Admin\Auth::index');
$routes->post('admin/login', 'Admin\Auth::loginProcess');
$routes->get('admin/logout', 'Admin\Auth::logout');

// Route #3: Grup untuk SEMUA HALAMAN ADMIN LAINNYA YANG PERLU PROTEKSI.
// Semua yang ada di dalam grup ini akan dicegat oleh filter 'login' jika user belum login.
$routes->group('admin', ['filter' => 'login'], static function ($routes) {
    // Contoh: URL /admin/dashboard akan memanggil Dashboard::index
    $routes->get('dashboard', 'Admin\Dashboard::index'); 
    
    // Tambahkan rute admin terproteksi lainnya di sini...
    // Misal: $routes->get('users', 'Admin\Users::index');

    // --- Rute untuk Manajemen Estimasi Harga ---
    $routes->get('price-estimate', 'Admin\PriceEstimateController::index');
    $routes->post('price-estimate/concept/store', 'Admin\PriceEstimateController::storeConcept');
    $routes->post('price-estimate/concept/update/(:num)', 'Admin\PriceEstimateController::updateConcept/$1');
    $routes->get('price-estimate/concept/delete/(:num)', 'Admin\PriceEstimateController::deleteConcept/$1');
    $routes->post('price-estimate/quality/store', 'Admin\PriceEstimateController::storeQuality');
    $routes->post('price-estimate/quality/update/(:num)', 'Admin\PriceEstimateController::updateQuality/$1');
    $routes->get('price-estimate/quality/delete/(:num)', 'Admin\PriceEstimateController::deleteQuality/$1');

});


// ==================================================================================
// === GRUP API UNTUK APLIKASI FLUTTER (INI BAGIAN YANG PALING PENTING) ===
// ==================================================================================
// Semua URL yang diawali dengan /api akan diarahkan ke Controller di dalam folder /Api
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    
    // --- AUTH ---
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    $routes->post('update-fcm-token', 'AuthController::update_fcm_token');

    // --- CONTENT & VOUCHER ---
    // INILAH RUTE YANG HILANG DAN MENYEBABKAN ERROR 404
    $routes->get('content/banners', 'ContentController::banners');
    $routes->get('content/tips', 'ContentController::tips');
    $routes->get('vouchers', 'VoucherController::index');

    // --- DESIGN PROJECTS ---
    $routes->post('design/submit', 'DesignController::submit');
    $routes->get('design/requests/(:num)', 'DesignController::getRequests/$1');
    $routes->get('design/requests/detail/(:num)', 'DesignController::getRequestDetail/$1'); // Rute untuk getDesignRequestDetail()
    $routes->get('project/surveys/(:num)', 'DesignController::getProjectSurveys/$1');
    $routes->get('project/designs/(:num)', 'DesignController::getProjectDesigns/$1');
    $routes->get('project/invoices/(:num)', 'DesignController::getProjectInvoices/$1');

    // --- CONSTRUCTION PROJECTS ---
    $routes->post('construction/submit', 'ConstructionController::submit');
    $routes->get('construction/requests/(:num)', 'ConstructionController::getProjects/$1');

    // --- RENOVATION PROJECTS ---
    $routes->post('renovation/submit', 'RenovationController::submit');
    $routes->get('renovation/requests/(:num)', 'RenovationController::getRequests/$1');
    
    // --- PAYMENT & NOTIFICATIONS ---
    $routes->post('payment/notification', 'PaymentController::notification');

    // --- CHAT ---
    // Namespace untuk chat bisa dibuat lebih spesifik jika controllernya ada di dalam folder 'Api/Chat'
    $routes->group('chat', static function ($routes) {
        $routes->get('all/(:num)', 'ChatController::getAllConversationsForUser/$1');
        $routes->get('messages/(:num)', 'ChatController::getMessages/$1');
        $routes->post('send', 'ChatController::sendMessage');
    });
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
