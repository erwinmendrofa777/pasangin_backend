<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <div class="card banner-card-container">
            <div class="card-header banner-card-header d-flex justify-content-between align-items-center">
                <h4 class="banner-card-title mb-0"><i class="fas fa-image text-primary me-2"></i>Tambah Banner Baru</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/banner') ?>" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 20px; font-weight: 600; font-size: 0.75rem;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('admin/banner/store') ?>" method="post" enctype="multipart/form-data" id="banner-create-form">
                    <?= csrf_field() ?>

                    <!-- Judul Banner -->
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.85rem; font-weight: 600;">Judul Banner (Opsional)</label>
                        <div class="banner-input-group">
                            <i class="fas fa-heading"></i>
                            <input type="text" name="title" id="banner-title-input" class="form-control" placeholder="Contoh: Promo Diskon 50%" autocomplete="off">
                        </div>
                    </div>

                    <!-- Target Aplikasi -->
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.85rem; font-weight: 600;">Target Aplikasi Penerima</label>
                        <input type="hidden" name="target_app" id="target-app-value" value="client">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="target-app-card active" data-target="client" id="card-target-client">
                                    <div class="card-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div class="card-label">Aplikasi Client</div>
                                    <div class="card-sub text-center">Untuk pelanggan & pemesan jasa</div>
                                    <div class="check-badge"><i class="fas fa-check-circle"></i></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="target-app-card" data-target="tukang" id="card-target-tukang">
                                    <div class="card-icon">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="card-label">Aplikasi Tukang</div>
                                    <div class="card-sub text-center">Untuk mitra pekerja lapangan</div>
                                    <div class="check-badge"><i class="fas fa-check-circle"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Gambar & Live Preview -->
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.85rem; font-weight: 600;">Upload Gambar Banner</label>
                        
                        <!-- Input File Tersembunyi -->
                        <input type="file" name="image" id="banner-file-input" accept="image/*" required style="display: none;">

                        <!-- Drag & Drop Zone -->
                        <div class="banner-upload-dropzone" id="banner-dropzone">
                            <div class="dropzone-placeholder text-center">
                                <div class="upload-icon-wrapper mb-3">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Seret & lepas berkas gambar di sini</h6>
                                <p class="text-muted text-small mb-3" style="font-size: 0.8rem;">atau klik area ini untuk memilih gambar</p>
                                <span class="badge bg-white text-muted border px-3 py-2" style="font-size: 0.7rem; border-radius: 8px;">Format: JPG/PNG · Maks: 2MB · Rasio Ideal: 2:1</span>
                            </div>
                        </div>
                        
                        <!-- Live Mockup Preview -->
                        <div class="banner-live-preview-wrapper mt-4" id="banner-preview-wrapper" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted text-small fw-bold" style="font-size: 0.75rem;"><i class="fas fa-eye text-success me-1"></i>Pratinjau Banner Live (Aplikasi Seluler)</span>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btn-remove-preview" style="font-size: 0.72rem; border-radius: 20px; padding: 2px 10px;">
                                    <i class="fas fa-trash me-1"></i> Ganti Gambar
                                </button>
                            </div>
                            <div class="banner-mockup-container">
                                <div class="mobile-mockup-frame">
                                    <img id="banner-preview-image" src="" alt="Pratinjau Banner">
                                    <div class="banner-overlay"></div>
                                    <div class="banner-title-overlay" id="banner-title-overlay-text">
                                        <!-- Diisi dinamis -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="form-group mb-0 text-right mt-4">
                        <?php if (can('banner_create')): ?>
                        <button type="submit" class="btn btn-primary btn-save-banner btn-icon icon-right ladda-button" data-style="zoom-in" id="btn-submit-banner">
                            <span class="ladda-label"><i class="fas fa-save me-1"></i> Simpan Banner</span>
                        </button>
                        <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-icon icon-right" disabled style="border-radius: 12px; padding: 12px 28px;">
                            <i class="fas fa-lock me-1"></i> Akses Ditolak
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
