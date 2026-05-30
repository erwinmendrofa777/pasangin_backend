<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Wallets\Controllers\Admin'], static function ($routes) {
    $routes->get('wallet', 'Wallet::index');
    $routes->post('wallet/update-balance', 'Wallet::update_balance');
    $routes->get('wallet/withdrawals', 'Wallet::withdrawals');
    $routes->get('wallet/withdraw-approve/(:num)/(:any)', 'Wallet::update_withdrawal_status/$1/$2');
});