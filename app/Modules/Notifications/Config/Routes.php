<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Notifications\Controllers\Admin'], static function ($routes) {
    // MENU NOTIFIKASI
    $routes->get('notification', 'Notification::index');
    $routes->post('notification/send', 'Notification::send');
    $routes->get('notification/create', 'Notification::create');
    $routes->get('notification/searchUsers', 'Notification::searchUsers');
    $routes->get('notification/getLatest', 'Notification::getLatest');
    $routes->post('notification/saveToken', 'Notification::saveToken');
});