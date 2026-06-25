<div class="row mt-1 g-4">

    <!-- ══════ GALERI DESAIN ══════ -->
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h6 class="font-weight-bold mb-1 text-dark" style="font-size:1.05rem; letter-spacing: 0.3px;">
                    <i class="fas fa-images text-primary mr-2"></i> Galeri Desain
                </h6>
                <small class="text-muted" style="font-size: 0.8rem; font-weight: 500;">
                    <i class="fas fa-file-alt mr-1"></i><?= count($design_list ?? []) ?> file terunggah
                </small>
            </div>
        </div>

        <?php if (empty($design_list)): ?>
            <div class="desain-empty text-muted py-5">
                <div class="desain-empty-icon mb-3">
                    <i class="fas fa-drafting-compass"></i>
                </div>
                <p class="mb-1 font-weight-bold text-dark" style="font-size:0.95rem;">Belum Ada Berkas Desain</p>
                <p class="mb-0 text-muted text-center px-4" style="font-size:0.82rem; max-width: 400px; line-height: 1.5;">Hasil desain konstruksi yang telah disetujui oleh admin akan ditampilkan secara otomatis di galeri ini.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($design_list as $d):
                    $ext = strtolower(pathinfo($d['file'] ?? '', PATHINFO_EXTENSION));
                    $isPdf = ($ext === 'pdf');
                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                    $is3D = in_array($ext, ['glb', 'gltf', 'dwg', 'dxf', 'obj', 'fbx', 'dae', '3ds', 'stl']);
                    $isZip = in_array($ext, ['zip', 'rar', '7z', 'tar', 'gz']);
                    
                    $fileUrl = !empty($d['design_requests_id'])
                        ? base_url('uploads/design_results/' . $d['file'])
                        : base_url('uploads/construction/designs/' . $d['file']);

                    // Tentukan style badge jenis file
                    if ($isImage) {
                        $badgeText = 'Gambar';
                        $badgeClass = 'desain-badge-image';
                        $badgeIcon = 'fa-image';
                    } elseif ($isPdf) {
                        $badgeText = 'Dokumen PDF';
                        $badgeClass = 'desain-badge-pdf';
                        $badgeIcon = 'fa-file-pdf';
                    } elseif ($isVideo) {
                        $badgeText = 'Video';
                        $badgeClass = 'desain-badge-video';
                        $badgeIcon = 'fa-play-circle';
                    } elseif ($is3D) {
                        $badgeText = 'Model 3D';
                        $badgeClass = 'desain-badge-3d';
                        $badgeIcon = 'fa-cubes';
                    } elseif ($isZip) {
                        $badgeText = 'Arsip ZIP';
                        $badgeClass = 'desain-badge-zip';
                        $badgeIcon = 'fa-file-archive';
                    } else {
                        $badgeText = strtoupper($ext) . ' File';
                        $badgeClass = 'desain-badge-generic';
                        $badgeIcon = 'fa-file-alt';
                    }
                    ?>
                    <div class="col-6 col-md-4 col-xl-4">
                        <div class="desain-gallery-card">
                            
                            <!-- Wrapper Media -->
                            <div class="desain-media-wrapper position-relative overflow-hidden">
                                
                                <!-- File Type Badge -->
                                <span class="desain-file-badge <?= $badgeClass ?>">
                                    <i class="fas <?= $badgeIcon ?> mr-1"></i><?= $badgeText ?>
                                </span>

                                <?php if ($isImage): ?>
                                    <img src="<?= $fileUrl ?>" class="desain-thumb" alt="<?= esc($d['title'] ?? '') ?>"
                                        style="cursor:pointer;" onclick="$('#glb-img-<?= $d['id'] ?>').click();">
                                <?php elseif ($isPdf): ?>
                                    <div class="desain-placeholder-wrapper" style="cursor:pointer;" onclick="window.open('<?= $fileUrl ?>', '_blank');">
                                        <div class="desain-pdf-placeholder">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                        </div>
                                    </div>
                                <?php elseif ($isVideo): ?>
                                    <div class="desain-placeholder-wrapper" style="cursor:pointer;" onclick="$('#glb-video-<?= $d['id'] ?>').click();">
                                        <div class="desain-video-placeholder">
                                            <i class="fas fa-file-video text-warning"></i>
                                            <span class="play-btn-overlay">
                                                <i class="fas fa-play text-warning"></i>
                                            </span>
                                        </div>
                                    </div>
                                <?php elseif ($is3D): ?>
                                    <a href="<?= $fileUrl ?>" download class="desain-placeholder-wrapper d-block text-decoration-none">
                                        <div class="desain-3d-placeholder">
                                            <i class="fas fa-cubes text-info"></i>
                                        </div>
                                    </a>
                                <?php elseif ($isZip): ?>
                                    <a href="<?= $fileUrl ?>" download class="desain-placeholder-wrapper d-block text-decoration-none">
                                        <div class="desain-zip-placeholder">
                                            <i class="fas fa-file-archive text-purple"></i>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= $fileUrl ?>" download class="desain-placeholder-wrapper d-block text-decoration-none">
                                        <div class="desain-generic-placeholder">
                                            <i class="fas fa-file-alt text-secondary"></i>
                                        </div>
                                    </a>
                                <?php endif; ?>

                                <!-- Hover overlay (Desktop Only) -->
                                <div class="desain-overlay">
                                    <?php if ($isImage): ?>
                                        <a href="<?= $fileUrl ?>" class="glightbox desain-overlay-btn" id="glb-img-<?= $d['id'] ?>"
                                            data-gallery="design-gallery" data-title="<?= esc($d['title'] ?? 'Tanpa Judul') ?>"
                                            data-description="Diunggah oleh: <?= esc($d['admin_name'] ?? 'Sistem') ?>" title="Lihat Gambar">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    <?php elseif ($isPdf): ?>
                                        <a href="<?= $fileUrl ?>" target="_blank" class="desain-overlay-btn" title="Buka PDF">
                                            <i class="fas fa-external-link-alt text-danger"></i>
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
                                            data-slide-class="glightbox-video-slide" data-type="inline" title="Putar Video">
                                            <i class="fas fa-play text-warning"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= $fileUrl ?>" download class="desain-overlay-btn" title="Unduh Berkas">
                                            <i class="fas fa-download text-success"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <!-- Info / Metadata -->
                            <div class="desain-meta">
                                <div class="font-weight-bold text-truncate desain-card-title" title="<?= esc($d['title'] ?? '') ?>">
                                    <?= esc($d['title'] ?? 'Tanpa Judul') ?>
                                </div>

                                <div class="mt-1.5 d-flex align-items-center text-muted desain-author" style="font-size:0.75rem;">
                                    <i class="fas fa-user-circle mr-1.5 text-secondary"></i>
                                    <span class="text-truncate" title="<?= esc($d['admin_name'] ?? 'Sistem') ?>">
                                        <?= esc($d['admin_name'] ?? 'Sistem') ?>
                                    </span>
                                </div>

                                <?php if (!empty($d['comment'])): ?>
                                    <div class="desain-comment-bubble mt-2" title="<?= esc($d['comment']) ?>">
                                        <i class="fas fa-comment-dots text-primary mr-1.5 mt-0.5 flex-shrink-0" style="font-size:0.75rem;"></i>
                                        <div class="desain-comment-text">
                                            "<?= esc($d['comment']) ?>"
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center desain-footer">
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt mr-1"></i><?= date('d M Y', strtotime($d['created_at'])) ?>
                                    </small>
                                </div>
                            </div>

                            <!-- Tombol aksi mobile (tampil di HP, hidden di desktop) -->
                            <div class="desain-mobile-actions">
                                <?php if ($isImage): ?>
                                    <a href="javascript:void(0);" onclick="$('#glb-img-<?= $d['id'] ?>').click();"
                                        class="btn btn-sm btn-outline-primary flex-grow-1">
                                        <i class="fas fa-eye mr-1"></i>Lihat
                                    </a>
                                <?php elseif ($isPdf): ?>
                                    <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-outline-danger flex-grow-1">
                                        <i class="fas fa-external-link-alt mr-1"></i>Buka PDF
                                    </a>
                                <?php elseif ($isVideo): ?>
                                    <a href="javascript:void(0);" onclick="$('#glb-video-<?= $d['id'] ?>').click();"
                                        class="btn btn-sm btn-outline-warning flex-grow-1">
                                        <i class="fas fa-play mr-1"></i>Putar
                                    </a>
                                <?php else: ?>
                                    <a href="<?= $fileUrl ?>" download class="btn btn-sm btn-outline-success flex-grow-1">
                                        <i class="fas fa-download mr-1"></i>Unduh
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