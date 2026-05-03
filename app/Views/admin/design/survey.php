<style>
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
    <!-- Kolom Form Tambah Survey -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-primary text-white" style="border-radius: 12px 12px 0 0; padding: 16px 20px;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Laporan Survey</h6>
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
                            required style="border-radius:8px;">
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
                        <input type="file" name="survey_file" class="form-control" style="border-radius:8px;"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <div class="form-text" style="font-size:0.75rem;"><i
                                class="fas fa-info-circle text-primary me-1"></i>Format: PDF, Word, atau Gambar (Max
                            2MB).</div>
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
    <div class="col-md-8">
        <div class="card shadow-sm border-0" style="border-radius: 12px; height: 100%; min-height: 510px;">
            <div class="card-header bg-primary border-bottom-0"
                style="border-radius: 12px 12px 0 0; padding: 20px 24px 10px;">
                <h6 class="mb-0 fw-bold text-white"><i class="fas fa-history me-2"></i>Riwayat Survey</h6>
            </div>
            <div class="card-body p-3 p-md-4" style="background: #f8f9fa;">
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
                            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                                <div class="card-body p-3 p-md-4">
                                    <div class="row align-items-center g-3">
                                        <!-- Info Kiri (Ikon + Judul + Note) -->
                                        <div class="col-12 col-md-5 d-flex gap-3">
                                            <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center"
                                                style="width: 48px; height: 48px; font-size: 1.25rem;">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
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
                                                    <a href="<?= base_url('uploads/survey/' . $s['file']) ?>" target="_blank"
                                                        class="btn btn-sm btn-outline-info rounded-3 px-3" data-bs-toggle="tooltip"
                                                        title="Lihat/Unduh File">
                                                        <i class="fas fa-download"></i>
                                                    </a>
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