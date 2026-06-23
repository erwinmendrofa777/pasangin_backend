<div class="row g-4 mt-1">

    <!-- ========== RIWAYAT SURVEY ========== -->
    <div class="col-md-12 d-flex flex-column mt-0">
        <div class="card survey-card-history h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white" style="font-weight:700; font-size:0.9rem;">
                    <i class="fas fa-history mr-2"></i>Riwayat Survey
                </h6>
            </div>
            <div class="card-body p-2" style="background:#f8f9fa; overflow-y:auto; max-height:520px;">
                <?php if (empty($survey_list)): ?>
                    <div class="empty-survey">
                        <div class="empty-survey-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h6 class="font-weight-bold text-dark mb-1">Belum Ada Survey</h6>
                        <p class="text-muted mb-0" style="font-size:0.83rem;">
                            Laporan survey akan tampil di sini setelah diunggah pada tahap desain.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($survey_list as $srv): 
                            $files = [];
                            if (!empty($srv['survey_file'])) {
                                $decoded = json_decode($srv['survey_file'], true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $files = $decoded;
                                } else {
                                    $files = [$srv['survey_file']];
                                }
                            }
                            ?>
                            <div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
                                <div class="card-body p-3 p-md-4">
                                    <div class="row align-items-center g-3">
 
                                        <!-- Info Kiri: Ikon Survey + Judul + Catatan -->
                                        <div class="col-12 col-md-7 d-flex gap-3">
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center"
                                                style="width:48px; height:48px; background:linear-gradient(135deg, #e0f2fe, #bae6fd); border-radius:10px; color:#0284c7; font-size:1.25rem; flex-shrink:0; border: 1px solid #bae6fd;">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </div>
                                            <div style="min-width:0;">
                                                <h6 class="font-weight-bold text-dark text-wrap mb-1"
                                                    style="font-size:0.95rem; line-height:1.3;">
                                                    <?= esc($srv['survey_title']) ?>
                                                </h6>
                                                <?php if (!empty($srv['survey_notes'])): ?>
                                                    <p class="text-muted mb-0 text-wrap" style="font-size:0.8rem; line-height:1.4;">
                                                        <?= esc($srv['survey_notes']) ?>
                                                    </p>
                                                <?php else: ?>
                                                    <span class="badge badge-light border text-secondary"
                                                        style="font-size:0.72rem;">Tidak ada catatan</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
 
                                        <!-- Info Tengah: Tanggal & PJ -->
                                        <div class="col-12 col-md-5 px-md-3 survey-divider-x">
                                            <div class="d-flex flex-row flex-md-column gap-3 gap-md-1 text-muted"
                                                style="font-size:0.85rem;">
                                                <div class="text-dark fw-bold"
                                                    title="Diunggah oleh: <?= esc($srv['admin_name'] ?? 'Sistem') ?>">
                                                    <i class="fas fa-user-tie mr-2 text-primary"></i>
                                                    <?= esc(strlen($srv['admin_name'] ?? 'Sistem') > 15 ? substr($srv['admin_name'] ?? 'Sistem', 0, 15) . '...' : ($srv['admin_name'] ?? 'Sistem')) ?>
                                                </div>
                                                <div>
                                                    <i class="fas fa-calendar-alt mr-2"></i><?= date('d M Y', strtotime($srv['created_at'])) ?>
                                                </div>
                                                <div>
                                                    <i class="fas fa-clock mr-2"></i><?= date('H:i', strtotime($srv['created_at'])) ?> WIB
                                                </div>
                                            </div>
                                        </div>
 
                                    </div>

                                    <!-- Lampiran Files (Grid List) -->
                                    <?php if (!empty($files)): ?>
                                        <div class="survey-attachments-box">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="text-muted fw-bold" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">
                                                    <i class="fas fa-paperclip mr-1 text-primary"></i> Lampiran (<?= count($files) ?>)
                                                </span>
                                            </div>
                                            <div class="survey-file-grid">
                                                <?php foreach ($files as $idx => $f): 
                                                    $fileExt = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                                                    $fUrl = base_url('uploads/construction/survey/' . $f);
                                                    $isPdf = ($fileExt === 'pdf');
                                                    $isVideo = in_array($fileExt, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                                                    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                                    $shortName = strlen($f) > 22 ? substr($f, 0, 12) . '...' . substr($f, -6) : $f;
                                                ?>
                                                    <!-- File Item Card -->
                                                    <div class="survey-file-card">
                                                        <!-- Thumbnail / Icon -->
                                                        <?php if ($isImage): ?>
                                                            <div class="file-icon-wrapper file-icon-image cursor-pointer" 
                                                                 onclick="$('#glb-survey-img-<?= $srv['id'] ?>-<?= $idx ?>').click();">
                                                                <img src="<?= $fUrl ?>">
                                                            </div>
                                                        <?php elseif ($isPdf): ?>
                                                            <div class="file-icon-wrapper file-icon-pdf">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </div>
                                                        <?php elseif ($isVideo): ?>
                                                            <div class="file-icon-wrapper file-icon-video cursor-pointer" 
                                                                 onclick="$('#glb-survey-vid-<?= $srv['id'] ?>-<?= $idx ?>').click();">
                                                                <i class="fas fa-file-video"></i>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="file-icon-wrapper file-icon-other">
                                                                <i class="fas fa-file-alt"></i>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- File Info -->
                                                        <div class="file-info">
                                                            <div class="file-name" title="<?= esc($f) ?>"><?= esc($shortName) ?></div>
                                                            <div class="file-meta"><?= esc($fileExt ?: 'FILE') ?></div>
                                                        </div>

                                                        <!-- File Actions -->
                                                        <div class="file-actions">
                                                            <?php if ($isImage): ?>
                                                                <a href="<?= $fUrl ?>" id="glb-survey-img-<?= $srv['id'] ?>-<?= $idx ?>" class="glightbox btn btn-outline-info btn-file-action" 
                                                                   data-gallery="survey-gallery-<?= $srv['id'] ?>"
                                                                   data-title="<?= esc($srv['survey_title']) ?>"
                                                                   data-description="Berkas: <?= esc($f) ?> | Diunggah oleh: <?= esc($srv['admin_name'] ?? 'Sistem') ?>"
                                                                   title="Lihat Gambar">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            <?php elseif ($isPdf): ?>
                                                                <a href="<?= $fUrl ?>" target="_blank" class="btn btn-outline-danger btn-file-action"
                                                                   title="Buka PDF">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                            <?php elseif ($isVideo): ?>
                                                                <div style="display:none;" id="video-survey-<?= $srv['id'] ?>-<?= $idx ?>">
                                                                    <div class="p-3 text-center" style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                                        <video src="<?= $fUrl ?>" controls style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata" playsinline></video>
                                                                        <div class="text-white mt-2 text-start px-2">
                                                                            <h6 class="mb-1 fw-bold text-white"><?= esc($srv['survey_title']) ?></h6>
                                                                            <small class="text-muted">Berkas: <?= esc($f) ?> | Diunggah oleh: <?= esc($srv['admin_name'] ?? 'Sistem') ?></small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <a href="#video-survey-<?= $srv['id'] ?>-<?= $idx ?>" class="glightbox btn btn-outline-warning btn-file-action" 
                                                                   id="glb-survey-vid-<?= $srv['id'] ?>-<?= $idx ?>"
                                                                   data-gallery="survey-gallery-<?= $srv['id'] ?>"
                                                                   data-slide-class="glightbox-video-slide"
                                                                   data-type="inline"
                                                                   title="Putar Video">
                                                                    <i class="fas fa-play"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                            
                                                            <a href="<?= $fUrl ?>" download class="btn btn-outline-secondary btn-file-action" 
                                                               title="Unduh File">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Feedback Klien Full Width -->
                                    <?php if (!empty($srv['comment'])): ?>
                                        <div class="mt-3 p-3 bg-light border" style="border-radius:8px;">
                                            <strong class="text-primary" style="font-size:0.85rem;">
                                                <i class="fas fa-comment-dots mr-2"></i>Feedback Klien:
                                            </strong>
                                            <p class="font-italic text-muted mb-0 mt-2 text-wrap"
                                                style="font-size:0.85rem; line-height:1.5;">
                                                "<?= esc($srv['comment']) ?>"
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>