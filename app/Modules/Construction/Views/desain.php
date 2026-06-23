<div class="row mt-1 g-3">

    <!-- ══════ GALERI DESAIN ══════ -->
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="font-weight-bold mb-0" style="font-size:0.9rem;">
                    <i class="fas fa-images text-primary mr-1"></i> Galeri Desain
                </h6>
                <small class="text-muted"><?= count($design_list ?? []) ?> file tersimpan</small>
            </div>
        </div>

        <?php if (empty($design_list)): ?>
            <div class="desain-empty text-muted py-5">
                <i class="fas fa-drafting-compass mb-3" style="font-size:2.5rem; opacity:0.2;"></i>
                <p class="mb-0" style="font-size:0.85rem;">Belum ada file desain. Hasil desain akan tampil di sini setelah disetujui pada tahap desain.</p>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($design_list as $d):
                    $ext = strtolower(pathinfo($d['file'] ?? '', PATHINFO_EXTENSION));
                    $isPdf = ($ext === 'pdf');
                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                    $fileUrl = !empty($d['design_requests_id'])
                        ? base_url('uploads/design_results/' . $d['file'])
                        : base_url('uploads/construction/designs/' . $d['file']);
                    ?>
                    <div class="col-6 col-md-4 col-xl-4">
                        <div class="desain-gallery-card">

                            <!-- Thumbnail / PDF / Video -->
                            <?php if ($isPdf): ?>
                                <div class="desain-pdf-placeholder" style="background:#fff5f5 !important;">
                                    <i class="fas fa-file-pdf text-danger" style="font-size:2.8rem;"></i>
                                </div>
                            <?php elseif ($isVideo): ?>
                                <div class="desain-pdf-placeholder position-relative"
                                    style="background:#fff9f0 !important; cursor:pointer;"
                                    onclick="$('#glb-video-<?= $d['id'] ?>').click();">
                                    <i class="fas fa-file-video text-warning" style="font-size:2.8rem;"></i>
                                    <span class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);">
                                        <i class="fas fa-play-circle text-warning bg-white rounded-circle"
                                            style="font-size:16px;"></i>
                                    </span>
                                </div>
                            <?php else: ?>
                                <img src="<?= $fileUrl ?>" class="desain-thumb" alt="<?= esc($d['title'] ?? '') ?>"
                                    style="cursor:pointer;" onclick="$('#glb-img-<?= $d['id'] ?>').click();">
                            <?php endif; ?>

                            <!-- Hover overlay -->
                            <div class="desain-overlay">
                                <?php if ($isPdf): ?>
                                    <a href="<?= $fileUrl ?>" target="_blank" class="desain-overlay-btn" title="Lihat PDF">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    </a>
                                <?php elseif ($isVideo): ?>
                                    <!-- Hidden video player container for native playbacks -->
                                    <div style="display:none;" id="video-design-<?= $d['id'] ?>">
                                        <div class="p-3 text-center"
                                            style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                            <video src="<?= $fileUrl ?>" controls
                                                style="width:100%; max-height:60vh; border-radius:8px; display:block;"
                                                preload="metadata" playsinline></video>
                                            <div class="text-white mt-2 text-start px-2">
                                                <h6 class="mb-1 fw-bold text-white"><?= esc($d['title'] ?? 'Tanpa Judul') ?></h6>
                                                <small class="text-muted">Diunggah oleh:
                                                    <?= esc($d['admin_name'] ?? 'Sistem') ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#video-design-<?= $d['id'] ?>" class="glightbox desain-overlay-btn"
                                        id="glb-video-<?= $d['id'] ?>" data-gallery="design-gallery"
                                        data-slide-class="glightbox-video-slide" data-type="inline">
                                        <i class="fas fa-play text-warning"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= $fileUrl ?>" class="glightbox desain-overlay-btn" id="glb-img-<?= $d['id'] ?>"
                                        data-gallery="design-gallery" data-title="<?= esc($d['title'] ?? 'Tanpa Judul') ?>"
                                        data-description="Diunggah oleh: <?= esc($d['admin_name'] ?? 'Sistem') ?>">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Info -->
                            <div class="desain-meta">
                                <div class="font-weight-bold text-truncate" style="font-size:0.82rem; color:#34395e;"
                                    title="<?= esc($d['title'] ?? '') ?>">
                                    <?= esc($d['title'] ?? 'Tanpa Judul') ?>
                                </div>

                                <div class="mt-1 d-flex align-items-center text-muted" style="font-size:0.75rem;">
                                    <i class="fas fa-user-tie text-primary mr-1" style="font-size:0.7rem;"></i>
                                    <span class="text-truncate" title="<?= esc($d['admin_name'] ?? 'Sistem') ?>">
                                        <?= esc($d['admin_name'] ?? 'Sistem') ?>
                                    </span>
                                </div>

                                <?php if (!empty($d['comment'])): ?>
                                    <div class="mt-1 p-2 rounded d-flex align-items-start"
                                        style="background:#f8f9fa; border:1px solid #e9ecef; font-size:0.75rem;"
                                        title="<?= esc($d['comment']) ?>">
                                        <i class="fas fa-comment-dots text-primary mr-1 mt-1 flex-shrink-0"></i>
                                        <div style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; text-overflow:ellipsis;"
                                            class="text-muted font-italic">
                                            "<?= esc($d['comment']) ?>"
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <small class="text-muted" style="font-size:0.7rem;">
                                        <i
                                            class="fas fa-calendar-alt mr-1"></i><?= date('d/m/y', strtotime($d['created_at'])) ?>
                                    </small>
                                </div>
                            </div>

                            <!-- Tombol aksi mobile (tampil di HP, hidden di desktop) -->
                            <div class="desain-mobile-actions">
                                <?php if ($isPdf): ?>
                                    <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-outline-danger flex-grow-1"
                                        style="border-radius:8px; font-size:0.78rem;">
                                        <i class="fas fa-file-pdf mr-1"></i>Lihat PDF
                                    </a>
                                <?php elseif ($isVideo): ?>
                                    <a href="javascript:void(0);" onclick="$('#glb-video-<?= $d['id'] ?>').click();"
                                        class="btn btn-sm btn-outline-warning flex-grow-1"
                                        style="border-radius:8px; font-size:0.78rem;">
                                        <i class="fas fa-play mr-1"></i>Putar
                                    </a>
                                <?php else: ?>
                                    <a href="javascript:void(0);" onclick="$('#glb-img-<?= $d['id'] ?>').click();"
                                        class="btn btn-sm btn-outline-info flex-grow-1"
                                        style="border-radius:8px; font-size:0.78rem;">
                                        <i class="fas fa-eye mr-1"></i>Lihat
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>