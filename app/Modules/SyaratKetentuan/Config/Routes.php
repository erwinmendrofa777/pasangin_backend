<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\SyaratKetentuan\Controllers\Admin'], static function ($routes) {
    // MENU SYARAT DAN KETENTUAN
    $routes->get('syarat_ketentuan', 'SyaratKetentuanController::index');
    $routes->get('syarat_ketentuan/detail/(:num)', 'SyaratKetentuanController::detail/$1');
    $routes->get('syarat_ketentuan/delete/(:num)', 'SyaratKetentuanController::delete/$1');
    $routes->get('syarat_ketentuan/create', 'SyaratKetentuanController::create');
    $routes->post('syarat_ketentuan/store', 'SyaratKetentuanController::store');
    $routes->get('syarat_ketentuan/edit/(:num)', 'SyaratKetentuanController::edit/$1');
    $routes->post('syarat_ketentuan/update/(:num)', 'SyaratKetentuanController::update/$1');
});