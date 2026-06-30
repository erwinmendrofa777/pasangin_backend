
<div class="row g-4 mt-1 survey-row">

    <!-- ========== FORM TAMBAH SURVEY ========== -->
    <div class="col-md-4 d-flex flex-column">
        <div class="card survey-card-form h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white" style="font-weight:700; font-size:0.9rem;">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Laporan Survey
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('admin/renovation/add-survey/' . $renovation['id']) ?>" method="post"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $renovation['id'] ?>">

                    <div class="form-group mb-3">
                        <label class="survey-label">Judul Laporan</label>
                        <input type="text" name="title" class="form-control survey-input"
                            placeholder="Contoh: Survey Lokasi Pertama" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="survey-label">Admin Pelaksana</label>
                        <select name="user_admin_id" class="form-control survey-input" required>
                            <option value="">— Pilih Admin —</option>
                            <?php foreach ($admin_users ?? [] as $au): ?>
                                <option value="<?= $au['id'] ?>">
                                    <?= esc($au['full_name'] ?? $au['username'] ?? 'Admin ' . $au['id']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="survey-label">Catatan (Opsional)</label>
                        <textarea name="description" class="form-control survey-input" rows="3"
                            placeholder="Tambahkan catatan hasil survey..." style="resize:none;"></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="survey-label">File Laporan</label>
                        <div class="file-upload-box">
                            <span class="file-label" id="surveyFileNameDisplay">Pilih atau seret file...</span>
                            <input type="file" name="file_url" id="surveyFileInput"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm,.mkv" required>
                            <i class="fas fa-paperclip"></i>
                        </div>
                        <div class="form-text mt-1" style="font-size:0.73rem;">
                            <i class="fas fa-info-circle text-primary mr-1"></i> Format: PDF, Gambar, atau Video (Max
                            50MB).
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-survey-submit w-100 ladda-button"
                        data-style="zoom-in">
                        <span class="ladda-label"><i class="fas fa-save mr-2"></i> Simpan Laporan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ========== RIWAYAT SURVEY ========== -->
    <div class="col-md-8 d-flex flex-column survey-right-col">
        <div class="card survey-card-history h-100 survey-right-card">
            <div class="card-header">
                <h6 class="mb-0 text-white" style="font-weight:700; font-size:0.9rem;">
                    <i class="fas fa-history mr-2"></i> Riwayat Survey
                </h6>
            </div>
            <div class="card-body p-2 survey-right-card-body">
                <?php if (empty($survey_list)): ?>
                    <div class="empty-survey">
                        <div class="empty-survey-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h6 class="font-weight-bold text-dark mb-1">Belum Ada Survey</h6>
                        <p class="text-muted mb-0" style="font-size:0.83rem;">
                            Tambahkan laporan survey pertama melalui form di samping.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($survey_list as $srv): ?>
                            <div class="card border-0 shadow-sm mb-0" style="border-radius:12px;">
                                <div class="card-body p-2 p-md-4">
                                    <div class="row align-items-center g-3">

                                        <!-- Info Kiri: Ikon + Judul + Catatan -->
                                        <div class="col-12 col-md-5 d-flex gap-3">
                                            <?php if (!empty($srv['file_url'])): ?>
                                                <?php
                                                $fileUrl = base_url('uploads/survey/' . $srv['file_url']);
                                                $ext = strtolower(pathinfo($srv['file_url'], PATHINFO_EXTENSION));
                                                $isPdf = ($ext === 'pdf');
                                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                                $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                                                ?>
                                                <?php if ($isPdf): ?>
                                                    <a href="<?= $fileUrl ?>" target="_blank"
                                                        class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                                                        style="width: 48px; height: 48px; font-size: 1.25rem; text-decoration:none; border: 1px solid rgba(220,53,69,0.2); background-color: rgba(234, 84, 85, 0.12); color: #ea5455;"
                                                        title="Lihat PDF">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                <?php elseif ($isImage): ?>
                                                    <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                                                        style="width: 48px; height: 48px; font-size: 1.25rem; cursor:pointer; background-color: rgba(40, 199, 111, 0.12); color: #28c76f;"
                                                        onclick="$('#glb-renovation-survey-img-<?= $srv['id'] ?>').click();">
                                                        <i class="fas fa-file-image"></i>
                                                    </div>
                                                <?php elseif ($isVideo): ?>
                                                    <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center position-relative"
                                                        style="width: 48px; height: 48px; font-size: 1.25rem; cursor:pointer; background-color: rgba(255, 159, 67, 0.12); color: #ff9f43;"
                                                        onclick="$('#glb-renovation-survey-video-<?= $srv['id'] ?>').click();">
                                                        <i class="fas fa-file-video"></i>
                                                        <span class="position-absolute"
                                                            style="top:50%;left:50%;transform:translate(-50%,-50%);">
                                                            <i class="fas fa-play-circle bg-white rounded-circle"
                                                                style="font-size:10px; color: #ff9f43;"></i>
                                                        </span>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                                                        style="width: 48px; height: 48px; font-size: 1.25rem; background-color: rgba(255, 92, 92, 0.12); color: var(--palette-primary, #ff5c5c);">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center"
                                                    style="width:48px; height:48px; background:linear-gradient(135deg,#ffe5e5,#d5dfff); border-radius:10px; color:var(--palette-primary); font-size:1.25rem; flex-shrink:0;">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div style="min-width:0;">
                                                <h6 class="font-weight-bold text-dark text-wrap mb-1"
                                                    style="font-size:0.95rem; line-height:1.3;">
                                                    <?= esc($srv['title']) ?>
                                                </h6>
                                                <?php if (!empty($srv['description'])): ?>
                                                    <p class="text-muted mb-0 text-wrap" style="font-size:0.8rem; line-height:1.4;">
                                                        <?= esc($srv['description']) ?>
                                                    </p>
                                                <?php else: ?>
                                                    <span class="badge badge-light border text-secondary"
                                                        style="font-size:0.72rem;">Tidak ada catatan</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Info Tengah: Tanggal & PJ -->
                                        <div class="col-12 col-md-4 px-md-3 survey-divider-x">
                                            <div class="d-flex flex-row flex-md-column gap-3 gap-md-1 text-muted"
                                                style="font-size:0.85rem;">
                                                <div class="text-dark fw-bold"
                                                    title="Diunggah oleh: <?= esc($srv['admin_name'] ?? 'Sistem') ?>">
                                                    <i class="fas fa-user-tie mr-2 text-primary"></i>
                                                    <?= esc(strlen($srv['admin_name'] ?? 'Sistem') > 15 ? substr($srv['admin_name'] ?? 'Sistem', 0, 15) . '...' : ($srv['admin_name'] ?? 'Sistem')) ?>
                                                </div>
                                                <div>
                                                    <i class="fas fa-calendar-alt mr-2"></i>
                                                    <?= date('d M Y', strtotime($srv['created_at'])) ?>
                                                </div>
                                                <div>
                                                    <i class="fas fa-clock mr-2"></i>
                                                    <?= date('H:i', strtotime($srv['created_at'])) ?>
                                                    WIB
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Aksi: Download + Hapus -->
                                        <div class="col-12 col-md-3 text-md-end pt-3 pt-md-0 survey-divider-y">
                                            <div class="d-flex justify-content-start justify-content-md-end gap-2">
                                                <?php if (!empty($srv['file_url'])): ?>
                                                    <?php if ($isPdf): ?>
                                                        <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-outline-danger"
                                                            style="border-radius:8px;" title="Lihat PDF">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    <?php elseif ($isImage): ?>
                                                        <a href="<?= $fileUrl ?>" id="glb-renovation-survey-img-<?= $srv['id'] ?>"
                                                            class="glightbox btn btn-sm btn-outline-info" data-gallery="survey-gallery"
                                                            data-title="<?= esc($srv['title']) ?>"
                                                            data-description="Diunggah oleh: <?= esc($srv['admin_name'] ?? 'Sistem') ?> &lt;br&gt; Tanggal: <?= date('d M Y', strtotime($srv['created_at'])) ?> <?= date('H:i', strtotime($srv['created_at'])) ?> WIB"
                                                            style="border-radius:8px;" title="Lihat Gambar">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php elseif ($isVideo): ?>
                                                        <!-- Hidden video player container for native playbacks -->
                                                        <div style="display:none;" id="video-survey-<?= $srv['id'] ?>">
                                                            <div class="p-3 text-center"
                                                                style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                                <video src="<?= $fileUrl ?>" controls
                                                                    style="width:100%; max-height:60vh; border-radius:8px; display:block;"
                                                                    preload="metadata" playsinline></video>
                                                                <div class="text-white mt-2 text-start px-2">
                                                                    <h6 class="mb-1 fw-bold text-white"><?= esc($srv['title']) ?></h6>
                                                                    <small class="text-muted">Diunggah oleh:
                                                                        <?= esc($srv['admin_name'] ?? 'Sistem') ?> |
                                                                        <?= date('d M Y, H:i', strtotime($srv['created_at'])) ?>
                                                                        WIB</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="#video-survey-<?= $srv['id'] ?>"
                                                            class="glightbox btn btn-sm btn-outline-warning"
                                                            id="glb-renovation-survey-video-<?= $srv['id'] ?>"
                                                            data-gallery="survey-gallery" data-type="inline"
                                                            data-slide-class="glightbox-video-slide" style="border-radius:8px;">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-outline-primary"
                                                            style="border-radius:8px;" title="Download File">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-light text-muted" style="border-radius:8px;"
                                                        disabled title="Tidak ada file">
                                                        <i class="fas fa-file-excel"></i>
                                                    </button>
                                                <?php endif; ?>

                                                <a href="<?= base_url('admin/renovation/delete_survey/' . $srv['id'] . '/' . $renovation['id']) ?>"
                                                    class="btn btn-sm btn-outline-danger ladda-button" data-style="zoom-in"
                                                    style="border-radius:8px;"
                                                    onclick="if(confirm('Apakah Anda yakin ingin menghapus laporan survey ini?')) { Ladda.create(this).start(); return true; } return false;"
                                                    title="Hapus Laporan">
                                                    <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Feedback Klien Full Width -->
                                    <?php if (!empty($srv['comment'])): ?>
                                        <div class="mt-3 p-3 bg-light border" style="border-radius:8px;">
                                            <strong class="text-primary" style="font-size:0.85rem;">
                                                <i class="fas fa-comment-dots mr-2"></i>Feedback Klien:
                                            </strong>
                                            <p class="font-italic text-muted mb-0 mt-2 text-wrap"
                                                style="font-size:0.85rem; line-height:1.5; word-break: break-word;">
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