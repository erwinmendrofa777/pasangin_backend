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
    <div class="col-md-12">
        <div class="card shadow-sm profile-card mb-4">

            <!-- Hero Banner -->
            <div class="profile-hero bg-primary">
                <div class="d-flex justify-content-between align-items-center" style="z-index:1;">
                    <h5 class="text-white mb-0 ms-1 fw-bold" style="font-size:1.2rem;">
                        <i class="fas fa-drafting-compass me-2 opacity-75"></i>Permintaan Desain
                        #<?= esc($request['id']) ?>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-pill <?= $currentMeta['class'] ?>">
                            <span class="dot"></span><?= $currentMeta['label'] ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Body -->
            <div class="profile-body pt-1">
                <div class="row">

                    <!-- ===== LEFT COLUMN: Informasi Klien + Spesifikasi ===== -->
                    <div class="col-md-6 pe-md-4 border-end">
                        <p class="section-title mt-2"><i class="fas fa-user-circle me-1"></i>Informasi Klien</p>
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

                        <p class="section-title mt-2"><i class="fas fa-paint-roller me-1"></i>Detail Spesifikasi Proyek</p>
                        <div class="info-list mb-4">
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

                    <!-- ===== RIGHT COLUMN: Lokasi + Peta + Jadwal + Update Status ===== -->
                    <div class="col-md-6 ps-md-4">
                        <p class="section-title mt-0 mt-sm-2"><i class="fas fa-map-marker-alt me-1"></i>Lokasi Proyek</p>
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
                        <div class="info-list mb-2">
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-play-circle"></i></div>
                                <div>
                                    <div class="info-label">Tanggal Mulai</div>
                                    <div class="info-value">
                                        <?= !empty($request['start_date']) ? date('d M Y', strtotime($request['start_date'])) : '<span class="text-muted fst-italic" style="font-size:0.85rem;">Belum disetel</span>' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fas fa-flag-checkered"></i></div>
                                <div>
                                    <div class="info-label">Target Selesai</div>
                                    <div class="info-value">
                                        <?= !empty($request['target_date']) ? date('d M Y', strtotime($request['target_date'])) : '<span class="text-muted fst-italic" style="font-size:0.85rem;">Belum disetel</span>' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Status Action -->
                        <?php if (can('design_detail')): ?>
                            <div class="p-3 bg-light rounded border border-light">
                                <p class="section-title mt-0"><i class="fas fa-sync-alt me-1"></i>Update Status Proyek</p>
                                <form action="<?= base_url('admin/design/update-status/' . $request['id']) ?>"
                                    method="post" class="d-flex gap-2">
                                    <?= csrf_field() ?>
                                    <select name="status" class="form-select form-control fw-bold" style="border-radius:8px;">
                                        <option value="PENDING" <?= $request['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                        <option value="SURVEY_SCHEDULED" <?= $request['status'] == 'SURVEY_SCHEDULED' ? 'selected' : '' ?>>SURVEY SCHEDULED</option>
                                        <option value="PAYMENT_VERIFIED" <?= $request['status'] == 'PAYMENT_VERIFIED' ? 'selected' : '' ?>>PAYMENT VERIFIED</option>
                                        <option value="COMPLETED" <?= $request['status'] == 'COMPLETED' ? 'selected' : '' ?>>COMPLETED</option>
                                        <option value="CANCELLED" <?= $request['status'] == 'CANCELLED' ? 'selected' : '' ?>>CANCELLED</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary ladda-button"
                                        data-style="zoom-in" style="border-radius:8px;">
                                        <span class="ladda-label">Update</span>
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
