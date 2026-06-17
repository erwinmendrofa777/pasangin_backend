<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Design\Controllers\Admin'], static function ($routes) {
    // Manajemen Design
    $routes->get('design', 'DesignRequests::index');
    $routes->get('design/managerial', 'DesignRequests::managerial');
    $routes->get('design/tugas', 'DesignRequests::tugas');
    $routes->get('design/export-pdf', 'DesignRequests::exportPdf');
    $routes->get('design/show/(:num)', 'DesignRequests::show/$1');
    $routes->get('design/delete/(:num)', 'DesignRequests::delete/$1');
    $routes->post('design/update-status/(:num)', 'DesignRequests::updateStatus/$1');
    $routes->post('design/add-survey/(:num)', 'DesignRequests::addSurvey/$1');
    $routes->get('design/delete-survey/(:num)', 'DesignRequests::deleteSurvey/$1');
    $routes->post('design/add-design-result/(:num)', 'DesignRequests::addDesignResult/$1');
    $routes->get('design/delete-design/(:num)', 'DesignRequests::deleteDesign/$1');
    $routes->post('design/add-invoice/(:num)', 'DesignRequests::addInvoice/$1');
    $routes->get('design/verify-payment/(:num)', 'DesignRequests::verifyPayment/$1');
    $routes->get('design/delete-invoice/(:num)', 'DesignRequests::deleteInvoice/$1');
    $routes->post('design/update-progress/(:num)', 'DesignRequests::updateProgress/$1');
    $routes->post('design/create-target/(:num)', 'DesignRequests::createTarget/$1');
    $routes->get('design/delete-target/(:num)/(:num)', 'DesignRequests::deleteTarget/$1/$2');
    $routes->post('design/update-target-progress/(:num)', 'DesignRequests::updateTargetProgress/$1');
    $routes->post('design/update-target-status-ajax', 'DesignRequests::updateTargetStatusAjax');
    $routes->post('design/update-target-designer-ajax', 'DesignRequests::updateTargetDesignerAjax');
    $routes->post('design/get-target-designs-ajax', 'DesignRequests::getTargetDesignsAjax');
    $routes->post('design/update-target-keterangan-ajax', 'DesignRequests::updateTargetKeteranganAjax');
    $routes->get('design/approve-design/(:num)', 'DesignRequests::approveDesign/$1');
    $routes->post('design/reject-design/(:num)', 'DesignRequests::rejectDesign/$1');
});