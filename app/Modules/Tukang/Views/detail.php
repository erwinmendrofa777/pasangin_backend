<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Mitra Tukang - <?= esc($tukang['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Mitra Tukang
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO BANNER ===== */
    .profile-hero {
        background: #0d6efd;
        border-radius: 16px 16px 0 0;
        padding: 18px 28px 68px;
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

    /* ===== AVATAR ===== */
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
        border-radius: 16px;
        border: 4px solid #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
        background: #e9ecef;
        transition: all 0.2s ease-in-out;
        cursor: zoom-in;
    }

    .avatar-img:hover {
        transform: scale(1.06);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.25);
    }

    /* ===== CARDS ===== */
    .profile-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .profile-body {
        padding: 0 24px 28px;
    }

    .action-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .action-card .card-header {
        background: #0d6efd !important;
        border-radius: 16px 16px 0 0;
        padding: 18px 22px;
        border: none;
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

    .status-berkas {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-ditolak {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-test {
        background: #e0f2fe;
        color: #075985;
    }

    .status-aktivasi {
        background: #e0e7ff;
        color: #3730a3;
    }

    .status-siap {
        background: #d1fae5;
        color: #065f46;
    }

    .verify-badge {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .bg-verified {
        background: #dcfce7;
        color: #15803d;
    }

    .bg-unverified {
        background: #f3f4f6;
        color: #4b5563;
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

    /* ===== PHOTO PREVIEW ===== */
    .doc-photo {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #dee2e6;
        cursor: zoom-in;
        transition: all 0.2s ease-in-out;
    }

    .doc-photo:hover {
        transform: scale(1.04);
        border-color: #0d6efd;
        box-shadow: 0 6px 18px rgba(13, 110, 253, 0.18);
    }

    /* ===== STATUS ACTION BUTTONS ===== */
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

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #0d6efd;
        margin-bottom: 10px;
        margin-top: 20px;
    }

    /* ===== RATINGS ===== */
    .rating-card {
        border-radius: 12px;
        border: 1px solid #f0f2f5;
        padding: 15px;
        margin-bottom: 15px;
        background: #fff;
    }

    /* ===== CUSTOM SCROLLBAR ===== */
    .ratings-scroll-container::-webkit-scrollbar {
        width: 6px;
    }

    .ratings-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .ratings-scroll-container::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .ratings-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #bbb;
    }

    @media (max-width: 767px) {
        .profile-hero {
            padding: 28px 18px 60px;
        }

        .profile-body {
            padding: 0 16px 22px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$status = $tukang['status'];
$statusMeta = [
    'Berkas Diproses' => ['class' => 'status-berkas',   'icon' => 'fas fa-file-medical'],
    'Ditolak'         => ['class' => 'status-ditolak',  'icon' => 'fas fa-times-circle'],
    'Proses Test'     => ['class' => 'status-test',     'icon' => 'fas fa-vial'],
    'Proses Aktivasi' => ['class' => 'status-aktivasi', 'icon' => 'fas fa-user-check'],
    'Siap Kerja'      => ['class' => 'status-siap',     'icon' => 'fas fa-check-double'],
];
$currentMeta = $statusMeta[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle'];
?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/tukang') ?>" class="btn btn-secondary btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4 align-items-start">

    <!-- LEFT COLUMN: INFO & DOCUMENTS -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm profile-card mb-4">

            <!-- Hero Banner -->
            <div class="profile-hero">
                <div class="d-flex justify-content-between align-items-center" style="z-index:1;">
                    <h5 class="text-white mb-0 ms-2 fw-bold">
                        <?= esc($tukang['name']) ?>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($tukang['is_verify'] == 1): ?>
                            <span class="verify-badge bg-verified"><i class="fas fa-check-circle me-1"></i> Terverifikasi</span>
                        <?php else: ?>
                            <span class="verify-badge bg-unverified"><i class="fas fa-clock me-1"></i> Belum Verif</span>
                        <?php endif; ?>
                        <span class="status-pill <?= $currentMeta['class'] ?>">
                            <span class="dot"></span><?= $status ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="profile-body">
                <div class="d-flex align-items-end justify-content-between mb-2">
                    <div class="avatar-wrapper">
                        <?php 
                        $avatarUrl = !empty($tukang['profile_photo']) ? base_url('uploads/tukang/' . $tukang['profile_photo']) : base_url('uploads/tukang/default.jpg');
                        ?>
                        <a href="<?= $avatarUrl ?>" class="glightbox" data-title="<?= esc($tukang['name']) ?>" data-description="Nama: <?= esc($tukang['name']) ?> &lt;br&gt; Spesialisasi: <?= esc($tukang['specialization'] ?: 'Umum') ?> &lt;br&gt; Email: <?= esc($tukang['email'] ?: '-') ?> &lt;br&gt; Telepon: <?= esc($tukang['phone'] ?: '-') ?> &lt;br&gt; Rating: <?= esc($tukang['rata_rata_rating'] ?: '0.0') ?> / 5.0">
                            <img src="<?= $avatarUrl ?>" alt="<?= esc($tukang['name']) ?>" class="avatar-img" data-toggle="tooltip" title="Klik untuk memperbesar">
                        </a>
                    </div>
                    <span class="text-muted small pb-2">
                        ID Mitra: <strong>#<?= $tukang['id'] ?></strong> | Terdaftar: <strong><?= date('d M Y', strtotime($tukang['created_at'])) ?></strong>
                    </span>
                </div>

                <hr class="my-3">

                <div class="row">
                    <!-- Personal Info -->
                    <div class="col-md-6">
                        <p class="section-title"><i class="fas fa-user me-1"></i>Informasi Pribadi</p>
                        <div class="info-list">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-id-card"></i></div>
                                <div>
                                    <div class="info-label">NIK</div>
                                    <div class="info-value"><?= esc($tukang['nik'] ?: '-') ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <div class="info-label">Email</div>
                                    <div class="info-value"><?= esc($tukang['email'] ?: '-') ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fab fa-whatsapp"></i></div>
                                <div>
                                    <div class="info-label">WhatsApp</div>
                                    <div class="info-value"><?= esc($tukang['phone'] ?: '-') ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-venus-mars"></i></div>
                                <div>
                                    <div class="info-label">Jenis Kelamin</div>
                                    <div class="info-value"><?= esc($tukang['gender'] ?: '-') ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-birthday-cake"></i></div>
                                <div>
                                    <div class="info-label">Tanggal Lahir</div>
                                    <div class="info-value"><?= esc($tukang['dob'] ?: '-') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Info & Stats -->
                    <div class="col-md-6">
                        <p class="section-title"><i class="fas fa-briefcase me-1"></i>Pekerjaan & Statistik</p>
                        <div class="info-list">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-tools"></i></div>
                                <div>
                                    <div class="info-label">Spesialisasi</div>
                                    <div class="info-value fw-bold text-primary"><?= esc($tukang['specialization'] ?: 'Umum') ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-star text-warning"></i></div>
                                <div>
                                    <div class="info-label">Rating Rata-rata</div>
                                    <div class="info-value fw-bold"><?= $tukang['rata_rata_rating'] ?> / 5.0 <span class="text-muted small fw-normal">(<?= $tukang['total_ulasan'] ?> ulasan)</span></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-wallet"></i></div>
                                <div>
                                    <div class="info-label">Saldo Wallet</div>
                                    <div class="info-value fw-bold text-success">Rp <?= number_format($tukang['balance'], 0, ',', '.') ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <div class="info-label">Alamat KTP</div>
                                    <div class="info-value small"><?= esc($tukang['ktp_address'] ?: '-') ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-home"></i></div>
                                <div>
                                    <div class="info-label">Alamat Domisili</div>
                                    <div class="info-value small"><?= esc($tukang['domicile_address'] ?: '-') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Documents Section -->
                <p class="section-title"><i class="fas fa-file-invoice me-1"></i>Dokumen Verifikasi</p>
                <div class="row g-3">
                    <div class="col-md-5 col-lg-4">
                        <div class="info-label mb-2 text-muted small fw-bold">FOTO SELFIE MITRA</div>
                        <?php if (!empty($tukang['selfie_photo'])): ?>
                            <?php $selfieUrl = base_url('uploads/tukang/selfie/' . $tukang['selfie_photo']); ?>
                            <a href="<?= $selfieUrl ?>" class="glightbox" data-title="Foto Selfie Mitra" data-description="Nama: <?= esc($tukang['name']) ?> &lt;br&gt; Spesialisasi: <?= esc($tukang['specialization'] ?: 'Umum') ?>">
                                <div class="position-relative">
                                    <img src="<?= $selfieUrl ?>"
                                        class="doc-photo shadow-sm"
                                        style="height: 140px; width: 100%; border-radius: 12px; object-fit: cover;">
                                    <div class="position-absolute bottom-0 end-0 m-2">
                                        <span class="badge bg-dark bg-opacity-50 rounded-circle p-2">
                                            <i class="fas fa-search-plus text-white small"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="bg-light d-flex flex-column align-items-center justify-content-center text-muted"
                                style="height:140px; border-radius:12px; border:1px dashed #ced4da;">
                                <i class="fas fa-user-circle fa-2x mb-2 opacity-25"></i>
                                <span style="font-size: 0.7rem;">Tidak ada foto</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- RATINGS & COMMENTS SECTION -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-star me-2 text-warning"></i>Ulasan & Rating Mitra
                </h6>
                <span class="badge bg-primary rounded-pill px-3"><?= count($ratings) ?> Ulasan</span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($ratings)): ?>
                    <div class="ratings-scroll-container" style="max-height: 480px; overflow-y: auto;">
                        <div class="list-group list-group-flush">
                            <?php foreach ($ratings as $r): ?>
                                <div class="list-group-item p-4 border-0" style="background: #fafafa; margin-bottom: 1px;">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">Customer</span>
                                                    <span class="text-muted" style="font-size: 0.75rem;">•</span>
                                                    <span class="text-muted" style="font-size: 0.75rem;"><?= date('d M Y', strtotime($r['created_at'])) ?></span>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <div class="badge bg-white border text-primary shadow-sm d-flex align-items-center gap-1 py-1 px-2" style="font-size: 0.65rem; border-radius: 6px;">
                                                        <span class="text-muted fw-normal">Skill:</span>
                                                        <span class="fw-bold"><?= $r['skill_score'] ?></span>
                                                        <i class="fas fa-star text-warning small"></i>
                                                    </div>
                                                    <div class="badge bg-white border text-success shadow-sm d-flex align-items-center gap-1 py-1 px-2" style="font-size: 0.65rem; border-radius: 6px;">
                                                        <span class="text-muted fw-normal">Behavior:</span>
                                                        <span class="fw-bold"><?= $r['behavior_score'] ?></span>
                                                        <i class="fas fa-star text-warning small"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-relative p-3 bg-white shadow-sm" style="border-radius: 12px; border-left: 4px solid #0d6efd;">
                                        <i class="fas fa-quote-left text-primary opacity-25 position-absolute top-0 start-0 m-2" style="font-size: 0.8rem;"></i>
                                        <p class="mb-0 text-dark" style="font-size: 0.88rem; line-height: 1.6; padding-left: 10px;">
                                            <?= esc($r['comment'] ?: 'Tidak ada komentar tertulis.') ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <div class="mb-3">
                            <i class="fas fa-comment-slash fa-3x opacity-10"></i>
                        </div>
                        <h6 class="fw-bold opacity-50">Belum Ada Ulasan</h6>
                        <p class="small mb-0 opacity-50 px-5">Mitra ini belum memiliki riwayat ulasan dari pelanggan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: MANAGEMENT -->
    <div class="col-12 col-lg-4">

        <!-- STATUS MANAGEMENT -->
        <?php if (can('tukang_status')): ?>
            <div class="card shadow-sm action-card mb-4">
                <div class="card-header">
                    <h6 class="text-white mb-0 fw-bold">
                        <i class="fas fa-tasks me-2"></i>Kelola Status Mitra
                    </h6>
                </div>
                <div class="card-body p-4 pt-3">
                    <div class="d-grid gap-2">
                        <?php
                        $steps = [
                            'Berkas Diproses' => ['color' => 'warning', 'icon' => 'fas fa-file-medical', 'desc' => 'Review berkas pendaftaran'],
                            'Proses Test'     => ['color' => 'info',    'icon' => 'fas fa-vial',         'desc' => 'Menunggu jadwal test skill'],
                            'Proses Aktivasi' => ['color' => 'primary', 'icon' => 'fas fa-user-check',   'desc' => 'Proses pengaktifan akun'],
                            'Siap Kerja'      => ['color' => 'success', 'icon' => 'fas fa-check-double', 'desc' => 'Mitra aktif & siap bekerja'],
                            'Ditolak'         => ['color' => 'danger',  'icon' => 'fas fa-times-circle', 'desc' => 'Pendaftaran tidak disetujui'],
                        ];

                        foreach ($steps as $key => $meta):
                            $isActive = ($status === $key);
                        ?>
                            <form action="<?= base_url('admin/tukang/update-status') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $tukang['id'] ?>">
                                <input type="hidden" name="status" value="<?= $key ?>">
                                <button type="submit"
                                    class="btn <?= $isActive ? 'btn-' . $meta['color'] : 'btn-outline-' . $meta['color'] ?> status-action-btn text-start w-100"
                                    <?= $isActive ? 'disabled' : '' ?>>
                                    <div class="d-flex align-items-center justify-content-between w-100">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="<?= $meta['icon'] ?>" style="width:20px; text-align:center;"></i>
                                            <div>
                                                <div style="font-size:0.85rem; font-weight:700; line-height:1.2;"><?= $key ?></div>
                                                <div style="font-size:0.7rem; font-weight:400; opacity:0.75;"><?= $meta['desc'] ?></div>
                                            </div>
                                        </div>
                                        <?php if ($isActive): ?>
                                            <i class="fas fa-check-circle" style="font-size:1.1rem;"></i>
                                        <?php endif; ?>
                                    </div>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- VERIFICATION MANAGEMENT -->
        <?php if (can('tukang_verify')): ?>
            <div class="card shadow-sm action-card mb-4" style="border: 1px solid #dee2e6;">
                <div class="card-header bg-white border-0 py-3" style="background: #fff !important;">
                    <h6 class="text-dark mb-0 fw-bold">
                        <i class="fas fa-user-shield me-2 text-primary"></i>Verifikasi Identitas
                    </h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <form action="<?= base_url('admin/tukang/update-verify') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $tukang['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Status Verifikasi</label>
                            <select name="is_verify" class="form-select" onchange="this.form.submit()">
                                <option value="0" <?= $tukang['is_verify'] == 0 ? 'selected' : '' ?>>Belum Verifikasi</option>
                                <option value="1" <?= $tukang['is_verify'] == 1 ? 'selected' : '' ?>>Terverifikasi (KTP Sesuai)</option>
                            </select>
                        </div>
                        <p class="text-muted mb-0 small" style="font-size: 0.7rem;">
                            <i class="fas fa-info-circle me-1"></i> Mengubah status ini akan mempengaruhi akses fitur mitra di aplikasi.
                        </p>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Danger Zone -->
        <?php if (can('tukang_delete')): ?>
            <div class="card shadow-sm border-0" style="border-radius:16px; background: #fff5f5;">
                <div class="card-body">
                    <a href="<?= base_url('admin/tukang/delete/' . $tukang['id']) ?>"
                        class="btn btn-outline-danger w-100 fw-bold"
                        style="border-radius:12px; font-size:0.85rem;"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus data mitra ini?')">
                        <i class="fas fa-trash-alt me-2"></i> Hapus Mitra Tukang
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Photo Viewer Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 text-center">
                <img src="" id="photoViewer" style="max-width:100%; border-radius:16px; box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
                <button type="button" class="btn btn-light mt-3 fw-bold rounded-pill px-4 shadow" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    function showPhoto(src) {
        document.getElementById('photoViewer').src = src;
    }

    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil!',
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
</script>
<?= $this->endSection() ?>