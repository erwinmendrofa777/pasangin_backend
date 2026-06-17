<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\AHSP\Controllers\Admin'], static function ($routes) {
    $routes->get('ahsp', 'AHSP::index', ['as' => 'admin.ahsp.index']);
    $routes->get('ahsp/show/(:num)', 'AHSP::show/$1');
    $routes->post('ahsp/store', 'AHSP::store');
    $routes->post('ahsp/update/(:num)', 'AHSP::update/$1');
    $routes->get('ahsp/delete/(:num)', 'AHSP::delete/$1');
});
