<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Saldo Admin & Platform
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Saldo Admin & Platform
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== UNIFIED HEADER + BALANCE CARD ===== */
    .header-card {
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02) !important;
        background: #fff !important;
        overflow: hidden !important;
    }

    .header-top {
        border-bottom: 1px solid #f0f4fa;
    }

    .balance-section {
        padding: 24px;
        transition: background 0.2s ease;
        border-top: 1px solid #f0f4fa;
    }

    .balance-section:hover {
        background: #fafcff;
    }

    .balance-section-green:hover {
        background: #f0fdf4;
    }

    .balance-section-primary:hover {
        background: #fff9f9;
    }

    @media (min-width: 992px) {
        .balance-section + .balance-section {
            border-left: 1px solid #f0f4fa;
        }
    }

    .balance-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .balance-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .balance-amount {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
        margin: 14px 0 18px;
    }

    /* ===== ACTION BUTTONS ===== */
    .fintech-btn {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1.5px solid transparent;
        cursor: pointer;
    }

    .fintech-btn-success {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }

    .fintech-btn-success:hover {
        background: #10B981;
        color: #fff;
        border-color: #10B981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    .fintech-btn-danger {
        background: #fff5f5;
        color: #e11d48;
        border-color: #fecdd3;
    }

    .fintech-btn-danger:hover {
        background: var(--palette-primary);
        color: #fff;
        border-color: var(--palette-primary);
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.25);
    }

    .fintech-btn-secondary {
        background: #f8fafc;
        color: #475569;
        border-color: #e2e8f0;
    }

    .fintech-btn-secondary:hover {
        background: #64748b;
        color: #fff;
        border-color: #64748b;
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.2);
    }

    /* ===== STATUS CHIPS ===== */
    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 50px;
        border: 1px solid transparent;
    }

    .status-chip-success {
        background: #ecfdf5;
        color: #059669;
        border-color: #d1fae5;
    }

    .status-chip-danger {
        background: #fff5f5;
        color: #e11d48;
        border-color: #ffe4e4;
    }

    .status-chip .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .status-chip-success .dot {
        background-color: #10B981;
        box-shadow: 0 0 8px #10B981;
        position: relative;
    }

    .status-chip-success .dot::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: inherit;
        top: 0;
        left: 0;
        animation: pulseRadar 1.8s ease-out infinite;
    }

    @keyframes pulseRadar {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(2.5); opacity: 0; }
    }

    .status-chip-danger .dot {
        background-color: #EF4444;
        box-shadow: 0 0 8px #EF4444;
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
        transition: all 0.25s ease;
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
    #table-transactions {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
    }

    #table-transactions thead tr {
        background: var(--palette-primary) !important;
    }

    #table-transactions thead th {
        color: rgba(255, 255, 255, 0.92) !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.6px !important;
        text-transform: uppercase !important;
        border-bottom: none !important;
        border-top: none !important;
        padding: 14px 12px !important;
        white-space: nowrap !important;
    }

    #table-transactions tbody tr {
        transition: background 0.15s ease !important;
    }

    #table-transactions tbody tr:hover {
        background-color: #fffafa !important;
    }

    #table-transactions tbody td {
        padding: 12px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f0f4fa !important;
        border-top: none !important;
        font-size: 0.88rem !important;
        color: #343a40 !important;
    }

    /* ===== FOOTER DATATABLE ===== */
    .dt-footer {
        padding: 14px 20px !important;
        border-top: 1px solid #f0f4fa !important;
        background: #fafcff !important;
    }

    .dataTables_info {
        font-size: 0.82rem !important;
        color: #6c757d !important;
    }

    .dataTables_paginate .page-item .page-link {
        border-radius: 8px !important;
        font-size: 0.82rem !important;
        margin: 0 3px !important;
        border: 1px solid transparent !important;
        color: var(--palette-primary) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: transparent !important;
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
        font-weight: 600 !important;
        box-shadow: 0 2px 6px rgba(255, 92, 92, 0.3) !important;
    }

    .dataTables_paginate .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #ffe5e5 !important;
        border-color: #ffe5e5 !important;
        color: var(--palette-primary) !important;
    }

    /* ===== BADGES ===== */
    .badge-premium {
        border-radius: 50px !important;
        padding: 6px 14px !important;
        font-size: 0.72rem !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
    }

    .badge-income {
        background-color: #ecfdf5 !important;
        color: #059669 !important;
        border: 1px solid #d1fae5 !important;
    }

    .badge-expense {
        background-color: #fff5f5 !important;
        color: #e11d48 !important;
        border: 1px solid #ffe4e4 !important;
    }

    .badge-source {
        background-color: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
    }

    /* ===== MODAL BEAUTIFICATION ===== */
    .fintech-modal .modal-content {
        border-radius: 24px;
        border: none;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .fintech-modal .modal-header {
        padding: 24px 30px;
        border-bottom: none;
    }

    .fintech-modal .modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: -0.2px;
    }

    .fintech-modal .modal-body {
        padding: 30px;
    }

    .fintech-modal .modal-footer {
        padding: 20px 30px 24px;
        border-top: none;
        background: #f8fafc;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .form-group-custom {
        margin-bottom: 20px;
    }

    .form-group-custom label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        margin-bottom: 8px;
        display: block;
    }

    .form-control-custom {
        border-radius: 12px !important;
        border: 1.5px solid #cbd5e1 !important;
        padding: 12px 16px !important;
        font-size: 0.92rem !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        transition: all 0.2s ease !important;
        outline: none !important;
        width: 100% !important;
    }

    .form-control-custom:focus {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.1) !important;
    }

    .form-control-custom::placeholder {
        color: #94a3b8 !important;
        font-weight: 500 !important;
    }

    .input-group-custom {
        display: flex !important;
        align-items: center !important;
        position: relative !important;
    }

    .input-group-custom .prefix {
        position: absolute !important;
        left: 16px !important;
        font-weight: 700 !important;
        color: #475569 !important;
        font-size: 0.92rem !important;
        pointer-events: none !important;
        z-index: 10 !important;
    }

    .input-group-custom .form-control-custom {
        padding-left: 45px !important;
    }

    @media (max-width: 991px) {
        .balance-section + .balance-section {
            border-top: 1px solid #f0f4fa;
        }
    }

    @media (max-width: 768px) {
        .balance-amount {
            font-size: 1.6rem;
        }

        .search-wrapper {
            width: 100% !important;
        }

        .dt-footer {
            flex-direction: column !important;
            align-items: center !important;
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

        #table-transactions th,
        #table-transactions td {
            white-space: nowrap;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('App\Modules\Wallets\Views\admin_balance\components\_header_card') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2 fs-5"></i>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?= $this->include('App\Modules\Wallets\Views\admin_balance\components\_idx_table') ?>
<?= $this->include('App\Modules\Wallets\Views\admin_balance\components\_idx_modals') ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Wallets\Views\admin_balance\components\_idx_scripts') ?>
<?= $this->endSection() ?>