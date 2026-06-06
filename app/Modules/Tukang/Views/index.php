<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Mitra Tukang
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Tukang
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
        border-color: var(--palette-primary);
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.1);
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
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
        background: #fff5f5;
    }

    #table-1 thead th {
        color: var(--palette-primary);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #ffdddd;
        padding: 14px 12px;
    }

    #table-1 tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        font-size: 0.88rem;
    }

    /* ===== AVATAR ===== */
    .tukang-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease-in-out;
        cursor: zoom-in;
    }

    .tukang-avatar:hover {
        transform: scale(1.1);
        border-color: var(--palette-primary);
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.25);
    }

    /* ===== BADGES ===== */
    .status-badge {
        border-radius: 50px;
        padding: 5px 14px;
        font-weight: 700;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-berkas {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-ditolak {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-test {
        background: #e0f2fe;
        color: #075985;
    }

    .status-aktivasi {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-siap {
        background: #d1fae5;
        color: #065f46;
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

    .btn-action-detail {
        background: var(--palette-primary);
        color: #fff;
    }

    .btn-action-detail:hover {
        background: var(--palette-primary-hover);
        color: #fff;
    }

    .btn-action-delete {
        background: #dc3545;
        color: #fff;
    }

    .btn-action-delete:hover {
        background: #bb2d3b;
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

    @media (max-width: 768px) {
        .table-card-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 16px !important;
            padding: 20px 16px !important;
            background: linear-gradient(to bottom, #f9fbff 0%, #ffffff 100%) !important;
        }

        .table-card-header h6 {
            font-size: 1rem !important;
            padding-bottom: 12px;
            border-bottom: 1px dashed #e2e8f0;
            width: 100%;
        }

        .header-actions {
            width: 100% !important;
            flex-direction: column !important;
            gap: 12px !important;
        }

        .header-actions .btn {
            width: 100% !important;
            padding: 10px 16px !important;
        }

        .search-wrapper {
            width: 100% !important;
            max-width: 100% !important;
        }

        .dt-footer {
            flex-direction: column;
            gap: 12px;
            padding: 16px !important;
        }

        #table-1 th,
        #table-1 td {
            white-space: nowrap;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Tukang\Views\components\_idx_table') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Tukang\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>