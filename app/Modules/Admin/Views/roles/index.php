<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Role
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Role
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

    /* ===== ROLE ICON ===== */
    .role-icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .role-icon-super {
        background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        color: #fff;
        box-shadow: 0 3px 8px rgba(13, 110, 253, 0.3);
    }

    .role-icon-custom {
        background: #e7f3ff;
        color: #0d6efd;
        border: 2px solid #dce8ff;
    }

    /* ===== PERMISSION BADGES ===== */
    .perm-pill {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin: 2px;
        white-space: nowrap;
    }

    .pill-parent {
        background: #e7f3ff;
        color: #0a58ca;
        border: 1px solid #cce5ff;
    }

    .pill-action {
        background: #f1f3f5;
        color: #6c757d;
        font-weight: 500;
        font-size: 0.67rem;
        border: 1px solid #e9ecef;
    }

    .pill-full {
        background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        color: #fff;
        border: none;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.2);
        font-size: 0.73rem;
        padding: 4px 12px;
    }

    .pill-more {
        background: #dee2e6;
        color: #495057;
        font-size: 0.67rem;
        padding: 3px 9px;
        border-radius: 20px;
        font-weight: 600;
        margin: 2px;
        display: inline-flex;
        align-items: center;
    }

    /* ===== ROLE TYPE BADGE ===== */
    .role-type-badge {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 2px 9px;
        border-radius: 20px;
    }

    .role-type-super {
        background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
        color: #fff;
    }

    .role-type-custom {
        background: #e7f3ff;
        color: #0d6efd;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        border: none;
        transition: all 0.18s ease;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-action-edit {
        background: #fff4e6;
        color: #fd7e14;
    }

    .btn-action-edit:hover {
        background: #fd7e14;
        color: #fff;
    }

    .btn-action-delete {
        background: #fff5f5;
        color: #fa5252;
    }

    .btn-action-delete:hover {
        background: #fa5252;
        color: #fff;
    }

    .btn-action-lock {
        background: #f1f3f5;
        color: #adb5bd;
        cursor: not-allowed;
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
        .search-wrapper {
            width: 100% !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\Admin\Views\roles\components\_table_card') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Admin\Views\roles\components\_scripts') ?>
<?= $this->endSection() ?>