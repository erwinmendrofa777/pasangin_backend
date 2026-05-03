<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Tips - <?= esc($tips['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Konten
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .detail-hero {
        background: #0d6efd;
        border-radius: 16px 16px 0 0;
        padding: 28px 28px 72px;
        position: relative;
        overflow: hidden;
    }

    .detail-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    /* ===== IMAGE PREVIEW ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -85px;
        margin-left: 10px;
    }

    .tips-preview-img {
        width: 240px;
        height: 140px;
        object-fit: cover;
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
    .badge-pill {
        border-radius: 50px;
        padding: 6px 16px;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .bg-tukang {
        background: #fff7ed;
        color: #9a3412;
    }

    .bg-client {
        background: #eff6ff;
        color: #1e40af;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #374151;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-4">
    <a href="<?= base_url('admin/tips') ?>" class="btn btn-light btn-sm px-3 shadow-sm" style="border-radius: 10px;">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card detail-card">
            <!-- Hero Section -->
            <div class="detail-hero bg-primary">
                <div class="d-flex justify-content-between align-items-center" style="z-index: 1;">
                    <h5 class="text-white mb-0 fw-bold">Tips & Tricks</h5>
                    <div class="d-flex gap-2">
                        <?php if ($tips['is_active'] == 1): ?>
                            <span class="badge-pill status-active"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                        <?php else: ?>
                            <span class="badge-pill status-inactive"><i class="fas fa-times-circle me-1"></i> Draft</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="detail-body">
                <!-- Visual -->
                <div class="d-flex align-items-end justify-content-between">
                    <div class="avatar-wrapper">
                        <img src="<?= base_url('uploads/tips/' . $tips['image']) ?>" alt="Tips Visual" class="tips-preview-img">
                    </div>
                    <div class="text-end">
                        <span class="text-muted small">
                            Dibuat Pada: <br><strong><?= date('d M Y, H:i', strtotime($tips['created_at'])) ?> WIB</strong>
                        </span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Content -->
                <div class="px-2">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <?php if (strtolower($tips['target_app']) == 'tukang'): ?>
                            <span class="badge-pill bg-tukang"><i class="fas fa-tools me-1"></i> Untuk Tukang</span>
                        <?php else: ?>
                            <span class="badge-pill bg-client"><i class="fas fa-user me-1"></i> Untuk Client</span>
                        <?php endif; ?>
                    </div>

                    <h3 class="fw-bold text-dark mb-4"><?= esc($tips['title']) ?></h3>

                    <div class="tips-content text-muted" style="line-height: 1.8; font-size: 1rem;">
                        <?= esc($tips['content']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <!-- ACTIONS CARD -->
        <?php if (can('tips_delete')): ?>
        <div class="card detail-card shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-cog me-2"></i>Tindakan</h6>
            </div>
            <div class="card-body pt-0">
                <div class="d-grid gap-3">
                    <a href="<?= base_url('admin/tips/delete/' . $tips['id']) ?>"
                        class="btn btn-outline-danger py-2 fw-bold"
                        style="border-radius: 12px;"
                        onclick="return confirm('Yakin ingin menghapus tips ini?')">
                        <i class="fas fa-trash-alt me-2"></i> Hapus Tips
                    </a>
                </div>
                <p class="text-muted small text-center mb-0 mt-3" style="font-size: 0.75rem;">
                    Tindakan penghapusan tidak dapat dibatalkan.
                </p>
            </div>
        </div>
        <?php endif; ?>
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