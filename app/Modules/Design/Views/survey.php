
<style>
    .scrollable-card-body::-webkit-scrollbar {
        width: 6px;
    }
    .scrollable-card-body::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .scrollable-card-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .scrollable-card-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .survey-right-card {
        background-color: #f8f9fa !important;
    }

    .survey-right-card-body {
        background: #f8f9fa;
        overflow-y: auto;
    }

    @media (max-width: 767.98px) {
        .survey-right-card-body {
            max-height: 450px;
        }
    }

    @media (min-width: 768px) {
        .survey-row {
            align-items: stretch;
        }
        .survey-right-col {
            position: relative;
        }
        .survey-right-card {
            position: absolute !important;
            top: 0;
            bottom: 0;
            left: calc(var(--bs-gutter-x, 1.5rem) / 2);
            right: calc(var(--bs-gutter-x, 1.5rem) / 2);
            height: auto !important;
        }
        .survey-right-card-body {
            flex: 1 1 0%;
            min-height: 0;
        }
    }
</style>

<div class="row g-4 mt-1 survey-row">
    <!-- Kolom Form Tambah Survey -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-primary text-white" style="border-radius: 12px 12px 0 0; padding: 16px 20px;">
                <h6 class="mb-0 fw-bold text-white"><i class="fas fa-plus-circle me-2"></i>Tambah Laporan Survey</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('admin/design/add-survey/' . $request['id']) ?>" method="post"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold text-muted"
                            style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Judul
                            Laporan</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Survey Lokasi Awal"
                            required style="height: 40px;border-radius:8px;font-size:13px;">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold text-muted"
                            style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Admin (User)</label>
                        <?php $surveyAdminId = $_GET['admin_id'] ?? session()->get('user_id'); ?>
                        <select name="user_admin_id" class="form-control" required
                            style="height: 40px;border-radius:8px;font-size:13px;">
                            <option value="">— Pilih Admin —</option>
                            <?php foreach ($admin_users ?? [] as $au): ?>
                                <option value="<?= $au['id'] ?>" <?= ($surveyAdminId == $au['id']) ? 'selected' : '' ?>>
                                    <?= esc($au['full_name'] ?? $au['username'] ?? 'Admin ' . $au['id']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold text-muted"
                            style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">Catatan
                            (Opsional)</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Tulis catatan singkat..."
                            style="border-radius:8px; resize:none;"></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label fw-semibold text-muted"
                            style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.5px;">File
                            Laporan</label>
                        <div class="file-upload-box">
                            <span class="file-label" id="surveyFileNameDisplay">Pilih atau seret file...</span>
                            <input type="file" name="survey_file" id="surveyFileInput" accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm,.mkv"
                                required>
                            <i class="fas fa-paperclip"></i>
                        </div>
                        <div class="form-text" style="font-size:0.75rem;"><i
                                class="fas fa-info-circle text-primary me-1"></i>Format: PDF, Gambar, atau Video (Max 50MB).</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold ladda-button" data-style="zoom-in"
                        style="border-radius:8px; padding:12px;">
                        <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Laporan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Riwayat Survey -->
    <div class="col-md-8 survey-right-col">
        <div class="card shadow-sm border-0 survey-right-card d-flex flex-column" style="border-radius: 12px;">
            <div class="card-header bg-primary border-bottom-0 flex-shrink-0"
                style="border-radius: 12px 12px 0 0; padding: 20px 24px 10px;">
                <h6 class="mb-0 fw-bold text-white"><i class="fas fa-history me-2"></i>Riwayat Survey</h6>
            </div>
            <div class="card-body p-2 p-md-3 scrollable-card-body survey-right-card-body">
                <?php if (empty($surveys)): ?>
                    <div class="text-center py-5 h-100 d-flex flex-column justify-content-center">
                        <i class="fas fa-clipboard-list mb-3" style="font-size: 3rem; color: #adb5bd;"></i>
                        <h6 class="text-muted fw-semibold">Belum ada riwayat survey</h6>
                        <p class="text-muted" style="font-size:0.85rem;">Tambahkan laporan survey pertama melalui form di
                            samping.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($surveys as $s): ?>
                            <?php 
                            $fileExt = '';
                            if (!empty($s['file'])) {
                                $fileExt = strtolower(pathinfo($s['file'], PATHINFO_EXTENSION));
                            }
                            $isPdf = ($fileExt === 'pdf');
                            $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                            $isVideo = in_array($fileExt, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                            ?>
                            <div class="card border-0 shadow-sm mb-0" style="border-radius: 12px;">
                                <div class="card-body p-3 p-md-4">
                                    <div class="row align-items-center g-3">
                                        <!-- Info Kiri (Ikon + Judul + Note) -->
                                        <div class="col-12 col-md-5 d-flex gap-3">
                                            <?php if ($isPdf): ?>
                                                <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px; font-size: 1.25rem; background-color: rgba(234, 84, 85, 0.12); color: #ea5455;">
                                                    <i class="fas fa-file-pdf"></i>
                                                </div>
                                            <?php elseif ($isImage): ?>
                                                <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px; font-size: 1.25rem; background-color: rgba(40, 199, 111, 0.12); color: #28c76f;">
                                                    <i class="fas fa-file-image"></i>
                                                </div>
                                            <?php elseif ($isVideo): ?>
                                                <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px; font-size: 1.25rem; background-color: rgba(255, 159, 67, 0.12); color: #ff9f43;">
                                                    <i class="fas fa-file-video"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px; font-size: 1.25rem; background-color: rgba(255, 92, 92, 0.12); color: var(--palette-primary, #ff5c5c);">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div style="min-width: 0;">
                                                <h6 class="mb-1 fw-bold text-dark text-wrap"
                                                    style="font-size: 0.95rem; line-height: 1.3;"><?= esc($s['title']) ?></h6>
                                                <?php if (!empty($s['note'])): ?>
                                                    <p class="text-muted mb-0 text-wrap"
                                                        style="font-size: 0.8rem; line-height: 1.4;">
                                                        <?= esc($s['note']) ?>
                                                    </p>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-secondary border">Tidak ada catatan</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Info Tengah (Tanggal & Waktu) -->
                                        <div class="col-12 col-md-4 px-md-3 survey-divider-x">
                                            <div class="d-flex flex-row flex-md-column gap-3 gap-md-1 text-muted"
                                                style="font-size: 0.85rem;">
                                                <div class="text-dark fw-semibold" title="Diunggah oleh: <?= esc($s['admin_name'] ?? 'Sistem') ?>">
                                                    <i class="fas fa-user-tie me-2 text-primary"></i><?= esc(strlen($s['admin_name'] ?? 'Sistem') > 15 ? substr($s['admin_name'] ?? 'Sistem', 0, 15) . '...' : ($s['admin_name'] ?? 'Sistem')) ?>
                                                </div>
                                                <div><i
                                                        class="fas fa-calendar-alt me-2"></i><?= date('d M Y', strtotime($s['created_at'])) ?>
                                                </div>
                                                <div><i
                                                        class="fas fa-clock me-2"></i><?= date('H:i', strtotime($s['created_at'])) ?>
                                                    WIB</div>
                                            </div>
                                        </div>

                                        <!-- Aksi (Kanan) -->
                                        <div class="col-12 col-md-3 text-md-end mt-3 mt-md-0 pt-3 pt-md-0 survey-divider-y">
                                            <div class="d-flex justify-content-start justify-content-md-end gap-2">
                                                <?php if (!empty($s['file'])): ?>
                                                    <?php $fileUrl = base_url('uploads/survey/' . $s['file']); ?>
                                                    <?php if ($isPdf): ?>
                                                        <a href="<?= $fileUrl ?>" target="_blank"
                                                            class="btn btn-sm btn-outline-danger rounded-3 px-3" data-bs-toggle="tooltip"
                                                            title="Buka PDF">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    <?php elseif ($isImage || $isVideo): ?>
                                                        <?php if ($isVideo): ?>
                                                            <!-- Hidden video player container for native playbacks -->
                                                            <div style="display:none;" id="video-survey-<?= $s['id'] ?>">
                                                                <div class="p-3 text-center" style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                                    <video src="<?= $fileUrl ?>" controls style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata" playsinline></video>
                                                                    <div class="text-white mt-2 text-start px-2">
                                                                        <h6 class="mb-1 fw-bold text-white"><?= esc($s['title']) ?></h6>
                                                                        <small class="text-muted">Diunggah oleh: <?= esc($s['admin_name'] ?? 'Sistem') ?> | <?= date('d M Y, H:i', strtotime($s['created_at'])) ?> WIB</small>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <a href="#video-survey-<?= $s['id'] ?>" class="glightbox btn btn-sm btn-outline-success rounded-3 px-3" 
                                                               data-gallery="survey-gallery" 
                                                               data-slide-class="glightbox-video-slide">
                                                                <i class="fas fa-play-circle"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= $fileUrl ?>" class="glightbox btn btn-sm btn-outline-success rounded-3 px-3" 
                                                               data-gallery="survey-gallery" 
                                                               data-title="<?= esc($s['title']) ?>" 
                                                               data-description="Diunggah oleh: <?= esc($s['admin_name'] ?? 'Sistem') ?> &lt;br&gt; Tanggal: <?= date('d M Y', strtotime($s['created_at'])) ?> <?= date('H:i', strtotime($s['created_at'])) ?> WIB">
                                                                <i class="fas fa-image"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <a href="<?= $fileUrl ?>" target="_blank"
                                                            class="btn btn-sm btn-outline-info rounded-3 px-3" data-bs-toggle="tooltip"
                                                            title="Download File">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-light text-muted rounded-3 px-3" disabled
                                                        title="Tidak ada file">
                                                        <i class="fas fa-file-excel"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <a href="<?= base_url('admin/design/delete-survey/' . $s['id']) ?>"
                                                    class="btn btn-sm btn-outline-danger rounded-3 px-3 ladda-button"
                                                    data-style="zoom-in"
                                                    onclick="if(confirm('Apakah Anda yakin ingin menghapus laporan survey ini? Tindakan ini tidak dapat dibatalkan.')) { Ladda.create(this).start(); return true; } return false;"
                                                    data-bs-toggle="tooltip" title="Hapus Laporan">
                                                    <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Feedback Klien Full Width -->
                                    <?php if (!empty($s['comment'])): ?>
                                        <div class="mt-3 p-3 bg-light border rounded-3 w-100">
                                            <strong class="text-primary" style="font-size: 0.85rem;"><i
                                                    class="fas fa-comment-dots me-2"></i>Feedback Klien:</strong>
                                            <p class="fst-italic text-muted mb-0 mt-2 text-wrap"
                                                style="font-size: 0.85rem; line-height: 1.5;">"<?= esc($s['comment']) ?>"</p>
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
