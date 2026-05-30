<style>
    .survey-card-form,
    .survey-card-history {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(103, 119, 239, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        animation: surveyFadeUp 0.4s ease both;
    }

    .survey-card-history {
        animation-delay: 0.1s;
    }

    @keyframes surveyFadeUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .survey-card-form .card-header,
    .survey-card-history .card-header {
        background: linear-gradient(135deg, #6777ef 0%, #7e8ef5 100%);
        border: none;
        padding: 16px 22px;
    }

    .survey-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .survey-input {
        border-radius: 8px;
        border: 1.5px solid #e0e4ff;
        padding: 10px 14px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .survey-input:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.12);
    }

    .btn-survey-submit {
        background: linear-gradient(135deg, #6777ef, #7e8ef5);
        border: none;
        border-radius: 8px;
        padding: 11px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-survey-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(103, 119, 239, 0.35);
    }

    .empty-survey {
        padding: 56px 24px;
        text-align: center;
        animation: surveyFadeUp 0.5s ease both 0.2s;
    }

    .empty-survey-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f0f3ff, #e0e4ff);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 1.6rem;
        color: #6777ef;
        opacity: 0.6;
    }

    /* Divider: horizontal di mobile, vertikal di desktop */
    .survey-divider-y {
        border-top: 1px dashed #dee2e6;
    }

    @media (min-width: 768px) {
        .survey-divider-x {
            border-left: 1px dashed #dee2e6;
        }

        .survey-divider-y {
            border-top: none;
        }
    }

    /* File Upload Box Premium */
    .file-upload-box {
        position: relative;
        height: 48px;
        background: #f8faff;
        border: 2px dashed #6777ef55;
        border-radius: 12px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-box:hover {
        background: #fff;
        border-color: #6777ef;
    }

    .file-upload-box .file-label {
        flex: 1;
        font-size: 13px;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-upload-box input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .file-upload-box i {
        color: #6777ef;
        font-size: 1.1rem;
        margin-left: 10px;
    }

    /* ===== GLIGHTBOX VIDEO INLINE SLIDE PREMIUM SYSTEM ===== */
    .glightbox-video-slide .gslide-inline {
        background: #000 !important;
        padding: 0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        width: 95% !important;
        max-width: 900px !important;
        border-radius: 12px;
        overflow: hidden !important;
        overflow-y: hidden !important;
    }
    .glightbox-video-slide .gslide-inner-content {
        background: #000 !important;
        overflow: hidden !important;
        width: 100% !important;
    }
    .glightbox-video-slide .gslide-description {
        display: none !important;
    }
    .glightbox-video-slide .gslide-media {
        box-shadow: none !important;
        overflow: hidden !important;
        background: #000 !important;
    }
</style>

<div class="row g-4 mt-1">

    <!-- ========== FORM TAMBAH SURVEY ========== -->
    <div class="col-md-4 d-flex flex-column">
        <div class="card survey-card-form h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white" style="font-weight:700; font-size:0.9rem;">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Laporan Survey
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('admin/construction/upload-survey') ?>" method="post"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $construction['id'] ?>">

                    <div class="form-group mb-3">
                        <label class="survey-label">Judul Laporan</label>
                        <input type="text" name="survey_title" class="form-control survey-input"
                            placeholder="Contoh: Survey Lokasi Pertama" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="survey-label">Admin Pelaksana</label>
                        <select name="user_admin_id" class="form-control survey-input" required>
                            <option value="">— Pilih Admin —</option>
                            <?php 
                            $currentUserId = session()->get('user_id');
                            foreach ($admin_users ?? [] as $au): 
                                $selected = ($au['id'] == $currentUserId) ? 'selected' : '';
                            ?>
                                <option value="<?= $au['id'] ?>" <?= $selected ?>>
                                    <?= esc($au['full_name'] ?? $au['username'] ?? 'Admin ' . $au['id']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="survey-label">Catatan (Opsional)</label>
                        <textarea name="survey_notes" class="form-control survey-input" rows="3"
                            placeholder="Tambahkan catatan hasil survey..." style="resize:none;"></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="survey-label">File Laporan</label>
                        <div class="file-upload-box">
                            <span class="file-label" id="surveyFileNameDisplay">Pilih atau seret file...</span>
                            <input type="file" name="survey_file" id="surveyFileInput"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm,.mkv" required>
                            <i class="fas fa-paperclip"></i>
                        </div>
                        <div class="form-text mt-1" style="font-size:0.73rem;">
                            <i class="fas fa-info-circle text-primary mr-1"></i>Format: PDF, Gambar, atau Video (Max 50MB).
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-survey-submit w-100 ladda-button"
                        data-style="zoom-in">
                        <span class="ladda-label"><i class="fas fa-save mr-2"></i>Simpan Laporan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ========== RIWAYAT SURVEY ========== -->
    <div class="col-md-8 d-flex flex-column">
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
                            Tambahkan laporan survey pertama melalui form di samping.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($survey_list as $srv): 
                            $file = $srv['survey_file'] ?? '';
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $isPdf = ($ext === 'pdf');
                            $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                            $fileUrl = !empty($file) ? base_url('uploads/construction/survey/' . $file) : '';
                            ?>
                            <div class="card border-0 shadow-sm mb-0" style="border-radius:12px;">
                                <div class="card-body p-2 p-md-4">
                                    <div class="row align-items-center g-3">

                                        <!-- Info Kiri: Ikon + Judul + Catatan -->
                                        <div class="col-12 col-md-5 d-flex gap-3">
                                            <?php if ($isPdf): ?>
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center"
                                                    style="width:48px; height:48px; background:#fff5f5; border-radius:10px; color:#dc3545; font-size:1.25rem; flex-shrink:0; border: 1px solid #ffcccc;">
                                                    <i class="fas fa-file-pdf"></i>
                                                </div>
                                            <?php elseif ($isVideo): ?>
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center position-relative"
                                                    style="width:48px; height:48px; background:#fff9f0; border-radius:10px; color:#ffc107; font-size:1.25rem; flex-shrink:0; border: 1px solid #ffeeba; cursor:pointer;"
                                                    onclick="$('#glb-survey-video-<?= $srv['id'] ?>').click();">
                                                    <i class="fas fa-file-video"></i>
                                                    <span class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);">
                                                        <i class="fas fa-play-circle text-warning bg-white rounded-circle" style="font-size:10px;"></i>
                                                    </span>
                                                </div>
                                            <?php elseif ($isImage): ?>
                                                <div style="width:48px; height:48px; display:inline-block; overflow:hidden; border: 1px solid #e4e9f0; cursor:pointer;" 
                                                    class="flex-shrink-0 rounded"
                                                    onclick="$('#glb-survey-img-<?= $srv['id'] ?>').click();">
                                                    <img src="<?= $fileUrl ?>" style="width:100%; height:100%; object-fit:cover;">
                                                </div>
                                            <?php else: ?>
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center"
                                                    style="width:48px; height:48px; background:linear-gradient(135deg,#e7f0ff,#d5dfff); border-radius:10px; color:#6777ef; font-size:1.25rem; flex-shrink:0;">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                            <?php endif; ?>
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
                                        <div class="col-12 col-md-4 px-md-3 survey-divider-x">
                                            <div class="d-flex flex-row flex-md-column gap-3 gap-md-1 text-muted"
                                                style="font-size:0.85rem;">
                                                <div class="text-dark fw-bold"
                                                    title="Diunggah oleh: <?= esc($srv['admin_name'] ?? 'Sistem') ?>">
                                                    <i class="fas fa-user-tie mr-2 text-primary"></i>
                                                    <?= esc(strlen($srv['admin_name'] ?? 'Sistem') > 15 ? substr($srv['admin_name'] ?? 'Sistem', 0, 15) . '...' : ($srv['admin_name'] ?? 'Sistem')) ?>
                                                </div>
                                                <div>
                                                    <i
                                                        class="fas fa-calendar-alt mr-2"></i><?= date('d M Y', strtotime($srv['created_at'])) ?>
                                                </div>
                                                <div>
                                                    <i
                                                        class="fas fa-clock mr-2"></i><?= date('H:i', strtotime($srv['created_at'])) ?>
                                                    WIB
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Aksi: Download + Hapus -->
                                        <div class="col-12 col-md-3 text-md-end pt-3 pt-md-0 survey-divider-y">
                                            <div class="d-flex justify-content-start justify-content-md-end gap-2">
                                                <?php if (!empty($srv['survey_file'])): ?>
                                                    <?php if ($isPdf): ?>
                                                        <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-outline-danger"
                                                            style="border-radius:8px;" title="Lihat PDF">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    <?php elseif ($isVideo): ?>
                                                        <!-- Hidden video player container for native playbacks -->
                                                        <div style="display:none;" id="video-survey-<?= $srv['id'] ?>">
                                                            <div class="p-3 text-center" style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                                <video src="<?= $fileUrl ?>" controls style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata" playsinline></video>
                                                                <div class="text-white mt-2 text-start px-2">
                                                                    <h6 class="mb-1 fw-bold text-white"><?= esc($srv['survey_title']) ?></h6>
                                                                    <small class="text-muted">Diunggah oleh: <?= esc($srv['admin_name'] ?? 'Sistem') ?></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="#video-survey-<?= $srv['id'] ?>" class="glightbox btn btn-sm btn-outline-warning" 
                                                           id="glb-survey-video-<?= $srv['id'] ?>"
                                                           data-gallery="survey-gallery"
                                                           data-slide-class="glightbox-video-slide"
                                                           data-type="inline"
                                                           style="border-radius:8px;">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= $fileUrl ?>" id="glb-survey-img-<?= $srv['id'] ?>" class="glightbox btn btn-sm btn-outline-info" 
                                                           data-gallery="survey-gallery"
                                                           data-title="<?= esc($srv['survey_title']) ?>"
                                                           data-description="Diunggah oleh: <?= esc($srv['admin_name'] ?? 'Sistem') ?>"
                                                           style="border-radius:8px;">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-light text-muted" style="border-radius:8px;"
                                                        disabled title="Tidak ada file">
                                                        <i class="fas fa-file-excel"></i>
                                                    </button>
                                                <?php endif; ?>

                                                <a href="<?= base_url('admin/construction/delete-survey/' . $srv['id'] . '/' . $construction['id']) ?>"
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const surveyInput = document.getElementById('surveyFileInput');
        if (surveyInput) {
            surveyInput.addEventListener('change', function (e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih atau seret file...';
                const display = document.getElementById('surveyFileNameDisplay');
                if (display) {
                    display.textContent = fileName;
                    display.style.color = e.target.files[0] ? '#34395e' : '#6c757d';
                    display.style.fontWeight = e.target.files[0] ? '600' : '400';
                }
            });
        }
    });
</script>