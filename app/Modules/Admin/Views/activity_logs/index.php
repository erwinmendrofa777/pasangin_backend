<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>Log Aktivitas Admin<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Manajemen Sistem<?= $this->endSection() ?>

<?= $this->section('style') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    :root {
        --primary-color: var(--palette-primary);
        --primary-light: #fff5f5;
        --secondary-color: #6c757d;
        --success-color: #47c363;
        --info-color: #3abaf4;
        --warning-color: #ffa426;
        --danger-color: #fc544b;
        --dark-color: #191d21;
    }

    /* Premium Header Card */
    .page-header-card {
        border: none;
        border-radius: 24px;
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        box-shadow: 0 15px 35px rgba(255, 92, 92, 0.25);
        overflow: hidden;
        position: relative;
        padding: 40px;
        margin-bottom: 30px;
        color: #fff;
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -20%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(50px);
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -15%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        filter: blur(30px);
    }

    .header-icon-box {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .page-header-card h4 {
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
    }

    .page-header-card p {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        margin-bottom: 0;
    }

    /* Main Content Card */
    .main-table-card {
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        background: #fff;
        padding: 10px;
    }

    /* Modern Table Styling */
    .table-custom thead th {
        background-color: #fcfcfd;
        color: #8e94a9;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1px solid #f1f3f9;
        padding: 20px 15px;
    }

    .table-custom tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f8f9fa;
        position: relative;
    }

    .table-custom tbody tr:hover {
        background-color: #fffafa !important;
        box-shadow: inset 4px 0 0 var(--primary-color);
    }

    .table-custom td {
        padding: 18px 15px;
        vertical-align: middle;
        color: #495057;
        font-weight: 500;
    }

    /* Admin Profile Circle */
    .admin-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary-light);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.8rem;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.15);
    }

    .admin-info .name {
        font-weight: 700;
        color: #2d3748;
        display: block;
        line-height: 1.2;
    }

    .admin-info .role {
        font-size: 0.7rem;
        color: #a0aec0;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    /* Action Badges */
    .action-badge {
        font-weight: 800;
        font-size: 0.65rem;
        padding: 6px 14px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-login {
        background: #e0f2f1;
        color: #00897b;
    }

    .action-logout {
        background: #fff3e0;
        color: #ef6c00;
    }

    .action-create {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .action-update {
        background: #e3f2fd;
        color: #1565c0;
    }

    .action-delete {
        background: #ffebee;
        color: #c62828;
    }

    .action-update_status {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .action-default {
        background: #f5f5f5;
        color: #616161;
    }

    /* Module Badge */
    .module-badge {
        background: #f8f9fa;
        color: #495057;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    /* Time Column */
    .time-cell {
        font-size: 0.85rem;
        color: #718096;
    }

    .time-cell .date {
        font-weight: 700;
        color: #2d3748;
        display: block;
    }

    /* DataTables Customization */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary-color) !important;
        color: white !important;
        border-radius: 10px !important;
        border: none !important;
    }

    .pagination-rounded .page-link {
        border-radius: 10px !important;
        margin: 0 3px;
        border: none;
        color: #718096;
        font-weight: 600;
    }

    .pagination-rounded .page-item.active .page-link {
        background-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.3);
    }

    /* ===== FOOTER DATATABLE ===== */
    .dt-footer {
        padding: 14px 20px;
        border-top: 1px solid #f0f4fa;
        background: #fffafa;
    }

    .dataTables_info {
        font-size: 0.82rem;
        color: #6c757d !important;
    }

    .dataTables_paginate .page-item .page-link {
        border-radius: 8px !important;
        font-size: 0.82rem !important;
        margin: 0 3px;
        border: 1px solid transparent;
        color: var(--palette-primary);
        align-items: center;
        justify-content: center;
    }

    .dataTables_paginate .page-item.active .page-link {
        background: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.3);
    }

    .dataTables_paginate .page-item:not(.active) .page-link:hover {
        background: #ffe5e5 !important;
        border-color: #ffe5e5 !important;
        color: var(--palette-primary) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\Admin\Views\activity_logs\components\_header') ?>
    <?= $this->include('App\Modules\Admin\Views\activity_logs\components\_table_card') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Admin\Views\activity_logs\components\_scripts') ?>
<?= $this->endSection() ?>