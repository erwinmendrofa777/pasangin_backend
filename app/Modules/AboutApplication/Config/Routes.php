<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\AboutApplication\Controllers\Admin'], static function ($routes) {
    // TENTANG APLIKASI PASANGIN
    $routes->get('about_application', 'AboutApplicationPasanginController::index');
    $routes->post('about_application/update', 'AboutApplicationPasanginController::update');
});

// API Private (wajib JWT)
$routes->group('api', ['namespace' => 'App\Modules\AboutApplication\Controllers\Api', 'filter' => 'auth'], static function ($routes) {
    $routes->get('tentang-aplikasi', 'AboutApplicationPasanginControllerApi::getAboutApplicationPasangin');
});