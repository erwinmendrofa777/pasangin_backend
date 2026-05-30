<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\AboutApplication\Controllers\Admin'], static function ($routes) {
    // TENTANG APLIKASI PASANGIN
    $routes->get('about_application', 'AboutApplicationPasanginController::index');
    $routes->post('about_application/update', 'AboutApplicationPasanginController::update');
});