<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Chat\Controllers\Admin'], static function ($routes) {

    // Chat Admin
    $routes->get('chat', 'ChatController::index');
    $routes->group('api/chat', function ($routes) {
        $routes->get('conversations', 'ChatController::getConversations');
        $routes->get('(:num)/messages', 'ChatController::getMessages/$1');
        $routes->post('send', 'ChatController::sendMessage');
        $routes->post('(:num)/status', 'ChatController::updateStatus/$1');
    });
});
