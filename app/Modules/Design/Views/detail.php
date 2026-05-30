<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Permintaan Desain
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Permintaan Desain
<?= $this->endSection() ?>

<?= $this->section('style') ?>
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
        color: #0d6efd;
    }

    /* ===== PROFILE CARD ===== */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .profile-hero {
        background: #0d6efd;
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

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #0d6efd;
        margin-bottom: 10px;
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
        color: #0d6efd;
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
        color: #0d6efd !important;
        background: rgba(13, 110, 253, 0.03) !important;
    }

    .nav-tabs-premium .nav-link.active {
        color: #0d6efd !important;
        border-bottom: 3px solid #0d6efd !important;
        background: transparent !important;
        position: relative;
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
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-3 back-btn-wrapper">
    <a href="<?= base_url('admin/design') ?>" class="btn btn-light btn-sm px-3 shadow-sm border"
        style="border-radius: 8px; font-weight: 600; color: #495057;">
        <i class="fas fa-arrow-left me-1 text-primary"></i> Kembali
    </a>
</div>

<div class="row">

    <div class="card">
        <div class="card-header p-0 bg-white" style="border-radius: 10px 10px 0 0;">
            <div class="nav-tabs-container">
                <button class="nav-scroll-btn left" onclick="scrollNav('left')"><i
                        class="fas fa-chevron-left"></i></button>
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs nav-tabs-premium" id="myTab" role="tablist">

                        <?php if (can('design_detail')): ?>
                            <li class="nav-item">
                                <a class="nav-link active" id="detail-tab" data-bs-toggle="tab" href="#detail" role="tab"><i
                                        class="fas fa-user"></i> Detail</a>
                            </li>
                        <?php endif; ?>

                        <?php if (can('design_survey')): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="survey-tab" data-bs-toggle="tab" href="#survey" role="tab"><i
                                        class="fas fa-clipboard-check"></i> Survey</a>
                            </li>
                        <?php endif; ?>

                        <?php if (can('design_desain')): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="design-tab" data-bs-toggle="tab" href="#design" role="tab"><i
                                        class="fas fa-drafting-compass"></i> Desain</a>
                            </li>
                        <?php endif; ?>

                        <?php if (can('design_target')): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="target-tab" data-bs-toggle="tab" href="#target" role="tab"><i
                                        class="fas fa-tasks"></i> Target</a>
                            </li>
                        <?php endif; ?>

                        <?php if (can('design_progress')): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="progress-tab" data-bs-toggle="tab" href="#progress" role="tab"><i
                                        class="fas fa-tasks"></i> Progress</a>
                            </li>
                        <?php endif; ?>

                        <?php if (can('design_pembayaran')): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment" role="tab"><i
                                        class="fas fa-wallet"></i> Pembayaran</a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
                <button class="nav-scroll-btn right" onclick="scrollNav('right')"><i
                        class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="tab-content" id="myTabContent">

                <!-- 1. TAB DETAIL -->
                <div class="tab-pane fade show active" id="detail" role="tabpanel">
                    <?php
                    $db = \Config\Database::connect();
                    $user = $db->table('users')->where('id', $request['user_id'])->get()->getRowArray();

                    $status = $request['status'] ?? 'PENDING';
                    $statusMeta = [
                        'PENDING' => ['class' => 'status-pending', 'icon' => 'fas fa-clock', 'label' => 'PENDING'],
                        'SURVEY_SCHEDULED' => ['class' => 'status-survey', 'icon' => 'fas fa-calendar-check', 'label' => 'SURVEY SCHEDULED'],
                        'PAYMENT_VERIFIED' => ['class' => 'status-payment', 'icon' => 'fas fa-file-invoice-dollar', 'label' => 'PAYMENT VERIFIED'],
                        'COMPLETED' => ['class' => 'status-completed', 'icon' => 'fas fa-check-circle', 'label' => 'COMPLETED'],
                        'CANCELLED' => ['class' => 'status-cancelled', 'icon' => 'fas fa-times-circle', 'label' => 'CANCELLED'],
                    ];
                    $currentMeta = $statusMeta[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => $status];
                    ?>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card shadow-sm profile-card mb-4">
                                <!-- Hero Banner -->
                                <div class="profile-hero bg-primary">
                                    <div class="d-flex justify-content-between align-items-center" style="z-index:1;">
                                        <h5 class="text-white mb-0 ms-1 fw-bold" style="font-size:1.2rem;">
                                            <i class="fas fa-drafting-compass me-2 opacity-75"></i>Permintaan Desain
                                            #<?= esc($request['id']) ?>
                                        </h5>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="status-pill <?= $currentMeta['class'] ?>">
                                                <span class="dot"></span><?= $currentMeta['label'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Profile Body -->
                                <div class="profile-body pt-1">
                                    <div class="row">
                                        <!-- Left Column -->
                                        <div class="col-md-6 pe-md-4 border-end">
                                            <p class="section-title mt-2"><i
                                                    class="fas fa-user-circle me-1"></i>Informasi Klien</p>
                                            <div class="info-list mb-4">
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-id-card"></i></div>
                                                    <div>
                                                        <div class="info-label">User ID (Akun)</div>
                                                        <div class="info-value"><?= esc($request['user_id'] ?? '-') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-user"></i></div>
                                                    <div>
                                                        <div class="info-label">Nama Klien</div>
                                                        <div class="info-value"><?= esc($request['full_name'] ?? '-') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                                                    <div>
                                                        <div class="info-label">Email</div>
                                                        <div class="info-value"><?= esc($user['email'] ?? '-') ?></div>
                                                    </div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-phone"></i></div>
                                                    <div>
                                                        <div class="info-label">Nomor Telepon</div>
                                                        <div class="info-value">
                                                            <?= esc($request['phone_number'] ?? '-') ?></div>
                                                    </div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                                                    <div>
                                                        <div class="info-label">Tanggal Pengajuan</div>
                                                        <div class="info-value">
                                                            <?= date('d M Y, H:i', strtotime($request['created_at'])) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="section-title mt-2"><i class="fas fa-paint-roller me-1"></i>Detail
                                                Spesifikasi Proyek</p>
                                            <div class="info-list mb-4">
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-palette"></i></div>
                                                    <div>
                                                        <div class="info-label">Konsep Desain</div>
                                                        <div class="info-value fw-bold text-primary">
                                                            <?= esc($request['design_concept'] ?? '-') ?></div>
                                                    </div>
                                                </div>
                                                <?php if (!empty($request['other_concept_desc'])): ?>
                                                    <div class="info-item border-0 pb-0">
                                                        <div class="info-icon"><i class="fas fa-comment-alt"></i></div>
                                                        <div>
                                                            <div class="info-label">Deskripsi Tambahan Konsep</div>
                                                            <div class="info-value" style="font-size:0.85rem;">
                                                                <?= esc($request['other_concept_desc']) ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-vector-square"></i></div>
                                                    <div>
                                                        <div class="info-label">Luas Lahan & Bangunan</div>
                                                        <div class="info-value">Tanah:
                                                            <?= esc($request['land_area'] ?? '0') ?> m² &nbsp;|&nbsp;
                                                            Bangunan: <?= esc($request['building_area'] ?? '0') ?> m²
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!empty($request['voucher_code'])): ?>
                                                    <div class="info-item">
                                                        <div class="info-icon text-success" style="background:#e6f9ed;"><i
                                                                class="fas fa-ticket-alt"></i></div>
                                                        <div>
                                                            <div class="info-label text-success">Kode Voucher</div>
                                                            <div class="info-value text-success fw-bold">
                                                                <?= esc($request['voucher_code']) ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Right Column -->
                                        <div class="col-md-6 ps-md-4">
                                            <p class="section-title mt-0 mt-sm-2"><i
                                                    class="fas fa-map-marker-alt me-1"></i>Lokasi Proyek</p>
                                            <div class="info-list mb-3">
                                                <div class="info-item border-0 pb-0 ">
                                                    <div class="info-icon"><i class="fas fa-home"></i></div>
                                                    <div>
                                                        <div class="info-label">Alamat Lengkap</div>
                                                        <div class="info-value">
                                                            <?= esc($request['location_address'] ?? '-') ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- ===== GOOGLE MAPS EMBED ===== -->
                                            <div class="mt-4">
                                                <p class="section-title mb-2"><i
                                                        class="fas fa-map-marked-alt me-1"></i>Peta Lokasi Geografis</p>
                                                <?php if (!empty($request['latitude']) && !empty($request['longitude'])): ?>
                                                    <div class="map-container shadow-sm p-1 bg-white"
                                                        style="border-radius: 14px; border: 1px solid #e9ecef;">
                                                        <iframe
                                                            src="https://maps.google.com/maps?q=<?= esc($request['latitude']) ?>,<?= esc($request['longitude']) ?>&hl=id&z=15&output=embed"
                                                            width="100%" height="220" style="border:0; border-radius:10px;"
                                                            allowfullscreen="" loading="lazy"
                                                            referrerpolicy="no-referrer-when-downgrade">
                                                        </iframe>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center p-4 bg-light"
                                                        style="border-radius: 12px; border: 1px dashed #ced4da;">
                                                        <i class="fas fa-map-marked-alt text-muted mb-2"
                                                            style="font-size:2rem; opacity:0.5;"></i>
                                                        <p class="text-muted mb-0"
                                                            style="font-size:0.85rem; font-weight:500;">Koordinat peta belum
                                                            disetel.</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <p class="section-title mt-4"><i
                                                    class="fas fa-calendar-check me-1"></i>Jadwal Proyek</p>
                                            <div class="info-list mb-2">
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-play-circle"></i></div>
                                                    <div>
                                                        <div class="info-label">Tanggal Mulai</div>
                                                        <div class="info-value">
                                                            <?= !empty($request['start_date']) ? date('d M Y', strtotime($request['start_date'])) : '<span class="text-muted fst-italic" style="font-size:0.85rem;">Belum disetel</span>' ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="info-item">
                                                    <div class="info-icon"><i class="fas fa-flag-checkered"></i></div>
                                                    <div>
                                                        <div class="info-label">Target Selesai</div>
                                                        <div class="info-value">
                                                            <?= !empty($request['target_date']) ? date('d M Y', strtotime($request['target_date'])) : '<span class="text-muted fst-italic" style="font-size:0.85rem;">Belum disetel</span>' ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Update Status Action -->
                                            <?php if (can('design_detail')): ?>
                                                <div class="p-3 bg-light rounded border border-light">
                                                    <p class="section-title mt-0"><i class="fas fa-sync-alt me-1"></i>Update
                                                        Status proyek</p>
                                                    <form
                                                        action="<?= base_url('admin/design/update-status/' . $request['id']) ?>"
                                                        method="post" class="d-flex gap-2">
                                                        <?= csrf_field() ?>
                                                        <select name="status" class="form-select form-control fw-bold"
                                                            style="border-radius:8px;">
                                                            <option value="PENDING" <?= $request['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                                            <option value="SURVEY_SCHEDULED"
                                                                <?= $request['status'] == 'SURVEY_SCHEDULED' ? 'selected' : '' ?>>SURVEY SCHEDULED</option>
                                                            <option value="PAYMENT_VERIFIED"
                                                                <?= $request['status'] == 'PAYMENT_VERIFIED' ? 'selected' : '' ?>>PAYMENT VERIFIED</option>
                                                            <option value="COMPLETED" <?= $request['status'] == 'COMPLETED' ? 'selected' : '' ?>>COMPLETED</option>
                                                            <option value="CANCELLED" <?= $request['status'] == 'CANCELLED' ? 'selected' : '' ?>>CANCELLED</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-primary ladda-button"
                                                            data-style="zoom-in" style="border-radius:8px;">
                                                            <span class="ladda-label">Update</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. TAB SURVEY -->
                <?php if (can('design_survey')): ?>
                    <div class="tab-pane fade" id="survey" role="tabpanel">
                        <!-- included file -->
                        <?= $this->include('App\Modules\Design\Views\survey') ?>
                    </div>
                <?php endif; ?>

                <!-- 3. TAB DESAIN -->
                <?php if (can('design_desain')): ?>
                    <div class="tab-pane fade" id="design" role="tabpanel">
                        <!-- included file -->
                        <?= $this->include('App\Modules\Design\Views\desain') ?>
                    </div>
                <?php endif; ?>

                <!-- 4. TAB TARGET -->
                <?php if (can('design_target')): ?>
                    <div class="tab-pane fade" id="target" role="tabpanel">
                        <!-- included file -->
                        <?= $this->include('App\Modules\Design\Views\target') ?>
                    </div>
                <?php endif; ?>

                <!-- 5. TAB PROGRESS -->
                <?php if (can('design_progress')): ?>
                    <div class="tab-pane fade" id="progress" role="tabpanel">
                        <!-- included file -->
                        <?= $this->include('App\Modules\Design\Views\progress') ?>
                    </div>
                <?php endif; ?>


                <!-- 6. TAB PEMBAYARAN -->
                <?php if (can('design_pembayaran')): ?>
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <!-- included file -->
                        <?= $this->include('App\Modules\Design\Views\pembayaran') ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    function scrollNav(direction) {
        const container = document.querySelector('.nav-tabs-premium');
        const scrollAmount = 200;
        if (direction === 'left') {
            container.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        } else {
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }
    }

    // Script untuk otomatis membuka Tab berdasarkan URL Hash
    document.addEventListener("DOMContentLoaded", function () {
        var hash = location.hash.replace(/^#/, '');
        if (hash) {
            var triggerEl = document.querySelector('#myTab a[href="#' + hash + '"]');
            if (triggerEl) {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
                // Scroll nav jika tab tersebut agak tertutup
                triggerEl.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }

        // Update URL hash ketika tab di-klik agar jika di-refresh tetap di tab yang sama
        var tabLinks = document.querySelectorAll('#myTab a[data-bs-toggle="tab"]');
        tabLinks.forEach(function (link) {
            link.addEventListener('shown.bs.tab', function (e) {
                if(history.pushState) {
                    history.pushState(null, null, e.target.hash);
                } else {
                    window.location.hash = e.target.hash;
                }
            });
        });
    });

    // Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 20000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 20000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
    $(document).on('submit', 'form', function () {
        var btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            var l = Ladda.create(btn[0]);
            l.start();
        }
    });

    // Reload GLightbox when switching tabs to ensure perfect event listener attachment
    $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function () {
        if (window.globalLightbox) {
            window.globalLightbox.reload();
        }
    });
</script>
<?= $this->endSection() ?>