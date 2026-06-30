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

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script>
    // Deteksi hash sebelum rendering halaman dimulai untuk mencegah Wrong Tab Flash
    (function() {
        var hash = window.location.hash.replace(/^#/, '');
        var validTabs = ['survey', 'target', 'design', 'rab', 'payment'];
        if (hash && validTabs.indexOf(hash) !== -1) {
            document.documentElement.className += ' has-tab-hash';
            document.documentElement.setAttribute('data-active-tab', hash);
        }
    })();
</script>

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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

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
        display: none;
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

    /* ===== WRONG TAB FLASH PREVENTION ===== */
    html.has-tab-hash .tab-content #detail {
        display: none !important;
    }
    html.has-tab-hash .tab-content #survey.tab-pane,
    html.has-tab-hash .tab-content #target.tab-pane,
    html.has-tab-hash .tab-content #design.tab-pane,
    html.has-tab-hash .tab-content #rab.tab-pane,
    html.has-tab-hash .tab-content #payment.tab-pane {
        display: none;
    }
    html[data-active-tab="survey"] .tab-content #survey.tab-pane,
    html[data-active-tab="target"] .tab-content #target.tab-pane,
    html[data-active-tab="design"] .tab-content #design.tab-pane,
    html[data-active-tab="rab"] .tab-content #rab.tab-pane,
    html[data-active-tab="payment"] .tab-content #payment.tab-pane {
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
    html[data-active-tab="target"] #myTab #target-tab,
    html[data-active-tab="design"] #myTab #design-tab,
    html[data-active-tab="rab"] #myTab #rab-tab,
    html[data-active-tab="payment"] #myTab #payment-tab {
        color: var(--palette-primary) !important;
        font-weight: 700 !important;
    }
    html[data-active-tab="survey"] #myTab #survey-tab::after,
    html[data-active-tab="target"] #myTab #target-tab::after,
    html[data-active-tab="design"] #myTab #design-tab::after,
    html[data-active-tab="rab"] #myTab #rab-tab::after,
    html[data-active-tab="payment"] #myTab #payment-tab::after {
        transform: translateX(-50%) scaleX(1) !important;
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
    </div>
</div>

<!-- Floating Action Chat Widget -->
<?= view('App\Modules\Chat\Views\components\_project_chat_widget', [
    'projectId' => $request['id'],
    'projectType' => 'design'
]) ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_scripts') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_survey_scripts') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_desain_scripts') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_target_scripts') ?>
<?= $this->include('App\Modules\Design\Views\components\_dtl_rab_scripts') ?>
<?= $this->endSection() ?>