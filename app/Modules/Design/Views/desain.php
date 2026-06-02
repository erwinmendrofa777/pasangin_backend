
<div class="row mt-4 g-3">
    <!-- ── PANEL UPLOAD ── -->
    <div class="col-lg-4">
        <div class="upload-card p-4 h-100">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                    style="width:36px;height:36px;background:#6777ef22;">
                    <i class="fas fa-cloud-upload-alt text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold" style="font-size:14px;">Upload Hasil Desain</h6>
                    <small class="text-muted">Format: JPG, PNG, PDF — maks. 5 MB</small>
                </div>
            </div>
            <hr class="mt-2 mb-3">

            <form action="<?= base_url('admin/design/add-design-result/' . $request['id']) ?>" method="post"
                enctype="multipart/form-data">
                <?= csrf_field() ?>

                <?php 
                $reqTargetId = $_GET['target_id'] ?? '';
                $reqAdminId = $_GET['admin_id'] ?? session()->get('user_id'); // default ke user yang sedang login
                ?>

                <div class="mb-3">
                    <label class="form-label"
                        style="font-size:11px;font-weight:700;color:#6c757d;letter-spacing:.5px;">PILIH TARGET</label>
                    <select name="design_targets_id" class="form-control form-control-sm" required
                        style="height: 40px;border-radius:8px;font-size:13px;">
                        <option value="">— Pilih Target —</option>
                        <?php foreach ($targets ?? [] as $tg): ?>
                            <option value="<?= $tg['id'] ?>" <?= ($reqTargetId == $tg['id']) ? 'selected' : '' ?>><?= esc($tg['task_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label"
                        style="font-size:11px;font-weight:700;color:#6c757d;letter-spacing:.5px;">PILIH ADMIN
                        (USER)</label>
                    <select name="user_admin_id" class="form-control form-control-sm" required
                        style="height: 40px;border-radius:8px;font-size:13px;">
                        <option value="">— Pilih Admin —</option>
                        <?php foreach ($admin_users ?? [] as $au): ?>
                            <option value="<?= $au['id'] ?>" <?= ($reqAdminId == $au['id']) ? 'selected' : '' ?>>
                                <?= esc($au['full_name'] ?? $au['username'] ?? 'Admin ' . $au['id']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label"
                        style="font-size:11px;font-weight:700;color:#6c757d;letter-spacing:.5px;">NAMA GAMBAR</label>
                    <input type="text" name="design_name" class="form-control form-control-sm"
                        placeholder="Contoh: Denah Lantai 1" required
                        style="height: 40px;border-radius:8px;font-size:13px;">
                </div>

                <div class="mb-4">
                    <label class="form-label"
                        style="font-size:11px;font-weight:700;color:#6c757d;letter-spacing:.5px;">FILE DESAIN</label>
                    <div class="file-upload-box">
                        <span class="file-label" id="fileNameDisplay">Pilih atau seret file...</span>
                        <input type="file" name="design_file" id="designFileInput"
                            accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm,.mkv" required>
                        <i class="fas fa-paperclip"></i>
                    </div>
                    <div class="form-text mt-1" style="font-size:0.7rem;color:#888;"><i
                            class="fas fa-info-circle text-primary me-1"></i>Format: PDF, Gambar, atau Video (Max 50MB).
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block ladda-button fw-bold" data-style="zoom-in"
                    style="height: 45px;border-radius:10px;letter-spacing:.3px;">
                    <span class="ladda-label"><i class="fas fa-cloud-upload-alt me-1"></i> Upload Sekarang</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ── GALERI ── -->
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="fw-bold mb-0" style="font-size:14px;"><i class="fas fa-images text-primary me-1"></i> Galeri
                    Desain</h6>
                <small class="text-muted"><?= count($design_results) ?> file tersimpan</small>
            </div>
        </div>

        <?php if (empty($design_results)): ?>
            <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5"
                style="border:2px dashed #dee2e6;border-radius:14px;min-height:220px;">
                <i class="fas fa-drafting-compass" style="font-size:40px;opacity:.2;"></i>
                <p class="mt-3 mb-0" style="font-size:13px;">Belum ada file desain yang diupload.</p>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($design_results as $d):
                    $ext = strtolower(pathinfo($d['file'], PATHINFO_EXTENSION));
                    $isPdf = ($ext === 'pdf');
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                    ?>
                    <?php
                    $revStatus = $d['status'] ?? 'PENDING';
                    $revBadgeClass = 'badge-warning';
                    if ($revStatus === 'APPROVED')
                        $revBadgeClass = 'badge-success';
                    elseif ($revStatus === 'REJECTED')
                        $revBadgeClass = 'badge-danger';
                    $isApproved = ($revStatus === 'APPROVED');
                    ?>
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="design-card <?= $isApproved ? 'approved' : '' ?>">

                            <!-- Thumbnail / PDF -->
                            <?php if ($isPdf): ?>
                                <div class="pdf-placeholder">
                                    <i class="fas fa-file-pdf text-danger" style="font-size:44px;"></i>
                                </div>
                            <?php elseif ($isVideo): ?>
                                <div class="video-placeholder">
                                    <i class="fas fa-file-video text-warning" style="font-size:44px;"></i>
                                    <div class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);">
                                        <i class="fas fa-play-circle text-warning bg-white rounded-circle"
                                            style="font-size:24px;box-shadow:0 2px 10px rgba(0,0,0,0.15);"></i>
                                    </div>
                                </div>
                            <?php else: ?>
                                <img src="<?= base_url('uploads/design_results/' . $d['file']) ?>" class="design-thumb"
                                    alt="<?= esc($d['design_name']) ?>">
                            <?php endif; ?>

                            <!-- Hover overlay -->
                            <div class="design-overlay">
                                <?php $fileUrl = base_url('uploads/design_results/' . $d['file']); ?>
                                <?php if ($isPdf): ?>
                                    <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-light" title="Lihat PDF"
                                        style="border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    </a>
                                <?php elseif ($isImage || $isVideo): ?>
                                    <?php if ($isVideo): ?>
                                        <!-- Hidden video player container for native playbacks -->
                                        <div style="display:none;" id="video-design-<?= $d['id'] ?>">
                                            <div class="p-3 text-center"
                                                style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                <video src="<?= $fileUrl ?>" controls
                                                    style="width:100%; max-height:60vh; border-radius:8px; display:block;"
                                                    preload="metadata" playsinline></video>
                                                <div class="text-white mt-2 text-start px-2">
                                                    <h6 class="mb-1 fw-bold text-white"><?= esc($d['design_name']) ?></h6>
                                                    <small class="text-muted">Revisi: Rev. <?= $d['revision_number'] ?? 1 ?> | Target:
                                                        <?= !empty($d['task_name']) ? esc($d['task_name']) : 'Tanpa Target' ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#video-design-<?= $d['id'] ?>" class="glightbox btn btn-sm btn-light"
                                            data-gallery="design-gallery" data-slide-class="glightbox-video-slide"
                                            style="border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-play text-warning"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= $fileUrl ?>" class="glightbox btn btn-sm btn-light" data-gallery="design-gallery"
                                            data-title="<?= esc($d['design_name']) ?>"
                                            data-description="Revisi: Rev. <?= $d['revision_number'] ?? 1 ?> &lt;br&gt; Target: <?= !empty($d['task_name']) ? esc($d['task_name']) : 'Tanpa Target' ?>"
                                            style="border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-light" title="Lihat File"
                                        style="border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/design/delete-design/' . $d['id']) ?>"
                                    class="btn btn-sm btn-light ladda-button" data-style="zoom-in" title="Hapus"
                                    style="border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;"
                                    onclick="if(confirm('Hapus file ini?')) { Ladda.create(this).start(); return true; } return false;">
                                    <span class="ladda-label"><i class="fas fa-trash text-danger"></i></span>
                                </a>
                            </div>

                            <!-- Approved ribbon -->
                            <?php if ($isApproved): ?>
                                <div
                                    style="position:absolute;top:8px;right:8px;background:#28a745;color:#fff;border-radius:20px;padding:2px 8px;font-size:10px;font-weight:700;">
                                    ✅ APPROVED
                                </div>
                            <?php endif; ?>

                            <!-- Info -->
                            <div class="design-meta">
                                <div class="d-flex align-items-center gap-1 flex-wrap mb-1">
                                    <span class="badge badge-primary" style="font-size:10px;">
                                        <?= !empty($d['task_name']) ? esc($d['task_name']) : 'Tanpa Target' ?>
                                    </span>
                                    <span class="badge <?= $revBadgeClass ?>" style="font-size:10px;">
                                        Rev. <?= $d['revision_number'] ?? 1 ?>
                                    </span>
                                </div>
                                <div class="fw-semibold text-truncate" style="font-size:12px;color:#34395e;"
                                    title="<?= esc($d['design_name']) ?>">
                                    <?= esc($d['design_name']) ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted" style="font-size:10px;"
                                        title="Diunggah oleh: <?= esc($d['admin_name'] ?? 'Sistem') ?>">
                                        <i class="fas fa-user-tie me-1"></i>
                                        <?= esc(strlen($d['admin_name'] ?? 'Sistem') > 10 ? substr($d['admin_name'] ?? 'Sistem', 0, 10) . '...' : ($d['admin_name'] ?? 'Sistem')) ?>
                                    </small>
                                    <small class="text-muted"
                                        style="font-size:10px;"><?= date('d/m/y', strtotime($d['created_at'])) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
