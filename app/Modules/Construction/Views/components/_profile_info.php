<!-- ======================== LEFT: PROFILE INFO ======================== -->
<div class="card profile-card">
    <!-- Hero Banner -->
    <div class="profile-hero pb-4">
        <div class="d-flex flex-column flex-md-row justify-content-end align-items-md-end gap-3"
            style="z-index:1;">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="role-chip-hero">
                    <i class="fas fa-hard-hat me-1"></i>Proyek
                </span>
                <span
                    class="status-pill status-<?= strtolower($currentConMeta['color']) ?>">
                    <span class="dot"></span><?= $currentConMeta['label'] ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Profile Body-->
    <div class="profile-body">

        <!-- Info List: Kontak -->
        <div class="info-list mb-4">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <p class="section-title text-primary"><i
                        class="fas fa-address-book me-1"></i>Kontak Klien</p>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-user"></i></div>
                <div class="flex-grow-1">
                    <div class="info-label">Nama</div>
                    <div class="info-value">
                        <?= esc($construction['full_name'] ?? '-') ?>
                    </div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-key"></i></div>
                <div class="flex-grow-1">
                    <div class="info-label">Id User</div>
                    <div class="info-value">
                        <?= esc($construction['user_id'] ?? '-') ?>
                    </div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="flex-grow-1">
                    <div class="info-label">Tanggal Pengajuan</div>
                    <div class="info-value">
                        <?= isset($construction['created_at']) ? date('d M Y', strtotime($construction['created_at'])) : '-' ?>
                    </div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                <div class="flex-grow-1">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <?= esc($construction['email'] ?? '-') ?>
                    </div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon text-success" style="background:#d1e7dd;"><i
                        class="fab fa-whatsapp"></i></div>
                <div
                    class="flex-grow-1 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                    <div>
                        <div class="info-label">Telepon / WhatsApp</div>
                        <div class="info-value">
                            <?= esc($construction['phone'] ?? '-') ?>
                        </div>
                    </div>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $construction['phone']) ?>"
                        target="_blank" class="btn btn-sm btn-success px-3 shadow-sm"
                        style="border-radius: 8px;"><i class="fab fa-whatsapp"></i>
                        Chat</a>
                </div>
            </div>
        </div>

        <!-- Info List: Detail Proyek -->
        <p class="section-title text-primary"><i
                class="fas fa-clipboard-list me-1"></i>Detail Proyek & Keuangan</p>
        <div class="info-list mb-4">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="info-item" style="border-bottom:none;">
                        <div class="info-icon text-warning" style="background:#fff3cd;">
                            <i class="fas fa-vector-square"></i>
                        </div>
                        <div>
                            <div class="info-label">Luas Tanah</div>
                            <div class="info-value">
                                <?= !empty($construction['land_area']) ? $construction['land_area'] . ' m²' : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="info-item" style="border-bottom:none;">
                        <div class="info-icon text-warning" style="background:#fff3cd;">
                            <i class="fas fa-home"></i>
                        </div>
                        <div>
                            <div class="info-label">Luas Bangunan</div>
                            <div class="info-value">
                                <?= !empty($construction['building_area']) ? $construction['building_area'] . ' m²' : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="info-item" style="border-bottom:none;">
                        <div class="info-icon text-success" style="background:#d1e7dd;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="info-label">Rencana Mulai</div>
                            <div class="info-value">
                                <?= !empty($construction['start_date']) ? date('d M Y', strtotime($construction['start_date'])) : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="info-item" style="border-bottom:none;">
                        <div class="info-icon text-success" style="background:#d1e7dd;">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                        <div>
                            <div class="info-label">Estimasi Waktu</div>
                            <div class="info-value">
                                <?= !empty($construction['week']) ? $construction['week'] . ' Minggu' : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="p-3 rounded"
                        style="background: #f8f9fa; border: 1px dashed #ced4da;">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 gap-1">
                            <span class="text-muted font-weight-bold text-uppercase"
                                style="font-size: 0.75rem;">Total Pembayaran
                                (Estimasi)</span>
                            <span class="font-weight-bold text-primary"
                                style="font-size: 1.1rem;">Rp
                                <?= number_format($construction['total_payment'] ?? 0, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted font-weight-bold text-uppercase"
                                style="font-size: 0.75rem;">Kode Voucher</span>
                            <span>
                                <?php if (!empty($construction['voucher_code'])): ?>
                                    <span class="badge badge-warning px-2 py-1"><i
                                            class="fas fa-ticket-alt mr-1"></i>
                                        <?= $construction['voucher_code'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info List: Lokasi -->
        <p class="section-title text-primary"><i
                class="fas fa-map-marked-alt me-1"></i>Lokasi Geografis & Foto</p>
        <div class="info-list mb-3">
            <div class="info-item">
                <div class="info-icon text-danger" style="background:#f8d7da;"><i
                        class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="info-label">Alamat Lengkap</div>
                    <div class="info-value">
                        <?= esc($construction['address'] ?? '-') ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($construction['latitude']) && !empty($construction['longitude'])): ?>
            <div class="map-container shadow-sm p-1 bg-white mb-3"
                style="border-radius: 14px; border: 1px solid #e9ecef;">
                <iframe
                    src="https://maps.google.com/maps?q=<?= esc($construction['latitude']) ?>,<?= esc($construction['longitude']) ?>&hl=id&z=15&output=embed"
                    width="100%" height="220" style="border:0; border-radius:10px;"
                    allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        <?php else: ?>
            <div class="text-center p-4 bg-light mb-3"
                style="border-radius: 12px; border: 1px dashed #ced4da;">
                <i class="fas fa-map-marked-alt text-muted mb-2"
                    style="font-size:2rem; opacity:0.5;"></i>
                <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:500;">
                    Koordinat peta belum disetel.</p>
            </div>
        <?php endif; ?>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <?php
            $hasPhotos = false;
            for ($i = 1; $i <= 5; $i++) {
                if (!empty($construction['gambar' . $i])) {
                    $hasPhotos = true;
                    $file = $construction['gambar' . $i];
                    $fileUrl = base_url('uploads/construction/' . $file);
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $isPdf = ($ext === 'pdf');
                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                    ?>
                    
                    <?php if ($isPdf): ?>
                        <!-- PDF Link -->
                        <a href="<?= $fileUrl ?>" target="_blank" class="d-flex flex-column align-items-center justify-content-center bg-light shadow-sm rounded position-relative text-decoration-none"
                           style="width: 75px; height: 75px; border: 1px solid #e4e9f0; background: #fff5f5 !important;" title="Lihat PDF">
                            <i class="fas fa-file-pdf text-danger" style="font-size:24px;"></i>
                            <span style="font-size:9px; font-weight:600; color:#dc3545;" class="mt-1">PDF</span>
                        </a>
                    <?php elseif ($isVideo): ?>
                        <!-- Video Player Container and Link -->
                        <div style="display:none;" id="video-construction-<?= $i ?>">
                            <div class="p-3 text-center" style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                <video src="<?= $fileUrl ?>" controls style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata" playsinline></video>
                                <div class="text-white mt-2 text-start px-2">
                                    <h6 class="mb-1 fw-bold text-white">Video Lokasi <?= $i ?></h6>
                                </div>
                            </div>
                        </div>
                        <a href="#video-construction-<?= $i ?>" class="glightbox d-flex flex-column align-items-center justify-content-center bg-light shadow-sm rounded position-relative text-decoration-none"
                           data-gallery="construction-gallery"
                           data-slide-class="glightbox-video-slide"
                           data-type="inline"
                           style="width: 75px; height: 75px; border: 1px solid #e4e9f0; background: #fff9f0 !important;">
                            <i class="fas fa-file-video text-warning" style="font-size:24px;"></i>
                            <span class="position-absolute" style="top:32%;left:50%;transform:translate(-50%,-50%);">
                                <i class="fas fa-play-circle text-warning bg-white rounded-circle" style="font-size:12px;"></i>
                            </span>
                            <span style="font-size:9px; font-weight:600; color:#e0a800;" class="mt-1">VIDEO</span>
                        </a>
                    <?php else: ?>
                        <!-- Image thumbnail with GLightbox -->
                        <a href="<?= $fileUrl ?>" class="glightbox shadow-sm rounded"
                           data-gallery="construction-gallery"
                           data-title="Foto Lokasi <?= $i ?>"
                           style="width: 75px; height: 75px; display: inline-block; overflow: hidden; border: 1px solid #e4e9f0;">
                            <img src="<?= $fileUrl ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto Lokasi <?= $i ?>">
                        </a>
                    <?php endif; ?>
                    
                    <?php
                }
            }
            if (!$hasPhotos): ?>
                <div class="text-center text-muted small w-100 py-3 bg-light rounded"
                    style="border: 1px dashed #ced4da;">Belum ada foto lokasi yang
                    diunggah.
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
