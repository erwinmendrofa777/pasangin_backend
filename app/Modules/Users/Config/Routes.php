<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Users\Controllers\Admin'], static function ($routes) {
    //MENU USER
    $routes->get('users', 'Users::index');
    $routes->get('users/detail/(:num)', 'Users::detail/$1');
    $routes->get('users/delete/(:num)', 'Users::delete/$1');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->post('users/update_status/(:num)/(:alpha)', 'Users::update_status/$1/$2', ['as' => 'admin.users.update_status']);
});