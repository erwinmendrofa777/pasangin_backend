<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Proyek Konstruksi
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Proyek Konstruksi
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script>
    // Deteksi hash sebelum rendering halaman dimulai untuk mencegah Wrong Tab Flash
    (function() {
        var hash = window.location.hash.replace(/^#/, '');
        var validTabs = ['survey', 'desain', 'rab', 'addendum', 'target', 'payment', 'absensi', 'material'];
        if (hash && validTabs.indexOf(hash) !== -1) {
            document.documentElement.className += ' has-tab-hash';
            document.documentElement.setAttribute('data-active-tab', hash);
        }
    })();
</script>

<style>
    @keyframes fadeInUpMini {
        from {
            opacity: 0;
            transform: translate3d(0, 15px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    .animate__fadeInUpMini {
        animation-name: fadeInUpMini !important;
        animation-duration: 0.35s !important;
        animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .section-title {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 0.82rem;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: var(--palette-primary);
        margin-bottom: 16px;
        margin-top: 10px;
    }

    .section-title i {
        color: var(--palette-primary) !important;
    }

    .tab-content {
        padding-top: 0px;
        min-height: 500px;
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
        background: linear-gradient(135deg, var(--palette-primary) 0%, #ff7c81 100%);
        border-radius: 16px;
        padding: 24px 28px !important;
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

    /* ===== STATUS PILL ===== */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
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
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .info-item:hover .info-icon {
        background: #ffe5e5;
        color: var(--palette-primary);
        transform: scale(1.05);
    }

    .info-label {
        font-size: 0.72rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 0.92rem;
        color: #1e293b;
        font-weight: 600;
        word-break: break-word;
    }

    /* ===== PROFILE CARD ===== */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }


    /* Premium Sliding Nav Tabs */
    .nav-tabs-container {
        position: relative;
        display: flex;
        align-items: center;
        background: #ffffff;
        border-radius: 12px;
        border-bottom: 1px solid #e2e8f0;
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

    .nav-tabs-wrapper::before,
    .nav-tabs-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 24px;
        z-index: 2;
        pointer-events: none;
    }

    .nav-tabs-wrapper::before {
        left: 0;
        background: linear-gradient(to right, #ffffff, transparent);
    }

    .nav-tabs-wrapper::after {
        right: 0;
        background: linear-gradient(to left, #ffffff, transparent);
    }

    .nav-tabs-premium {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0 0;
        gap: 4px;
        scrollbar-width: none;
        -ms-overflow-style: none;
        border-bottom: none !important;
        scroll-behavior: smooth;
        align-items: center;
        width: 100%;
    }

    .nav-tabs-premium::-webkit-scrollbar {
        display: none;
    }


    .nav-scroll-btn {
        width: 44px;
        align-self: stretch;
        background: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s ease;
        font-size: 12px;
        flex-shrink: 0;
    }

    .nav-scroll-btn:hover {
        color: var(--palette-primary);
        background: #f8fafc;
    }

    .nav-scroll-btn.left {
        border-right: 1px solid #f1f5f9;
    }

    .nav-scroll-btn.right {
        border-left: 1px solid #f1f5f9;
    }

    .nav-tabs-premium .nav-item {
        flex: 1 1 0%;
        min-width: max-content;
        margin-bottom: 0;
    }

    .nav-tabs-premium .nav-link {
        position: relative;
        border: none !important;
        color: #64748b !important;
        padding: 18px 16px !important;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        gap: 8px;
        background: transparent !important;
        border-radius: 0 !important;
    }

    .nav-tabs-premium .nav-link i {
        font-size: 14px;
        color: #94a3b8;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .nav-tabs-premium .nav-link:hover {
        color: var(--palette-primary) !important;
    }

    .nav-tabs-premium .nav-link:hover i {
        color: var(--palette-primary) !important;
        transform: translateY(-1px);
    }

    .nav-tabs-premium .nav-link.active {
        color: var(--palette-primary) !important;
        font-weight: 700 !important;
        background: transparent !important;
    }

    .nav-tabs-premium .nav-link.active i {
        color: var(--palette-primary) !important;
    }
    .nav-tabs-premium .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%) scaleX(0);
        width: calc(100% - 24px);
        height: 3px;
        background: var(--palette-primary);
        border-radius: 3px 3px 0 0;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: center;
    }

    .nav-tabs-premium .nav-link.active::after {
        transform: translateX(-50%) scaleX(1);
    }

    /* Active colors override for specific links */
    .nav-tabs-premium .nav-link.active.text-success {
        color: #47c363 !important;
    }
    .nav-tabs-premium .nav-link.active.text-success::after {
        background: #47c363 !important;
    }

    .nav-tabs-premium .nav-link.active.text-warning {
        color: #ffa426 !important;
    }
    .nav-tabs-premium .nav-link.active.text-warning::after {
        background: #ffa426 !important;
    }

    .nav-tabs-premium .nav-link.active.text-primary {
        color: var(--palette-primary) !important;
    }
    .nav-tabs-premium .nav-link.active.text-primary::after {
        background: var(--palette-primary) !important;
    }

    .nav-tabs-premium .nav-link.active.text-danger {
        color: #fc544b !important;
    }
    .nav-tabs-premium .nav-link.active.text-danger::after {
        background: #fc544b !important;
    }

    /* ===== GLIGHTBOX VIDEO INLINE SLIDE PREMIUM SYSTEM ===== */
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

    /* ===== WRONG TAB FLASH PREVENTION ===== */
    html.has-tab-hash .tab-content #detail {
        display: none !important;
    }
    html.has-tab-hash .tab-content #survey.tab-pane,
    html.has-tab-hash .tab-content #desain.tab-pane,
    html.has-tab-hash .tab-content #rab.tab-pane,
    html.has-tab-hash .tab-content #addendum.tab-pane,
    html.has-tab-hash .tab-content #target.tab-pane,
    html.has-tab-hash .tab-content #payment.tab-pane,
    html.has-tab-hash .tab-content #absensi.tab-pane,
    html.has-tab-hash .tab-content #material.tab-pane {
        display: none;
    }
    html[data-active-tab="survey"] .tab-content #survey.tab-pane,
    html[data-active-tab="desain"] .tab-content #desain.tab-pane,
    html[data-active-tab="rab"] .tab-content #rab.tab-pane,
    html[data-active-tab="addendum"] .tab-content #addendum.tab-pane,
    html[data-active-tab="target"] .tab-content #target.tab-pane,
    html[data-active-tab="payment"] .tab-content #payment.tab-pane,
    html[data-active-tab="absensi"] .tab-content #absensi.tab-pane,
    html[data-active-tab="material"] .tab-content #material.tab-pane {
        display: block !important;
        opacity: 1 !important;
    }

    html.has-tab-hash #myTab #detail-tab {
        color: #64748b !important;
        font-weight: 600 !important;
    }
    html.has-tab-hash #myTab #detail-tab::after {
        transform: translateX(-50%) scaleX(0) !important;
    }
    html[data-active-tab="survey"] #myTab #survey-tab,
    html[data-active-tab="desain"] #myTab #desain-tab,
    html[data-active-tab="rab"] #myTab #rab-tab,
    html[data-active-tab="addendum"] #myTab #addendum-tab,
    html[data-active-tab="target"] #myTab #target-tab,
    html[data-active-tab="payment"] #myTab #payment-tab,
    html[data-active-tab="absensi"] #myTab #absensi-tab,
    html[data-active-tab="material"] #myTab #material-tab {
        color: var(--palette-primary) !important;
        font-weight: 700 !important;
    }
    html[data-active-tab="survey"] #myTab #survey-tab::after,
    html[data-active-tab="desain"] #myTab #desain-tab::after,
    html[data-active-tab="rab"] #myTab #rab-tab::after,
    html[data-active-tab="addendum"] #myTab #addendum-tab::after,
    html[data-active-tab="target"] #myTab #target-tab::after,
    html[data-active-tab="payment"] #myTab #payment-tab::after,
    html[data-active-tab="absensi"] #myTab #absensi-tab::after,
    html[data-active-tab="material"] #myTab #material-tab::after {
        transform: translateX(-50%) scaleX(1) !important;
    }
