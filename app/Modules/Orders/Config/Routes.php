<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Orders\Controllers\Admin'], static function ($routes) {
    // Manajemen Pesanan & Wallet
    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/detail/(:num)', 'OrderController::detail/$1');
    $routes->post('orders/update/(:num)', 'OrderController::updateStatus/$1');
});