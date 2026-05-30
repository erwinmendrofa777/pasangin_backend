<?= $this->section('style') ?>
<?= $this->endSection() ?>

<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Detail Renovasi <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Detail Proyek Renovasi <?= $this->endSection() ?>

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
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <a href="<?= base_url('admin/renovation') ?>" class="btn btn-secondary btn-sm">
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

                            <?php if (can('renovation_detail')): ?>
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#detail"><i
                                            class="fas fa-info-circle"></i> Detail</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_target')): ?>
                                <li class="nav-item"><a class="nav-link text-warning" data-bs-toggle="tab" href="#target"><i
                                            class="fas fa-bullseye"></i> Target</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_survey')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#survey"><i
                                            class="fas fa-map-marker-alt"></i> Survey</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_desain')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#desain"><i
                                            class="fas fa-bezier-curve"></i> Desain</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_rab')): ?>
                                <li class="nav-item"><a class="nav-link text-primary" data-bs-toggle="tab" href="#rab"><i
                                            class="fas fa-calculator"></i> Kelola RAB</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_pembayaran')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#payment"><i
                                            class="fas fa-credit-card"></i> Pembayaran</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_progress')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#progress"><i
                                            class="fas fa-chart-line"></i> Progress</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_lowongan')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#info-pekerjaan"><i
                                            class="fas fa-tools"></i> Lowongan</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation_absensi')): ?>
                                <li class="nav-item"><a class="nav-link text-primary" data-bs-toggle="tab"
                                        href="#absensi"><i class="fas fa-user-check"></i> Absensi</a></li>
                            <?php endif; ?>

                            <?php if (can('renovation')): ?>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#material"><i
                                            class="fas fa-boxes"></i> Pengajuan Material</a></li>
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
                        // Status Configuration for Renovation
                        $renStatus = $renovation['status'] ?? 'PENDING';
                        $renStatusMeta = [
                            'PENDING' => ['color' => 'warning', 'icon' => 'fas fa-clock', 'label' => 'Pending', 'desc' => 'Menunggu tindak lanjut'],
                            'SURVEY' => ['color' => 'info', 'icon' => 'fas fa-map-marked-alt', 'label' => 'Survey', 'desc' => 'Sedang tahap survey'],
                            'DESIGNING' => ['color' => 'primary', 'icon' => 'fas fa-drafting-compass', 'label' => 'Desain', 'desc' => 'Proses pembuatan desain'],
                            'RAB' => ['color' => 'secondary', 'icon' => 'fas fa-file-invoice-dollar', 'label' => 'RAB', 'desc' => 'Penyusunan RAB'],
                            'RENOVATION' => ['color' => 'primary', 'icon' => 'fas fa-hard-hat', 'label' => 'Renovasi', 'desc' => 'Pembangunan berjalan'],
                            'COMPLETED' => ['color' => 'success', 'icon' => 'fas fa-check-circle', 'label' => 'Selesai', 'desc' => 'Proyek telah selesai'],
                            'CANCELLED' => ['color' => 'danger', 'icon' => 'fas fa-times-circle', 'label' => 'Dibatalkan', 'desc' => 'Proyek dibatalkan'],
                        ];
                        $currentRenMeta = $renStatusMeta[$renStatus] ?? ['color' => 'dark', 'icon' => 'fas fa-circle', 'label' => $renStatus, 'desc' => 'Status tidak diketahui'];

                        // Initials
                        $nameParts = explode(' ', trim($renovation['full_name'] ?? 'K'));
                        $initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
                        ?>

                        <div class="row g-4 align-items-start mt-1">

                            <!-- ======================== LEFT: PROFILE INFO ======================== -->
                            <div class="col-12 col-md-7 mb-4">
                                <div class="card profile-card">
                                    <!-- Hero Banner -->
                                    <div class="profile-hero pb-4">
                                        <div class="d-flex flex-column flex-md-row justify-content-end align-items-md-end gap-3"
                                            style="z-index:1;">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="role-chip-hero">
                                                    <i class="fas fa-hard-hat me-1"></i>Proyek
                                                </span>
                                                <span
                                                    class="status-pill status-<?= strtolower($currentRenMeta['color']) ?>">
                                                    <span class="dot"></span><?= $currentRenMeta['label'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Profile Body-->
                                    <div class="profile-body">

                                        <!-- Info List: Kontak -->
                                        <div class="info-list mb-4">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <p class="section-title text-primary"><i
                                                        class="fas fa-address-book me-1"></i>Kontak Klien</p>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon"><i class="fas fa-user"></i></div>
                                                <div class="flex-grow-1">
                                                    <div class="info-label">Nama</div>
                                                    <div class="info-value">
                                                        <?= esc($renovation['full_name'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon"><i class="fas fa-key"></i></div>
                                                <div class="flex-grow-1">
                                                    <div class="info-label">Id User</div>
                                                    <div class="info-value">
                                                        <?= esc($renovation['user_id'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                                                <div class="flex-grow-1">
                                                    <div class="info-label">Tanggal Pengajuan</div>
                                                    <div class="info-value">
                                                        <?= isset($renovation['created_at']) ? date('d M Y', strtotime($renovation['created_at'])) : '-' ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                                                <div class="flex-grow-1">
                                                    <div class="info-label">Email</div>
                                                    <div class="info-value">
                                                        <?= esc($renovation['email'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-icon text-success" style="background:#d1e7dd;"><i
                                                        class="fab fa-whatsapp"></i></div>
                                                <div
                                                    class="flex-grow-1 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                                    <div>
                                                        <div class="info-label">Telepon / WhatsApp</div>
                                                        <div class="info-value">
                                                            <?= esc($renovation['phone'] ?? '-') ?>
                                                        </div>
                                                    </div>
                                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $renovation['phone']) ?>"
                                                        target="_blank" class="btn btn-sm btn-success px-3 shadow-sm"
                                                        style="border-radius: 8px;"><i class="fab fa-whatsapp"></i>
                                                        Chat</a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info List: Detail Proyek -->
                                        <p class="section-title text-primary"><i
                                                class="fas fa-clipboard-list me-1"></i>Detail Proyek & Keuangan</p>
                                        <div class="info-list mb-4">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="info-item" style="border-bottom:none;">
                                                        <div class="info-icon text-warning" style="background:#fff3cd;">
                                                            <i class="fas fa-vector-square"></i>
                                                        </div>
                                                        <div>
                                                            <div class="info-label">Luas Tanah</div>
                                                            <div class="info-value">
                                                                <?= !empty($renovation['land_area']) ? $renovation['land_area'] . ' m²' : '-' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="info-item" style="border-bottom:none;">
                                                        <div class="info-icon text-warning" style="background:#fff3cd;">
                                                            <i class="fas fa-home"></i>
                                                        </div>
                                                        <div>
                                                            <div class="info-label">Luas Bangunan</div>
                                                            <div class="info-value">
                                                                <?= !empty($renovation['building_area']) ? $renovation['building_area'] . ' m²' : '-' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="info-item" style="border-bottom:none;">
                                                        <div class="info-icon text-success" style="background:#d1e7dd;">
                                                            <i class="fas fa-calendar-check"></i>
                                                        </div>
                                                        <div>
                                                            <div class="info-label">Rencana Mulai</div>
                                                            <div class="info-value">
                                                                <?= !empty($renovation['start_date']) ? date('d M Y', strtotime($renovation['start_date'])) : '-' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="info-item" style="border-bottom:none;">
                                                        <div class="info-icon text-success" style="background:#d1e7dd;">
                                                            <i class="fas fa-stopwatch"></i>
                                                        </div>
                                                        <div>
                                                            <div class="info-label">Estimasi Waktu</div>
                                                            <div class="info-value">
                                                                <?= !empty($renovation['week']) ? $renovation['week'] . ' Minggu' : '-' ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <div class="p-3 rounded"
                                                        style="background: #f8f9fa; border: 1px dashed #ced4da;">
                                                        <div
                                                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 gap-1">
                                                            <span class="text-muted font-weight-bold text-uppercase"
                                                                style="font-size: 0.75rem;">Total Pembayaran
                                                                (Estimasi)</span>
                                                            <span class="font-weight-bold text-primary"
                                                                style="font-size: 1.1rem;">Rp
                                                                <?= number_format($renovation['total_payment'] ?? 0, 0, ',', '.') ?></span>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-muted font-weight-bold text-uppercase"
                                                                style="font-size: 0.75rem;">Kode Voucher</span>
                                                            <span>
                                                                <?php if (!empty($renovation['voucher_code'])): ?>
                                                                    <span class="badge badge-warning px-2 py-1"><i
                                                                            class="fas fa-ticket-alt mr-1"></i>
                                                                        <?= $renovation['voucher_code'] ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info List: Lokasi -->
                                        <p class="section-title text-primary"><i
                                                class="fas fa-map-marked-alt me-1"></i>Lokasi Geografis & Foto</p>
                                        <div class="info-list mb-3">
                                            <div class="info-item">
                                                <div class="info-icon text-danger" style="background:#f8d7da;"><i
                                                        class="fas fa-map-marker-alt"></i></div>
                                                <div>
                                                    <div class="info-label">Alamat Lengkap</div>
                                                    <div class="info-value">
                                                        <?= esc($renovation['address'] ?? '-') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($renovation['latitude']) && !empty($renovation['longitude'])): ?>
                                            <div class="map-container shadow-sm p-1 bg-white mb-3"
                                                style="border-radius: 14px; border: 1px solid #e9ecef;">
                                                <iframe
                                                    src="https://maps.google.com/maps?q=<?= esc($renovation['latitude']) ?>,<?= esc($renovation['longitude']) ?>&hl=id&z=15&output=embed"
                                                    width="100%" height="220" style="border:0; border-radius:10px;"
                                                    allowfullscreen="" loading="lazy"
                                                    referrerpolicy="no-referrer-when-downgrade">
                                                </iframe>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center p-4 bg-light mb-3"
                                                style="border-radius: 12px; border: 1px dashed #ced4da;">
                                                <i class="fas fa-map-marked-alt text-muted mb-2"
                                                    style="font-size:2rem; opacity:0.5;"></i>
                                                <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:500;">
                                                    Koordinat peta belum disetel.</p>
                                            </div>
                                        <?php endif; ?>

                                        <div class="gallery gallery-md mt-3 d-flex flex-wrap gap-2">
                                            <?php
                                            $hasPhotos = false;
                                            for ($i = 1; $i <= 5; $i++) {
                                                if (!empty($renovation['gambar' . $i])) {
                                                    $hasPhotos = true;
                                                    $fileUrl = base_url('uploads/renovation/' . $renovation['gambar' . $i]);
                                                    ?>
                                                    <a href="<?= $fileUrl ?>" class="glightbox shadow-sm rounded"
                                                       data-gallery="renovation-gallery"
                                                       data-title="Foto Lokasi <?= $i ?>"
                                                       style="width: 75px; height: 75px; display: inline-block; overflow: hidden; border: 1px solid #e4e9f0;">
                                                        <img src="<?= $fileUrl ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto Lokasi <?= $i ?>">
                                                    </a>
                                                    <?php
                                                }
                                            }
                                            if (!$hasPhotos): ?>
                                                <div class="text-center text-muted small w-100 py-3 bg-light rounded"
                                                    style="border: 1px dashed #ced4da;">Belum ada foto lokasi yang
                                                    diunggah.
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- ======================== RIGHT: UPDATE STATUS ======================== -->
                            <div class="col-12 col-md-5 mb-4">
                                <div class="card action-card">
                                    <!-- Card Header -->
                                    <div class="card-header">
                                        <h6 class="text-white mb-0 fw-bold">
                                            <i class="fas fa-sliders-h mr-2"></i>Kelola Status Proyek
                                        </h6>
                                    </div>

                                    <div class="card-body p-2 pt-2">
                                        <form id="updateStatusFormDirect"
                                            action="<?= base_url('admin/renovation/update_status') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $renovation['id'] ?>">
                                            <input type="hidden" name="status" id="selectedStatusInput"
                                                value="<?= $renStatus ?>">

                                            <div class="d-flex flex-column" style="gap: 10px;">
                                                <?php foreach ($renStatusMeta as $key => $act):
                                                    $isActive = ($renStatus === $key);
                                                    ?>
                                                    <button type="button"
                                                        class="btn <?= $isActive ? 'btn-' . $act['color'] . ' btn-current-status' : 'btn-outline-' . $act['color'] ?> status-action-btn text-left w-100"
                                                        data-status="<?= $key ?>" data-color="<?= $act['color'] ?>"
                                                        data-is-active="<?= $isActive ? 'true' : 'false' ?>">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between w-100">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="<?= $act['icon'] ?>"
                                                                    style="width:20px; text-align:center;"></i>
                                                                <div class="ml-2">
                                                                    <div
                                                                        style="font-size:0.88rem; font-weight:700; line-height:1.2; text-align: left;">
                                                                        <?= $act['label'] ?>
                                                                    </div>
                                                                    <div
                                                                        style="font-size:0.72rem; font-weight:400; opacity:0.75; text-align: left;">
                                                                        <?= $act['desc'] ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php if ($isActive): ?>
                                                                <i class="fas fa-check-circle status-icon ml-2"
                                                                    style="font-size:1rem;"></i>
                                                            <?php else: ?>
                                                                <i class="fas fa-chevron-right status-icon ml-2"
                                                                    style="font-size:0.75rem; opacity:0.6;"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                    </button>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="mt-4 pt-3 border-top text-center">
                                                <button type="submit"
                                                    class="btn btn-primary btn-block btn-lg ladda-button shadow-sm"
                                                    data-style="zoom-in" style="border-radius: 8px; font-weight: bold;">
                                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>

                                        <div class="mt-3 pt-3 border-top">
                                            <p class="text-muted mb-0" style="font-size:0.78rem;">
                                                <i class="fas fa-info-circle text-primary mr-1"></i>
                                                Pilih status baru lalu klik tombol Simpan. Tombol berwarna solid
                                                adalah
                                                status saat ini.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end tab 1 detail -->

                    <!-- --------- -->
                    <!-- tab 3 target -->
                    <!-- --------- -->
                    <?php if (can('renovation_target')): ?>
                        <div class="tab-pane fade" id="target" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\target') ?>
                        </div>
                    <?php endif; ?>

                    <!-- ------------ -->
                    <!-- tab 4 survey -->
                    <!-- ------------ -->
                    <?php if (can('renovation_survey')): ?>
                        <div class="tab-pane fade" id="survey" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\survey') ?>
                        </div>
                    <?php endif; ?>

                    <!-- ------------ -->
                    <!-- tab 5 desain -->
                    <!-- ------------ -->
                    <?php if (can('renovation_desain')): ?>
                        <div class="tab-pane fade" id="desain" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\desain') ?>
                        </div>
                    <?php endif; ?>

                    <!-- --------- -->
                    <!-- tab 6 rab -->
                    <!-- --------- -->
                    <?php if (can('renovation_rab')): ?>
                        <div class="tab-pane fade" id="rab" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\rab') ?>
                        </div>
                    <?php endif; ?>


                    <!-- ---------------- -->
                    <!-- tab 8 pembayaran -->
                    <!-- ---------------- -->
                    <?php if (can('renovation_pembayaran')): ?>
                        <div class="tab-pane fade" id="payment" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\pembayaran') ?>
                        </div>
                    <?php endif; ?>

                    <!-- -------------- -->
                    <!-- tab 9 progress -->
                    <!-- -------------- -->
                    <?php if (can('renovation_progress')): ?>
                        <div class="tab-pane fade" id="progress" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\progress') ?>
                        </div>
                    <?php endif; ?>

                    <!-- -------------------- -->
                    <!-- tab 10 info pekerjaan -->
                    <!-- -------------------- -->
                    <?php if (can('renovation_progress')): ?>
                        <div class="tab-pane fade" id="info-pekerjaan" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\loker') ?>
                        </div>
                    <?php endif; ?>

                    <!-- -------------------- -->
                    <!-- tab 11 absensi -->
                    <!-- -------------------- -->
                    <?php if (can('renovation_absensi')): ?>
                        <div class="tab-pane fade" id="absensi" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\absensi') ?>
                        </div>
                    <?php endif; ?>

                    <!-- ---------------------- -->
                    <!-- tab 12 material        -->
                    <!-- ---------------------- -->
                    <?php if (can('renovation')): ?>
                        <div class="tab-pane fade" id="material" role="tabpanel">
                            <?= $this->include('App\Modules\Renovation\Views\material_submissions') ?>
                        </div>
                    <?php endif; ?>

                </div><!-- end .tab-content -->
            </div><!-- end .card-body -->
        </div><!-- end .card -->
    </div><!-- end .col-12 -->
</div><!-- end .row -->

<?= $this->endSection(); ?>



<?= $this->section('script') ?>
<!-- JS Libraries -->
<script>
    // Sliding Nav Logic - Globally Accessible
    function scrollNav(direction) {
        const container = document.querySelector('.nav-tabs-premium');
        const scrollAmount = 400;
        const currentScroll = container.scrollLeft;

        if (direction === 'left') {
            container.scrollTo({
                left: currentScroll - scrollAmount,
                behavior: 'smooth'
            });
        } else {
            container.scrollTo({
                left: currentScroll + scrollAmount,
                behavior: 'smooth'
            });
        }
    }


    // chocolate js
    $(document).ready(function () {
        // Flash Messages
        <?php if (session()->getFlashdata('success')): ?>
            iziToast.success({
                timeout: 5000,
                title: 'Berhasil',
                message: '<?= session()->getFlashdata('success') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            iziToast.error({
                timeout: 5000,
                title: 'Gagal',
                message: '<?= session()->getFlashdata('error') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>

        // Ladda Integration
        $(document).on('submit', 'form', function () {
            var btn = $(this).find('.ladda-button');
            if (btn.length > 0) {
                var l = Ladda.create(btn[0]);
                l.start();
            }
        });

        // Status Selection Logic - Klik tombol untuk memilih status baru
        $(document).on('click', '.status-action-btn', function () {
            var $btn = $(this);
            var newStatus = $btn.data('status');

            // Jika tombol ini sudah merupakan status aktif yang belum diubah, skip
            // (tapi tetap boleh diklik untuk re-confirm pilihan)

            // Update hidden input dengan status baru
            $('#selectedStatusInput').val(newStatus);

            // Reset semua tombol ke outline
            $('.status-action-btn').each(function () {
                var color = $(this).data('color');
                $(this).removeClass('btn-' + color + ' btn-current-status').addClass('btn-outline-' + color);
                $(this).find('.status-icon').removeClass('fa-check-circle').addClass('fa-chevron-right').css('font-size', '0.75rem').css('opacity', '0.6');
            });

            // Set tombol yang diklik menjadi solid (aktif)
            var color = $btn.data('color');
            $btn.removeClass('btn-outline-' + color).addClass('btn-' + color + ' btn-current-status');
            $btn.find('.status-icon').removeClass('fa-chevron-right').addClass('fa-check-circle').css('font-size', '1rem').css('opacity', '1');
        });

        // Restore Tab Logic
        var hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });

        // Call RAB calculation functions if they exist (defined in sub-views)
        try {
            calculateGrandTotalRab();
        } catch (e) { }
        try {
            calculateGrandTotalAddendum();
        } catch (e) { }

        // Reload GLightbox when switching tabs to ensure perfect event listener attachment
        document.querySelectorAll('a[data-toggle="tab"]').forEach(tabLink => {
            tabLink.addEventListener('shown.bs.tab', () => {
                if (typeof GLightbox !== 'undefined') {
                    GLightbox({ selector: '.glightbox' });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>