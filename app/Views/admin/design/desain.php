<style>
    /* ── Upload dropzone area ── */
    .upload-card {
        border: 2px dashed #c9d1db;
        border-radius: 14px;
        background: #fafbfc;
        transition: border-color .2s, background .2s;
    }

    .upload-card:hover {
        border-color: #6777ef;
        background: #f0f2ff;
    }

    /* ── Design gallery card ── */
    .design-card {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e4e9f0;
        transition: transform .2s, box-shadow .2s;
        position: relative;
    }

    .design-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(103, 119, 239, .18);
    }

    .design-card.approved {
        border: 2px solid #28a745 !important;
        box-shadow: 0 4px 16px rgba(40, 167, 69, .18);
    }

    .design-card .design-thumb {
        height: 140px;
        object-fit: cover;
        width: 100%;
        display: block;
    }

    .design-card .design-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 140px;
        background: rgba(30, 35, 60, .55);
        opacity: 0;
        transition: opacity .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .design-card:hover .design-overlay {
        opacity: 1;
    }

    .design-card .design-meta {
        padding: 10px 12px 12px;
    }

    .pdf-placeholder {
        height: 140px;
        background: #fff5f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ── Revision timeline (Tab Progress) ── */
    .rev-timeline {
        position: relative;
        padding-left: 28px;
    }

    .rev-timeline::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .rev-item {
        position: relative;
        margin-bottom: 18px;
    }

    .rev-item:last-child {
        margin-bottom: 0;
    }

    .rev-dot {
        position: absolute;
        left: -24px;
        top: 6px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #adb5bd;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .rev-dot.approved {
        background: #28a745;
        box-shadow: 0 0 0 2px #28a74533;
    }

    .rev-dot.rejected {
        background: #dc3545;
        box-shadow: 0 0 0 2px #dc354533;
    }

    .rev-dot.pending {
        background: #ffc107;
        box-shadow: 0 0 0 2px #ffc10733;
    }

    .rev-box {
        background: #fff;
        border: 1px solid #e4e9f0;
        border-radius: 10px;
        padding: 12px 14px;
    }

    .rev-box.approved {
        border-color: #28a745;
        background: #f0fff4;
    }

    .rev-box.rejected {
        border-color: #dc354533;
        background: #fff8f8;
    }
</style>

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

                <div class="mb-3">
                    <label class="form-label"
                        style="font-size:11px;font-weight:700;color:#6c757d;letter-spacing:.5px;">PILIH TARGET</label>
                    <select name="design_targets_id" class="form-control form-control-sm" required
                        style="border-radius:8px;font-size:13px;">
                        <option value="">— Pilih Target —</option>
                        <?php foreach ($targets ?? [] as $tg): ?>
                            <option value="<?= $tg['id'] ?>"><?= esc($tg['task_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label"
                        style="font-size:11px;font-weight:700;color:#6c757d;letter-spacing:.5px;">NAMA GAMBAR</label>
                    <input type="text" name="design_name" class="form-control form-control-sm"
                        placeholder="Contoh: Denah Lantai 1" required style="border-radius:8px;font-size:13px;">
                </div>

                <div class="mb-4">
                    <label class="form-label"
                        style="font-size:11px;font-weight:700;color:#6c757d;letter-spacing:.5px;">FILE DESAIN</label>
                    <div class="position-relative">
                        <input type="file" name="design_file" id="designFileInput" class="form-control form-control-sm"
                            accept=".png,.jpg,.jpeg,.pdf" required
                            style="border-radius:8px;font-size:13px;padding-right:40px;">
                        <i class="fas fa-paperclip text-muted position-absolute"
                            style="right:12px;top:50%;transform:translateY(-50%);pointer-events:none;"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block ladda-button fw-bold" data-style="zoom-in"
                    style="border-radius:10px;letter-spacing:.3px;">
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
                            <?php else: ?>
                                <img src="<?= base_url('uploads/design_results/' . $d['file']) ?>" class="design-thumb"
                                    alt="<?= esc($d['design_name']) ?>">
                            <?php endif; ?>

                            <!-- Hover overlay -->
                            <div class="design-overlay">
                                <a href="<?= base_url('uploads/design_results/' . $d['file']) ?>" target="_blank"
                                    class="btn btn-sm btn-light" title="Lihat"
                                    style="border-radius:50%;width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
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
                                <small class="text-muted"><?= date('d M Y', strtotime($d['created_at'])) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>