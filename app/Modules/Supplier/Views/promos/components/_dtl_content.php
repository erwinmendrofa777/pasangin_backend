<!-- BACK BUTTON -->
<div class="mb-4">
    <a href="<?= base_url('admin/promo') ?>" class="btn btn-light shadow-sm px-4" style="border-radius: 10px;">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Promo
    </a>
</div>

<div class="row g-4">
    <!-- LEFT: PROMO DETAILS -->
    <div class="col-lg-8">
        <div class="card detail-card">
            <!-- Hero -->
            <div class="promo-hero">
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                    <h5 class="text-white mb-0 fw-bold"><?= esc($promo['title']) ?></h5>
                    <span class="status-pill <?= $promo['status'] == 'active' ? 'status-active' : 'status-inactive' ?>">
                        <i class="fas <?= $promo['status'] == 'active' ? 'fa-check-circle' : 'fa-times-circle' ?> me-1"></i>
                        <?= $promo['status'] == 'active' ? 'Aktif' : 'Non-Aktif' ?>
                    </span>
                </div>
            </div>

            <div class="card-body px-4 pb-4">
                <div class="d-flex align-items-end justify-content-between">
                    <div class="promo-img-wrapper">
                        <?php
                        $photoUrl = !empty($promo['photo'])
                            ? (strpos($promo['photo'], 'http') === 0 ? $promo['photo'] : base_url('uploads/promos/' . $promo['photo']))
                            : base_url('assets/img/news/img01.jpg');
                        ?>
                        <a href="<?= $photoUrl ?>" class="glightbox" data-title="<?= esc($promo['title']) ?>" data-description="Supplier: <?= esc($promo['supplier_name']) ?>">
                            <img src="<?= $photoUrl ?>" alt="Promo Image" class="promo-img shadow" style="cursor: pointer;">
                        </a>
                    </div>
                    <div class="text-end mb-2">
                        <small class="text-muted d-block">ID Promo: #<?= $promo['id'] ?></small>
                        <small class="text-muted d-block">Dibuat: <?= date('d M Y', strtotime($promo['created_at'])) ?></small>
                    </div>
                </div>

                <div class="mt-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-store"></i></div>
                                <div>
                                    <div class="info-label">Supplier</div>
                                    <div class="info-value"><?= esc($promo['supplier_name']) ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-percentage"></i></div>
                                <div>
                                    <div class="info-label">Jenis Diskon</div>
                                    <div class="info-value"><?= $promo['discount_type'] == 'fixed' ? 'Potongan Harga (Rp)' : 'Persentase (%)' ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-tags"></i></div>
                                <div>
                                    <div class="info-label">Nilai Potongan</div>
                                    <div class="info-value text-danger fw-bold">
                                        <?= $promo['discount_type'] == 'fixed' ? 'Rp ' . number_format($promo['discount_value'], 0, ',', '.') : $promo['discount_value'] . '%' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon"><i class="far fa-calendar-alt"></i></div>
                                <div>
                                    <div class="info-label">Masa Berlaku</div>
                                    <div class="info-value"><?= date('d M Y', strtotime($promo['start_date'])) ?> s/d <?= date('d M Y', strtotime($promo['end_date'])) ?></div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                                <div>
                                    <div class="info-label">Deskripsi</div>
                                    <div class="info-value"><?= esc($promo['description']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-light rounded-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-ticket-alt me-2 text-primary"></i>Kode Promo Supplier</h6>
                    <div class="promo-code-box">
                        <div class="promo-code-text"><?= esc($promo['promo_code']) ?></div>
                        <small class="text-muted mt-2 d-block">Gunakan kode ini saat melakukan pemesanan dari supplier terkait.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: ACTIONS -->
    <div class="col-lg-4">
        <?php if (can('promo_status') || can('promo_delete')): ?>
        <div class="card detail-card action-card mb-4">
            <div class="card-header">
                <h6 class="text-white mb-0 fw-bold"><i class="fas fa-cog me-2"></i>Kelola Promo</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <?php if (can('promo_status')): ?>
                    <div>
                        <form action="<?= base_url('admin/promo/update_status/' . $promo['id'] . '/' . ($promo['status'] == 'active' ? 'inactive' : 'active')) ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn <?= $promo['status'] == 'active' ? 'btn-outline-danger' : 'btn-outline-success' ?> w-100 status-action-btn">
                                <span>
                                    <i class="fas <?= $promo['status'] == 'active' ? 'fa-times-circle' : 'fa-check-circle' ?> me-1"></i>
                                    <?= $promo['status'] == 'active' ? 'Non-Aktifkan Promo' : 'Aktifkan Promo' ?>
                                </span>
                                <i class="fas fa-chevron-right small"></i>
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>

                    <?php if (can('promo_delete')): ?>
                    <button type="button" class="btn btn-outline-danger w-100 status-action-btn" data-bs-toggle="modal" data-bs-target="#deletePromoModal">
                        <span><i class="fas fa-trash-alt me-2"></i>Hapus Promo</span>
                        <i class="fas fa-chevron-right small"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="alert alert-info border-0 shadow-sm rounded-4 p-3" style="background: #eef2ff;">
            <div class="d-flex gap-3">
                <i class="fas fa-info-circle text-primary mt-1"></i>
                <div class="small text-dark">
                    <strong>Informasi:</strong> Promo yang non-aktif tidak akan muncul di aplikasi pelanggan. Pastikan masa berlaku masih valid saat mengaktifkan kembali.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- IMAGE PREVIEW MODAL -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="<?= $photoUrl ?>" class="img-fluid rounded-bottom" style="width: 100%;">
            </div>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deletePromoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4 text-danger">
                    <i class="fas fa-exclamation-triangle fa-4x"></i>
                </div>
                <h4 class="fw-bold mb-3">Hapus Promo?</h4>
                <p class="text-muted mb-4">Anda akan menghapus promo <strong><?= esc($promo['title']) ?></strong>. Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                    <a href="<?= base_url('admin/promo/delete/' . $promo['id']) ?>" class="btn btn-danger px-4" style="border-radius: 12px;">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>
