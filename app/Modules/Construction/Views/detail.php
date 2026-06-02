<?= $this->section('style') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_absensi_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_addendum_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_desain_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_loker_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_material_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_pelamar_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_pembayaran_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_progress_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_rab_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_survey_styles') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_target_styles') ?>
<?= $this->endSection() ?>

<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Detail Konstruksi <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Detail Proyek Konstruksi <?= $this->endSection() ?>

<?= $this->section('content'); ?>

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
        border-left: 5px solid #6777ef;
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
        background: #6777EF;
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
        background: #6777EF;
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
        background: #e7f0ff;
        color: #0d6efd;
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
        background: #e7f0ff;
        color: #0d6efd;
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
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .action-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        height: 100%;
    }

    .action-card .card-header {
        background: #6777EF !important;
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
        color: #6777ef;
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
        color: #394eea;
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
        color: #6777ef !important;
        background: rgba(103, 119, 239, 0.03) !important;
    }

    .nav-tabs-premium .nav-link.active {
        color: #6777ef !important;
        border-bottom: 3px solid #6777ef !important;
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
        border-bottom-color: #6777ef !important;
        color: #6777ef !important;
    }

    .nav-tabs-premium .nav-link.active.text-danger {
        border-bottom-color: #fc544b !important;
        color: #fc544b !important;
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
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <a href="<?= base_url('admin/construction') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header p-0 bg-white" style="border-radius: 10px 10px 0 0;">
                <div class="nav-tabs-container">
                    <button class="nav-scroll-btn left" onclick="scrollNav('left')"><i
                            class="fas fa-chevron-left"></i></button>
                    <div class="nav-tabs-wrapper">

                        <ul class="nav nav-tabs nav-tabs-premium" id="myTab" role="tablist">

                            <?php if (can('construction_detail')): ?>
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#detail"><i
                                            class="fas fa-info-circle"></i> Detail</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_pelamar')): ?>
                                <li class="nav-item"><a class="nav-link text-success" data-bs-toggle="tab"
                                        href="#pelamar"><i class="fas fa-users-cog"></i> Pelamar</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_target')): ?>
                                <li class="nav-item"><a class="nav-link text-warning" data-bs-toggle="tab" href="#target"><i
                                            class="fas fa-bullseye"></i> Target</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_survey')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#survey"><i
                                            class="fas fa-map-marker-alt"></i> Survey</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_desain')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#desain"><i
                                            class="fas fa-bezier-curve"></i> Desain</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_rab')): ?>
                                <li class="nav-item"><a class="nav-link text-primary" data-bs-toggle="tab" href="#rab"><i
                                            class="fas fa-calculator"></i> Kelola RAB</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_addendum')): ?>
                                <li class="nav-item"><a class="nav-link text-primary" data-bs-toggle="tab"
                                        href="#addendum"><i class="fas fa-file-signature"></i> Addendum</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_pembayaran')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#payment"><i
                                            class="fas fa-credit-card"></i> Pembayaran</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_progress')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#progress"><i
                                            class="fas fa-chart-line"></i> Progress</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_lowongan')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#info-pekerjaan"><i
                                            class="fas fa-tools"></i> Lowongan</a></li>
                            <?php endif; ?>

                            <?php if (can('construction_absensi')): ?>
                                <li class="nav-item"><a class="nav-link text-primary" data-bs-toggle="tab"
                                        href="#absensi"><i class="fas fa-user-check"></i> Absensi</a></li>
                            <?php endif; ?>

                            <?php if (can('construction')): ?>
                                <li class="nav-item"><a class="nav-link text-primary" data-bs-toggle="tab"
                                        href="#material"><i class="fas fa-box-open"></i> Pengajuan Material</a></li>
                            <?php endif; ?>

                        </ul>
                    </div>
                    <button class="nav-scroll-btn right" onclick="scrollNav('right')"><i
                            class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="tab-content pt-0 pe-1 ps-1" id="myTabContent">

                    <!-- ------------ -->
                    <!-- tab 1 detail -->
                    <div class="tab-pane fade show active" id="detail" role="tabpanel">
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
                            'conStatus'      => $conStatus,
                            'conStatusMeta'  => $conStatusMeta,
                            'currentConMeta' => $currentConMeta,
                        ]);
                        ?>

                        <div class="row g-4 align-items-start mt-1">
                            <!-- Left: Profile & Project Details -->
                            <div class="col-12 col-md-7 mb-4">
                                <?= $this->include('App\Modules\Construction\Views\components\_profile_info') ?>
                            </div>

                            <!-- Right: Workflow Status Controller -->
                            <div class="col-12 col-md-5 mb-4">
                                <?= $this->include('App\Modules\Construction\Views\components\_status_manager') ?>
                            </div>
                        </div>
                    </div>
                    <!-- end tab 1 detail -->

                    <!-- ------------ -->
                    <!-- tab 2 pelamar -->
                    <!-- ------------ -->
                    <?php if (can('construction_pelamar')): ?>
                        <div class="tab-pane fade" id="pelamar" role="tabpanel">
                            <?= $this->include('App\Modules\Construction\Views\pelamar') ?>
                        </div>
                    <?php endif; ?>

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

                    <!-- -------------- -->
                    <!-- tab 9 progress -->
                    <!-- -------------- -->
                    <?php if (can('construction_progress')): ?>
                        <div class="tab-pane fade" id="progress" role="tabpanel">
                            <?= $this->include('App\Modules\Construction\Views\progress') ?>
                        </div>
                    <?php endif; ?>

                    <!-- -------------------- -->
                    <!-- tab 10 info pekerjaan -->
                    <!-- -------------------- -->
                    <?php if (can('construction_progress')): ?>
                        <div class="tab-pane fade" id="info-pekerjaan" role="tabpanel">
                            <?= $this->include('App\Modules\Construction\Views\loker') ?>
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
            </div><!-- end .card-body -->
        </div><!-- end .card -->
    </div><!-- end .col-12 -->
</div><!-- end .row -->

<?= $this->endSection(); ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_detail_scripts') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_absensi_scripts') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_addendum_scripts') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_material_scripts') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_pembayaran_scripts') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_progress_scripts') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_rab_scripts') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_survey_scripts') ?>
    <?= $this->include('App\Modules\Construction\Views\components\_dtl_target_scripts') ?>
<?= $this->endSection() ?>