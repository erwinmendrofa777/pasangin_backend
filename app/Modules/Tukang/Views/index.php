<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Mitra Tukang
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Tukang
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HEADER CARD ===== */
    .header-card {
        border: 1px solid rgba(255, 92, 92, 0.08) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        background: #fff !important;
    }

    /* ===== PREMIUM CUSTOM SEARCH ===== */
    .search-wrapper {
        position: relative;
        display: inline-block;
    }

    .search-input {
        display: block !important;
        width: 100% !important;
        height: 40px !important;
        border-radius: 10px !important;
        font-size: 0.82rem !important;
        border: 1.5px solid #e2e8f0 !important;
        background: #f8fafc !important;
        color: #334155 !important;
        font-weight: 600 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01) !important;
        outline: none !important;
    }

    .search-input:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }

    .search-input:focus {
        border-color: var(--palette-primary) !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.12), 0 6px 16px rgba(255, 92, 92, 0.06) !important;
        transform: translateY(-1px);
        color: #0f172a !important;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
        z-index: 5;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .search-input:focus ~ .search-icon,
    .search-input:hover ~ .search-icon {
        color: var(--palette-primary) !important;
        transform: translateY(-50%) scale(1.15) rotate(15deg) !important;
    }

    .search-input::placeholder {
        color: #94a3b8;
        opacity: 0.8;
    }

    /* ===== PRIMARY BUTTON SHADOW OVERRIDE ===== */
    .btn-primary {
        background-color: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        box-shadow: 0 4px 10px rgba(255, 92, 92, 0.25) !important;
        transition: all 0.2s ease !important;
    }

    .btn-primary:hover {
        background-color: var(--palette-primary-hover) !important;
        border-color: var(--palette-primary-hover) !important;
        box-shadow: 0 6px 16px rgba(255, 92, 92, 0.4) !important;
    }

    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary:active:focus,
    .btn-primary.active,
    .btn-primary:focus:active,
    .btn-primary.disabled:focus {
        background-color: var(--palette-primary-hover) !important;
        border-color: var(--palette-primary-hover) !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 92, 92, 0.3) !important;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 16px !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01) !important;
        overflow: hidden !important;
        background: #fff !important;
    }

    .table-card .card-body {
        padding: 0 !important;
    }

    /* ===== TABLE ===== */
    #table-1 {
        margin-top: 0px !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border-radius: 16px !important;
        overflow: hidden !important;
    }

    #table-1 thead tr {
        background: var(--palette-primary) !important;
    }

    #table-1 thead th {
        color: rgba(255, 255, 255, 0.92) !important;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: none !important;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    #table-1 thead th:first-child {
        border-top-left-radius: 16px !important;
    }

    #table-1 thead th:last-child {
        border-top-right-radius: 16px !important;
    }

    #table-1 tbody tr:last-child td:first-child {
        border-bottom-left-radius: 16px !important;
    }

    #table-1 tbody tr:last-child td:last-child {
        border-bottom-right-radius: 16px !important;
    }

    #table-1 tbody tr {
        transition: background 0.15s ease;
    }

    #table-1 tbody tr:hover {
        background: #fffafa !important;
    }

    #table-1 tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
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
        border-radius: 30px !important;
        padding: 6px 14px !important;
        font-weight: 700;
        font-size: 0.72rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        border: 1px solid transparent;
    }

    .status-berkas {
        background: #fffbeb !important;
        color: #d97706 !important;
        border: 1px solid #fde68a !important;
    }

    .status-ditolak {
        background: #fef2f2 !important;
        color: #dc2626 !important;
        border: 1px solid #fee2e2 !important;
    }

    .status-test {
        background: #f0f9ff !important;
        color: #0284c7 !important;
        border: 1px solid #bae6fd !important;
    }

    .status-aktivasi {
        background: #f5f3ff !important;
        color: #7c3aed !important;
        border: 1px solid #ddd6fe !important;
    }

    .status-siap {
        background: #f0fdf4 !important;
        color: #16a34a !important;
        border: 1px solid #bbf7d0 !important;
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
        color: var(--palette-primary) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
    }

    .dataTables_paginate .page-item.disabled .page-link {
        color: #98a6ad !important;
        opacity: 0.5;
        background: transparent !important;
    }

    .dataTables_paginate .page-item.active .page-link {
        background: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.3);
    }

    .dataTables_paginate .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #ffe5e5 !important;
        border-color: #ffe5e5 !important;
        color: var(--palette-primary) !important;
    }

    @media (max-width: 768px) {
        .search-wrapper {
            width: 100% !important;
        }

        .dt-footer {
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 12px;
            padding: 16px !important;
        }

        .dataTables_paginate {
            display: flex !important;
            justify-content: center !important;
            width: 100% !important;
        }

        .dataTables_paginate .pagination {
            justify-content: center !important;
            margin: 0 !important;
        }

        .dataTables_info {
            text-align: center !important;
            width: 100% !important;
        }

        #table-1 th,
        #table-1 td {
            white-space: nowrap;
        }
    }

    /* ===== PREMIUM GROUP TREE TABLE & PAGINATION ===== */
    .group-parent-row {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .group-parent-row:hover {
        background-color: #fff9f9 !important;
    }
    .group-parent-row.expanded {
        background-color: #fff5f5 !important;
    }
    .group-parent-row.expanded .transition-icon {
        transform: rotate(90deg) !important;
        color: var(--palette-primary) !important;
    }
    .group-parent-row.expanded .toggle-detail-btn {
        background-color: var(--palette-primary) !important;
        color: #fff !important;
    }
    .group-detail-row {
        background-color: #f8fafc !important;
    }
    .group-detail-row table {
        background-color: #ffffff !important;
    }
    .group-detail-row table th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-weight: 700;
        border-bottom: 1.5px solid #e2e8f0;
    }
    
    /* Pagination styling overrides */
    .pagination .page-item .page-link {
        border-radius: 6px !important;
        margin: 0 2px;
        padding: 5px 11px;
        font-weight: 600;
        color: #475569;
        border: 1px solid #e2e8f0;
        transition: all 0.15s ease;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--palette-primary) !important;
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.2);
    }
    .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #fff5f5 !important;
        border-color: #ffcccc !important;
        color: var(--palette-primary) !important;
    }
    .pagination .page-item.disabled .page-link {
        color: #94a3b8;
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }

    /* Soft premium badges */
    .badge-soft-owner {
        background: #fffbeb !important;
        color: #d97706 !important;
        border: 1px solid #fde68a !important;
    }
    .badge-soft-approved {
        background: #f0fdf4 !important;
        color: #16a34a !important;
        border: 1px solid #bbf7d0 !important;
    }
    .badge-soft-pending {
        background: #fff7ed !important;
        color: #ea580c !important;
        border: 1px solid #ffedd5 !important;
    }
    .badge-soft-rejected {
        background: #fef2f2 !important;
        color: #dc2626 !important;
        border: 1px solid #fee2e2 !important;
    }
    @media (min-width: 992px) {
        .stats-divider {
            border-left: 1.5px solid rgba(226, 232, 240, 0.8) !important;
        }
    }
    #main-group-table thead th {
        color: #ffffff !important;
    }
    .group-detail-row table thead th {
        color: #334155 !important;
        font-weight: 700 !important;
    }

    /* ===== PREMIUM MODAL & TABLE DESIGN ===== */
    .modal-content {
        border-radius: 20px !important;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.08), 0 4px 16px rgba(0, 0, 0, 0.02) !important;
    }
    .modal-backdrop.show {
        opacity: 0.45 !important;
        background-color: #0f172a !important;
    }
    .btn-close-custom:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
        transform: scale(1.05);
    }
    .btn-close-custom:active {
        transform: scale(0.95);
    }
    .badge-status-completed {
        background: #ecfdf5 !important;
        color: #059669 !important;
        border: 1px solid #a7f3d0 !important;
    }
    .badge-status-inprogress {
        background: #eff6ff !important;
        color: #2563eb !important;
        border: 1px solid #bfdbfe !important;
    }
    .badge-status-pending {
        background: #fffbeb !important;
        color: #d97706 !important;
        border: 1px solid #fde68a !important;
    }
    .badge-status-notstarted {
        background: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
    }
    .project-card-badge {
        background: rgba(255, 92, 92, 0.05) !important;
        color: var(--palette-primary) !important;
        border: 1.5px solid rgba(255, 92, 92, 0.15) !important;
        font-weight: 700 !important;
        border-radius: 6px !important;
    }
    .premium-target-card {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.015);
        border-radius: 12px !important;
        transition: all 0.25s ease-in-out;
    }
    .premium-target-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
        border-color: rgba(255, 92, 92, 0.18) !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Tukang\Views\components\_header_card') ?>

<?= $this->include('App\Modules\Tukang\Views\components\_idx_table') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Tukang\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>