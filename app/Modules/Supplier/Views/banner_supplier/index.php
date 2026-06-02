<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Banner Supplier
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
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
        border-radius: 12px !important;
        border: 1.5px solid #dee2e6;
        transition: all 0.2s ease;
        font-size: 0.88rem;
        height: 42px;
    }

    .search-wrapper input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    .search-wrapper input::placeholder {
        color: #adb5bd;
        opacity: 0.8;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #fff;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-bottom: 0 !important;
    }

    #table-1 thead tr {
        background: #f0f6ff;
    }

    #table-1 thead th {
        color: #0d6efd;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #dce8ff;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #f8fbff !important;
    }

    #table-1 tbody td {
        padding: 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== BANNER IMAGE ===== */
    .banner-preview {
        width: 140px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
        object-position: center;
        border: 2px solid #dce8ff;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.12);
        transition: transform 0.2s ease;
        cursor: pointer;
    }

    .banner-preview:hover {
        transform: scale(1.05);
    }

    /* ===== BADGES ===== */
    .status-badge {
        border-radius: 50px;
        padding: 4px 14px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-transform: uppercase;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c3;
        color: #854d0e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-action-detail {
        background: #0d6efd;
        color: #0d6efd;
    }

    .btn-action-detail:hover {
        background: #0d6efd;
        color: #fff;
    }

    .btn-action-edit {
        background: #fd7e14;
        color: #fd7e14;
    }

    .btn-action-edit:hover {
        background: #fd7e14;
        color: #fff;
    }

    .btn-action-delete {
        background: #dc3545;
        color: #dc3545;
    }

    .btn-action-delete:hover {
        background: #dc3545;
        color: #fff;
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

    @media (max-width: 768px) {
        .card-header-flex {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start !important;
        }

        .search-wrapper {
            width: 100% !important;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Supplier\Views\banner_supplier\components\_idx_table') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Supplier\Views\banner_supplier\components\_idx_scripts') ?>
<?= $this->endSection() ?>