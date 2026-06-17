<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Satuan\Controllers\Admin'], static function ($routes) {
    $routes->get('satuan', 'Satuan::index', ['as' => 'admin.satuan.index']);
    $routes->post('satuan/store', 'Satuan::store');
    $routes->post('satuan/update/(:num)', 'Satuan::update/$1');
    $routes->get('satuan/delete/(:num)', 'Satuan::delete/$1');
});
