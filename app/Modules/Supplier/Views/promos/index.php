<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Promo Supplier
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Promo
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HEADER CARD ===== */
    .page-header-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(103, 119, 239, 0.05);
        border-radius: 50%;
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -30px;
        width: 260px;
        height: 260px;
        background: rgba(103, 119, 239, 0.03);
        border-radius: 50%;
    }

    /* ===== STAT PILLS ===== */
    .stat-pill {
        background: #f0f4ff;
        border-radius: 50px;
        padding: 6px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #4b49ac;
        font-weight: 700;
        border: 1px solid #e0e6ff;
    }

    .stat-pill .stat-num {
        background: #6777ef;
        color: #fff;
        border-radius: 50px;
        padding: 1px 10px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* ===== SEARCH INPUT ===== */
    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 0.95rem;
        pointer-events: none;
        z-index: 5;
    }

    .search-wrapper input {
        padding-left: 44px !important;
        border-radius: 50px !important;
        border: 1.5px solid #e4e6fc;
        transition: all 0.3s ease;
        font-size: 0.88rem;
        width: 280px;
        height: 44px;
        background: #fdfdff !important;
    }

    .search-wrapper input:focus {
        border-color: #6777ef;
        background: #fff !important;
        box-shadow: 0 8px 20px rgba(103, 119, 239, 0.15);
        width: 350px;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(103, 119, 239, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-bottom: 0 !important;
    }

    #table-1 thead tr {
        background: #f8f9ff;
    }

    #table-1 thead th {
        color: #6777ef;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #eef0ff;
        border-top: none;
        padding: 14px 12px;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #fcfcff !important;
    }

    #table-1 tbody td {
        padding: 16px 12px;
        vertical-align: middle;
        border-color: #f1f3f9;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== PROMO IMAGE ===== */
    .promo-thumb {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        object-position: center;
        border: 2px solid #eef0ff;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.1);
    }

    /* ===== BADGES ===== */
    .badge-status {
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .discount-pill {
        background: #fff0f0;
        color: #e03131;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        border: 1px solid #ffc9c9;
    }

    /* ===== FOOTER DATATABLE ===== */
    .dt-footer {
        padding: 14px 20px;
        border-top: 1px solid #f0f4fa;
        background: #fafcff;
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
        color: #0d6efd;
        align-items: center;
        justify-content: center;
    }

    .dataTables_paginate .page-item.active .page-link {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
    }

    .dataTables_paginate .page-item:not(.active) .page-link:hover {
        background: #e7f0ff !important;
        border-color: #e7f0ff !important;
        color: #0d6efd !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Supplier\Views\promos\components\_idx_table') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Supplier\Views\promos\components\_idx_scripts') ?>
<?= $this->endSection() ?>