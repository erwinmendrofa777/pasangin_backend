<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Tips\Controllers\Admin'], static function ($routes) {
    $routes->get('tips/create', 'Tips::create');
    $routes->post('tips/store', 'Tips::store');
    $routes->post('tips/upload-image', 'Tips::uploadEditorImage');
    $routes->get('tips/detail/(:num)', 'Tips::show/$1');
    $routes->get('tips/edit/(:num)', 'Tips::edit/$1');
    $routes->post('tips/update/(:num)', 'Tips::update/$1');
    $routes->get('tips/delete/(:num)', 'Tips::delete/$1');
    $routes->post('tips/update-status/(:num)', 'Tips::updateIsActive/$1');
    $routes->resource('tips', ['controller' => 'Tips']);
});