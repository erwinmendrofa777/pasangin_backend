
<div class="row mt-4 g-3">
    <!-- ── GALERI ── -->
    <div class="col-lg-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="fw-bold mb-0" style="font-size:14px;"><i class="fas fa-images text-primary me-1"></i> Galeri Desain</h6>
                <small class="text-muted"><?= count($design_results) ?> file tersimpan</small>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalUploadDesign"
                    style="border-radius:10px; height: 38px; padding: 0 16px; letter-spacing: 0.2px; font-size: 12px; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-plus-circle" style="font-size: 13px;"></i> Unggah Desain Baru
                </button>
            </div>
        </div>

        <?php
        // Kelompokkan design_results berdasarkan design_targets_id
        $designsByTarget = [];
        $pendingModals = [];
        foreach ($design_results ?? [] as $d) {
            $tid = $d['design_targets_id'] ?? 0;
            $designsByTarget[$tid][] = $d;
        }

        // Cari target mana yang harus dibuka secara default
        $openTargetId = null;
        if (!empty($targets)) {
            foreach ($targets as $t) {
                $targetDesigns = $designsByTarget[$t['id']] ?? [];
                $designsByRevision = [];
                foreach ($targetDesigns as $d) {
                    $revNum = $d['revision_number'] ?? 1;
                    $designsByRevision[$revNum][] = $d;
                }
                foreach ($designsByRevision as $revNum => $filesInRev) {
                    if (($filesInRev[0]['status'] ?? 'PENDING') === 'PENDING') {
                        $openTargetId = $t['id'];
                        break 2; // Buka target pertama yang memiliki revisi pending
                    }
                }
            }
            if ($openTargetId === null) {
                $openTargetId = $targets[0]['id']; // Default buka target pertama
            }
        }
        ?>

        <?php if (empty($targets)): ?>
            <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5"
                style="border:2px dashed #dee2e6;border-radius:14px;min-height:220px;">
                <i class="fas fa-drafting-compass" style="font-size:40px;opacity:.2;"></i>
                <p class="mt-3 mb-0" style="font-size:13px;">Belum ada target. Buat target terlebih dahulu di tab <strong>Target</strong>.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="accordion design-accordion" id="accordionDesign">
                        <?php 
                        foreach ($targets as $t):
                            $statusClr = 'secondary';
                            if ($t['status'] === 'DONE')
                                $statusClr = 'success';
                            elseif ($t['status'] === 'ON PROGRESS')
                                $statusClr = 'primary';
                            elseif ($t['status'] === 'PENDING')
                                $statusClr = 'warning';

                            $targetDesigns = $designsByTarget[$t['id']] ?? [];

                            // Kelompokkan data targetDesigns berdasarkan revision_number
                            $designsByRevision = [];
                            foreach ($targetDesigns as $d) {
                                $revNum = $d['revision_number'] ?? 1;
                                $designsByRevision[$revNum][] = $d;
                            }
                            krsort($designsByRevision);

                            $isOpen = ($t['id'] == $openTargetId);
                            ?>
                            <div class="accordion-item">
                                <!-- Accordion Header -->
                                <h2 class="accordion-header" id="heading-<?= $t['id'] ?>">
                                    <button class="accordion-button <?= $isOpen ? '' : 'collapsed' ?>" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse-<?= $t['id'] ?>" 
                                            aria-expanded="<?= $isOpen ? 'true' : 'false' ?>" 
                                            aria-controls="collapse-<?= $t['id'] ?>">
                                        <div class="d-flex align-items-center justify-content-between w-100 pe-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-dark" style="font-size: 14px; font-family: 'Plus Jakarta Sans', sans-serif;">
                                                    <i class="fas fa-bullseye text-primary me-1.5" style="font-size: 13px;"></i>
                                                    <?= esc($t['task_name']) ?>
                                                </span>
                                                <span class="badge bg-<?= $statusClr === 'warning' ? 'warning text-dark' : $statusClr ?> ms-2 px-2.5 py-1 fw-bold" style="font-size: 10px; letter-spacing: 0.3px; border-radius: 6px;"><?= $t['status'] ?></span>
                                            </div>
                                            <span class="text-muted fw-bold text-uppercase d-none d-sm-inline" style="font-size: 10px; letter-spacing: 0.8px;">
                                                HARI KE <?= $t['start_week'] ?> &ndash; HARI KE <?= $t['end_week'] ?>
                                                <?php if (!empty($t['keterangan'])): ?>
                                                    &nbsp;|&nbsp;<i class="fas fa-sticky-note me-0.5"></i> <?= esc($t['keterangan']) ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </button>
                                </h2>

                                <!-- Accordion Body Collapse Wrapper -->
                                <div id="collapse-<?= $t['id'] ?>" class="accordion-collapse collapse <?= $isOpen ? 'show' : '' ?>" 
                                     aria-labelledby="heading-<?= $t['id'] ?>" 
                                     data-bs-parent="#accordionDesign">
                                    <div class="accordion-body p-4 bg-white">
                                        
                                        <?php if (empty($designsByRevision)): ?>
                                            <div class="text-center text-muted py-4 my-2" style="font-size: 12px; border: 1.5px dashed #cbd5e1; border-radius: 10px; background: #fafbfc;">
                                                <i class="fas fa-images fa-2x mb-2 text-muted" style="opacity:.35;"></i>
                                                <p class="mb-0 fw-semibold">Belum ada file desain untuk target ini.</p>
                                                <small class="text-muted">Klik tombol "Unggah Desain Baru" di atas untuk menambahkan hasil desain.</small>
                                            </div>
                                        <?php else: ?>
                                            <div class="rev-timeline">
                                                <?php 
                                                $isFirstRev = true;
                                                foreach ($designsByRevision as $revNum => $filesInRev):
                                                    $firstDesign = $filesInRev[0];
                                                    $revSt = $firstDesign['status'] ?? 'PENDING';
                                                    $dotCls = strtolower($revSt);
                                                    $boxCls = ($revSt === 'APPROVED') ? 'approved' : (($revSt === 'REJECTED') ? 'rejected' : '');
                                                    
                                                    // Buka revisi pertama secara default, tutup revisi lama
                                                    $isOpenRev = $isFirstRev;
                                                    $isFirstRev = false;

                                                    if ($revSt === 'PENDING') {
                                                        $pendingModals[] = [
                                                            'id' => $firstDesign['id'],
                                                            'revNum' => $revNum
                                                        ];
                                                    }
                                                    ?>
                                                    <div class="rev-item">
                                                        <div class="rev-dot <?= $dotCls ?>"></div>
                                                        <div class="rev-box <?= $boxCls ?> p-3 mb-3 shadow-sm" style="border-radius: 10px; transition: all 0.2s;">
                                                            
                                                            <!-- Baris atas: label revisi + badge status + tanggal (dan tombol aksi Desktop) -->
                                                            <div class="d-flex align-items-center justify-content-between mb-0 pb-2">
                                                                <div class="d-flex align-items-center gap-2 cursor-pointer rev-toggle-btn <?= $isOpenRev ? '' : 'collapsed' ?>" 
                                                                     data-bs-toggle="collapse" 
                                                                     data-bs-target="#collapse-rev-<?= $t['id'] ?>-<?= $revNum ?>"
                                                                     aria-expanded="<?= $isOpenRev ? 'true' : 'false' ?>"
                                                                     aria-controls="collapse-rev-<?= $t['id'] ?>-<?= $revNum ?>"
                                                                     style="cursor: pointer; user-select: none;">
                                                                    <span class="text-muted me-1 rev-chevron">
                                                                        <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
                                                                    </span>
                                                                    <?php if ($revSt === 'APPROVED'): ?>
                                                                        <span style="font-size:14px;">✅</span>
                                                                    <?php endif; ?>
                                                                    <span class="fw-bold text-dark" style="font-size:13px; font-family: 'Plus Jakarta Sans', sans-serif;">
                                                                        Rev. <?= $revNum ?>
                                                                    </span>
                                                                    <?php
                                                                    $badgeCls = 'bg-warning text-dark';
                                                                    if ($revSt === 'APPROVED')
                                                                        $badgeCls = 'bg-success text-white';
                                                                    elseif ($revSt === 'REJECTED')
                                                                        $badgeCls = 'bg-danger text-white';
                                                                    ?>
                                                                    <span class="badge <?= $badgeCls ?> px-2.5 py-0.5" style="font-size:9px; font-weight: 700; border-radius: 4px;"><?= $revSt ?></span>
                                                                </div>

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <small class="text-muted" style="font-size: 10px;"><i class="far fa-calendar-alt me-1"></i><?= date('d M Y, H:i', strtotime($firstDesign['created_at'])) ?></small>
                                                                    
                                                                    <?php if ($revSt === 'PENDING'): ?>
                                                                        <!-- Tombol Aksi Desktop -->
                                                                        <div class="d-none d-md-flex gap-2" onclick="event.stopPropagation();">
                                                                            <a href="<?= base_url('admin/design/approve-design/' . $firstDesign['id']) ?>"
                                                                                class="btn btn-xs btn-success px-2.5 py-1 fw-bold"
                                                                                onclick="return confirm('Approve revisi ini? Revisi PENDING lain akan otomatis di-reject.');"
                                                                                title="Approve Revisi"
                                                                                style="border-radius: 6px; font-size: 11px; display: flex; align-items: center; gap: 4px;">
                                                                                <i class="fas fa-check"></i> Approve
                                                                            </a>
                                                                            <button type="button" class="btn btn-xs btn-outline-danger px-2.5 py-1 fw-bold" data-bs-toggle="modal"
                                                                                data-bs-target="#modalReject-<?= $firstDesign['id'] ?>" title="Reject Revisi"
                                                                                style="border-radius: 6px; font-size: 11px; display: flex; align-items: center; gap: 4px;">
                                                                                <i class="fas fa-times"></i> Reject
                                                                            </button>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>

                                                            <!-- Collapse Content Wrapper -->
                                                            <div id="collapse-rev-<?= $t['id'] ?>-<?= $revNum ?>" class="collapse <?= $isOpenRev ? 'show' : '' ?> mt-3 pt-3 border-top" style="border-top: 1px dashed #e2e8f0 !important;">
                                                                
                                                                <!-- Preview file + nama (bisa banyak berkas dalam revisi ini) -->
                                                                <div class="d-flex flex-column gap-2 mb-2">
                                                                    <?php foreach ($filesInRev as $fileItem):
                                                                        $ext = strtolower(pathinfo($fileItem['file'], PATHINFO_EXTENSION));
                                                                        $dtype = $fileItem['design_type'] ?? 'general';
                                                                        $fileUrl = base_url('uploads/design_results/' . $fileItem['file']);
                                                                        ?>
                                                                        <div class="d-flex align-items-center gap-3 p-2 rounded border bg-light design-file-item" style="border: 1px solid #e2e8f0 !important; background: #fafbfc !important;">
                                                                            <?php if ($dtype === 'pdf'): ?>
                                                                                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                                                                    style="width:52px;height:52px;border-radius:8px;background:#fee2e2 !important;border: 1px solid #fca5a5;">
                                                                                    <i class="far fa-file-pdf text-danger" style="font-size:24px;"></i>
                                                                                </div>
                                                                            <?php elseif ($dtype === 'video'): ?>
                                                                                <div class="d-flex align-items-center justify-content-center flex-shrink-0 position-relative"
                                                                                    style="width:52px;height:52px;border-radius:8px;background:#fef3c7 !important;border: 1px solid #fcd34d;">
                                                                                    <i class="far fa-file-video text-warning" style="font-size:24px;"></i>
                                                                                    <span class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);">
                                                                                        <i class="fas fa-play-circle text-warning bg-white rounded-circle" style="font-size:12px;"></i>
                                                                                    </span>
                                                                                </div>
                                                                            <?php elseif ($dtype === '3d'): ?>
                                                                                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                                                                    style="width:52px;height:52px;border-radius:8px;background:#ecfeff !important;border: 1px solid #a5f3fc;">
                                                                                    <i class="fas fa-cubes text-info" style="font-size:24px;"></i>
                                                                                </div>
                                                                            <?php else: ?>
                                                                                <img src="<?= $fileUrl ?>"
                                                                                    style="width:52px;height:52px;object-fit:cover;border-radius:8px;flex-shrink:0;border: 1px solid #cbd5e1;"
                                                                                    alt="<?= esc($fileItem['design_name']) ?>">
                                                                            <?php endif; ?>
                                                                            
                                                                            <div class="flex-grow-1 min-w-0">
                                                                                <div class="fw-semibold text-dark text-truncate" style="font-size:12px;" title="<?= esc($fileItem['design_name']) ?>">
                                                                                    <?= esc($fileItem['design_name']) ?>
                                                                                </div>
                                                                                <small class="text-muted d-block" style="font-size: 8px; margin-top: 1px;"><?= $dtype === '3d' ? '3D OBJECT' : strtoupper($ext) ?></small>
                                                                                
                                                                                <div class="mt-2 d-flex gap-2 align-items-center">
                                                                                    <?php if ($dtype === 'pdf'): ?>
                                                                                        <a href="<?= $fileUrl ?>" target="_blank"
                                                                                            class="btn btn-xs btn-outline-danger px-2 py-0.5 fw-bold" style="font-size: 9px; border-radius: 4px;" title="Lihat PDF">
                                                                                            <i class="fas fa-file-pdf me-0.5"></i> Lihat PDF
                                                                                        </a>
                                                                                    <?php elseif ($dtype === 'video'): ?>
                                                                                        <div style="display:none;" id="video-desain-<?= $fileItem['id'] ?>">
                                                                                            <div class="p-3 text-center" style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                                                                <video src="<?= $fileUrl ?>" controls style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata" playsinline></video>
                                                                                                <div class="text-white mt-2 text-start px-2">
                                                                                                    <h6 class="mb-1 fw-bold text-white"><?= esc($fileItem['design_name']) ?></h6>
                                                                                                    <small class="text-muted">Revisi: Rev. <?= $revNum ?></small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <a href="#video-desain-<?= $fileItem['id'] ?>" class="glightbox btn btn-xs btn-outline-warning px-2 py-0.5 fw-bold" 
                                                                                           style="font-size: 9px; border-radius: 4px;"
                                                                                           data-gallery="desain-gallery-<?= $t['id'] ?>-<?= $revNum ?>"
                                                                                           data-slide-class="glightbox-video-slide">
                                                                                            <i class="fas fa-play me-0.5"></i> Putar Video
                                                                                        </a>
                                                                                    <?php elseif ($dtype === '3d'): ?>
                                                                                        <button type="button" class="btn btn-xs btn-outline-info px-2 py-0.5 fw-bold" style="font-size: 9px; border-radius: 4px;" title="Salin Nama Objek"
                                                                                                onclick="navigator.clipboard.writeText('<?= esc($fileItem['file']) ?>'); iziToast.success({title: 'Copied', message: 'Nama objek disalin!', position: 'topRight'});">
                                                                                            <i class="far fa-copy me-0.5"></i> Salin Nama Objek
                                                                                        </button>
                                                                                    <?php else: ?>
                                                                                        <a href="<?= $fileUrl ?>" class="glightbox btn btn-xs btn-outline-primary px-2 py-0.5 fw-bold" 
                                                                                           style="font-size: 9px; border-radius: 4px;"
                                                                                           data-gallery="desain-gallery-<?= $t['id'] ?>-<?= $revNum ?>"
                                                                                           data-title="<?= esc($fileItem['design_name']) ?>"
                                                                                           data-description="Revisi: Rev. <?= $revNum ?>"
                                                                                           title="Lihat Gambar">
                                                                                            <i class="fas fa-eye me-0.5"></i> Lihat
                                                                                        </a>
                                                                                    <?php endif; ?>

                                                                                    <!-- Tombol Hapus Berkas (Khusus Admin) -->
                                                                                    <a href="<?= base_url('admin/design/delete-design/' . $fileItem['id']) ?>" 
                                                                                       class="btn btn-xs btn-outline-danger px-2 py-0.5 fw-bold" 
                                                                                       style="font-size: 9px; border-radius: 4px;"
                                                                                       onclick="return confirm('Hapus file ini?');" 
                                                                                       title="Hapus">
                                                                                        <i class="fas fa-trash-alt me-0.5"></i> Hapus
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>

                                                                <!-- Catatan (jika REJECTED/APPROVED) -->
                                                                <?php if (!empty($firstDesign['revision_note'])): ?>
                                                                    <div class="mt-2 px-3 py-2 rounded bg-light border-start border-3 border-secondary text-start" style="font-size:11px; line-height: 1.4; color: #475569;">
                                                                        <i class="fas fa-comment-alt text-muted me-1"></i> <strong>Catatan:</strong> 
                                                                        <?= esc($firstDesign['revision_note']) ?>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <!-- Gambar Catatan Revisi (image_revision_note) -->
                                                                <?php
                                                                $revImages = [];
                                                                if (!empty($firstDesign['image_revision_note'])) {
                                                                    $decoded = json_decode($firstDesign['image_revision_note'], true);
                                                                    if (is_array($decoded)) {
                                                                        $revImages = $decoded;
                                                                    }
                                                                }
                                                                ?>
                                                                <?php if (!empty($revImages)): ?>
                                                                    <div class="mt-2 text-start">
                                                                        <div class="d-flex align-items-center gap-1 mb-1 text-muted" style="font-size:10px;">
                                                                            <i class="fas fa-images"></i>
                                                                            <span>Lampiran Catatan Gambar (<?= count($revImages) ?>)</span>
                                                                        </div>
                                                                        <div class="d-flex flex-wrap gap-2">
                                                                            <?php foreach ($revImages as $idx => $imgFile): ?>
                                                                                <?php $imgUrl = base_url('uploads/design_results/revision_comment/' . $imgFile); ?>
                                                                                <a href="<?= $imgUrl ?>" class="glightbox" data-gallery="rev-note-gallery-<?= $firstDesign['id'] ?>" data-title="Catatan Gambar <?= $idx + 1 ?> — Rev. <?= $revNum ?>" title="Lihat gambar catatan">
                                                                                    <img src="<?= $imgUrl ?>" alt="Catatan gambar" style="width:40px; height:40px; object-fit:cover; border-radius:6px; border:1px solid #cbd5e1; cursor:zoom-in; transition:.15s;">
                                                                                </a>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <!-- Tombol Aksi Mobile (Hanya tampil di HP, diletakkan paling bawah) -->
                                                                <?php if ($revSt === 'PENDING'): ?>
                                                                    <div class="d-flex d-md-none gap-2 mt-3 pt-2" style="border-top: 1px dashed #dee2e6;">
                                                                        <a href="<?= base_url('admin/design/approve-design/' . $firstDesign['id']) ?>"
                                                                            class="btn btn-sm btn-success flex-fill fw-bold"
                                                                            onclick="return confirm('Approve revisi ini? Revisi PENDING lain akan otomatis di-reject.');"
                                                                            title="Approve Revisi"
                                                                            style="font-size: 11px; border-radius: 6px;">
                                                                            <i class="fas fa-check me-1"></i> Approve
                                                                        </a>
                                                                        <button type="button" class="btn btn-sm btn-outline-danger flex-fill fw-bold" data-bs-toggle="modal"
                                                                            data-bs-target="#modalReject-<?= $firstDesign['id'] ?>" title="Reject Revisi"
                                                                            style="font-size: 11px; border-radius: 6px;">
                                                                            <i class="fas fa-times me-1"></i> Reject
                                                                        </button>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div> <!-- End Collapse Content Wrapper -->
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Upload Desain Baru -->
<div class="modal fade" id="modalUploadDesign" tabindex="-1" role="dialog" aria-labelledby="modalUploadDesignTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header border-bottom py-3 px-4" style="background: #f8fafc; border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                        style="width:32px;height:32px;background:var(--palette-primary)22;">
                        <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 14px;"></i>
                    </div>
                    <h6 class="modal-title fw-bold mb-0" id="modalUploadDesignTitle" style="font-size: 15px; color: #1e293b;">Upload Hasil Desain</h6>
                </div>
                <button type="button" class="btn-close shadow-none border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
            </div>
            <?php 
            $reqTargetId = $_GET['target_id'] ?? '';
            $reqAdminId = $_GET['admin_id'] ?? session()->get('user_id');
            ?>
            <form id="uploadDesignForm" action="<?= base_url('admin/design/add-design-result/' . $request['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body py-4 px-4" style="max-height: 65vh; overflow-y: auto;">
                    <div class="row">
                        <!-- Kolom Kiri: Input Form -->
                        <div class="col-md-6 border-end pe-md-4">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold" style="font-size:10px;letter-spacing:.5px;text-transform:uppercase;">PILIH TARGET</label>
                                    <select name="design_targets_id" class="form-control form-control-sm" required style="height: 40px;border-radius:8px;font-size:13px; border: 1.5px solid #cbd5e1;">
                                        <option value="">— Pilih Target —</option>
                                        <?php foreach ($targets ?? [] as $tg): ?>
                                            <option value="<?= $tg['id'] ?>" <?= ($reqTargetId == $tg['id']) ? 'selected' : '' ?>><?= esc($tg['task_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold" style="font-size:10px;letter-spacing:.5px;text-transform:uppercase;">PILIH ADMIN (USER)</label>
                                    <select name="user_admin_id" class="form-control form-control-sm" required style="height: 40px;border-radius:8px;font-size:13px; border: 1.5px solid #cbd5e1;">
                                        <option value="">— Pilih Admin —</option>
                                        <?php foreach ($admin_users ?? [] as $au): ?>
                                            <option value="<?= $au['id'] ?>" <?= ($reqAdminId == $au['id']) ? 'selected' : '' ?>>
                                                <?= esc($au['full_name'] ?? $au['username'] ?? 'Admin ' . $au['id']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold" style="font-size:10px;letter-spacing:.5px;text-transform:uppercase;">NAMA GAMBAR</label>
                                <input type="text" name="design_name" class="form-control form-control-sm" placeholder="Contoh: Denah Lantai 1" required style="height: 40px;border-radius:8px;font-size:13px; border: 1.5px solid #cbd5e1;">
                            </div>

                            <div class="mb-3" id="fileUploadContainer">
                                <label class="form-label text-muted fw-bold" style="font-size:10px;letter-spacing:.5px;text-transform:uppercase;">FILE DESAIN (OPSIONAL)</label>
                                <div class="dropzone-area" id="dropzoneArea">
                                    <input type="file" name="design_files[]" id="designFileInput" accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm,.mkv,.obj,.fbx,.glb,.gltf,.dwg,.rvt" multiple class="d-none">
                                    <div class="dropzone-content text-center py-4">
                                        <div class="dropzone-icon-wrapper mb-2 mx-auto d-flex align-items-center justify-content-center">
                                            <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 20px;"></i>
                                        </div>
                                        <h6 class="dropzone-title fw-bold mb-1" style="font-size: 13px; color: #344054;">Tarik & lepaskan file di sini</h6>
                                        <p class="dropzone-subtitle text-muted mb-0" style="font-size: 11px;">atau <span class="text-primary fw-bold" style="text-decoration: underline;">pilih dari komputer</span></p>
                                        <span class="d-block mt-2 text-muted" style="font-size: 9px;">Format: PDF, Gambar, Video, atau File 3D (Maks. 50MB)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="3dObjectInputContainer">
                                <label class="form-label text-muted fw-bold" style="font-size:10px;letter-spacing:.5px;text-transform:uppercase;">NAMA OBJEK 3D (STRING) (OPSIONAL)</label>
                                <input type="text" name="3d_object_name" id="3dObjectNameInput" class="form-control form-control-sm" placeholder="Contoh: UnityObject_Building_Floor1" style="height: 40px;border-radius:8px;font-size:13px; border: 1.5px solid #cbd5e1;">
                            </div>
                        </div>

                        <!-- Kolom Kanan: Pratinjau Berkas -->
                        <div class="col-md-6 ps-md-4 d-flex flex-column" id="modalUploadPreviewList" style="min-height: 300px;">
                            <label class="form-label text-muted fw-bold mb-3" style="font-size:10px;letter-spacing:.5px;text-transform:uppercase;">Pratinjau Berkas Terpilih</label>
                            
                            <!-- Placeholder Kosong -->
                            <div class="text-center text-muted py-5 px-3 my-auto d-flex flex-column align-items-center justify-content-center" id="previewPlaceholder" style="border: 2px dashed #e2e8f0; border-radius: 12px; height: 100%; background: #fafbfc; min-height: 200px;">
                                <i class="fas fa-folder-open fa-3x mb-3 text-muted" style="opacity: 0.35;"></i>
                                <p class="mb-0 fw-semibold" style="font-size: 12px; color: #64748b;">Belum ada berkas terpilih</p>
                                <small class="text-muted text-center mt-1" style="font-size: 10px; max-width: 220px;">Tarik & lepaskan file atau isi kolom objek 3D untuk melihat pratinjau</small>
                            </div>

                            <!-- List File Preview -->
                            <div class="preview-files-list d-none flex-column gap-2" style="max-height: 320px; overflow-y: auto; padding-right: 4px;">
                                <!-- Dinamis via JS -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4" style="background: #f8fafc; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal" style="height: 38px; border-radius: 8px; font-size: 12px; padding: 0 16px;">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold" style="height: 38px; border-radius: 8px; font-size: 12px; padding: 0 16px;">
                        <i class="fas fa-cloud-upload-alt me-1"></i> Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($pendingModals as $pm): ?>
    <!-- Modal Reject -->
    <div class="modal fade" id="modalReject-<?= $pm['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header bg-danger text-white py-2 px-3" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h6 class="modal-title mb-0" style="font-size: 13px;"><i class="fas fa-times-circle me-1"></i>Reject Revisi Rev. <?= $pm['revNum'] ?></h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
                </div>
                <form action="<?= base_url('admin/design/reject-design/' . $pm['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body py-3">
                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold text-dark" style="font-size:11px;">Catatan untuk Klien</label>
                            <textarea name="revision_note" class="form-control" rows="3" placeholder="Contoh: Proporsi ruangan belum sesuai, mohon direvisi kembali." required style="font-size:11px; border-radius: 8px; border: 1.5px solid #cbd5e1;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius: 6px; font-size: 11px;">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 6px; font-size: 11px;">
                            <i class="fas fa-times me-1"></i> Kirim Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>