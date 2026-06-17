<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Construction\Controllers\Admin'], static function ($routes) {
    // Manajemen construction
    $routes->get('construction', 'Construction::index');
    $routes->get('construction/export-pdf', 'Construction::exportPdf');
    $routes->get('construction/detail/(:num)', 'Construction::detail/$1');
    $routes->post('construction/update-status', 'Construction::updateStatus');
    $routes->post('construction/upload-survey', 'Construction::uploadSurvey');
    $routes->post('construction/upload-design', 'Construction::uploadDesign');
    $routes->post('construction/upload-rab', 'Construction::uploadRab');
    $routes->get('construction/delete-survey/(:num)/(:num)', 'Construction::deleteSurvey/$1/$2');
    $routes->get('construction/delete-design/(:num)/(:num)', 'Construction::deleteDesign/$1/$2');
    $routes->post('construction/add-progress', 'Construction::addProgress');
    $routes->get('construction/delete-progress/(:num)/(:num)', 'Construction::deleteProgress/$1/$2');
    $routes->post('construction/create_invoice', 'Construction::create_invoice');
    $routes->get('construction/delete_invoice/(:num)/(:num)', 'Construction::delete_invoice/$1/$2');
    $routes->post('construction/add-target', 'Construction::add_target');
    $routes->post('construction/update_target_status/(:num)/(:alpha)', 'Construction::update_target_status/$1/$2');
    $routes->get('construction/delete-target/(:num)/(:num)', 'Construction::delete_target/$1/$2');
    $routes->post('construction/update-job-info', 'Construction::update_job_info');
    $routes->post('construction/update_applicant_status', 'Construction::update_applicant_status');
    $routes->get('construction/update_progress_status/(:num)/(:alpha)', 'Construction::update_progress_status/$1/$2');
    $routes->get('construction/delete-attendance/(:num)/(:num)', 'Construction::delete_attendance/$1/$2');
    $routes->post('construction/update-material-status/(:num)', 'Construction::update_material_submission_status/$1');
    $routes->post('construction/add-material-submission', 'Construction::add_material_submission');
    $routes->post('construction/update-material-submission/(:num)', 'Construction::update_material_submission/$1');
    $routes->get('construction/delete-material-submission/(:num)', 'Construction::delete_material_submission/$1');

    //construction target
    $routes->get('construction/target/(:num)', 'Construction::view_target/$1');
    $routes->post('construction/create-target/(:num)', 'Construction::createTarget/$1');
    $routes->post('construction/update-schedule', 'Construction::update_schedule');

    // Fitur RAB construksi & renovasi (SINKRON DENGAN ConstructionApi & RenovationApi)
    $routes->post('construction/save_rab_row', 'RabController::save_rab_row');
    $routes->post('construction/save_all_rab/(:num)', 'RabController::save_all_rab/$1');
    $routes->get('construction/delete_rab_row/(:num)', 'RabController::delete_rab_row/$1');
    $routes->get('construction/get_rab_materials/(:num)', 'RabController::get_rab_materials/$1');
    $routes->post('construction/add_rab_material', 'RabController::add_rab_material');
    $routes->post('construction/select_rab_material', 'RabController::select_rab_material');
    $routes->get('construction/delete_rab_material/(:num)', 'RabController::delete_rab_material/$1');
    $routes->get('construction/recalculate_rab_price/(:num)', 'RabController::recalculate_rab_price/$1');
    $routes->get('construction/lock_rab/(:num)', 'RabController::lock_rab/$1');
    $routes->get('construction/unlock_rab/(:num)', 'RabController::unlock_rab/$1');
    $routes->get('construction/download-rab-template/(:num)', 'RabController::download_rab_template/$1');
    $routes->post('construction/import-rab-excel/(:num)', 'RabController::import_rab_excel/$1');
    $routes->get('construction/export-rab-excel/(:num)', 'RabController::export_rab_excel/$1');
    $routes->get('construction/cetak-pdf/(:num)', 'Surat::exportPdf/$1');

    // Fitur ADDENDUM 
    $routes->post('construction/save_addendum_row', 'Construction::save_addendum_row');
    $routes->post('construction/save_all_addendum/(:num)', 'Construction::save_all_addendum/$1');
    $routes->get('construction/delete_addendum_row/(:num)', 'Construction::delete_addendum_row/$1');
    $routes->get('construction/get_addendum_materials/(:num)', 'Construction::get_addendum_materials/$1');
    $routes->post('construction/add_addendum_material', 'Construction::add_addendum_material');
    $routes->get('construction/delete_addendum_material/(:num)', 'Construction::delete_addendum_material/$1');
    $routes->get('construction/lock_addendum/(:num)', 'Construction::lock_addendum/$1');
    $routes->get('construction/unlock_addendum/(:num)', 'Construction::unlock_addendum/$1');

    $routes->post('save_rab_row', 'Construction::save_rab_row');
    $routes->get('delete_rab_row/(:num)', 'Construction::delete_rab_row/$1');
    $routes->get('get_rab_materials/(:num)', 'Construction::get_rab_materials/$1');
    $routes->post('add_rab_material', 'Construction::add_rab_material');
    $routes->get('delete_rab_material/(:num)', 'Construction::delete_rab_material/$1');
});