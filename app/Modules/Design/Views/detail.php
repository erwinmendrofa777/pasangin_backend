<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Permintaan Desain
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Permintaan Desain
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ===== AVATAR / ICON ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -55px;
    }

    .avatar-initials {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--palette-primary);
    }

    /* ===== PROFILE CARD ===== */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .profile-hero {
        background: var(--palette-primary);
        border-radius: 16px 16px 0 0;
        padding: 18px 28px 24px;
        position: relative;
        overflow: hidden;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .profile-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .profile-body {
        padding: 0 24px 28px;
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

    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-survey {
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-payment {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-default {
        background: #e2e3e5;
        color: #41464b;
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

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--palette-primary);
        margin-bottom: 10px;
    }

    /* Premium Sliding Nav Tabs */
    .nav-tabs-container {
        position: relative;
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 12px;
        border-bottom: 1px solid #f1f3f9;
        overflow: hidden !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .nav-tabs-wrapper {
        overflow: hidden;
        flex: 1;
        min-width: 0;
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
        -ms-overflow-style: none;
        border-bottom: none !important;
        scroll-behavior: smooth;
    }

    .nav-tabs-premium::-webkit-scrollbar {
        display: none;
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
        color: #0b5ed7;
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
        padding: 16px 20px !important;
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

    /* GLightbox overrides */
    .glightbox-video-slide .gslide-inline {
        background: #000 !important;
        padding: 0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        width: 95% !important;
        max-width: 900px !important;
        border-radius: 12px;
        overflow: hidden !important;
        overflow-y: hidden !important;
    }

    .glightbox-video-slide .gslide-inner-content {
        background: #000 !important;
        overflow: hidden !important;
        width: 100% !important;
    }

    .glightbox-video-slide .gslide-description {
        display: none !important;
    }

    .glightbox-video-slide .gslide-media {
        box-shadow: none !important;
        overflow: hidden !important;
        background: #000 !important;
    }

    @media (max-width: 768px) {
        .profile-hero {
            padding: 20px 20px 40px;
        }

        .profile-hero .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
        }

        .profile-body {
            padding: 0 16px 20px;
        }

        .avatar-initials {
            width: 80px;
            height: 80px;
            font-size: 2rem;
            border-width: 3px;
        }

        .avatar-wrapper {
            margin-top: -40px;
        }

        .back-btn-wrapper {
            margin-bottom: 20px !important;
        }

        .back-btn-wrapper .btn {
            border-radius: 10px !important;
            padding: 8px 16px !important;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            background: #fff;
            color: #495057;
            border: 1px solid #dee2e6;
        }
    }
</style>
<?= $this->include('App\Modules\Design\Views\components\_dtl_survey_styles') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_desain_styles') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_target_styles') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_pembayaran_styles') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_rab_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <!-- Card for Tab Navigation -->
        <div class="card shadow-sm mb-3" style="border-radius: 12px; overflow: hidden; border: none;">
            <div class="card-body p-0">
                <div class="nav-tabs-container">
                    <button class="nav-scroll-btn left" onclick="scrollNav('left')"><i
                            class="fas fa-chevron-left"></i></button>
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs nav-tabs-premium" id="myTab" role="tablist">

                            <?php if (can('design_detail')): ?>
                                <li class="nav-item">
                                    <a class="nav-link active" id="detail-tab" data-bs-toggle="tab" href="#detail"
                                        role="tab">
                                        <i class="fas fa-user"></i> Detail
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('design_survey')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="survey-tab" data-bs-toggle="tab" href="#survey" role="tab">
                                        <i class="fas fa-clipboard-check"></i> Survey
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('design_target')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="target-tab" data-bs-toggle="tab" href="#target" role="tab">
                                        <i class="fas fa-tasks"></i> Target
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('design_desain')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="design-tab" data-bs-toggle="tab" href="#design" role="tab">
                                        <i class="fas fa-drafting-compass"></i> Desain
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('design_detail')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="rab-tab" data-bs-toggle="tab" href="#rab" role="tab">
                                        <i class="fas fa-file-invoice-dollar"></i> RAB
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php /* if (can('design_progress')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="progress-tab" data-bs-toggle="tab" href="#progress" role="tab">
                                        <i class="fas fa-tasks"></i> Progress
                                    </a>
                                </li>
                            <?php endif; */ ?>

                            <?php if (can('design_pembayaran')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment" role="tab">
                                        <i class="fas fa-wallet"></i> Pembayaran
                                    </a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>
                    <button class="nav-scroll-btn right" onclick="scrollNav('right')"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

        <!-- Tab Content Area -->
        <div class="tab-content" id="myTabContent">

            <!-- 1. TAB DETAIL -->
            <div class="tab-pane fade show active py-0" id="detail" role="tabpanel">
                <?= $this->include('App\Modules\Design\Views\components\_dtl_tab_detail') ?>
            </div>

            <!-- 2. TAB SURVEY -->
            <?php if (can('design_survey')): ?>
                <div class="tab-pane fade py-0" id="survey" role="tabpanel">
                    <?= $this->include('App\Modules\Design\Views\survey') ?>
                </div>
            <?php endif; ?>

            <!-- 3. TAB TARGET -->
            <?php if (can('design_target')): ?>
                <div class="tab-pane fade py-0" id="target" role="tabpanel">
                    <?= $this->include('App\Modules\Design\Views\target') ?>
                </div>
            <?php endif; ?>

            <!-- 4. TAB DESAIN -->
            <?php if (can('design_desain')): ?>
                <div class="tab-pane fade py-0" id="design" role="tabpanel">
                    <?= $this->include('App\Modules\Design\Views\desain') ?>
                </div>
            <?php endif; ?>

            <!-- TAB RAB -->
            <?php if (can('design_detail')): ?>
                <div class="tab-pane fade py-0" id="rab" role="tabpanel">
                    <?= $this->include('App\Modules\Design\Views\rab') ?>
                </div>
            <?php endif; ?>

            <?php /* <!-- 5. TAB PROGRESS -->
            <?php if (can('design_progress')): ?>
                <div class="tab-pane fade py-0" id="progress" role="tabpanel">
                    <?= $this->include('App\Modules\Design\Views\progress') ?>
                </div>
            <?php endif; */ ?>

            <!-- 6. TAB PEMBAYARAN -->
            <?php if (can('design_pembayaran')): ?>
                <div class="tab-pane fade py-0" id="payment" role="tabpanel">
                    <?= $this->include('App\Modules\Design\Views\pembayaran') ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_scripts') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_survey_scripts') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_desain_scripts') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_target_scripts') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_rab_scripts') ?>
<?= $this->endSection() ?>