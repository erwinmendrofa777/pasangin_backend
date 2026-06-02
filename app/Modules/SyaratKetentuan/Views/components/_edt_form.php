<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card form-card">
            <div class="card-header d-flex align-items-center">
                <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-light btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left small"></i>
                </a>
                <h4 class="mb-0 fw-800 text-primary" style="font-size: 1.1rem;">Edit Dokumen Syarat & Ketentuan</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/syarat_ketentuan/update/' . $data['id']) ?>" method="post" id="sk-form">
                    <?= csrf_field() ?>
                    <!-- Hidden input to hold Editor.js JSON output -->
                    <input type="hidden" name="description" id="description-hidden">

                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Target Aplikasi</label>
                            <select name="target_app" class="form-select form-control-custom <?= session('errors.target_app') ? 'is-invalid' : '' ?>" required>
                                <option value="">-- Pilih Target --</option>
                                <option value="CLIENT" <?= old('target_app', $data['target_app']) == 'CLIENT' ? 'selected' : '' ?>>Aplikasi Client</option>
                                <option value="TUKANG" <?= old('target_app', $data['target_app']) == 'TUKANG' ? 'selected' : '' ?>>Aplikasi Tukang (Mitra)</option>
                                <option value="SUPPLIER" <?= old('target_app', $data['target_app']) == 'SUPPLIER' ? 'selected' : '' ?>>Aplikasi Supplier</option>
                                <option value="PROYEK" <?= old('target_app', $data['target_app']) == 'PROYEK' ? 'selected' : '' ?>>Manajemen Proyek</option>
                            </select>
                            <?php if (session('errors.target_app')) : ?>
                                <div class="invalid-feedback fw-bold mt-2 ps-1"><?= session('errors.target_app') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label-custom">Judul Dokumen</label>
                            <input type="text" name="title" class="form-control form-control-custom <?= session('errors.title') ? 'is-invalid' : '' ?>" value="<?= old('title', $data['title']) ?>" placeholder="E.g. Kebijakan Privasi Client" required>
                            <?php if (session('errors.title')) : ?>
                                <div class="invalid-feedback fw-bold mt-2 ps-1"><?= session('errors.title') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Konten Dokumen</label>
                            <p class="text-muted small mb-2" style="margin-top: -4px;">
                                <i class="fas fa-info-circle me-1 text-primary"></i>
                                Gunakan toolbar <kbd>+</kbd> untuk menambah blok (Heading, List, Delimiter). Klik pada teks dan gunakan shortcut <kbd>/</kbd> untuk perintah cepat.
                            </p>
                            <div class="editor-wrapper">
                                <div id="editorjs"></div>
                            </div>
                            <div id="editor-error" class="text-danger small fw-bold mt-2 ps-1" style="display:none;">
                                <i class="fas fa-exclamation-circle me-1"></i> Konten dokumen tidak boleh kosong.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5">
                        <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-light px-4 fw-bold" style="border-radius: 10px;">Batal</a>
                        <?php if (can('syarat_ketentuan_update')): ?>
                        <button type="submit" class="btn btn-primary px-4 fw-bold ladda-button" data-style="zoom-in" style="border-radius: 10px;" id="submit-btn">
                            <span class="ladda-label"><i class="fas fa-save me-2"></i>Update Perubahan</span>
                        </button>
                        <?php else: ?>
                        <button type="button" class="btn btn-secondary px-4 fw-bold" style="border-radius: 10px;" disabled>
                            <i class="fas fa-lock me-2"></i>Akses Ditolak
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
