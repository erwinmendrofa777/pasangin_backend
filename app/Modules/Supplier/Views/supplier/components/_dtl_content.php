<?php
/* ===== STATUS META ===== */
$status = $supplier['status'] ?? 'unknown';
$statusMeta = [
    'approved' => ['class' => 'status-approved', 'icon' => 'fas fa-check-circle', 'label' => 'Approved'],
    'pending' => ['class' => 'status-pending', 'icon' => 'fas fa-clock', 'label' => 'Pending'],
    'rejected' => ['class' => 'status-rejected', 'icon' => 'fas fa-times-circle', 'label' => 'Rejected'],
    'banned' => ['class' => 'status-banned', 'icon' => 'fas fa-ban', 'label' => 'Banned'],
];
$currentMeta = $statusMeta[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => ucfirst($status)];

/* ===== AVATAR / LOGO ===== */
$avatarSrc = null;
if (!empty($supplier['logo_url'])) {
    $avatarSrc = strpos($supplier['logo_url'], 'http') === 0
        ? $supplier['logo_url']
        : base_url('uploads/supplierLogo/' . $supplier['logo_url']);
}

/* ===== INITIALS ===== */
$nameParts = explode(' ', trim($supplier['name'] ?? 'S'));
$initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
?>

<!-- ===== 2-COLUMN LAYOUT ===== -->
<div class="row g-4 align-items-start">

    <!-- ======================== LEFT: PROFILE INFO ======================== -->
    <div class="col-12 col-md-7">
        <div class="card shadow-sm profile-card">

            <!-- Hero Banner -->
            <div class="profile-hero bg-primary">
                <div class="d-flex justify-content-end align-items-center" style="z-index:1;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="role-chip-hero">
                            <i class="fas fa-truck me-1"></i>Supplier
                        </span>
                        <span class="status-pill <?= $currentMeta['class'] ?>">
                            <span class="dot"></span><?= $currentMeta['label'] ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Body -->
            <div class="profile-body">

                <!-- Avatar + ID -->
                <div class="d-flex align-items-end justify-content-between mb-3">
                    <div class="avatar-wrapper">
                        <?php if ($supplier['logo_url']): ?>
                            <?php $logoUrl = base_url('uploads/supplier/' . $supplier['logo_url']); ?>
                            <a href="<?= $logoUrl ?>" class="glightbox" data-title="<?= esc($supplier['name']) ?>"
                                data-description="Nama Toko: <?= esc($supplier['name']) ?> &lt;br&gt; Email: <?= esc($supplier['email'] ?: '-') ?> &lt;br&gt; Telepon: <?= esc($supplier['phone'] ?: '-') ?>">
                                <img src="<?= $logoUrl ?>" alt="<?= esc($supplier['name']) ?>" class="avatar-img"
                                    id="img-preview" data-toggle="tooltip" title="Klik untuk memperbesar">
                            </a>
                        <?php else: ?>
                            <div class="avatar-initials"><?= $initials ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="text-muted" style="font-size:0.77rem; padding-bottom:4px;">
                        Dibuat Pada: <strong><?= esc($supplier['created_at']) ?></strong>
                    </span>
                </div>

                <hr class="my-3">

                <!-- Info List -->
                <p class="section-title"><i class="fas fa-building me-1"></i>Informasi Supplier</p>

                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-building"></i></div>
                        <div>
                            <div class="info-label">Nama Perusahaan / Supplier</div>
                            <div class="info-value"><?= esc($supplier['name'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-user-tie"></i></div>
                        <div>
                            <div class="info-label">Kontak Person</div>
                            <div class="info-value"><?= esc($supplier['contact_person'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= esc($supplier['email'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fab fa-whatsapp"></i></div>
                        <div>
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value"><?= esc($supplier['phone'] ?? '-') ?></div>
                        </div>
                    </div>

                    <p class="section-title mt-4 mb-3"><i class="fas fa-map-marked-alt me-1"></i>Lokasi & Demografi</p>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="info-label">Alamat Lengkap</div>
                            <div class="info-value"><?= esc($supplier['address'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-city"></i></div>
                        <div>
                            <div class="info-label">Kecamatan</div>
                            <div class="info-value"><?= esc($supplier['district'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-building"></i></div>
                        <div>
                            <div class="info-label">Kota</div>
                            <div class="info-value"><?= esc($supplier['city'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-map"></i></div>
                        <div>
                            <div class="info-label">Provinsi</div>
                            <div class="info-value"><?= esc($supplier['province'] ?? '-') ?></div>
                        </div>
                    </div>

                    <!-- ===== GOOGLE MAPS EMBED ===== -->
                    <div class="mt-4">
                        <p class="section-title mb-2"><i class="fas fa-map-marked-alt me-1"></i>Peta Lokasi Geografis
                            (Gmaps)</p>
                        <?php if (!empty($supplier['latitude']) && !empty($supplier['longitude'])): ?>
                            <div class="map-container shadow-sm p-1 bg-white"
                                style="border-radius: 14px; border: 1px solid #e9ecef;">
                                <iframe
                                    src="https://maps.google.com/maps?q=<?= esc($supplier['latitude']) ?>,<?= esc($supplier['longitude']) ?>&hl=id&z=15&output=embed"
                                    width="100%" height="220" style="border:0; border-radius:10px;" allowfullscreen=""
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        <?php else: ?>
                            <div class="text-center p-4 bg-light" style="border-radius: 12px; border: 1px dashed #ced4da;">
                                <i class="fas fa-map-marked-alt text-muted mb-2" style="font-size:2rem; opacity:0.5;"></i>
                                <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:500;">Koordinat peta belum
                                    disetel.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /LEFT -->

    <!-- ======================== RIGHT: UPDATE STATUS ======================== -->
    <div class="col-12 col-md-5 mt-1 mt-md-4">
        <?php if (can('suppliers_status')): ?>
            <div class="card shadow-sm mb-3 action-card">

                <!-- Card Header -->
                <div class="card-header">
                    <h6 class="text-white mb-0 fw-bold">
                        <i class="fas fa-sliders-h me-2"></i>Kelola Status Supplier
                    </h6>
                </div>

                <div class="card-body p-4 pt-3">
                    <div class="d-grid gap-2">
                        <?php
                        $actions = [
                            'approved' => ['color' => 'success', 'icon' => 'fas fa-check-circle', 'label' => 'Approved', 'desc' => 'Supplier aktif & terverifikasi'],
                            'pending' => ['color' => 'warning', 'icon' => 'fas fa-clock', 'label' => 'Pending', 'desc' => 'Menunggu verifikasi admin'],
                            'rejected' => ['color' => 'danger', 'icon' => 'fas fa-times-circle', 'label' => 'Rejected', 'desc' => 'Verifikasi supplier ditolak'],
                            'banned' => ['color' => 'dark', 'icon' => 'fas fa-ban', 'label' => 'Banned', 'desc' => 'Akses supplier diblokir'],
                        ];
                        foreach ($actions as $key => $act):
                            $isActive = ($status === $key);
                            ?>
                            <button type="button"
                                class="btn <?= $isActive ? 'btn-' . $act['color'] : 'btn-outline-' . $act['color'] ?> status-action-btn text-start"
                                <?= $isActive ? 'disabled' : '' ?>         <?= !$isActive ? 'data-bs-toggle="modal" data-bs-target="#confirmStatusModal"' : '' ?> data-status="<?= $key ?>"
                                data-status-label="<?= $act['label'] ?>" data-color="<?= $act['color'] ?>"
                                data-icon="<?= $act['icon'] ?>">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="<?= $act['icon'] ?>" style="width:16px; text-align:center;"></i>
                                        <div>
                                            <div style="font-size:0.88rem; font-weight:700; line-height:1.2;">
                                                <?= $act['label'] ?>
                                            </div>
                                            <div style="font-size:0.72rem; font-weight:400; opacity:0.75;"><?= $act['desc'] ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($isActive): ?>
                                        <i class="fas fa-check-circle ms-2" style="font-size:1rem;"></i>
                                    <?php else: ?>
                                        <i class="fas fa-chevron-right ms-2" style="font-size:0.75rem; opacity:0.6;"></i>
                                    <?php endif; ?>
                                </div>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Note -->
                    <div class="mt-3 pt-3 border-top">
                        <p class="text-muted mb-0" style="font-size:0.78rem;">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            Tombol berwarna solid menunjukkan status yang sedang aktif dan tidak dapat dipilih kembali.
                        </p>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <!-- Actions Card -->
        <?php if (can('suppliers_delete')): ?>
            <div class="card shadow-sm section-card">
                <div class="card-body">
                    <button type="button" class="btn btn-outline-danger w-100"
                        style="border-radius:10px; font-size:0.85rem; font-weight:600;" data-bs-toggle="modal"
                        data-bs-target="#deleteSupplierModal">
                        <i class="fas fa-trash-alt me-2"></i>Hapus Supplier Ini
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- /RIGHT -->

</div>
<!-- /2-COLUMN LAYOUT -->


<!-- ===== CONFIRMATION MODAL ===== -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="confirmStatusModalLabel">
                    <i class="fas fa-shield-alt text-primary me-2"></i>Konfirmasi Perubahan Status
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div id="modalIconWrap" class="mb-3 mx-auto"
                    style="width:68px;height:68px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.9rem;">
                </div>
                <p class="mb-1 fw-semibold" style="font-size:1rem;">Ubah status menjadi</p>
                <h5 id="modalStatusLabel" class="fw-bold mb-3"></h5>
                <p class="text-muted" style="font-size:0.85rem;">
                    Status akun <strong><?= esc($supplier['name']) ?></strong> akan segera diperbarui.
                    Pastikan keputusan ini sudah benar.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <form id="updateStatusForm" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" id="modalConfirmBtn" class="btn px-4 fw-semibold">
                        <i class="fas fa-check me-1"></i>Ya, Ubah Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ===== DELETE CONFIRMATION MODAL ===== -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger" id="deleteSupplierModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus Supplier
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3 mx-auto shadow-sm"
                    style="width:68px;height:68px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem; background:#fff5f5; color:#e03131;">
                    <i class="fas fa-building text-danger"></i>
                </div>
                <h5 class="fw-bold mb-2">Hapus Supplier Ini?</h5>
                <p class="text-muted px-3" style="font-size:0.85rem;">
                    Anda akan menghapus data <strong><?= esc($supplier['name']) ?></strong>.
                    Tindakan ini permanen dan data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-4">
                <button type="button" class="btn btn-light px-4 fw-semibold" data-bs-dismiss="modal"
                    style="border-radius:8px;">
                    Batal
                </button>
                <a href="<?= base_url('admin/suppliers/delete/' . $supplier['id']) ?>"
                    class="btn btn-danger px-4 fw-semibold" style="border-radius:8px;">
                    <i class="fas fa-trash-alt me-1"></i>Hapus Permanen
                </a>
            </div>
        </div>
    </div>
</div>