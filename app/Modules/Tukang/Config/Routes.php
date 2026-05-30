<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Tukang\Controllers\Admin'], static function ($routes) {
    // Manajemen Tukang
    $routes->get('tukang', 'Tukang::index');
    $routes->get('tukang/index', 'Tukang::index');
    $routes->get('tukang/create', 'Tukang::create');
    $routes->post('tukang/store', 'Tukang::store');
    $routes->get('tukang/detail/(:num)', 'Tukang::detail/$1');
    $routes->get('tukang/delete/(:num)', 'Tukang::delete/$1');
    $routes->post('tukang/update-status', 'Tukang::update_status');
    $routes->post('tukang/update-verify', 'Tukang::update_verify');
});