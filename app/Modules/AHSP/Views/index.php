<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola AHSP
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
    .btn-primary:active {
        background-color: var(--palette-primary-hover) !important;
        border-color: var(--palette-primary-hover) !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 92, 92, 0.3) !important;
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
    #table-ahsp {
        margin-top: 0px !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border-radius: 16px !important;
        overflow: hidden !important;
    }

    #table-ahsp thead tr {
        background: var(--palette-primary) !important;
    }

    #table-ahsp thead th {
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

    #table-ahsp thead th:first-child {
        border-top-left-radius: 16px !important;
    }

    #table-ahsp thead th:last-child {
        border-top-right-radius: 16px !important;
    }

    #table-ahsp tbody tr:last-child td:first-child {
        border-bottom-left-radius: 16px !important;
    }

    #table-ahsp tbody tr:last-child td:last-child {
        border-bottom-right-radius: 16px !important;
    }

    #table-ahsp tbody tr {
        transition: background 0.15s ease;
    }

    #table-ahsp tbody tr:hover {
        background: #fffafa !important;
    }

    #table-ahsp tbody td {
        padding: 14px 12px;
        vertical-align: middle;
        border-color: #f0f4fa;
        font-size: 0.88rem;
        color: #343a40;
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .btn-action-show {
        background: #3b82f6;
        color: #fff;
    }

    .btn-action-show:hover {
        background: #2563eb;
        color: #fff;
    }

    .btn-action-edit {
        background: #f76707;
        color: #fff;
    }

    .btn-action-edit:hover {
        background: #d35400;
        color: #fff;
    }

    .btn-action-delete {
        background: #e03131;
        color: #fff;
    }

    .btn-action-delete:hover {
        background: #bd2130;
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

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #8e94a9;
    }

    .empty-state i {
        font-size: 3rem;
        color: #d0d4f5;
        margin-bottom: 16px;
    }

    /* Tab Custom Styling inside Modal */
    .nav-tabs-premium {
        border-bottom: 2px solid #f1f5f9;
    }
    .nav-tabs-premium .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 12px 20px;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }
    .nav-tabs-premium .nav-link:hover {
        color: var(--palette-primary);
        border-bottom: 2px solid rgba(255, 92, 92, 0.3);
    }
    .nav-tabs-premium .nav-link.active {
        color: var(--palette-primary);
        border-bottom: 2px solid var(--palette-primary);
        background: transparent;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\AHSP\Views\components\_header_card') ?>
<?= $this->include('App\Modules\AHSP\Views\components\_idx_content') ?>
<?= $this->include('App\Modules\AHSP\Views\components\_form_modal') ?>
<?= $this->include('App\Modules\AHSP\Views\components\_detail_modal') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\AHSP\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>
