<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Admin\Controllers\Admin'], static function ($routes) {
    // MENU ADMIN (PEGAWAI)
    $routes->get('admin', 'AdminController::index');
    $routes->get('admin/create', 'AdminController::create');
    $routes->post('admin/store', 'AdminController::store');
    $routes->get('admin/edit/(:num)', 'AdminController::edit/$1');
    $routes->post('admin/update/(:num)', 'AdminController::update/$1');
    $routes->get('admin/delete/(:num)', 'AdminController::delete/$1');

    // LOG AKTIVITAS
    $routes->get('activity-logs', 'ActivityLog::index');

    // MENU ROLES (HAK AKSES)
    $routes->get('roles', 'RoleController::index');
    $routes->get('roles/create', 'RoleController::create');
    $routes->post('roles/store', 'RoleController::store');
    $routes->get('roles/edit/(:num)', 'RoleController::edit/$1');
    $routes->post('roles/update/(:num)', 'RoleController::update/$1');
    $routes->get('roles/delete/(:num)', 'RoleController::delete/$1');
});