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
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                            data-target="#modalReject<?= $d['id'] ?>" title="Reject Revisi">
                                                            <i class="fas fa-times me-1"></i>Reject
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Preview file + nama -->
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if ($isPdf): ?>
                                                <div class="d-flex align-items-center justify-content-center bg-light flex-shrink-0"
                                                    style="width:72px;height:72px;border-radius:8px;">
                                                    <i class="fas fa-file-pdf text-danger" style="font-size:32px;"></i>
                                                </div>
                                            <?php else: ?>
                                                <img src="<?= base_url('uploads/design_results/' . $d['file']) ?>"
                                                    style="width:72px;height:72px;object-fit:cover;border-radius:8px;flex-shrink:0;"
                                                    alt="<?= esc($d['design_name']) ?>">
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-semibold" style="font-size:13px;"><?= esc($d['design_name']) ?></div>
                                                <div class="mt-1 d-flex gap-1">
                                                    <a href="<?= base_url('uploads/design_results/' . $d['file']) ?>" target="_blank"
                                                        class="btn btn-xs btn-outline-info" title="Lihat File">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Catatan (jika REJECTED/APPROVED) -->
                                        <?php if (!empty($d['revision_note'])): ?>
                                            <div class="mt-2 px-2 py-1 rounded" style="background:rgba(0,0,0,.04);font-size:12px;">
                                                <i class="fas fa-sticky-note text-muted me-1"></i>
                                                <?= esc($d['revision_note']) ?>
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
                                                <button type="button" class="btn btn-sm btn-outline-danger flex-fill" data-toggle="modal"
                                                    data-target="#modalReject<?= $d['id'] ?>" title="Reject Revisi">
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
                                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
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
                                                            data-dismiss="modal">Batal</button>
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