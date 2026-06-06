<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Konten
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== PAGE HEADER ===== */
    .page-header-card {
        border: none;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        box-shadow: 0 10px 30px rgba(255, 92, 92, 0.25);
        overflow: hidden;
        position: relative;
        padding: 28px 32px;
        margin-bottom: 24px;
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -70px;
        left: -30px;
        width: 250px;
        height: 250px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 50%;
    }

    .page-header-card h4 {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 4px;
        position: relative;
        z-index: 1;
    }

    .page-header-card p {
        color: rgba(255, 255, 255, 0.75);
        margin: 0;
        font-size: 0.88rem;
        position: relative;
        z-index: 1;
    }

    .btn-add-tips {
        background: rgba(255, 255, 255, 0.2);
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        color: #fff;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 9px 20px;
        transition: all 0.2s;
        backdrop-filter: blur(5px);
        position: relative;
        z-index: 1;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-tips:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    /* ===== STATS ROW ===== */
    .stat-mini-card {
        border: none;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 4px 16px rgba(255, 92, 92, 0.08);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .stat-mini-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .stat-mini-card .stat-val {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
        color: #2d3748;
    }

    .stat-mini-card .stat-lbl {
        font-size: 0.72rem;
        font-weight: 700;
        color: #8e94a9;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-top: 2px;
    }

    /* ===== TABLE CARD ===== */
    .table-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(255, 92, 92, 0.08);
    }

    .table-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f4fa;
        padding: 20px 28px;
        border-radius: 20px 20px 0 0;
    }

    .table-card .card-body {
        padding: 0;
    }

    /* ===== SEARCH INPUT ===== */
    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 0.85rem;
        pointer-events: none;
        z-index: 5;
    }

    .search-wrapper input {
        padding-left: 40px !important;
        border-radius: 10px !important;
        border: 1.5px solid #e9ecef;
        font-size: 0.85rem;
        height: 38px;
        transition: all 0.2s;
    }

    .search-wrapper input:focus {
        border-color: var(--palette-primary);
        box-shadow: 0 0 0 3px rgba(255, 92, 92, 0.12);
        outline: none;
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
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #ffdddd;
        padding: 14px 16px;
    }

    #table-1 tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.875rem;
        border-bottom: 1px solid #f8f9fa;
    }

    #table-1 tbody tr:last-child td {
        border-bottom: none;
    }

    #table-1 tbody tr:hover {
        background: #fffafa;
    }

    /* ===== TIPS IMAGE ===== */
    .tips-img {
        width: 110px;
        height: 65px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    /* ===== TITLE + EXCERPT ===== */
    .tips-title {
        font-weight: 700;
        color: #2d3748;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    .tips-excerpt {
        color: #8e94a9;
        font-size: 0.78rem;
        max-width: 280px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 3px;
    }

    /* ===== BADGES ===== */
    .badge-pill {
        border-radius: 50px;
        padding: 5px 12px;
        font-weight: 700;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .bg-tukang {
        background: #fff7ed;
        color: #9a3412;
    }

    .bg-client {
        background: #eff6ff;
        color: #1e40af;
    }

    .status-active {
        background: #e8fdf0;
        color: #0a6640;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #6b7280;
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

    .btn-action-detail {
        background: var(--palette-primary);
        color: var(--palette-primary);
    }

    .btn-action-detail:hover {
        background: var(--palette-primary);
        color: #fff;
    }

    .btn-action-edit {
        background: #f76707;
        color: #f76707;
    }

    .btn-action-edit:hover {
        background: #f76707;
        color: #fff;
    }

    .btn-action-delete {
        background: #e03131;
        color: #e03131;
    }

    .btn-action-delete:hover {
        background: #e03131;
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

    /* ===== EMPTY STATE ===== */
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Tips\Views\components\_idx_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Tips\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>