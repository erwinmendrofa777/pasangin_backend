<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Users\Controllers\Admin'], static function ($routes) {
    //MENU USER
    $routes->get('users', 'Users::index');
    $routes->get('users/detail/(:num)', 'Users::detail/$1');
    $routes->get('users/delete/(:num)', 'Users::delete/$1');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->post('users/update_status/(:num)/(:alpha)', 'Users::update_status/$1/$2', ['as' => 'admin.users.update_status']);

    // AJAX Endpoints
    $routes->get('users/get_orders/(:num)', 'Users::get_orders/$1');
    $routes->get('users/get_projects/(:num)', 'Users::get_projects/$1');
});

$routes->group('api', ['filter' => 'auth', 'namespace' => 'App\Modules\Users\Controllers\Api'], static function ($routes) {
    $routes->post('alamat', 'AlamatUserController::create');
    $routes->get('alamat', 'AlamatUserController::get');
    $routes->put('alamat/(:num)', 'AlamatUserController::put/$1');
    $routes->patch('alamat/(:num)', 'AlamatUserController::patch/$1');
    $routes->delete('alamat/(:num)', 'AlamatUserController::delete/$1');
});