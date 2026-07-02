<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Supplier\Controllers\Admin'], static function ($routes) {
    // Manajemen Supplier
    $routes->get('suppliers', 'SupplierController::index', ['as' => 'admin.suppliers.index']);
    $routes->get('suppliers/detail/(:num)', 'SupplierController::detail/$1');
    $routes->get('suppliers/create', 'SupplierController::create');
    $routes->post('suppliers/save', 'SupplierController::save');
    $routes->get('suppliers/edit/(:num)', 'SupplierController::edit/$1');
    $routes->post('suppliers/update/(:num)', 'SupplierController::update/$1');
    $routes->get('suppliers/delete/(:num)', 'SupplierController::delete/$1');
    $routes->post('suppliers/update_status/(:num)/(:alpha)', 'SupplierController::updateStatus/$1/$2', ['as' => 'admin.supplier.update_status']);

    // Manajemen Banner Supplier
    $routes->get('banner-supplier', 'SupplierBannerController::index');
    $routes->get('banner-supplier/add', 'SupplierBannerController::add');
    $routes->post('banner-supplier/save', 'SupplierBannerController::save');
    $routes->get('banner-supplier/edit/(:num)', 'SupplierBannerController::edit/$1');
    $routes->post('banner-supplier/update/(:num)', 'SupplierBannerController::update/$1');
    $routes->get('banner-supplier/detail/(:num)', 'SupplierBannerController::detail/$1');
    $routes->post('banner-supplier/update-status', 'SupplierBannerController::updateStatus');
    $routes->post('banner-supplier/delete/(:num)', 'SupplierBannerController::delete/$1');

    // MENU PROMO
    $routes->get('promo', 'PromoController::index');
    $routes->get('promo/detail/(:num)', 'PromoController::detail/$1');
    $routes->post('promo/update_status/(:num)/(:alpha)', 'PromoController::update_status/$1/$2');
    $routes->get('promo/delete/(:num)', 'PromoController::delete/$1');

    // Sales Assistance Web UI
    $routes->get('sales/claim', 'SalesSupplierController::claimView');
    $routes->post('sales/claim', 'SalesSupplierController::claimProcess');
    $routes->get('sales/suppliers', 'SalesSupplierController::mySuppliers');
    $routes->get('sales/suppliers/(:num)/products', 'SalesSupplierController::supplierProducts/$1');
    $routes->get('sales/suppliers/(:num)/products-ajax', 'SalesSupplierController::supplierProductsAjax/$1');
    $routes->get('sales/suppliers/(:num)/products/create', 'SalesSupplierController::createProduct/$1');
    $routes->post('sales/suppliers/(:num)/products/store', 'SalesSupplierController::storeProduct/$1');
    $routes->get('sales/suppliers/(:num)/products/edit/(:num)', 'SalesSupplierController::editProduct/$1/$2');
    $routes->post('sales/suppliers/(:num)/products/update/(:num)', 'SalesSupplierController::updateProduct/$1/$2');
    $routes->get('sales/suppliers/(:num)/products/delete/(:num)', 'SalesSupplierController::deleteProduct/$1/$2');
    $routes->get('sales/suppliers/(:num)/release', 'SalesSupplierController::releaseSupplier/$1');
    $routes->post('sales/suppliers/(:num)/categories/store', 'SalesSupplierController::storeCategory/$1');
    $routes->post('sales/satuan/store', 'SalesSupplierController::storeSatuan');
});
