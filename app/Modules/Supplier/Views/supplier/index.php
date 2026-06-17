<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Supplier
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Supplier
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

    /* ===== PREMIUM CUSTOM DROPDOWN & SEARCH ===== */
    .filter-wrapper,
    .search-wrapper {
        position: relative;
        display: inline-block;
    }

    /* Common Hover & Focus transitions */
    .dropdown-trigger,
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

    .dropdown-trigger:hover,
    .search-input:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }

    .dropdown-trigger:focus,
    .dropdown-trigger.open,
    .search-input:focus {
        border-color: var(--palette-primary) !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.12), 0 6px 16px rgba(255, 92, 92, 0.06) !important;
        transform: translateY(-1px);
        color: #0f172a !important;
    }

    /* Icons animation */
    .filter-wrapper .filter-icon,
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

    .dropdown-trigger.open .arrow-icon {
        transform: rotate(180deg);
        color: var(--palette-primary) !important;
    }

    .dropdown-trigger.open ~ .filter-icon,
    .dropdown-trigger:focus ~ .filter-icon,
    .dropdown-trigger:hover ~ .filter-icon {
        color: var(--palette-primary) !important;
        transform: translateY(-50%) scale(1.15) rotate(-10deg) !important;
    }

    .search-input:focus ~ .search-icon,
    .search-input:hover ~ .search-icon {
        color: var(--palette-primary) !important;
        transform: translateY(-50%) scale(1.15) rotate(15deg) !important;
    }

    /* Custom Dropdown Options Menu */
    .dropdown-menu-list {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        margin-top: 8px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08) !important;
        border: 1px solid #e2e8f0 !important;
        padding: 6px 0 !important;
        z-index: 1000;
        display: block !important;
        visibility: hidden;
        opacity: 0;
        transform: translateY(10px) scale(0.95);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .dropdown-menu-list.show {
        visibility: visible;
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .dropdown-item-custom {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
        border-radius: 10px;
        margin: 2px 6px;
    }

    .dropdown-item-custom:hover {
        background: #fff5f5;
        color: var(--palette-primary);
        transform: translateX(4px);
    }

    .dropdown-item-custom.active {
        background: rgba(255, 92, 92, 0.08);
        color: var(--palette-primary);
    }

    .search-input::placeholder {
        color: #94a3b8;
        opacity: 0.8;
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
        padding: 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
    }

    /* ===== AVATAR ===== */
    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        object-position: center;
        border: 2px solid #ffdddd;
        box-shadow: 0 2px 8px rgba(255, 92, 92, 0.12);
        transition: all 0.2s ease-in-out;
        cursor: zoom-in;
    }

    .user-avatar:hover {
        transform: scale(1.08);
        border-color: var(--palette-primary);
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.24);
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
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-banned {
        background: #e5e7eb;
        color: #1f2937;
    }

    .status-default {
        background: #e9ecef;
        color: #495057;
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
        background: #ff4d4d !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(255, 77, 77, 0.15) !important;
    }

    .btn-action-detail i {
        color: #fff !important;
    }

    .btn-action-detail:hover {
        background: #ff3333 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.35) !important;
    }

    .btn-action-edit {
        background: #ff9f43 !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(255, 159, 67, 0.15) !important;
    }

    .btn-action-edit i {
        color: #fff !important;
    }

    .btn-action-edit:hover {
        background: #ff8f23 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(255, 159, 67, 0.35) !important;
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

    mark {
        background-color: #dbeafe;
        color: #1d4ed8;
        padding: 1px 3px;
        border-radius: 3px;
    }

    @media (max-width: 768px) {
        .table-card-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 16px;
            padding: 16px !important;
        }

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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Supplier\Views\supplier\components\_header_card') ?>
<?= $this->include('App\Modules\Supplier\Views\supplier\components\_idx_table') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Supplier\Views\supplier\components\_idx_scripts') ?>
<?= $this->endSection() ?>