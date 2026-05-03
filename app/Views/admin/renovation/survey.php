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
</style>

<div class="row g-4 mt-1">

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
                        <label class="survey-label">Catatan (Opsional)</label>
                        <textarea name="description" class="form-control survey-input" rows="3"
                            placeholder="Tambahkan catatan hasil survey..." style="resize:none;"></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="survey-label">File Laporan</label>
                        <input type="file" name="file_url" class="form-control survey-input"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <div class="form-text mt-1" style="font-size:0.73rem;">
                            <i class="fas fa-info-circle text-primary mr-1"></i> Format: PDF, Word, atau Gambar (Max
                            2MB).
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
    <div class="col-md-8 d-flex flex-column">
        <div class="card survey-card-history h-100">
            <div class="card-header">
                <h6 class="mb-0 text-white" style="font-weight:700; font-size:0.9rem;">
                    <i class="fas fa-history mr-2"></i> Riwayat Survey
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
                        <?php foreach ($survey_list as $srv): ?>
                            <div class="card border-0 shadow-sm mb-0" style="border-radius:12px;">
                                <div class="card-body p-2">
                                    <div class="row align-items-center g-3">

                                        <!-- Info Kiri: Ikon + Judul + Catatan -->
                                        <div class="col-12 col-md-5 d-flex gap-3">
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center"
                                                style="width:48px; height:48px; background:linear-gradient(135deg,#e7f0ff,#d5dfff); border-radius:10px; color:#6777ef; font-size:1.25rem; flex-shrink:0;">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
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

                                        <!-- Info Tengah: Tanggal & Waktu -->
                                        <div class="col-12 col-md-4 px-md-3 survey-divider-x">
                                            <div class="d-flex flex-row flex-md-column gap-3 gap-md-1 text-muted"
                                                style="font-size:0.85rem;">
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
                                                    <a href="<?= base_url('uploads/survey/' . $srv['file_url']) ?>" target="_blank"
                                                        class="btn btn-sm btn-outline-info" style="border-radius:8px;"
                                                        title="Lihat File">
                                                        <i class="fas fa-download"></i>
                                                    </a>
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