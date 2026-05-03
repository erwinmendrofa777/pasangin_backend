<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Voucher - <?= esc($voucher['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Voucher
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO BANNER ===== */
    .voucher-hero {
        background: #0d6efd;
        border-radius: 16px 16px 0 0;
        padding: 18px 28px 68px;
        position: relative;
        overflow: hidden;
    }

    .voucher-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .voucher-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== VOUCHER IMAGE ===== */
    .voucher-preview-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -90px;
    }

    .voucher-preview-img {
        width: 240px;
        height: 140px;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
        border: 4px solid #fff;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        background: #e9ecef;
    }

    /* ===== CARDS ===== */
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .detail-body {
        padding: 0 24px 28px;
    }

    /* ===== BADGES ===== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-expired {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== INFO LIST ===== */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 12px;
        background: #e7f0ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .info-label {
        font-size: 0.72rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 0.95rem;
        color: #2d3436;
        font-weight: 600;
    }

    /* ===== CODE BOX ===== */
    .voucher-code-box {
        background: #f8fafc;
        border: 2px dashed #0d6efd;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        margin-top: 20px;
    }

    .voucher-code-text {
        font-family: 'Monaco', 'Consolas', monospace;
        font-size: 1.5rem;
        font-weight: 800;
        color: #0d6efd;
        letter-spacing: 2px;
    }

    @media (max-width: 767px) {
        .voucher-preview-img {
            width: 100%;
            height: auto;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-4">
    <a href="<?= base_url('admin/vouchers') ?>" class="btn btn-light btn-sm px-3 shadow-sm" style="border-radius: 10px;">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card detail-card">
            <!-- Hero Section -->
            <div class="voucher-hero bg-primary">
                <div class="d-flex justify-content-between align-items-center" style="z-index: 1;">
                    <div></div>
                    <?php
                    $isExpired = strtotime($voucher['valid_until']) < time();
                    if ($isExpired): ?>
                        <span class="status-badge status-expired"><i class="fas fa-exclamation-circle"></i> Expired</span>
                    <?php else: ?>
                        <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Aktif</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-body">
                <!-- Voucher Visual -->
                <div class="d-flex align-items-end justify-content-between">
                    <div class="voucher-preview-wrapper">
                        <img src="<?= base_url('uploads/vouchers/' . $voucher['image']) ?>" alt="Voucher Visual" class="voucher-preview-img">
                    </div>
                    <div class="text-end">
                        <span class="text-muted" style="font-size: 0.75rem;">
                            Dibuat Pada: <br><strong><?= date('d M Y, H:i', strtotime($voucher['created_at'])) ?> WIB</strong>
                        </span>
                    </div>
                </div>

                <hr class="my-3">

                <!-- Details -->
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-primary fw-bold small text-uppercase mb-3" style="letter-spacing: 1px;">Detail Voucher</p>
                        <div class="info-list">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-ticket-alt"></i></div>
                                <div>
                                    <div class="info-label">Nama Voucher</div>
                                    <div class="info-value"><?= esc($voucher['name']) ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-tags"></i></div>
                                <div>
                                    <div class="info-label">Potongan Harga</div>
                                    <div class="info-value text-success">Rp <?= number_format($voucher['discount_nominal'], 0, ',', '.') ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                                <div>
                                    <div class="info-label">Berlaku Hingga</div>
                                    <div class="info-value"><?= date('d F Y', strtotime($voucher['valid_until'])) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="text-primary fw-bold small text-uppercase mb-3" style="letter-spacing: 1px;">Kode & Deskripsi</p>
                        <div class="voucher-code-box">
                            <div class="info-label mb-2">Kode Voucher</div>
                            <div class="voucher-code-text"><?= esc($voucher['code']) ?></div>
                        </div>
                        <div class="mt-4">
                            <div class="info-label">Deskripsi</div>
                            <div class="info-value fw-normal text-muted" style="font-size: 0.88rem; line-height: 1.6;">
                                <?= esc($voucher['description'] ?: 'Tidak ada deskripsi.') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <!-- STATUS MANAGEMENT CARD -->
        <?php if (can('vouchers_status')): ?>
        <div class="card detail-card shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-toggle-on text-primary me-2"></i>Status</h6>
            </div>
            <div class="card-body pt-0">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('admin/vouchers/update-status/' . $voucher['id'] . '/1') ?>"
                        class="btn <?= $voucher['is_active'] == 1 ? 'btn-success shadow-sm' : 'btn-outline-success' ?> fw-bold py-2 d-flex align-items-center justify-content-center gap-2"
                        style="border-radius:12px;">
                        <i class="fas fa-check-circle"></i> <?= $voucher['is_active'] == 1 ? 'Voucher Aktif' : 'Aktifkan' ?>
                    </a>
                    <a href="<?= base_url('admin/vouchers/update-status/' . $voucher['id'] . '/0') ?>"
                        class="btn <?= $voucher['is_active'] == 0 ? 'btn-danger shadow-sm' : 'btn-outline-danger' ?> fw-bold py-2 d-flex align-items-center justify-content-center gap-2"
                        style="border-radius:12px;">
                        <i class="fas fa-times-circle"></i> <?= $voucher['is_active'] == 0 ? 'Voucher Nonaktif' : 'Nonaktifkan' ?>
                    </a>
                </div>
                <div class="mt-3 text-center">
                    <span class="text-muted small">
                        Status saat ini: <strong><?= $voucher['is_active'] == 1 ? 'AKTIF' : 'NONAKTIF' ?></strong>
                    </span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ACTIONS CARD -->
        <?php if (can('vouchers_delete')): ?>
        <div class="card detail-card shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-cog me-2 text-primary"></i>Tindakan Lainnya</h6>
            </div>
            <div class="card-body pt-0">
                <div class="d-grid gap-3">
                    <button type="button" class="btn btn-outline-danger py-2 fw-bold" style="border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#deleteVoucherModal">
                        <i class="fas fa-trash-alt me-2"></i> Hapus Voucher
                    </button>
                </div>
                <p class="text-muted small text-center mb-0 mt-3" style="font-size: 0.75rem;">
                    Penghapusan voucher bersifat permanen dan akan menghapus gambar terkait dari server.
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteVoucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-body text-center p-5">
                <div class="mb-4 text-danger">
                    <i class="fas fa-exclamation-triangle fa-4x"></i>
                </div>
                <h4 class="fw-bold mb-3">Hapus Voucher?</h4>
                <p class="text-muted mb-4">
                    Anda akan menghapus voucher <strong><?= esc($voucher['name']) ?></strong>. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                    <a href="<?= base_url('admin/vouchers/delete/' . $voucher['id']) ?>" class="btn btn-danger px-4 fw-bold" style="border-radius: 10px;">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
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