<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card form-card">
            <div class="card-header d-flex align-items-center">
                <h4 class="mb-0 fw-800 text-primary" style="font-size: 1.1rem;">
                    <i class="fas fa-info-circle me-2"></i>Tentang Aplikasi Pasangin
                </h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/about_application/update') ?>" method="post" id="about-form">
                    <?= csrf_field() ?>
                    <!-- Hidden input to hold Editor.js JSON output -->
                    <input type="hidden" name="description" id="description-hidden">

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label-custom">Deskripsi Aplikasi</label>
                            <p class="text-muted small mb-2" style="margin-top: -4px;">
                                <i class="fas fa-info-circle me-1 text-primary"></i>
                                Gunakan toolbar <kbd>+</kbd> untuk menambah blok (Heading, List, Delimiter).
                            </p>
                            <div class="editor-wrapper">
                                <div id="editorjs"></div>
                            </div>
                            <div id="editor-error" class="text-danger small fw-bold mt-2 ps-1" style="display:none;">
                                <i class="fas fa-exclamation-circle me-1"></i> Deskripsi tidak boleh kosong.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-5">
                        <?php if (can('about_application_update')): ?>
                            <button type="submit" class="btn btn-primary px-4 fw-bold ladda-button" data-style="zoom-in"
                                style="border-radius: 10px;" id="submit-btn">
                                <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Perubahan</span>
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary px-4 fw-bold" style="border-radius: 10px;"
                                disabled>
                                <i class="fas fa-lock me-2"></i>Akses Ditolak
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
