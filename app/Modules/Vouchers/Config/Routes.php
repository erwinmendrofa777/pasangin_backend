<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Vouchers\Controllers\Admin'], static function ($routes) {
    $routes->get('vouchers/create', 'Voucher::create');
    $routes->post('vouchers/store', 'Voucher::store');
    $routes->get('vouchers/detail/(:num)', 'Voucher::show/$1');
    $routes->get('vouchers/delete/(:num)', 'Voucher::delete/$1');
    $routes->get('vouchers/update-status/(:num)/(:num)', 'Voucher::updateStatus/$1/$2');
    $routes->resource('vouchers', ['controller' => 'Voucher']);
});

$routes->group('api', ['filter' => 'auth', 'namespace' => 'App\Modules\Vouchers\Controllers\Api'], static function ($routes) {
    $routes->get('vouchers', 'VoucherController::index');
});