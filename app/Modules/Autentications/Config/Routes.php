<?php

$routes->group('', ['namespace' => 'App\Modules\Autentications\Controllers\Admin'], static function ($routes) {
    // Login / Logout
    $routes->get('/', 'Login::index');
    $routes->get('admin/login', 'Login::index');
    $routes->post('admin/login', 'Login::loginProcess');
    $routes->get('admin/logout', 'Login::logout');

    // Forgot Password / Reset Password
    $routes->get('forgot-password', 'Auth::forgotPasswordForm');
    $routes->post('forgot-password', 'Auth::forgotPassword');
    $routes->get('verify-code', 'Auth::verifyCodeForm');
    $routes->post('process-verify-code', 'Auth::processVerifyCode');
    $routes->get('reset-password', 'Auth::resetPasswordForm');
    $routes->post('update-password', 'Auth::updatePassword');
});
