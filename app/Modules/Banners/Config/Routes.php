<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Banners\Controllers\Admin'], static function ($routes) {
    // Manajemen Konten
    $routes->get('banner', 'Banner::index');
    $routes->get('banner/create', 'Banner::create');
    $routes->post('banner/store', 'Banner::store');
    $routes->get('banner/delete/(:num)', 'Banner::delete/$1');
});