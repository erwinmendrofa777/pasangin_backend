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
                        <a href="<?= base_url('uploads/supplier/banner/' . $banner['image']) ?>" class="glightbox"
                            data-title="<?= esc($banner['title']) ?>"
                            data-description="Supplier: <?= esc($banner['supplier_name'] ?? 'Supplier') ?>">
                            <img src="<?= base_url('uploads/supplier/banner/' . $banner['image']) ?>"
                                alt="Banner Visual" class="banner-preview-img shadow" style="cursor:pointer;">
                        </a>
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