</style>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_absensi_styles') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_addendum_styles') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_desain_styles') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_loker_styles') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_material_styles') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_pembayaran_styles') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_rab_styles') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_survey_styles') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_target_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">

        <!-- Card for Tab Navigation -->
        <div class="card shadow-sm mb-0" style="border-radius: 12px; overflow: hidden; border: none;">
            <div class="card-body p-0">
                <div class="nav-tabs-container">
                    <button class="nav-scroll-btn left" onclick="navigateTab('left')"><i
                            class="fas fa-chevron-left"></i></button>
                    <div class="nav-tabs-wrapper">

                        <ul class="nav nav-tabs nav-tabs-premium" id="myTab" role="tablist">

                            <?php if (can('construction_detail')): ?>
                                <li class="nav-item">
                                    <a class="nav-link active" id="detail-tab" data-bs-toggle="tab" href="#detail">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('construction_survey')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="survey-tab" data-bs-toggle="tab" href="#survey">
                                        <i class="fas fa-map-marker-alt"></i> Survey
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('construction_desain')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="desain-tab" data-bs-toggle="tab" href="#desain">
                                        <i class="fas fa-bezier-curve"></i> Desain
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('construction_rab')): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-primary" id="rab-tab" data-bs-toggle="tab" href="#rab">
                                        <i class="fas fa-calculator"></i> Kelola RAB
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('construction_addendum')): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-primary" id="addendum-tab" data-bs-toggle="tab" href="#addendum">
                                        <i class="fas fa-file-signature"></i> Addendum
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('construction_target')): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-warning" id="target-tab" data-bs-toggle="tab" href="#target">
                                        <i class="fas fa-bullseye"></i> Target
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('construction_pembayaran')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment">
                                        <i class="fas fa-credit-card"></i> Pembayaran
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('construction_absensi')): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-primary" id="absensi-tab" data-bs-toggle="tab" href="#absensi">
                                        <i class="fas fa-user-check"></i> Absensi
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (can('construction')): ?>
                                <li class="nav-item">
                                    <a class="nav-link text-primary" id="material-tab" data-bs-toggle="tab" href="#material">
                                        <i class="fas fa-box-open"></i> Pengajuan Material
                                    </a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>
                    <button class="nav-scroll-btn right" onclick="navigateTab('right')"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

        <!-- Tab Content Area -->
        <div class="tab-content pt-0 pe-1 ps-1" id="myTabContent">

            <!-- ------------ -->
            <!-- tab 1 detail -->
            <div class="tab-pane fade show active py-0" id="detail" role="tabpanel">
                <?php
                // Status Configuration for Construction
                $conStatus = $construction['status'] ?? 'PENDING';
                $conStatusMeta = [
                    'PENDING' => ['color' => 'warning', 'icon' => 'fas fa-clock', 'label' => 'Pending', 'desc' => 'Menunggu tindak lanjut'],
                    'SURVEY' => ['color' => 'info', 'icon' => 'fas fa-map-marked-alt', 'label' => 'Survey', 'desc' => 'Sedang tahap survey'],
                    'DESIGNING' => ['color' => 'primary', 'icon' => 'fas fa-drafting-compass', 'label' => 'Desain', 'desc' => 'Proses pembuatan desain'],
                    'RAB' => ['color' => 'secondary', 'icon' => 'fas fa-file-invoice-dollar', 'label' => 'RAB', 'desc' => 'Penyusunan RAB'],
                    'CONSTRUCTION' => ['color' => 'primary', 'icon' => 'fas fa-hard-hat', 'label' => 'Konstruksi', 'desc' => 'Pembangunan berjalan'],
                    'COMPLETED' => ['color' => 'success', 'icon' => 'fas fa-check-circle', 'label' => 'Selesai', 'desc' => 'Proyek telah selesai'],
                    'CANCELLED' => ['color' => 'danger', 'icon' => 'fas fa-times-circle', 'label' => 'Dibatalkan', 'desc' => 'Proyek dibatalkan'],
                ];
                $currentConMeta = $conStatusMeta[$conStatus] ?? ['color' => 'dark', 'icon' => 'fas fa-circle', 'label' => $conStatus, 'desc' => 'Status tidak diketahui'];

                // Initials
                $nameParts = explode(' ', trim($construction['full_name'] ?? 'K'));
                $initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));

                // Set variables for subviews (CodeIgniter 4 view includes load variables from view data)
                $this->setData([
                    'conStatus' => $conStatus,
                    'conStatusMeta' => $conStatusMeta,
                    'currentConMeta' => $currentConMeta,
                ]);
                ?>

                <?= $this->include('App\Modules\Construction\Views\components\_dtl_tab_detail') ?>
            </div>
            <!-- end tab 1 detail -->



            <!-- --------- -->
            <!-- tab 3 target -->
            <!-- --------- -->
            <?php if (can('construction_target')): ?>
                <div class="tab-pane fade" id="target" role="tabpanel">
                    <?= $this->include('App\Modules\Construction\Views\target') ?>
                </div>
            <?php endif; ?>

            <!-- ------------ -->
            <!-- tab 4 survey -->
            <!-- ------------ -->
            <?php if (can('construction_survey')): ?>
                <div class="tab-pane fade" id="survey" role="tabpanel">
                    <?= $this->include('App\Modules\Construction\Views\survey') ?>
                </div>
            <?php endif; ?>

            <!-- ------------ -->
            <!-- tab 5 desain -->
            <!-- ------------ -->
            <?php if (can('construction_desain')): ?>
                <div class="tab-pane fade" id="desain" role="tabpanel">
                    <?= $this->include('App\Modules\Construction\Views\desain') ?>
                </div>
            <?php endif; ?>

            <!-- --------- -->
            <!-- tab 6 rab -->
            <!-- --------- -->
            <?php if (can('construction_rab')): ?>
                <div class="tab-pane fade" id="rab" role="tabpanel">
                    <?= $this->include('App\Modules\Construction\Views\rab') ?>
                </div>
            <?php endif; ?>

            <!-- --------- -->
            <!-- tab 7 addendum -->
            <!-- --------- -->
            <?php if (can('construction_addendum')): ?>
                <div class="tab-pane fade" id="addendum" role="tabpanel">
                    <?= $this->include('App\Modules\Construction\Views\addendum') ?>
                </div>
            <?php endif; ?>

            <!-- ---------------- -->
            <!-- tab 8 pembayaran -->
            <!-- ---------------- -->
            <?php if (can('construction_pembayaran')): ?>
                <div class="tab-pane fade" id="payment" role="tabpanel">
                    <?= $this->include('App\Modules\Construction\Views\pembayaran') ?>
                </div>
            <?php endif; ?>





            <!-- -------------------- -->
            <!-- tab 11 absensi -->
            <!-- -------------------- -->
            <?php if (can('construction_absensi')): ?>
                <div class="tab-pane fade" id="absensi" role="tabpanel">
                    <?= $this->include('App\Modules\Construction\Views\absensi') ?>
                </div>
            <?php endif; ?>

            <?php if (can('construction')): ?>
                <div class="tab-pane fade" id="material" role="tabpanel">
                    <?= $this->include('App\Modules\Construction\Views\material_submissions') ?>
                </div>
            <?php endif; ?>

        </div><!-- end .tab-content -->
        <script>
            // Jalankan sinkronisasi tab aktif secara instan sebelum seluruh halaman selesai dimuat/di-paint
            (function() {
                var hash = window.location.hash.replace(/^#/, '');
                if (hash) {
                    var triggerEl = document.querySelector('#myTab a[href="#' + hash + '"]');
                    var targetPane = document.querySelector('.tab-content #' + hash);
                    if (triggerEl && targetPane) {
                        // Hapus active dari tab detail default
                        document.querySelectorAll('#myTab .nav-link').forEach(function(el) {
                            el.classList.remove('active');
                        });
                        document.querySelectorAll('.tab-content .tab-pane').forEach(function(el) {
                            el.classList.remove('show', 'active');
                        });
                        // Set active ke tab tujuan
                        triggerEl.classList.add('active');
                        targetPane.classList.add('show', 'active');
                    }
                }
            })();
        </script>
    </div><!-- end .col-12 -->
</div><!-- end .row -->

<!-- Floating Action Chat Widget -->
<?= view('App\Modules\Chat\Views\components\_project_chat_widget', [
    'projectId' => $construction['id'],
    'projectType' => 'construction'
]) ?>

<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Construction\Views\components\_detail_scripts') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_absensi_scripts') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_addendum_scripts') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_material_scripts') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_pembayaran_scripts') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_rab_scripts') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_survey_scripts') ?>
<?= $this->include('App\Modules\Construction\Views\components\_dtl_target_scripts') ?>
<?= $this->endSection() ?>