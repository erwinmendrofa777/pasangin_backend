<?php
// Resolve user data for client info display
$db = \Config\Database::connect();
$user = $db->table('users')->where('id', $request['user_id'])->get()->getRowArray();

$status = $request['status'] ?? 'PENDING';
$statusMeta = [
    'PENDING'          => ['class' => 'status-pending',   'icon' => 'fas fa-clock',              'label' => 'PENDING'],
    'SURVEY_SCHEDULED' => ['class' => 'status-survey',    'icon' => 'fas fa-calendar-check',     'label' => 'SURVEY SCHEDULED'],
    'PAYMENT_VERIFIED' => ['class' => 'status-payment',   'icon' => 'fas fa-file-invoice-dollar','label' => 'PAYMENT VERIFIED'],
    'COMPLETED'        => ['class' => 'status-completed', 'icon' => 'fas fa-check-circle',       'label' => 'COMPLETED'],
    'CANCELLED'        => ['class' => 'status-cancelled', 'icon' => 'fas fa-times-circle',       'label' => 'CANCELLED'],
];
$currentMeta = $statusMeta[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => $status];
?>

<div class="row mt-4">
    <!-- 1. Header Card (Stand-alone) -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm profile-card mb-0">
            <!-- Hero Banner -->
            <div class="profile-hero bg-primary" style="padding: 20px 28px;">
                <div class="d-flex justify-content-between align-items-center" style="z-index:1; position: relative;">
                    <h5 class="text-white mb-0 ms-1 fw-bold" style="font-size:1.25rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                        <i class="fas fa-drafting-compass me-2 opacity-75"></i>Permintaan Desain #<?= esc($request['id']) ?>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-pill <?= $currentMeta['class'] ?>" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                            <span class="dot"></span><?= $currentMeta['label'] ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== LEFT COLUMN CARD: Informasi Klien + Spesifikasi ===== -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="card-body p-4">
                <p class="section-title mt-0"><i class="fas fa-user-circle me-1"></i>Informasi Klien</p>
                <div class="info-list mb-4">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-id-card"></i></div>
                        <div>
                            <div class="info-label">User ID (Akun)</div>
                            <div class="info-value"><?= esc($request['user_id'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-user"></i></div>
                        <div>
                            <div class="info-label">Nama Klien</div>
                            <div class="info-value"><?= esc($request['full_name'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= esc($user['email'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value"><?= esc($request['phone_number'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <div class="info-label">Tanggal Pengajuan</div>
                            <div class="info-value"><?= date('d M Y, H:i', strtotime($request['created_at'])) ?></div>
                        </div>
                    </div>
                </div>

                <p class="section-title mt-4"><i class="fas fa-paint-roller me-1"></i>Detail Spesifikasi Proyek</p>
                <div class="info-list mb-2">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-palette"></i></div>
                        <div>
                            <div class="info-label">Konsep Desain</div>
                            <div class="info-value fw-bold text-primary"><?= esc($request['design_concept'] ?? '-') ?></div>
                        </div>
                    </div>
                    <?php if (!empty($request['other_concept_desc'])): ?>
                        <div class="info-item border-0 pb-0">
                            <div class="info-icon"><i class="fas fa-comment-alt"></i></div>
                            <div>
                                <div class="info-label">Deskripsi Tambahan Konsep</div>
                                <div class="info-value" style="font-size:0.85rem;"><?= esc($request['other_concept_desc']) ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-vector-square"></i></div>
                        <div>
                            <div class="info-label">Luas Lahan &amp; Bangunan</div>
                            <div class="info-value">Tanah: <?= esc($request['land_area'] ?? '0') ?> m² &nbsp;|&nbsp;
                                Bangunan: <?= esc($request['building_area'] ?? '0') ?> m²
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($request['voucher_code'])): ?>
                        <div class="info-item">
                            <div class="info-icon text-success" style="background:#e6f9ed;"><i class="fas fa-ticket-alt"></i></div>
                            <div>
                                <div class="info-label text-success">Kode Voucher</div>
                                <div class="info-value text-success fw-bold"><?= esc($request['voucher_code']) ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RIGHT COLUMN CARD: Lokasi + Peta + Jadwal + Update Status ===== -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="card-body p-4">
                <p class="section-title mt-0"><i class="fas fa-map-marker-alt me-1"></i>Lokasi Proyek</p>
                <div class="info-list mb-3">
                    <div class="info-item border-0 pb-0">
                        <div class="info-icon"><i class="fas fa-home"></i></div>
                        <div>
                            <div class="info-label">Alamat Lengkap</div>
                            <div class="info-value"><?= esc($request['location_address'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed -->
                <div class="mt-4">
                    <p class="section-title mb-2"><i class="fas fa-map-marked-alt me-1"></i>Peta Lokasi Geografis</p>
                    <?php if (!empty($request['latitude']) && !empty($request['longitude'])): ?>
                        <div class="map-container shadow-sm p-1 bg-white"
                            style="border-radius: 14px; border: 1px solid #e9ecef;">
                            <iframe
                                src="https://maps.google.com/maps?q=<?= esc($request['latitude']) ?>,<?= esc($request['longitude']) ?>&hl=id&z=15&output=embed"
                                width="100%" height="220" style="border:0; border-radius:10px;"
                                allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-4 bg-light"
                            style="border-radius: 12px; border: 1px dashed #ced4da;">
                            <i class="fas fa-map-marked-alt text-muted mb-2"
                                style="font-size:2rem; opacity:0.5;"></i>
                            <p class="text-muted mb-0"
                                style="font-size:0.85rem; font-weight:500;">Koordinat peta belum disetel.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Jadwal Proyek -->
                <p class="section-title mt-4"><i class="fas fa-calendar-check me-1"></i>Jadwal Proyek</p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border border-light" style="background-color: #f8fafc !important;">
                            <div class="text-muted mb-1" style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Mulai</div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-play-circle text-primary" style="font-size: 1.1rem; opacity: 0.8;"></i>
                                <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                                    <?= !empty($request['start_date']) ? date('d M Y', strtotime($request['start_date'])) : '<span class="text-muted fw-normal fst-italic" style="font-size:0.85rem;">Belum disetel</span>' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border border-light" style="background-color: #f8fafc !important;">
                            <div class="text-muted mb-1" style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Target Selesai</div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-flag-checkered text-success" style="font-size: 1.1rem; opacity: 0.8;"></i>
                                <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                                    <?= !empty($request['target_date']) ? date('d M Y', strtotime($request['target_date'])) : '<span class="text-muted fw-normal fst-italic" style="font-size:0.85rem;">Belum disetel</span>' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Status Action -->
                <?php if (can('design_detail')): ?>
                    <div class="p-3 rounded-3 border" style="background-color: #f8fafc; border-color: #e2e8f0 !important; margin-top: 24px;">
                        <p class="section-title mt-0 mb-2" style="border: none; padding-left: 0; font-size: 0.75rem;"><i class="fas fa-sync-alt me-1 text-primary"></i>Update Status Proyek</p>
                        <form action="<?= base_url('admin/design/update-status/' . $request['id']) ?>"
                            method="post" class="d-flex gap-2">
                            <?= csrf_field() ?>
                            <select name="status" class="form-select form-control fw-semibold" style="border-radius:10px; border-color: #cbd5e1; font-size: 0.9rem; padding: 10px 14px; height: auto;">
                                <option value="PENDING" <?= $request['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                <option value="SURVEY_SCHEDULED" <?= $request['status'] == 'SURVEY_SCHEDULED' ? 'selected' : '' ?>>SURVEY SCHEDULED</option>
                                <option value="PAYMENT_VERIFIED" <?= $request['status'] == 'PAYMENT_VERIFIED' ? 'selected' : '' ?>>PAYMENT VERIFIED</option>
                                <option value="COMPLETED" <?= $request['status'] == 'COMPLETED' ? 'selected' : '' ?>>COMPLETED</option>
                                <option value="CANCELLED" <?= $request['status'] == 'CANCELLED' ? 'selected' : '' ?>>CANCELLED</option>
                            </select>
                            <button type="submit" class="btn btn-primary ladda-button px-4"
                                data-style="zoom-in" style="border-radius:10px; font-weight: 600; font-size: 0.9rem; background: var(--palette-primary); border: none;">
                                <span class="ladda-label">Update</span>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
