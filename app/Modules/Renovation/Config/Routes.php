<?php

$routes->group('admin', ['filter' => 'login', 'namespace' => 'App\Modules\Renovation\Controllers\Admin'], static function ($routes) {
    //Menu Renovation
    $routes->get('renovation', 'Renovation::index');
    $routes->get('renovation/detail/(:num)', 'Renovation::detail/$1');
    $routes->post('renovation/update_status', 'Renovation::update_status');
    $routes->post('renovation/update-job-info', 'Renovation::update_job_info');
    $routes->post('renovation/save-job-info', 'Renovation::save_job_info');
    $routes->post('renovation/update_applicant_status', 'Renovation::update_applicant_status');
    $routes->post('renovation/add-survey/(:num)', 'Renovation::add_survey/$1');
    $routes->post('renovation/add-design/(:num)', 'Renovation::add_design/$1');
    $routes->get('renovation/delete-design/(:num)/(:num)', 'Renovation::delete_design/$1/$2');
    $routes->post('renovation/add-progress/(:num)', 'Renovation::add_progress/$1');
    $routes->post('renovation/create_invoice', 'Renovation::create_invoice');
    $routes->post('renovation/add-target', 'Renovation::add_target');
    $routes->get('renovation/delete-target/(:num)/(:num)', 'Renovation::delete_target/$1/$2');
    $routes->post('renovation/update_target_status/(:num)/(:alpha)', 'Renovation::update_target_status/$1/$2');
    $routes->get('renovation/delete_invoice/(:num)/(:num)', 'Renovation::delete_invoice/$1/$2');
    $routes->get('renovation/delete_survey/(:num)/(:num)', 'Renovation::delete_survey/$1/$2');
    $routes->post('renovation/update_progress_status/(:num)/(:alpha)', 'Renovation::update_progress_status/$1/$2');
    $routes->get('renovation/delete-attendance/(:num)/(:num)', 'Renovation::delete_attendance/$1/$2');
    $routes->post('renovation/update-material-status/(:num)', 'Renovation::update_material_submission_status/$1');
    $routes->post('renovation/add-material-submission', 'Renovation::add_material_submission');
    $routes->post('renovation/update-material-submission/(:num)', 'Renovation::update_material_submission/$1');
    $routes->get('renovation/delete-material-submission/(:num)', 'Renovation::delete_material_submission/$1');

    //renovation target
    $routes->get('renovation/target/(:num)', 'Renovation::view_target/$1');
    $routes->post('renovation/create-target/(:num)', 'Renovation::createTarget/$1');
    $routes->post('renovation/update-schedule', 'Renovation::update_schedule');

    $routes->post('renovation/save_rab_row', 'RenovationRabController::save_rab_row');
    $routes->post('renovation/save_all_rab/(:num)', 'RenovationRabController::save_all_rab/$1');
    $routes->get('renovation/delete_rab_row/(:num)', 'RenovationRabController::delete_rab_row/$1');
    $routes->get('renovation/get_rab_materials/(:num)', 'RenovationRabController::get_rab_materials/$1');
    $routes->post('renovation/add_rab_material', 'RenovationRabController::add_rab_material');
    $routes->get('renovation/delete_rab_material/(:num)', 'RenovationRabController::delete_rab_material/$1');
    $routes->get('renovation/lock_rab/(:num)', 'RenovationRabController::lock_rab/$1');
    $routes->get('renovation/unlock_rab/(:num)', 'RenovationRabController::unlock_rab/$1');
    $routes->get('renovation/cetak-pdf/(:num)', '\App\Modules\Construction\Controllers\Admin\Surat::renovationExportPdf/$1');
    $routes->get('renovation/export-rab-excel/(:num)', 'RenovationRabController::export_rab_excel/$1');
    $routes->get('renovation/download-rab-template/(:num)', 'RenovationRabController::download_rab_template/$1');
    $routes->post('renovation/import-rab-excel/(:num)', 'RenovationRabController::import_rab_excel/$1');
});