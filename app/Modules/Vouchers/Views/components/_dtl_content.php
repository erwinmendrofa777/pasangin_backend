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
                        <a href="<?= base_url('uploads/vouchers/' . $voucher['image']) ?>" class="glightbox" data-title="<?= esc($voucher['name']) ?>" data-description="Kode: <?= esc($voucher['code']) ?> | Potongan: Rp <?= number_format($voucher['discount_nominal'], 0, ',', '.') ?>">
                            <img src="<?= base_url('uploads/vouchers/' . $voucher['image']) ?>" alt="Voucher Visual" class="voucher-preview-img" style="cursor:pointer;">
                        </a>
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
                        <p class="text-primary fw-bold small text-uppercase mb-3" style="letter-spacing: 1px;">Kode &amp; Deskripsi</p>
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
