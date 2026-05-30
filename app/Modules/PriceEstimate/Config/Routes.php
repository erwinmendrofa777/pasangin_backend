<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\PriceEstimate\Controllers\Admin'], static function ($routes) {
    // --- Rute untuk Manajemen Estimasi Harga ---
    $routes->get('price-estimate', 'PriceEstimateController::index');
    $routes->post('price-estimate/concept/store', 'PriceEstimateController::storeConcept');
    $routes->post('price-estimate/concept/update/(:num)', 'PriceEstimateController::updateConcept/$1');
    $routes->get('price-estimate/concept/delete/(:num)', 'PriceEstimateController::deleteConcept/$1');
    $routes->post('price-estimate/quality/store', 'PriceEstimateController::storeQuality');
    $routes->post('price-estimate/quality/update/(:num)', 'PriceEstimateController::updateQuality/$1');
    $routes->get('price-estimate/quality/delete/(:num)', 'PriceEstimateController::deleteQuality/$1');
});
