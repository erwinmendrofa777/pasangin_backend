<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Chat\Controllers\Admin'], static function ($routes) {

    // Chat Admin — Index (semua)
    $routes->get('chat', 'CSChatController::index');

    // Chat Admin — Per Fungsi
    $routes->get('chat/cs', 'CSChatController::cs');
    $routes->get('chat/monitoring', 'SupplierChatController::monitoring');
    $routes->get('chat/project', 'ProjectChatController::project');
    $routes->group('api/chat', function ($routes) {
        // Customer Service (CS) Chat Admin API
        $routes->get('conversations', 'CSChatController::getConversations');
        $routes->get('(:num)/messages', 'CSChatController::getMessages/$1');
        $routes->post('send', 'CSChatController::sendMessage');
        $routes->post('(:num)/status', 'CSChatController::updateStatus/$1');

        // Monitoring Chat Supplier (Admin API)
        $routes->get('supplier/conversations', 'SupplierChatController::getSupplierConversations');
        $routes->get('supplier/(:num)/messages', 'SupplierChatController::getSupplierMessages/$1');

        // Chat Proyek (Admin API)
        $routes->get('project/conversations', 'ProjectChatController::getProjectConversations');
        $routes->get('project/(:num)/info', 'ProjectChatController::getProjectConversationInfo/$1');
        $routes->get('project/(:num)/messages', 'ProjectChatController::getProjectMessages/$1');
        $routes->post('project/send', 'ProjectChatController::sendProjectMessage');
        $routes->post('project/(:num)/status', 'ProjectChatController::updateProjectStatus/$1');

        // Admin memulai chat proyek baru
        $routes->get('project/available-projects', 'ProjectChatController::getAvailableProjects');
        $routes->post('project/create', 'ProjectChatController::createProjectConversation');
    });
});
