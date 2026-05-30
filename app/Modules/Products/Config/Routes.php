<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Products\Controllers\Admin'], static function ($routes) {
    //manajemen produk
    $routes->get('products', 'ProductController::index', ['as' => 'admin.products.index']);
    $routes->get('products/detail/(:num)', 'ProductController::detail/$1');
    $routes->post('products/update_status/(:num)/(:segment)', 'ProductController::updateStatus/$1/$2');
    $routes->get('products/delete/(:num)', 'ProductController::delete/$1');
});
