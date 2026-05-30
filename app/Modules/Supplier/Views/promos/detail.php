<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Promo - <?= esc($promo['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Promo
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO BANNER ===== */
    .promo-hero {
        background: #6777ef;
        border-radius: 16px 16px 0 0;
        padding: 20px 28px 70px;
        position: relative;
        overflow: hidden;
    }

    .promo-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }

    .promo-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== PROMO IMAGE ===== */
    .promo-img-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -60px;
    }

    .promo-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
        border: 4px solid #fff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        background: #fff;
    }

    /* ===== CARDS ===== */
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(103, 119, 239, 0.1), 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .action-card .card-header {
        background: #6777EF !important;
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
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== INFO LIST ===== */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 16px 0;
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
        background: #f0f4ff;
        color: #6777ef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .info-label {
        font-size: 0.7rem;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 0.95rem;
        color: #2d3436;
        font-weight: 600;
        word-break: break-word;
    }

    /* ===== ACTION BUTTONS ===== */
    .status-action-btn {
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 12px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .status-action-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .promo-code-box {
        background: #f8f9ff;
        border: 2px dashed #6777ef;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
    }

    .promo-code-text {
        font-size: 1.5rem;
        font-weight: 800;
        color: #6777ef;
        letter-spacing: 2px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

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

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
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
                title: 'Gagal!',
                message: '<?= session()->getFlashdata('error') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>

        if (window.GLightbox) {
            GLightbox({ selector: '.glightbox' });
        }
    });
</script>
<?= $this->endSection() ?>