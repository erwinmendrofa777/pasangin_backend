<?php
// Resolving conStatus and conStatusMeta is already done in App\Modules\Construction\Views\detail.php lines 737-760
// and passed as view data! Let's default them if somehow not set.
$conStatus = $construction['status'] ?? 'PENDING';
$currentConMeta = $conStatusMeta[$conStatus] ?? ['color' => 'dark', 'icon' => 'fas fa-circle', 'label' => $conStatus, 'desc' => 'Status tidak diketahui'];
?>

<div class="row mt-4">
    <!-- 1. Header Card (Stand-alone) -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm profile-card mb-0">
            <!-- Hero Banner -->
            <div class="profile-hero bg-primary" style="padding: 20px 28px;">
                <div class="d-flex justify-content-between align-items-center" style="z-index:1; position: relative;">
                    <h5 class="text-white mb-0 ms-1 fw-bold" style="font-size:1.25rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                        <i class="fas fa-hard-hat me-2 opacity-75"></i>Proyek Konstruksi #<?= esc($construction['id']) ?>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-pill status-<?= strtolower($currentConMeta['color']) ?>" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
                            <span class="dot"></span><?= $currentConMeta['label'] ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== LEFT COLUMN CARD: Informasi Klien + Spesifikasi & Keuangan ===== -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="card-body p-4">
                <p class="section-title mt-0"><i class="fas fa-user-circle me-1"></i>Informasi Klien</p>
                <div class="info-list mb-4">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-id-card"></i></div>
                        <div>
                            <div class="info-label">User ID (Akun)</div>
                            <div class="info-value"><?= esc($construction['user_id'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-user"></i></div>
                        <div>
                            <div class="info-label">Nama Klien</div>
                            <div class="info-value"><?= esc($construction['full_name'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= esc($construction['email'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-phone"></i></div>
                        <div class="flex-grow-1 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                            <div>
                                <div class="info-label">Nomor Telepon</div>
                                <div class="info-value"><?= esc($construction['phone'] ?? '-') ?></div>
                            </div>
                            <?php if (!empty($construction['phone'])): ?>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $construction['phone']) ?>"
                                    target="_blank" class="btn btn-sm btn-success px-3 shadow-sm"
                                    style="border-radius: 8px;"><i class="fab fa-whatsapp"></i> Chat</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <div class="info-label">Tanggal Pengajuan</div>
                            <div class="info-value"><?= isset($construction['created_at']) ? date('d M Y, H:i', strtotime($construction['created_at'])) : '-' ?></div>
                        </div>
                    </div>
                </div>

                <p class="section-title mt-4"><i class="fas fa-clipboard-list me-1"></i>Detail Proyek & Keuangan</p>
                <div class="info-list mb-2">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-vector-square"></i></div>
                        <div>
                            <div class="info-label">Luas Lahan &amp; Bangunan</div>
                            <div class="info-value">
                                Tanah: <?= !empty($construction['land_area']) ? esc($construction['land_area']) . ' m²' : '-' ?> &nbsp;|&nbsp;
                                Bangunan: <?= !empty($construction['building_area']) ? esc($construction['building_area']) . ' m²' : '-' ?>
                            </div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon text-success" style="background:#e6f9ed;"><i class="fas fa-money-bill-wave"></i></div>
                        <div>
                            <div class="info-label text-success">Total Pembayaran (Estimasi)</div>
                            <div class="info-value text-success fw-bold">Rp <?= number_format($construction['total_payment'] ?? 0, 0, ',', '.') ?></div>
                        </div>
                    </div>
                    <?php if (!empty($construction['voucher_code'])): ?>
                        <div class="info-item">
                            <div class="info-icon text-success" style="background:#e6f9ed;"><i class="fas fa-ticket-alt"></i></div>
                            <div>
                                <div class="info-label text-success">Kode Voucher</div>
                                <div class="info-value text-success fw-bold"><?= esc($construction['voucher_code']) ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RIGHT COLUMN CARD: Lokasi + Peta + Jadwal + Foto/Video + Update Status ===== -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100" style="border: none; border-radius: 16px; overflow: hidden;">
            <div class="card-body p-4">
                <p class="section-title mt-0"><i class="fas fa-map-marker-alt me-1"></i>Lokasi Proyek</p>
                <div class="info-list mb-3">
                    <div class="info-item border-0 pb-0">
                        <div class="info-icon"><i class="fas fa-home"></i></div>
                        <div>
                            <div class="info-label">Alamat Lengkap</div>
                            <div class="info-value"><?= esc($construction['address'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed -->
                <div class="mt-4">
                    <p class="section-title mb-2"><i class="fas fa-map-marked-alt me-1"></i>Peta Lokasi Geografis</p>
                    <?php if (!empty($construction['latitude']) && !empty($construction['longitude'])): ?>
                        <div class="map-container shadow-sm p-1 bg-white"
                            style="border-radius: 14px; border: 1px solid #e9ecef;">
                            <iframe
                                src="https://maps.google.com/maps?q=<?= esc($construction['latitude']) ?>,<?= esc($construction['longitude']) ?>&hl=id&z=15&output=embed"
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
                                    <?= !empty($construction['start_date']) ? date('d M Y', strtotime($construction['start_date'])) : '<span class="text-muted fw-normal fst-italic" style="font-size:0.85rem;">Belum disetel</span>' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border border-light" style="background-color: #f8fafc !important;">
                            <div class="text-muted mb-1" style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Estimasi Waktu</div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-stopwatch text-success" style="font-size: 1.1rem; opacity: 0.8;"></i>
                                <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                                    <?= !empty($construction['week']) ? esc($construction['week']) . ' Minggu' : '<span class="text-muted fw-normal fst-italic" style="font-size:0.85rem;">Belum disetel</span>' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Foto & Video Lokasi -->
                <p class="section-title mt-4"><i class="fas fa-images me-1"></i>Foto & Video Lokasi</p>
                <div class="d-flex flex-wrap gap-2 mb-4">
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
                            ?>
                            <?php if ($isPdf): ?>
                                <a href="<?= $fileUrl ?>" target="_blank" class="d-flex flex-column align-items-center justify-content-center bg-light shadow-sm rounded position-relative text-decoration-none"
                                   style="width: 75px; height: 75px; border: 1px solid #e4e9f0; background: #fff5f5 !important;" title="Lihat PDF">
                                    <i class="fas fa-file-pdf text-danger" style="font-size:24px;"></i>
                                    <span style="font-size:9px; font-weight:600; color:#dc3545;" class="mt-1">PDF</span>
                                </a>
                            <?php elseif ($isVideo): ?>
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
                            style="border: 1px dashed #ced4da;">Belum ada foto lokasi yang diunggah.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Update Status Action -->
                <?php if (can('construction')): ?>
                    <div class="p-3 rounded-3 border" style="background-color: #f8fafc; border-color: #e2e8f0 !important; margin-top: 24px;">
                        <p class="section-title mt-0 mb-2" style="border: none; padding-left: 0; font-size: 0.75rem;"><i class="fas fa-sync-alt me-1 text-primary"></i>Update Status Proyek</p>
                        <form action="<?= base_url('admin/construction/update-status') ?>"
                            method="post" class="d-flex gap-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $construction['id'] ?>">
                            <select name="status" class="form-select form-control fw-semibold" style="border-radius:10px; border-color: #cbd5e1; font-size: 0.9rem; padding: 10px 14px; height: auto;">
                                <?php foreach ($conStatusMeta as $key => $act): ?>
                                    <option value="<?= $key ?>" <?= $conStatus == $key ? 'selected' : '' ?>><?= $act['label'] ?></option>
                                <?php endforeach; ?>
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
