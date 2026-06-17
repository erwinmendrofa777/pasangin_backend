<div class="mt-4">
    <?php
    // Kelompokkan design_results berdasarkan design_targets_id, urutkan dari revisi terbaru
    $designsByTarget = [];
    foreach ($design_results ?? [] as $d) {
        $tid = $d['design_targets_id'] ?? 0;
        $designsByTarget[$tid][] = $d;
    }
    // Urutkan setiap group: revisi terbesar (terbaru) di atas
    foreach ($designsByTarget as &$grp) {
        usort($grp, fn($a, $b) => ($b['revision_number'] ?? 1) <=> ($a['revision_number'] ?? 1));
    }
    unset($grp);
    ?>

    <?php if (empty($targets)): ?>
        <div class="text-center text-muted py-5">
            <i class="fas fa-tasks fa-3x mb-3" style="opacity:.2;"></i>
            <p>Belum ada target. Buat target terlebih dahulu di tab <strong>Target</strong>.</p>
        </div>
    <?php else: ?>
        <?php foreach ($targets as $t):
            $statusClr = 'secondary';
            if ($t['status'] === 'DONE')
                $statusClr = 'success';
            elseif ($t['status'] === 'ON PROGRESS')
                $statusClr = 'primary';
            elseif ($t['status'] === 'PENDING')
                $statusClr = 'warning';

            $targetDesigns = $designsByTarget[$t['id']] ?? [];
            ?>
            <div class="card border mb-4" style="border-radius: 10px; overflow: hidden;">

                <!-- Header Target -->
                <div class="card-header d-flex align-items-center justify-content-between py-2 px-3"
                    style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="font-size: 14px;">
                            <i class="fas fa-layer-group text-primary me-1"></i>
                            <?= esc($t['task_name']) ?>
                        </span>
                        <span class="badge badge-<?= $statusClr ?> ms-2 px-2"><?= $t['status'] ?></span>
                    </div>
                    <small class="text-muted">
                        HARI KE <?= $t['start_week'] ?> &ndash; HARI KE <?= $t['end_week'] ?>
                        <?php if (!empty($t['keterangan'])): ?>
                            &nbsp;<i class="fas fa-sticky-note"></i> <?= esc($t['keterangan']) ?>
                        <?php endif; ?>
                    </small>
                </div>

                <!-- Isi: Timeline Revisi -->
                <div class="card-body p-3">
                    <?php if (empty($targetDesigns)): ?>
                        <div class="text-center text-muted py-3" style="font-size: 13px;">
                            <i class="fas fa-image me-1" style="opacity:.3;"></i> Belum ada file desain untuk target ini.
                        </div>
                    <?php else: ?>
                        <div class="rev-timeline">
                            <?php foreach ($targetDesigns as $d):
                                $revSt = $d['status'] ?? 'PENDING';
                                $dotCls = strtolower($revSt);
                                $boxCls = ($revSt === 'APPROVED') ? 'approved' : (($revSt === 'REJECTED') ? 'rejected' : '');
                                $ext = strtolower(pathinfo($d['file'], PATHINFO_EXTENSION));
                                $isPdf = ($ext === 'pdf');
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                                $revNum = $d['revision_number'] ?? 1;
                                ?>
                                <div class="rev-item">
                                    <div class="rev-dot <?= $dotCls ?>"></div>
                                    <div class="rev-box <?= $boxCls ?>">
                                        <!-- Baris atas: label revisi + badge status + tanggal (dan tombol aksi Desktop) -->
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if ($revSt === 'APPROVED'): ?>
                                                    <span style="font-size:15px;">✅</span>
                                                <?php endif; ?>
                                                <span class="fw-bold" style="font-size:13px;">
                                                    Rev. <?= $revNum ?>
                                                </span>
                                                <?php
                                                $badgeCls = 'badge-warning';
                                                if ($revSt === 'APPROVED')
                                                    $badgeCls = 'badge-success';
                                                elseif ($revSt === 'REJECTED')
                                                    $badgeCls = 'badge-danger';
                                                ?>
                                                <span class="badge <?= $badgeCls ?>" style="font-size:11px;"><?= $revSt ?></span>
                                            </div>

                                            <div class="d-flex align-items-center gap-3">
                                                <small class="text-muted text-end"><?= date('d M Y, H:i', strtotime($d['created_at'])) ?></small>
                                                
                                                <?php if ($revSt === 'PENDING'): ?>
                                                    <!-- Tombol Aksi Desktop -->
                                                    <div class="d-none d-md-flex gap-1">
                                                        <a href="<?= base_url('admin/design/approve-design/' . $d['id']) ?>"
                                                            class="btn btn-sm btn-success ladda-button" data-style="zoom-in"
                                                            onclick="return confirm('Approve revisi ini? Revisi PENDING lain akan otomatis di-reject.');"
                                                            title="Approve Revisi">
                                                            <span class="ladda-label"><i class="fas fa-check me-1"></i>Approve</span>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalReject<?= $d['id'] ?>" title="Reject Revisi">
                                                            <i class="fas fa-times me-1"></i>Reject
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Preview file + nama -->
                                        <div class="d-flex align-items-center gap-3">
                                            <?php $fileUrl = base_url('uploads/design_results/' . $d['file']); ?>
                                            <?php if ($isPdf): ?>
                                                <div class="d-flex align-items-center justify-content-center bg-light flex-shrink-0"
                                                    style="width:72px;height:72px;border-radius:8px;background:#fff5f5 !important;">
                                                    <i class="fas fa-file-pdf text-danger" style="font-size:32px;"></i>
                                                </div>
                                            <?php elseif ($isVideo): ?>
                                                <div class="d-flex align-items-center justify-content-center bg-light flex-shrink-0 position-relative"
                                                    style="width:72px;height:72px;border-radius:8px;background:#fff9f0 !important;">
                                                    <i class="fas fa-file-video text-warning" style="font-size:32px;"></i>
                                                    <span class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);">
                                                        <i class="fas fa-play-circle text-warning bg-white rounded-circle" style="font-size:16px;"></i>
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <img src="<?= $fileUrl ?>"
                                                    style="width:72px;height:72px;object-fit:cover;border-radius:8px;flex-shrink:0;"
                                                    alt="<?= esc($d['design_name']) ?>">
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-semibold" style="font-size:13px;"><?= esc($d['design_name']) ?></div>
                                                <div class="mt-1 d-flex gap-1">
                                                    <?php if ($isPdf): ?>
                                                        <a href="<?= $fileUrl ?>" target="_blank"
                                                            class="btn btn-xs btn-outline-danger" title="Lihat PDF">
                                                            <i class="fas fa-file-pdf"></i> Lihat PDF
                                                        </a>
                                                    <?php elseif ($isVideo): ?>
                                                        <!-- Hidden video player container for native playbacks -->
                                                        <div style="display:none;" id="video-progress-<?= $d['id'] ?>">
                                                            <div class="p-3 text-center" style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                                <video src="<?= $fileUrl ?>" controls style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata" playsinline></video>
                                                                <div class="text-white mt-2 text-start px-2">
                                                                    <h6 class="mb-1 fw-bold text-white"><?= esc($d['design_name']) ?></h6>
                                                                    <small class="text-muted">Revisi: Rev. <?= $d['revision_number'] ?? 1 ?></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="#video-progress-<?= $d['id'] ?>" class="glightbox btn btn-xs btn-outline-warning" 
                                                           data-gallery="progress-gallery"
                                                           data-slide-class="glightbox-video-slide">
                                                            <i class="fas fa-play"></i> Putar Video
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= $fileUrl ?>" class="glightbox btn btn-xs btn-outline-info" 
                                                           data-gallery="progress-gallery"
                                                           data-title="<?= esc($d['design_name']) ?>"
                                                           data-description="Revisi: Rev. <?= $d['revision_number'] ?? 1 ?>"
                                                           title="Lihat Gambar">
                                                            <i class="fas fa-eye"></i> Lihat
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Catatan (jika REJECTED/APPROVED) -->
                                        <?php if (!empty($d['revision_note'])): ?>
                                            <div class="mt-2 px-2 py-1 rounded" style="background:rgba(0,0,0,.04);font-size:12px;">
                                                <i class="fas fa-sticky-note text-muted me-1"></i> catatan revisi : 
                                                <?= esc($d['revision_note']) ?>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Gambar Catatan Revisi (image_revision_note) -->
                                        <?php
                                        $revImages = [];
                                        if (!empty($d['image_revision_note'])) {
                                            $decoded = json_decode($d['image_revision_note'], true);
                                            if (is_array($decoded)) {
                                                $revImages = $decoded;
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($revImages)): ?>
                                            <div class="mt-2">
                                                <div class="d-flex align-items-center gap-1 mb-1" style="font-size:11px; color:#6c757d;">
                                                    <i class="fas fa-images"></i>
                                                    <span>Lampiran Gambar (<?= count($revImages) ?>)</span>
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php foreach ($revImages as $idx => $imgFile): ?>
                                                        <?php $imgUrl = base_url('uploads/design_results/revision_comment/' . $imgFile); ?>
                                                        <a href="<?= $imgUrl ?>"
                                                            class="glightbox"
                                                            data-gallery="rev-note-gallery-<?= $d['id'] ?>"
                                                            data-title="Catatan Gambar <?= $idx + 1 ?> — Rev. <?= $revNum ?>"
                                                            title="Lihat gambar catatan">
                                                            <img src="<?= $imgUrl ?>"
                                                                alt="Catatan gambar <?= $idx + 1 ?>"
                                                                style="width:56px; height:56px; object-fit:cover; border-radius:6px; border:2px solid #dee2e6; cursor:zoom-in; transition:.15s;"
                                                                onmouseover="this.style.borderColor='#0d6efd';"
                                                                onmouseout="this.style.borderColor='#dee2e6';">
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Tombol Aksi Mobile (Hanya tampil di HP, diletakkan paling bawah) -->
                                        <?php if ($revSt === 'PENDING'): ?>
                                            <div class="d-flex d-md-none gap-2 mt-3 pt-2" style="border-top: 1px dashed #dee2e6;">
                                                <a href="<?= base_url('admin/design/approve-design/' . $d['id']) ?>"
                                                    class="btn btn-sm btn-success flex-fill ladda-button" data-style="zoom-in"
                                                    onclick="return confirm('Approve revisi ini? Revisi PENDING lain akan otomatis di-reject.');"
                                                    title="Approve Revisi">
                                                    <span class="ladda-label"><i class="fas fa-check me-1"></i>Approve</span>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger flex-fill" data-bs-toggle="modal"
                                                    data-bs-target="#modalReject<?= $d['id'] ?>" title="Reject Revisi">
                                                    <i class="fas fa-times me-1"></i>Reject
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Modal Reject -->
                                <?php if ($revSt === 'PENDING'): ?>
                                    <div class="modal fade" id="modalReject<?= $d['id'] ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white py-2 px-3">
                                                    <h6 class="modal-title mb-0"><i class="fas fa-times-circle me-1"></i>Reject Revisi Rev.
                                                        <?= $revNum ?>
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="<?= base_url('admin/design/reject-design/' . $d['id']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold" style="font-size:13px;">Catatan untuk
                                                                Klien</label>
                                                            <textarea name="revision_note" class="form-control" rows="3"
                                                                placeholder="Contoh: Proporsi ruangan belum sesuai, mohon direvisi kembali."
                                                                required style="font-size:13px;"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer py-2">
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm btn-danger ladda-button"
                                                            data-style="zoom-in">
                                                            <span class="ladda-label"><i class="fas fa-times me-1"></i>Kirim Reject</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>