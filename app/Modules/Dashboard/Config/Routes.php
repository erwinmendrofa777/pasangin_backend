<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Dashboard\Controllers\Admin'], static function ($routes) {
    // MENU DASHBOARD
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');
});