<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Detail Renovasi <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Detail Proyek Renovasi <?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #34395e;
        margin-bottom: 20px;
    }

    .tab-content {
        padding-top: 20px;
        min-height: 500px;
        background: #fff;
        padding: 25px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
    }

    .summary-box {
        background: #f4f6f9;
        border-left: 5px solid var(--palette-primary);
        padding: 20px;
        margin-bottom: 25px;
        border-radius: 5px;
    }

    .summary-label {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
        color: #888;
        display: block;
        margin-bottom: 5px;
    }

    /* Premium Nav Tabs Styling */
    /* ===== AVATAR & HERO ===== */
    .profile-hero {
        background: var(--palette-primary);
        border-radius: 16px 16px 0 0;
        padding: 18px 28px 68px;
        position: relative;
        overflow: hidden;
    }

    .profile-body {
        padding: 0 24px 28px;
        border-radius: 0 0 16px 16px;
        background: #fff;
    }

    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -55px;
    }

    .avatar-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        object-position: center;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        background: #e9ecef;
    }

    .avatar-initials {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        background: var(--palette-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: 700;
        color: #fff;
    }

    /* ===== STATUS PILL ===== */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .status-pill .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.75;
    }

    .status-primary {
        background: #ffe5e5;
        color: var(--palette-primary);
    }

    .status-success {
        background: #d1e7dd;
        color: #0a5c36;
    }

    .status-warning {
        background: #fff3cd;
        color: #7d5a00;
    }

    .status-danger {
        background: #f8d7da;
        color: #842029;
    }

    .status-info {
        background: #cff4fc;
        color: #055160;
    }

    .status-secondary {
        background: #e2e3e5;
        color: #41464b;
    }

    .status-dark {
        background: #d3d3d4;
        color: #1c1f23;
    }

    /* ===== INFO LIST ===== */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 10px;
        background: #ffe5e5;
        color: var(--palette-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .info-label {
        font-size: 0.72rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.93rem;
        color: #212529;
        font-weight: 500;
        word-break: break-word;
    }

    /* ===== CARDS & BUTTONS ===== */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .action-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        height: 100%;
    }

    .action-card .card-header {
        background: var(--palette-primary) !important;
        border-radius: 16px 16px 0 0;
        padding: 18px 22px;
        border: none;
    }

    .status-action-btn {
        border-radius: 10px;
        font-size: 0.83rem;
        font-weight: 600;
        padding: 10px 12px;
        transition: all 0.18s ease;
        border: 2px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .status-action-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    }

    .status-action-btn:disabled {
        opacity: 0.85;
        cursor: not-allowed;
    }

    .btn-current-status {
        cursor: default;
        opacity: 0.9;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .role-chip-hero {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border-radius: 50px;
        padding: 5px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }


    /* Premium Sliding Nav Tabs */
    .nav-tabs-container {
        position: relative;
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 10px 10px 0 0;
        border-bottom: 1px solid #f1f3f9;
        overflow: hidden !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .nav-tabs-wrapper {
        overflow: hidden;
        flex: 1;
        min-width: 0;
        /* Critical for flex overflow */
        position: relative;
    }


    .nav-tabs-premium {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0 10px;
        gap: 5px;
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE/Edge */
        border-bottom: none !important;
        scroll-behavior: smooth;
    }

    .nav-tabs-premium::-webkit-scrollbar {
        display: none;
        /* Chrome/Safari */
    }

    .nav-scroll-btn {
        width: 40px;
        height: 100%;
        min-height: 55px;
        background: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--palette-primary);
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        font-size: 14px;
        opacity: 0.8;
        flex-shrink: 0;
    }


    .nav-scroll-btn:hover {
        background: #f8f9fa;
        opacity: 1;
        color: var(--palette-primary-hover);
    }

    .nav-scroll-btn.left {
        border-right: 1px solid #f1f3f9;
        box-shadow: 5px 0 10px rgba(0, 0, 0, 0.02);
    }

    .nav-scroll-btn.right {
        border-left: 1px solid #f1f3f9;
        box-shadow: -5px 0 10px rgba(0, 0, 0, 0.02);
    }




    .nav-tabs-premium .nav-item {
        margin-bottom: -1px;
    }

    .nav-tabs-premium .nav-link {
        border: none !important;
        border-bottom: 3px solid transparent !important;
        color: #8e94a9 !important;
        padding: 18px 15px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
        background: transparent !important;
    }

    .nav-tabs-premium .nav-link i {
        font-size: 14px;
    }

    .nav-tabs-premium .nav-link:hover {
        color: var(--palette-primary) !important;
        background: rgba(255, 92, 92, 0.03) !important;
    }

    .nav-tabs-premium .nav-link.active {
        color: var(--palette-primary) !important;
        border-bottom: 3px solid var(--palette-primary) !important;
        background: transparent !important;
        position: relative;
    }

    /* Active colors override for specific links */
    .nav-tabs-premium .nav-link.active.text-success {
        border-bottom-color: #47c363 !important;
        color: #47c363 !important;
    }

    .nav-tabs-premium .nav-link.active.text-warning {
        border-bottom-color: #ffa426 !important;
        color: #ffa426 !important;
    }

    .nav-tabs-premium .nav-link.active.text-primary {
        border-bottom-color: var(--palette-primary) !important;
        color: var(--palette-primary) !important;
    }

    .nav-tabs-premium .nav-link.active.text-danger {
        border-bottom-color: #fc544b !important;
        color: #fc544b !important;
    }
</style>

<?php if (can('renovation_target')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_target_styles') ?>
<?php endif; ?>
<?php if (can('renovation_survey')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_survey_styles') ?>
<?php endif; ?>
<?php if (can('renovation_desain')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_desain_styles') ?>
<?php endif; ?>
<?php if (can('renovation_rab')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_rab_styles') ?>
<?php endif; ?>
<?php if (can('renovation_pembayaran')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_pembayaran_styles') ?>
<?php endif; ?>
<?php if (can('renovation_progress')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_progress_styles') ?>
<?php endif; ?>
<?php if (can('renovation_lowongan')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_loker_styles') ?>
<?php endif; ?>
<?php if (can('renovation_absensi')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_absensi_styles') ?>
<?php endif; ?>
<?php if (can('renovation')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_material_styles') ?>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Renovation\Views\components\_dtl_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Renovation\Views\components\_dtl_scripts') ?>

<?php if (can('renovation_target')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_target_scripts') ?>
<?php endif; ?>
<?php if (can('renovation_survey')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_survey_scripts') ?>
<?php endif; ?>
<?php if (can('renovation_rab')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_rab_scripts') ?>
<?php endif; ?>
<?php if (can('renovation_pembayaran')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_pembayaran_scripts') ?>
<?php endif; ?>
<?php if (can('renovation_progress')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_progress_scripts') ?>
<?php endif; ?>
<?php if (can('renovation_absensi')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_absensi_scripts') ?>
<?php endif; ?>
<?php if (can('renovation')): ?>
    <?= $this->include('App\Modules\Renovation\Views\components\_dtl_material_scripts') ?>
<?php endif; ?>
<?= $this->endSection() ?>