<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Banner Supplier - <?= esc($banner['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .detail-hero {
        background: #6777ef;
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
        margin-top: -60px;
        margin-left: 10px;
    }

    .banner-preview-img {
        width: 320px;
        height: 180px;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
        border: 4px solid #fff;
        box-shadow: 0 8px 30px rgba(103, 119, 239, 0.2);
        background: #e9ecef;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .banner-preview-img:hover {
        transform: scale(1.02);
    }

    /* ===== CARDS ===== */
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(103, 119, 239, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        background: #fff;
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
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* ===== INFO STYLES ===== */
    .info-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
        display: block;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }

    .action-sidebar {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
    }

    @media (max-width: 768px) {
        .detail-hero {
            padding: 20px 20px 60px;
        }

        .avatar-wrapper {
            margin-top: -40px;
            margin-left: 0;
            width: 100%;
            text-align: center;
        }

        .banner-preview-img {
            width: 100%;
            max-width: 320px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-4">
    <a href="<?= base_url('admin/banner-supplier') ?>" class="btn btn-light btn-sm px-3 shadow-sm"
        style="border-radius: 10px; font-weight: 600;">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card detail-card">
            <!-- Hero Section -->
            <div class="detail-hero">
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                    <h5 class="text-white mb-0 fw-bold">Detail Banner Supplier</h5>
                    <div class="d-flex gap-2">
                        <?php
                        $statusClass = 'status-pending';
                        if ($banner['status'] == 'APPROVED')
                            $statusClass = 'status-approved';
                        if ($banner['status'] == 'REJECTED')
                            $statusClass = 'status-rejected';
                        ?>
                        <span class="badge-pill <?= $statusClass ?> bg-white shadow-sm">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i> <?= $banner['status'] ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="detail-body">
                <!-- Visual & Meta -->
                <div class="d-flex align-items-end justify-content-between flex-wrap gap-3">
                    <div class="avatar-wrapper">
                        <img src="<?= base_url('uploads/supplier/banner/' . $banner['image']) ?>" alt="Banner Visual"
                            class="banner-preview-img shadow" onclick="window.open(this.src, '_blank')">
                    </div>
                    <div class="text-end flex-grow-1">
                        <span>
                            <h3 class="fw-bold text-dark mb-0">
                                <?= esc($banner['title']) ?>
                            </h3>
                        </span>
                        <span class="text-muted small">
                            Diajukan Pada: <br><strong><?= date('d M Y, H:i', strtotime($banner['created_at'])) ?>
                                WIB</strong>
                        </span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Content -->
                <div class="px-2">
                    <div class="row g-4">
                        <div class="col-12">

                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-4" style="background: #f8fbff; border: 1px solid #eef2ff;">
                                <span class="info-label text-primary"><i class="fas fa-calendar-check me-1"></i>Masa
                                    Penayangan</span>
                                <div class="info-value mt-1">
                                    <?= date('d M Y', strtotime($banner['start_date'])) ?>
                                    <span class="text-muted mx-1">s/d</span>
                                    <?= date('d M Y', strtotime($banner['end_date'])) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-4" style="background: #fffcf0; border: 1px solid #fff5d1;">
                                <span class="info-label text-warning"><i class="fas fa-sticky-note me-1"></i>Catatan
                                    Dari Admin</span>
                                <div class="info-value mt-1" style="font-weight: 400; font-style: italic;">
                                    <?= esc($banner['note'] ?: 'Tidak ada catatan admin.') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-top">
                        <span class="info-label mb-3">Identitas Supplier</span>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white p-2 rounded-circle me-3"
                                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= esc($banner['supplier_name']) ?></div>
                                        <div class="small text-muted">ID: #SUP-
                                            <?= $banner['id_supplier'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6">
                                        <span class="info-label" style="font-size: 0.65rem;">Email</span>
                                        <div class="info-value small text-truncate">
                                            <?= esc($banner['supplier_email']) ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <span class="info-label" style="font-size: 0.65rem;">Telepon</span>
                                        <div class="info-value small"><?= esc($banner['supplier_phone']) ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-center">
                                    <a href="<?= base_url('admin/suppliers/detail/' . $banner['id_supplier']) ?>"
                                        class="btn btn-outline-primary btn-sm w-50 shadow-sm mt-2"
                                        style="border-radius: 10px; font-weight: 600; border-style: dashed;">
                                        <i class="fas fa-external-link-alt me-1"></i> Buka Profil Lengkap Mitra
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <!-- ACTIONS CARD -->
        <div class="action-sidebar shadow-sm">
            <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="fas fa-cog me-2 text-primary"></i>Kontrol
                Akses</h6>

            <?php if (can('banner_supplier_status')): ?>
                <div class="d-grid gap-2 mb-3">
                    <button class="btn btn-success py-2 fw-bold btn-update-status" data-status="APPROVED"
                        style="border-radius: 12px;">
                        <i class="fas fa-check-circle me-2"></i> Setujui Banner
                    </button>
                    <button class="btn btn-danger py-2 fw-bold btn-update-status" data-status="REJECTED"
                        style="border-radius: 12px;">
                        <i class="fas fa-times-circle me-2"></i> Tolak Pengajuan
                    </button>
                    <hr class="my-2">
                    <button class="btn btn-light py-2 text-muted fw-bold btn-update-status" data-status="PENDING"
                        style="border-radius: 12px;">
                        Kembalikan ke Pending
                    </button>
                </div>
            <?php endif; ?>

            <?php if (can('banner_supplier_update')): ?>
                <a href="<?= base_url('admin/banner-supplier/edit/' . $banner['id']) ?>"
                    class="btn btn-outline-primary w-100 py-2 fw-bold mb-3" style="border-radius: 12px;">
                    <i class="fas fa-pencil-alt me-2"></i> Edit Data Banner
                </a>
            <?php endif; ?>

            <?php if (can('banner_supplier_delete')): ?>
                <button class="btn btn-link text-danger w-100 btn-sm text-decoration-none fw-bold btn-delete"
                    data-id="<?= $banner['id'] ?>">
                    <i class="fas fa-trash-alt me-2"></i> Hapus Permanen
                </button>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST">
    <?= csrf_field() ?>
</form>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function () {
        // Status Update Handler
        $('.btn-update-status').on('click', function () {
            var status = $(this).data('status');
            var id = '<?= $banner['id'] ?>';

            Swal.fire({
                title: 'Ubah Status?',
                text: "Status banner akan diubah menjadi " + status + ".",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6777ef',
                cancelButtonColor: '#adb5bd',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/banner-supplier/update-status') ?>',
                        type: 'POST',
                        data: {
                            id: id,
                            status: status,
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                        },
                        success: function (response) {
                            if (response.status) {
                                iziToast.success({ title: 'Berhasil!', message: response.message, position: 'topCenter' });
                                setTimeout(() => { location.reload(); }, 1000);
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        }
                    });
                }
            });
        });

        // Delete Handler
        $('.btn-delete').on('click', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Banner?',
                text: "Data banner dan file akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6777ef',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').attr('action', '<?= base_url('admin/banner-supplier/delete') ?>/' + id).submit();
                }
            });
        });

        // Flash Messages
        <?php if (session()->getFlashdata('success')): ?>
            iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>